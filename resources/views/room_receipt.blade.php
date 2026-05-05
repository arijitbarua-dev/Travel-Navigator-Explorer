<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Room Booking Receipt</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background: #f3f4f6; padding: 40px 20px; color: #333; }
        .receipt-container { max-width: 600px; margin: 0 auto; background: #fff; padding: 40px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        .header { text-align: center; border-bottom: 2px dashed #eee; padding-bottom: 20px; margin-bottom: 30px; }
        .header h1 { margin: 0; color: #2563eb; font-size: 28px; }
        .header p { color: #6b7280; margin-top: 5px; }
        .details-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 30px; }
        .detail-item label { display: block; font-size: 12px; color: #6b7280; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 4px; }
        .detail-item span { font-size: 16px; font-weight: 600; color: #1f2937; }
        .table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        .table th, .table td { padding: 15px 0; border-bottom: 1px solid #eee; text-align: left; }
        .table th { color: #6b7280; font-weight: 500; font-size: 14px; text-transform: uppercase; }
        .table td { font-size: 16px; font-weight: 500; color: #1f2937; }
        .table .right { text-align: right; }
        .total-row td { font-size: 20px; font-weight: bold; color: #2563eb; border-bottom: none; padding-top: 20px; }
        .actions { text-align: center; margin-top: 40px; }
        .btn { display: inline-block; padding: 12px 24px; border-radius: 6px; text-decoration: none; font-weight: 600; font-size: 14px; cursor: pointer; border: none; }
        .btn-print { background: #2563eb; color: #fff; margin-right: 10px; }
        .btn-back { background: #f3f4f6; color: #4b5563; }
        @media print {
            body { background: #fff; padding: 0; }
            .receipt-container { box-shadow: none; padding: 0; }
            .actions { display: none; }
        }
    </style>
</head>
<body>
    <div class="receipt-container">
        <div class="header">
            <h1>TravelNavigator</h1>
            <p>Payment Receipt</p>
        </div>

        <div class="details-grid">
            <div class="detail-item">
                <label>Receipt #</label>
                <span>{{ strtoupper(substr($session->id, -8)) }}</span>
            </div>
            <div class="detail-item">
                <label>Date</label>
                <span>{{ date('F j, Y') }}</span>
            </div>
            <div class="detail-item">
                <label>Customer</label>
                <span>{{ $metadata->customer_name }}</span>
            </div>
            <div class="detail-item">
                <label>Status</label>
                <span style="color: #059669;">PAID</span>
            </div>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th>Description</th>
                    <th class="right">Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Room Booking: {{ $metadata->room_name }} (1 night)</td>
                    <td class="right">৳{{ number_format($metadata->price) }}</td>
                </tr>
                <tr class="total-row">
                    <td>Total Paid</td>
                    <td class="right">৳{{ number_format($metadata->price) }}</td>
                </tr>
            </tbody>
        </table>

        <div class="actions">
            <button onclick="window.print()" class="btn btn-print">🖨️ Print Receipt</button>
            <a href="/dashboard" class="btn btn-back">Return to Dashboard</a>
        </div>
    </div>
</body>
</html>
