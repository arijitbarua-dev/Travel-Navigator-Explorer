<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Guide;
use App\Models\Booking;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;

class PaymentController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Show Booking Form
    |--------------------------------------------------------------------------
    */
    public function showBookingForm(Request $request)
    {
        $guides = Guide::all();
        $selectedGuideId = $request->query('guide_id');
        return view('booking_form', compact('guides', 'selectedGuideId'));
    }

    /*
    |--------------------------------------------------------------------------
    | Create Stripe Checkout Session & Redirect
    |--------------------------------------------------------------------------
    */
    public function checkout(Request $request)
    {
        $request->validate([
            'guide_id'       => 'required|exists:guides,id',
            'customer_name'  => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'start_date'     => 'required|date|after_or_equal:today',
            'end_date'       => 'required|date|after:start_date',
        ]);

        $guide = Guide::findOrFail($request->guide_id);

        // Calculate number of days
        $startDate = new \DateTime($request->start_date);
        $endDate   = new \DateTime($request->end_date);
        $numDays   = $startDate->diff($endDate)->days;

        if ($numDays < 1) {
            return redirect()->back()->with('error', 'Minimum booking is 1 day.');
        }

        $totalAmount = $guide->price_per_day * $numDays;

        // Convert BDT to USD (approximate rate: 1 USD ≈ 110 BDT)
        $totalInUsd = round($totalAmount / 110, 2);
        $totalInCents = (int) round($totalInUsd * 100);
        if ($totalInCents < 50) {
            $totalInCents = 50; // Stripe minimum
        }

        $extraData = [
            'notes'        => $request->notes ?? '',
            'start_date'   => $request->start_date,
            'end_date'     => $request->end_date,
            'num_days'     => $numDays,
            'total_amount' => $totalAmount,
        ];

        // Create booking record with pending status
        $booking = Booking::create([
            'guide_id'       => $guide->id,
            'destination_id' => $guide->destination_id ?? 1,
            'guest_name'     => $request->customer_name,
            'guest_email'    => $request->customer_email,
            'date'           => $request->start_date,
            'status'         => 'pending',
            'notes'          => json_encode($extraData),
        ]);

        $secretKey = config('services.stripe.secret');

        // Create Stripe Checkout Session
        Stripe::setApiKey($secretKey);

        try {
            $session = StripeSession::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency'     => 'usd',
                        'product_data' => [
                            'name'        => 'Guide Booking: ' . $guide->name,
                            'description' => ($guide->specialization ?? $guide->speciality ?? '') . ' — ' . $numDays . ' day(s)',
                        ],
                        'unit_amount' => $totalInCents,
                    ],
                    'quantity' => 1,
                ]],
                'mode'        => 'payment',
                'success_url' => route('booking.success') . '?session_id={CHECKOUT_SESSION_ID}&booking_id=' . $booking->id,
                'cancel_url'  => route('booking.cancel') . '?booking_id=' . $booking->id,
                'customer_email' => $request->customer_email,
                'metadata' => [
                    'booking_id' => $booking->id,
                    'guide_name' => $guide->name,
                ],
            ]);

            // Store stripe session ID
            $booking->update(['stripe_session_id' => $session->id]);

            return redirect($session->url);

        } catch (\Exception $e) {
            $booking->update(['status' => 'cancelled']);
            return redirect()->back()->with('error', 'Payment initialization failed: ' . $e->getMessage());
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Payment Success
    |--------------------------------------------------------------------------
    */
    public function success(Request $request)
    {
        $booking = Booking::with('guide')->findOrFail($request->query('booking_id'));
        $secretKey = config('services.stripe.secret') ?: env('STRIPE_SECRET');

        if ($request->query('session_id')) {
            try {
                \Stripe\Stripe::setApiKey($secretKey);
                $session = \Stripe\Checkout\Session::retrieve($request->query('session_id'));

                if ($session->payment_status === 'paid') {
                    $booking->update([
                        'status' => 'confirmed',
                    ]);
                }
            } catch (\Exception $e) {
                // Log error but still show page — payment may have succeeded
            }
        }

        $booking = $this->hydrateBooking($booking);

        return view('booking_success', compact('booking'));
    }

    /*
    |--------------------------------------------------------------------------
    | Payment Cancelled
    |--------------------------------------------------------------------------
    */
    public function cancel(Request $request)
    {
        if ($request->query('booking_id')) {
            $booking = Booking::find($request->query('booking_id'));
            if ($booking && $booking->status === 'pending') {
                $booking->update(['status' => 'cancelled']);
            }
        }

        return view('booking_cancel');
    }

    /*
    |--------------------------------------------------------------------------
    | Booking Receipt
    |--------------------------------------------------------------------------
    */
    public function receipt($id)
    {
        $booking = Booking::with('guide')->findOrFail($id);
        $booking = $this->hydrateBooking($booking);
        return view('booking_receipt', compact('booking'));
    }

    /*
    |--------------------------------------------------------------------------
    | Dummy Payment Checkout (Local Bypass)
    |--------------------------------------------------------------------------
    */
    public function dummyCheckout(Request $request)
    {
        $booking = Booking::with('guide')->findOrFail($request->query('booking_id'));
        return view('stripe_dummy', compact('booking'));
    }

    /*
    |--------------------------------------------------------------------------
    | Dummy Payment Process (Local Bypass)
    |--------------------------------------------------------------------------
    */
    public function dummyProcess(Request $request)
    {
        $booking = Booking::findOrFail($request->booking_id);
        
        $booking->update([
            'status' => 'confirmed',
        ]);
        
        return redirect(route('booking.success') . '?booking_id=' . $booking->id);
    }

    public function showPaymentFrontend(Request $request, $guideId)
    {
        $guide = \App\Models\Guide::findOrFail($guideId);
        $user = $request->user();

        $request->validate([
            'start_date' => 'required|date|after_or_equal:today',
            'end_date'   => 'required|date|after:start_date',
        ]);

        $startDate = new \DateTime($request->start_date);
        $endDate   = new \DateTime($request->end_date);
        $numDays   = $startDate->diff($endDate)->days;

        if ($numDays < 1) {
            return redirect()->back()->with('error', 'Minimum booking is 1 day.');
        }

        $totalAmount = $guide->price_per_day * $numDays;

        $extraData = [
            'notes'        => $request->notes ?? '',
            'start_date'   => $request->start_date,
            'end_date'     => $request->end_date,
            'num_days'     => $numDays,
            'total_amount' => $totalAmount,
        ];

        // Create booking record with pending status
        $booking = \App\Models\Booking::create([
            'guide_id'       => $guide->id,
            'destination_id' => $guide->destination_id ?? 1,
            'guest_name'     => $user->name,
            'guest_email'    => $user->email,
            'date'           => $request->start_date,
            'notes'          => json_encode($extraData),
            'status'         => 'pending',
        ]);

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
                            'name'        => 'Guide Booking: ' . $guide->name,
                            'description' => ($guide->specialization ?? '') . ' — ' . $numDays . ' day(s)',
                        ],
                        'unit_amount' => $totalInCents,
                    ],
                    'quantity' => 1,
                ]],
                'mode'        => 'payment',
                'success_url' => route('booking.success') . '?session_id={CHECKOUT_SESSION_ID}&booking_id=' . $booking->id,
                'cancel_url'  => route('booking.cancel') . '?booking_id=' . $booking->id,
                'customer_email' => $user->email,
                'metadata' => [
                    'booking_id' => $booking->id,
                    'guide_name' => $guide->name,
                    'bdt_amount' => $totalAmount,
                ],
            ]);

            return redirect($session->url);

        } catch (\Exception $e) {
            $booking->update(['status' => 'cancelled']);
            return redirect()->back()->with('error', 'Payment initialization failed: ' . $e->getMessage());
        }
    }

    public function paymentMethodCheck(Request $request, $owner_id, $amount, $duration, $package_id, $session_id)
    {
        $booking = \App\Models\Booking::find($package_id);
        if ($booking) {
            $secretKey = config('services.stripe.secret') ?: env('STRIPE_SECRET');
            \Stripe\Stripe::setApiKey($secretKey);

            try {
                $session = \Stripe\Checkout\Session::retrieve($session_id);
                if ($session->payment_status === 'paid') {
                    $booking->update([
                        'status' => 'confirmed',
                    ]);

                    // Cancel the subscription immediately since it was a one-time guide booking
                    if ($session->subscription) {
                        $subscription = \Stripe\Subscription::retrieve($session->subscription);
                        $subscription->cancel();
                    }
                }
            } catch (\Exception $e) {
                // Ignore exception
            }
        }

        // Mock data for success_payment.php
        $package = [
            'pakage_title' => $booking ? 'Guide Booking: ' . ($booking->guide->name ?? '') : 'Guide Booking',
            'duration' => $duration,
            'd_m_y' => 'days',
            'how_many' => $duration,
            'employee_limit' => 'N/A',
            'property_limit' => 'N/A',
            'pakage_objective' => 'Tour Guide Booking',
        ];
        
        $user = ['type' => 2];

        return view('payment.success_payment', compact('package', 'user'));
    }

    private function hydrateBooking($booking)
    {
        $extraData = json_decode($booking->notes, true);
        if (is_array($extraData)) {
            $booking->notes = $extraData['notes'] ?? '';
            $booking->start_date = \Carbon\Carbon::parse($extraData['start_date'] ?? $booking->date);
            $booking->end_date = \Carbon\Carbon::parse($extraData['end_date'] ?? $booking->date);
            $booking->num_days = $extraData['num_days'] ?? 1;
            $booking->total_amount = $extraData['total_amount'] ?? 0;
        } else {
            $booking->start_date = \Carbon\Carbon::parse($booking->date);
            $booking->end_date = \Carbon\Carbon::parse($booking->date)->addDays(1);
            $booking->num_days = 1;
            $booking->total_amount = $booking->guide ? $booking->guide->price_per_day : 0;
        }
        $booking->customer_email = $booking->guest_email;
        $booking->customer_name = $booking->guest_name;
        $booking->customer_phone = '';
        return $booking;
    }
}
