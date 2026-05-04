<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout | Travel Navigator Explorer</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Inter', sans-serif; display: flex; min-height: 100vh; color: #30313d; }
        .left-panel { flex: 1; background-color: #f7f7f9; padding: 60px; border-right: 1px solid #e6e6e6; display: flex; flex-direction: column; justify-content: center; }
        .right-panel { flex: 1; background-color: #ffffff; padding: 60px; display: flex; flex-direction: column; justify-content: center; align-items: center; }
        .back-link { display: inline-flex; align-items: center; color: #6b7280; text-decoration: none; font-size: 14px; font-weight: 500; margin-bottom: 40px; }
        .back-link:hover { color: #111827; }
        .test-mode-badge { display: inline-block; background-color: #fcebe5; color: #c84801; font-size: 12px; font-weight: 600; padding: 4px 8px; border-radius: 4px; margin-bottom: 16px; text-transform: uppercase; letter-spacing: 0.5px; }
        .amount { font-size: 36px; font-weight: 600; color: #1f2937; margin-bottom: 24px; }
        .summary-item { display: flex; justify-content: space-between; font-size: 15px; color: #4b5563; margin-bottom: 12px; }
        .summary-item strong { color: #1f2937; font-weight: 500; }
        .divider { height: 1px; background-color: #e5e7eb; margin: 24px 0; }
        
        .checkout-form { width: 100%; max-width: 400px; }
        .checkout-title { font-size: 20px; font-weight: 600; margin-bottom: 24px; color: #1f2937; }
        .form-group { margin-bottom: 16px; }
        .form-label { display: block; font-size: 14px; font-weight: 500; color: #374151; margin-bottom: 6px; }
        .form-input { width: 100%; padding: 12px; font-size: 15px; border: 1px solid #d1d5db; border-radius: 6px; transition: border-color 0.15s, box-shadow 0.15s; outline: none; }
        .form-input:focus { border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.2); }
        .card-element { border: 1px solid #d1d5db; border-radius: 6px; overflow: hidden; display: flex; flex-direction: column; }
        .card-element .row { display: flex; }
        .card-element .row:not(:last-child) { border-bottom: 1px solid #d1d5db; }
        .card-element .col { flex: 1; }
        .card-element .col:not(:last-child) { border-right: 1px solid #d1d5db; }
        .card-element input { border: none; border-radius: 0; box-shadow: none; width: 100%; padding: 12px; font-size: 15px; outline: none; }
        .card-element input:focus { background-color: #f9fafb; }
        .pay-button { width: 100%; background-color: #000000; color: #ffffff; font-size: 16px; font-weight: 600; padding: 14px; border: none; border-radius: 6px; cursor: pointer; margin-top: 24px; transition: background-color 0.2s; }
        .pay-button:hover { background-color: #1f2937; }
        .secure-badge { text-align: center; font-size: 13px; color: #6b7280; margin-top: 16px; display: flex; align-items: center; justify-content: center; gap: 6px; }
        
        @media (max-width: 768px) {
            body { flex-direction: column; }
            .left-panel, .right-panel { padding: 30px 20px; }
            .left-panel { border-right: none; border-bottom: 1px solid #e6e6e6; }
        }
    </style>
</head>
<body>

    <div class="left-panel">
        <div style="max-width: 400px; margin: 0 auto; width: 100%;">
            <a href="{{ route('booking.cancel', ['booking_id' => $booking->id]) }}" class="back-link">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                Travel Navigator Explorer
            </a>
            
            <div class="test-mode-badge">Test Mode</div>
            <div class="amount">৳{{ number_format($booking->total_amount, 2) }}</div>
            
            <div class="summary-item">
                <span>Guide Booking: {{ $booking->guide->name }}</span>
                <strong>৳{{ number_format($booking->total_amount, 2) }}</strong>
            </div>
            <div class="summary-item" style="font-size: 13px; margin-top: -8px;">
                <span>{{ $booking->guide->speciality }} — {{ $booking->num_days }} day(s)</span>
            </div>
            
            <div class="divider"></div>
            
            <div class="summary-item">
                <strong>Total due</strong>
                <strong>৳{{ number_format($booking->total_amount, 2) }}</strong>
            </div>
        </div>
    </div>

    <div class="right-panel">
        <form class="checkout-form" method="POST" action="{{ route('booking.dummy.process') }}">
            @csrf
            <input type="hidden" name="booking_id" value="{{ $booking->id }}">
            
            <h2 class="checkout-title">Payment details</h2>
            
            <div class="form-group">
                <label class="form-label">Email</label>
                <input type="email" class="form-input" value="{{ $booking->customer_email }}" required>
            </div>
            
            <div class="form-group">
                <label class="form-label">Card information</label>
                <div class="card-element">
                    <div class="row">
                        <input type="text" placeholder="Card number" value="4242 4242 4242 4242" required>
                    </div>
                    <div class="row">
                        <div class="col">
                            <input type="text" placeholder="MM / YY" value="12 / 34" required>
                        </div>
                        <div class="col">
                            <input type="text" placeholder="CVC" value="123" required>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="form-group">
                <label class="form-label">Name on card</label>
                <input type="text" class="form-input" value="{{ $booking->customer_name }}" required>
            </div>
            
            <div class="form-group">
                <label class="form-label">Country or region</label>
                <select class="form-input">
                    <option value="BD">Bangladesh</option>
                    <option value="US">United States</option>
                    <option value="UK">United Kingdom</option>
                </select>
            </div>
            
            <button type="submit" class="pay-button">Pay ৳{{ number_format($booking->total_amount, 2) }}</button>
            
            <div class="secure-badge">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                Payments are secure and encrypted.
            </div>
        </form>
    </div>

</body>
</html>
