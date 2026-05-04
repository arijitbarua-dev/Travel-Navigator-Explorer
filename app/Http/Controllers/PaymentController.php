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

        // Create booking record with pending status
        $booking = Booking::create([
            'user_id'        => auth()->id(),
            'guide_id'       => $guide->id,
            'customer_name'  => $request->customer_name,
            'customer_email' => $request->customer_email,
            'customer_phone' => $request->customer_phone,
            'start_date'     => $request->start_date,
            'end_date'       => $request->end_date,
            'num_days'       => $numDays,
            'total_amount'   => $totalAmount,
            'status'         => 'pending',
        ]);

        $secretKey = config('services.stripe.secret');

        // BYPASS STRIPE FOR LOCAL TESTING IF PLACEHOLDER KEY IS DETECTED
        if (str_contains($secretKey, 'your_secret_key_here')) {
            $booking->update([
                'status' => 'confirmed',
                'stripe_session_id' => 'mock_session_' . uniqid(),
                'stripe_payment_intent_id' => 'mock_pi_' . uniqid(),
            ]);
            return redirect(route('booking.success') . '?booking_id=' . $booking->id);
        }

        // Create Stripe Checkout Session
        Stripe::setApiKey($secretKey);

        try {
            $session = StripeSession::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency'     => 'bdt',
                        'product_data' => [
                            'name'        => 'Guide Booking: ' . $guide->name,
                            'description' => $guide->speciality . ' — ' . $numDays . ' day(s)',
                        ],
                        'unit_amount' => $totalAmount * 100, // Stripe uses smallest currency unit (paisa)
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
        $secretKey = config('services.stripe.secret');

        if ($request->query('session_id') && !str_contains($secretKey, 'your_secret_key_here')) {
            try {
                Stripe::setApiKey($secretKey);
                $session = StripeSession::retrieve($request->query('session_id'));

                if ($session->payment_status === 'paid') {
                    $booking->update([
                        'status' => 'confirmed',
                        'stripe_payment_intent_id' => $session->payment_intent,
                    ]);
                }
            } catch (\Exception $e) {
                // Log error but still show page — payment may have succeeded
            }
        }

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
        return view('booking_receipt', compact('booking'));
    }
}
