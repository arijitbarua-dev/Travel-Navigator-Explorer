<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Guide;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;

class BookingController extends Controller
{
    public function store(Request $request, $guideId)
    {
        $guide = Guide::findOrFail($guideId);
        $user = $request->user();

        $request->validate([
            'start_date' => 'required|date|after_or_equal:today',
            'end_date'   => 'required|date|after:start_date',
            'notes'      => 'nullable|string|max:1000',
        ]);

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
            'user_id'        => $user->id,
            'guide_id'       => $guide->id,
            'destination_id' => $guide->destination_id,
            'customer_name'  => $user->name,
            'customer_email' => $user->email,
            'guest_name'     => $user->name,
            'guest_email'    => $user->email,
            'start_date'     => $request->start_date,
            'end_date'       => $request->end_date,
            'num_days'       => $numDays,
            'total_amount'   => $totalAmount,
            'notes'          => $request->notes,
            'status'         => 'pending',
        ]);

        // Create Stripe Checkout Session
        $secretKey = config('services.stripe.secret');
        Stripe::setApiKey($secretKey);

        try {
            // Convert BDT to USD (approximate rate: 1 USD ≈ 110 BDT)
            $totalInUsd = round($totalAmount / 110, 2);
            $totalInCents = (int) round($totalInUsd * 100);

            // Ensure minimum charge of $0.50 (Stripe minimum)
            if ($totalInCents < 50) {
                $totalInCents = 50;
            }

            $session = StripeSession::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency'     => 'usd',
                        'product_data' => [
                            'name'        => 'Guide Booking: ' . $guide->name,
                            'description' => $guide->specialization . ' — ' . $numDays . ' day(s)',
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

            // Store stripe session ID
            $booking->update(['stripe_session_id' => $session->id]);

            return redirect($session->url);

        } catch (\Exception $e) {
            $booking->update(['status' => 'cancelled']);
            return redirect()->back()->with('error', 'Payment initialization failed: ' . $e->getMessage());
        }
    }
}