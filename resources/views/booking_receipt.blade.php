<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt #TVN-{{ str_pad($booking->id, 5, '0', STR_PAD_LEFT) }} — Travel Navigator Explorer</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
        :root{--ink:#1a1a18;--cream:#f7f4ef;--warm:#ede8df;--accent:#c8753a;--accent-lt:#f0e0d0;--muted:#7a7671;--white:#fff;--radius:12px;--shadow:0 4px 24px rgba(26,26,24,.08)}
        body{font-family:'DM Sans',sans-serif;background:var(--cream);color:var(--ink);min-height:100vh;padding:40px 20px}
        .receipt-wrapper{max-width:600px;margin:0 auto}
        .no-print{margin-bottom:20px;display:flex;gap:12px}
        .btn{padding:11px 24px;border:none;border-radius:8px;font-family:'DM Sans',sans-serif;font-size:.88rem;font-weight:500;cursor:pointer;text-decoration:none;transition:background .18s}
        .btn-print{background:var(--accent);color:var(--white)}
        .btn-print:hover{background:#b5622c}
        .btn-back{background:var(--white);color:var(--muted);border:1.5px solid var(--warm)}
        .btn-back:hover{border-color:var(--accent);color:var(--accent)}
        .receipt{background:var(--white);border:1px solid var(--warm);border-radius:16px;padding:48px 40px;box-shadow:var(--shadow)}
        .receipt-header{text-align:center;margin-bottom:32px;padding-bottom:24px;border-bottom:2px solid var(--warm)}
        .receipt-header h1{font-family:'Playfair Display',serif;font-size:1.5rem;color:var(--ink);margin-bottom:4px}
        .receipt-header .company{font-size:.85rem;color:var(--muted);margin-bottom:12px}
        .receipt-header .ref{font-size:.82rem;color:var(--accent);font-weight:600;background:var(--accent-lt);padding:6px 16px;border-radius:20px;display:inline-block}
        .receipt-section{margin-bottom:24px}
        .receipt-section h3{font-family:'Playfair Display',serif;font-size:1rem;font-weight:700;margin-bottom:14px;padding-bottom:10px;border-bottom:1px solid var(--warm);color:var(--ink)}
        .info-grid{display:grid;grid-template-columns:1fr 1fr;gap:12px 24px}
        .info-item .label{font-size:.78rem;color:var(--muted);text-transform:uppercase;letter-spacing:.5px;margin-bottom:2px}
        .info-item .value{font-size:.9rem;font-weight:500}
        .cost-table{width:100%;border-collapse:collapse}
        .cost-table th{text-align:left;font-size:.78rem;color:var(--muted);text-transform:uppercase;letter-spacing:.5px;padding:8px 0;border-bottom:1px solid var(--warm)}
        .cost-table th:last-child{text-align:right}
        .cost-table td{padding:12px 0;font-size:.9rem;border-bottom:1px solid rgba(237,232,223,.5)}
        .cost-table td:last-child{text-align:right;font-weight:500}
        .cost-table .total-row td{border-top:2px solid var(--warm);border-bottom:none;padding-top:16px;font-weight:700}
        .cost-table .total-row td:last-child{font-family:'Playfair Display',serif;font-size:1.3rem;color:var(--accent)}
        .receipt-footer{text-align:center;margin-top:32px;padding-top:24px;border-top:1px solid var(--warm)}
        .receipt-footer .status{display:inline-block;padding:6px 20px;border-radius:20px;font-size:.82rem;font-weight:600}
        .status-confirmed{background:#e6f4ec;color:#2d8a4e}
        .status-pending{background:#fef3c7;color:#92400e}
        .status-cancelled{background:#fef2f2;color:#991b1b}
        .receipt-footer .txn{font-size:.75rem;color:var(--muted);margin-top:12px;word-break:break-all}
        .receipt-footer .date{font-size:.78rem;color:var(--muted);margin-top:8px}
        @media print{
            body{background:#fff;padding:0}
            .no-print{display:none!important}
            .receipt{box-shadow:none;border:none;padding:24px}
            .receipt-wrapper{max-width:100%}
        }
        @media(max-width:500px){
            .receipt{padding:28px 20px}
            .info-grid{grid-template-columns:1fr}
        }
    </style>
</head>
<body>
<div class="receipt-wrapper">
    <div class="no-print">
        <button onclick="window.print()" class="btn btn-print">🖨️ Print Receipt</button>
        <a href="{{ route('home') }}" class="btn btn-back">← Back to Home</a>
    </div>
    <div class="receipt">
        <div class="receipt-header">
            <h1>Travel<span style="color:var(--accent)">Navigator</span> Explorer</h1>
            <p class="company">Cox's Bazar Tour Guide Booking Service</p>
            <span class="ref">Receipt #TVN-{{ str_pad($booking->id, 5, '0', STR_PAD_LEFT) }}</span>
        </div>
        <div class="receipt-section">
            <h3>Customer Information</h3>
            <div class="info-grid">
                <div class="info-item">
                    <p class="label">Name</p>
                    <p class="value">{{ $booking->customer_name }}</p>
                </div>
                <div class="info-item">
                    <p class="label">Email</p>
                    <p class="value">{{ $booking->customer_email }}</p>
                </div>
                @if($booking->customer_phone)
                <div class="info-item">
                    <p class="label">Phone</p>
                    <p class="value">{{ $booking->customer_phone }}</p>
                </div>
                @endif
            </div>
        </div>
        <div class="receipt-section">
            <h3>Guide Details</h3>
            <div class="info-grid">
                <div class="info-item">
                    <p class="label">Guide Name</p>
                    <p class="value">{{ $booking->guide->name }}</p>
                </div>
                <div class="info-item">
                    <p class="label">Speciality</p>
                    <p class="value">{{ $booking->guide->speciality }}</p>
                </div>
                <div class="info-item">
                    <p class="label">Start Date</p>
                    <p class="value">{{ $booking->start_date->format('d M, Y') }}</p>
                </div>
                <div class="info-item">
                    <p class="label">End Date</p>
                    <p class="value">{{ $booking->end_date->format('d M, Y') }}</p>
                </div>
            </div>
        </div>
        <div class="receipt-section">
            <h3>Cost Breakdown</h3>
            <table class="cost-table">
                <thead><tr><th>Description</th><th>Amount</th></tr></thead>
                <tbody>
                    <tr>
                        <td>Guide Fee (৳{{ number_format($booking->guide->price_per_day) }}/day × {{ $booking->num_days }} day{{ $booking->num_days > 1 ? 's' : '' }})</td>
                        <td>৳{{ number_format($booking->total_amount) }}</td>
                    </tr>
                    <tr class="total-row">
                        <td>Total</td>
                        <td>৳{{ number_format($booking->total_amount) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="receipt-footer">
            <span class="status status-{{ $booking->status }}">{{ ucfirst($booking->status) }}</span>
            @if($booking->stripe_payment_intent_id)
            <p class="txn">Transaction ID: {{ $booking->stripe_payment_intent_id }}</p>
            @endif
            <p class="date">Issued on {{ $booking->created_at->format('d M, Y \a\t h:i A') }}</p>
        </div>
    </div>
</div>
</body>
</html>
