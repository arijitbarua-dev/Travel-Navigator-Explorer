<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room;
use App\Models\RoomRequest;
use App\Models\RoomBooking;

class RoomController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Dashboard + Search (MAIN FEATURE)
    |--------------------------------------------------------------------------
    */
    public function search(Request $request)
    {
        $query = Room::query();

        // Budget filter
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Guests filter
        if ($request->filled('guests')) {
            $query->where('persons', '>=', $request->guests);
        }

        // Date validation (optional for now)
        if ($request->filled('checkin') && $request->filled('checkout')) {
            $request->validate([
                'checkin' => 'date',
                'checkout' => 'date|after:checkin',
            ]);
        }

        // If no filters → don't show all rooms immediately (optional UX choice)
        if (!$request->hasAny(['min_price', 'max_price', 'guests', 'checkin', 'checkout'])) {
            $booking = auth()->check() ? RoomBooking::where('user_id', auth()->id())
                ->where('payment_status', 'completed')
                ->latest()
                ->first() : null;
            return view('customer.dashboard', compact('booking'));
        }

        $rooms = $query->get();
        $booking = auth()->check() ? RoomBooking::where('user_id', auth()->id())
            ->where('payment_status', 'completed')
            ->latest()
            ->first() : null;

        return view('customer.dashboard', compact('rooms', 'booking'));
    }


    /*
    |--------------------------------------------------------------------------
    | Show all rooms (admin/general page)
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        $rooms = Room::all();
        return view('rooms', compact('rooms'));
    }


    /*
    |--------------------------------------------------------------------------
    | Show single room
    |--------------------------------------------------------------------------
    */
    public function show($id)
    {
        $room = Room::find($id);

        if (!$room) {
            return redirect()->back()->with('error', 'Room not found.');
        }

        return view('room', compact('room'));
    }

    public function bookingForm(Request $request)
    {
        $room = Room::findOrFail($request->room_id);
        return view('room_booking', compact('room'));
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
        ]);

        $room = Room::findOrFail($request->room_id);
        
        $startDate = new \DateTime($request->start_date);
        $endDate   = new \DateTime($request->end_date);
        $numDays   = $startDate->diff($endDate)->days;

        if ($numDays < 1) {
            return redirect()->back()->with('error', 'Minimum booking is 1 night.');
        }

        $totalAmount = $room->price * $numDays;

        $secretKey = config('services.stripe.secret') ?: env('STRIPE_SECRET');
        \Stripe\Stripe::setApiKey($secretKey);

        $totalInUsd = round($totalAmount / 110, 2);
        $totalInCents = (int) round($totalInUsd * 100);
        if ($totalInCents < 50) {
            $totalInCents = 50;
        }

        try {
            $session = \Stripe\Checkout\Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency'     => 'usd',
                        'product_data' => [
                            'name'        => 'Room Booking: ' . $room->name,
                            'description' => $numDays . ' night(s)',
                        ],
                        'unit_amount' => $totalInCents,
                    ],
                    'quantity' => 1,
                ]],
                'mode'        => 'payment',
                'success_url' => route('room.success') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url'  => route('dashboard') . '?payment=cancelled',
                'metadata' => [
                    'type' => 'room',
                    'room_id' => $room->id,
                    'room_name' => $room->name,
                    'price' => $totalAmount,
                    'nights' => $numDays,
                    'customer_email' => auth()->user()->email ?? 'guest@example.com',
                    'customer_name' => auth()->user()->name ?? 'Guest',
                ],
            ]);

            RoomBooking::create([
                'user_id' => auth()->id(),
                'room_id' => $room->id,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'nights' => $numDays,
                'total_amount' => $totalAmount,
                'payment_status' => 'pending',
                'stripe_session_id' => $session->id,
            ]);

            return redirect($session->url);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Payment failed: ' . $e->getMessage());
        }
    }

    public function success(Request $request)
    {
        $sessionId = $request->query('session_id');
        if (!$sessionId) return redirect('/rooms');

        $secretKey = config('services.stripe.secret') ?: env('STRIPE_SECRET');
        \Stripe\Stripe::setApiKey($secretKey);
        
        try {
            $session = \Stripe\Checkout\Session::retrieve($sessionId);
            $metadata = $session->metadata;
            
            $booking = RoomBooking::where('stripe_session_id', $sessionId)->first();
            if ($booking) {
                $booking->update(['payment_status' => 'completed']);
            }
            
            return view('room_success', compact('session', 'metadata'));
        } catch (\Exception $e) {
            return redirect('/rooms');
        }
    }
    
    public function receipt(Request $request)
    {
        $sessionId = $request->query('session_id');
        if (!$sessionId) return redirect('/rooms');
        
        $secretKey = config('services.stripe.secret') ?: env('STRIPE_SECRET');
        \Stripe\Stripe::setApiKey($secretKey);
        
        try {
            $session = \Stripe\Checkout\Session::retrieve($sessionId);
            $metadata = $session->metadata;
            return view('room_receipt', compact('session', 'metadata'));
        } catch (\Exception $e) {
            return redirect('/rooms');
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Store new room (request for admin approval)
    |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'sqft' => 'nullable|numeric',
            'persons' => 'nullable|numeric',
            'description' => 'required|string',
            'services' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $imagePath = null;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('rooms', 'public');
        }

        RoomRequest::create([
            'name' => $request->name,
            'price' => $request->price,
            'sqft' => $request->sqft,
            'persons' => $request->persons,
            'description' => $request->description,
            'services' => $request->services,
            'image' => $imagePath,
            'status' => 'pending',
        ]);

        return redirect()->back()->with('success', 'Room submitted for admin approval!');
    }


    /*
    |--------------------------------------------------------------------------
    | Edit room
    |--------------------------------------------------------------------------
    */
    public function edit($id)
    {
        $room = Room::find($id);

        if (!$room) {
            return redirect()->back()->with('error', 'Room not found.');
        }

        return view('edit-room', compact('room'));
    }


    /*
    |--------------------------------------------------------------------------
    | Update room
    |--------------------------------------------------------------------------
    */
    public function update(Request $request, $id)
    {
        $room = Room::find($id);

        if (!$room) {
            return redirect()->back()->with('error', 'Room not found.');
        }

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('rooms', 'public');
            $room->image = $imagePath;
        }

        $room->name = $request->name;
        $room->price = $request->price;
        $room->description = $request->description;
        $room->sqft = $request->sqft;
        $room->persons = $request->persons;
        $room->services = $request->services;

        $room->save();

        return redirect('/rooms')->with('success', 'Room updated successfully.');
    }


    /*
    |--------------------------------------------------------------------------
    | Delete room
    |--------------------------------------------------------------------------
    */
    public function delete($id)
    {
        $room = Room::find($id);

        if ($room) {
            $room->delete();
            return redirect('/rooms')->with('success', 'Room deleted successfully.');
        }

        return redirect('/rooms')->with('error', 'Room not found.');
    }


    /*
    |--------------------------------------------------------------------------
    | Cost Calculator
    |--------------------------------------------------------------------------
    */
    public function costForm()
    {
        $rooms = Room::all();
        return view('cost', compact('rooms'));
    }

    public function calculateCost(Request $request)
    {
        $room = Room::find($request->room_id);
        $nights = $request->nights;

        if (!$room) {
            return redirect()->back()->with('error', 'Room not found.');
        }

        $total = $room->price * $nights;

        return view('cost_result', compact('room', 'nights', 'total'));
    }


    /*
    |--------------------------------------------------------------------------
    | Travel Budget
    |--------------------------------------------------------------------------
    */
    public function travelBudgetForm()
    {
        return view('travel_budget');
    }

    public function travelBudgetCalculate(Request $request)
    {
        $request->validate([
            'hotel_cost' => 'required|numeric',
            'nights' => 'required|numeric',
            'food_per_day' => 'required|numeric',
            'transport_cost' => 'required|numeric',
        ]);

        $hotelTotal = $request->hotel_cost * $request->nights;
        $foodTotal = $request->food_per_day * $request->nights;
        $transportTotal = $request->transport_cost;

        $total = $hotelTotal + $foodTotal + $transportTotal;

        if ($total < 5000) {
            $category = "Budget";
        } elseif ($total < 15000) {
            $category = "Moderate";
        } else {
            $category = "Luxury";
        }

        return view('travel_budget_result', compact('total', 'category'));
    }
}