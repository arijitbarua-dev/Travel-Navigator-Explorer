<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmed — Travel Navigator Explorer</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
        :root{--ink:#1a1a18;--cream:#f7f4ef;--warm:#ede8df;--accent:#c8753a;--accent-lt:#f0e0d0;--muted:#7a7671;--white:#fff;--success:#2d8a4e;--success-lt:#e6f4ec;--radius:12px;--shadow:0 4px 24px rgba(26,26,24,.08)}
        body{font-family:'DM Sans',sans-serif;background:var(--cream);color:var(--ink);min-height:100vh;display:flex;align-items:center;justify-content:center;padding:40px 20px}
        .card{background:var(--white);border:1px solid var(--warm);border-radius:16px;padding:48px 40px;max-width:520px;width:100%;box-shadow:var(--shadow);text-align:center}
        .success-icon{width:80px;height:80px;background:var(--success-lt);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 24px;animation:popIn .5s cubic-bezier(.17,.67,.35,1.25);font-size:2.2rem}
        @keyframes popIn{0%{transform:scale(0);opacity:0}100%{transform:scale(1);opacity:1}}
        .card h1{font-family:'Playfair Display',serif;font-size:1.6rem;font-weight:700;color:var(--success);margin-bottom:8px}
        .card .message{font-size:.93rem;color:var(--muted);margin-bottom:32px;line-height:1.6}
        .details-box{background:var(--cream);border:1px solid var(--warm);border-radius:10px;padding:24px;text-align:left;margin-bottom:28px}
        .details-box h3{font-family:'Playfair Display',serif;font-size:1rem;font-weight:700;margin-bottom:16px;padding-bottom:12px;border-bottom:1px solid var(--warm)}
        .detail-row{display:flex;justify-content:space-between;padding:8px 0;font-size:.88rem;border-bottom:1px solid rgba(237,232,223,.5)}
        .detail-row:last-child{border-bottom:none}
        .detail-row .label{color:var(--muted)}
        .detail-row .value{font-weight:500}
        .detail-row.total{border-top:1.5px solid var(--warm);border-bottom:none;padding-top:14px;margin-top:6px}
        .detail-row.total .label{font-family:'Playfair Display',serif;font-weight:700;color:var(--ink);font-size:.95rem}
        .detail-row.total .value{font-family:'Playfair Display',serif;font-weight:700;color:var(--accent);font-size:1.2rem}
        .transaction-id{font-size:.78rem;color:var(--muted);background:var(--cream);padding:8px 14px;border-radius:6px;margin-bottom:24px;word-break:break-all}
        .btn-group{display:flex;gap:12px}
        .btn{flex:1;padding:13px;border:none;border-radius:8px;font-family:'DM Sans',sans-serif;font-size:.9rem;font-weight:500;cursor:pointer;text-decoration:none;text-align:center;transition:background .18s,transform .1s}
        .btn:active{transform:scale(.97)}
        .btn-primary{background:var(--accent);color:var(--white)}
        .btn-primary:hover{background:#b5622c}
        .btn-secondary{background:var(--cream);color:var(--muted);border:1.5px solid var(--warm)}
        .btn-secondary:hover{border-color:var(--accent);color:var(--accent)}
        @media(max-width:480px){.card{padding:32px 24px}.btn-group{flex-direction:column}}
    </style>
</head>
<body>
<div class="card">
    <div class="success-icon">✅</div>
    <h1>Booking Confirmed!</h1>
    <p class="message">
        Your guide booking has been successfully processed.<br>
        A confirmation has been sent to <strong>{{ $booking->customer_email }}</strong>.
    </p>
    <div class="details-box">
        <h3>Booking Summary</h3>
        <div class="detail-row">
            <span class="label">Booking ID</span>
            <span class="value">#TVN-{{ str_pad($booking->id, 5, '0', STR_PAD_LEFT) }}</span>
        </div>
        <div class="detail-row">
            <span class="label">Guide</span>
            <span class="value">{{ $booking->guide->name }}</span>
        </div>
        <div class="detail-row">
            <span class="label">Speciality</span>
            <span class="value">{{ $booking->guide->speciality }}</span>
        </div>
        <div class="detail-row">
            <span class="label">Start Date</span>
            <span class="value">{{ $booking->start_date->format('d M, Y') }}</span>
        </div>
        <div class="detail-row">
            <span class="label">End Date</span>
            <span class="value">{{ $booking->end_date->format('d M, Y') }}</span>
        </div>
        <div class="detail-row">
            <span class="label">Duration</span>
            <span class="value">{{ $booking->num_days }} day{{ $booking->num_days > 1 ? 's' : '' }}</span>
        </div>
        <div class="detail-row">
            <span class="label">Rate</span>
            <span class="value">৳{{ number_format($booking->guide->price_per_day) }}/day</span>
        </div>
        <div class="detail-row total">
            <span class="label">Total Paid</span>
            <span class="value">৳{{ number_format($booking->total_amount) }}</span>
        </div>
    </div>
    @if($booking->stripe_payment_intent_id)
    <div class="transaction-id">Transaction ID: {{ $booking->stripe_payment_intent_id }}</div>
    @endif
    <div class="btn-group">
        <a href="{{ route('booking.receipt', $booking->id) }}" class="btn btn-primary">📄 View Receipt</a>
        <a href="{{ route('home') }}" class="btn btn-secondary">← Back to Home</a>
    </div>
</div>
</body>
</html>
