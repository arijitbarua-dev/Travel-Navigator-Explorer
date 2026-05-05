<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cost Summary — Hotel</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --ink:       #1a1a18;
            --cream:     #f7f4ef;
            --warm:      #ede8df;
            --accent:    #c8753a;
            --accent-lt: #f0e0d0;
            --muted:     #7a7671;
            --white:     #ffffff;
            --radius:    12px;
            --shadow:    0 4px 24px rgba(26,26,24,.08);
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--cream);
            color: var(--ink);
            min-height: 100vh;
        }

        /* ── HERO ── */
        .hero {
            position: relative;
            height: 240px;
            background: var(--ink);
            overflow: hidden;
        }
        .hero::before {
            content: '';
            position: absolute; inset: 0;
            background: linear-gradient(135deg, #2a2420 0%, #3d2e1e 50%, #1a1a18 100%);
            opacity: .9;
        }
        .hero::after {
            content: '🧾';
            position: absolute;
            font-size: 18rem; opacity: .04;
            right: -40px; bottom: -40px; line-height: 1;
        }
        .hero-content {
            position: relative; z-index: 1;
            height: 100%;
            display: flex; flex-direction: column;
            justify-content: center;
            padding: 0 56px;
        }
        .hero-tag {
            font-size: .72rem; font-weight: 500;
            letter-spacing: 3px; text-transform: uppercase;
            color: var(--accent-lt); margin-bottom: 12px;
        }
        .hero h1 {
            font-family: 'Playfair Display', serif;
            font-size: clamp(1.8rem, 3.5vw, 2.6rem);
            color: var(--white); line-height: 1.2; margin-bottom: 12px;
        }
        .hero p { font-size: .93rem; color: rgba(255,255,255,.6); }

        /* ── MAIN ── */
        .main {
            max-width: 520px;
            margin: 0 auto;
            padding: 56px 40px 80px;
        }

        /* ── SUMMARY CARD ── */
        .summary-card {
            background: var(--white);
            border: 1px solid var(--warm);
            border-radius: var(--radius);
            padding: 40px 36px;
            box-shadow: var(--shadow);
        }
        .summary-card h2 {
            font-family: 'Playfair Display', serif;
            font-size: 1.4rem; font-weight: 700;
            margin-bottom: 28px;
            padding-bottom: 16px;
            border-bottom: 1px solid var(--warm);
            text-align: center;
        }

        /* ── ROOM INFO ── */
        .room-info {
            background: var(--cream);
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 24px;
            border: 1px solid var(--warm);
        }
        .room-info h3 {
            font-family: 'Playfair Display', serif;
            font-size: 1.15rem; font-weight: 700;
            margin-bottom: 12px;
            text-align: center;
        }
        .info-row {
            display: flex; justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid var(--warm);
            font-size: .9rem;
        }
        .info-row:last-child { border-bottom: none; }
        .info-row .label { color: var(--muted); }
        .info-row .value { font-weight: 500; color: var(--ink); }

        /* ── TOTAL ── */
        .total-box {
            background: var(--ink);
            border-radius: 10px;
            padding: 24px;
            text-align: center;
            margin-bottom: 28px;
        }
        .total-box .total-label {
            font-size: .78rem; font-weight: 500;
            letter-spacing: 2px; text-transform: uppercase;
            color: rgba(255,255,255,.55);
            margin-bottom: 8px;
        }
        .total-box .total-amount {
            font-family: 'Playfair Display', serif;
            font-size: 2.6rem; font-weight: 700;
            color: var(--accent-lt);
            line-height: 1;
        }
        .total-box .total-sub {
            font-size: .82rem;
            color: rgba(255,255,255,.45);
            margin-top: 8px;
        }

        /* ── ACTIONS ── */
        .actions { display: flex; gap: 12px; }
        .btn-primary {
            flex: 1; padding: 13px;
            background: var(--accent); color: var(--white);
            border: none; border-radius: 8px;
            font-family: 'DM Sans', sans-serif;
            font-size: .93rem; font-weight: 500;
            text-align: center; text-decoration: none;
            cursor: pointer;
            transition: background .18s;
            display: inline-block;
        }
        .btn-primary:hover { background: #b5622c; }
        .btn-secondary {
            flex: 1; padding: 13px;
            background: var(--white); color: var(--muted);
            border: 1.5px solid var(--warm); border-radius: 8px;
            font-family: 'DM Sans', sans-serif;
            font-size: .93rem; font-weight: 500;
            text-align: center; text-decoration: none;
            transition: border-color .18s, color .18s;
            display: inline-block;
        }
        .btn-secondary:hover { border-color: var(--accent); color: var(--accent); }

        /* ── RESPONSIVE ── */
        @media (max-width: 600px) {
            .hero-content { padding: 0 24px; }
            .main { padding: 40px 20px 60px; }
            .summary-card { padding: 28px 20px; }
            .actions { flex-direction: column; }
        }
    </style>
</head>
<body>

<!-- HERO -->
<div class="hero">
    <div class="hero-content">
        <span class="hero-tag">Your Estimate</span>
        <h1>Cost Summary</h1>
        <p>Here's a breakdown of your stay cost.</p>
    </div>
</div>

<!-- MAIN -->
<div class="main">
    <div class="summary-card">
        <h2>Booking Estimate</h2>

        <!-- ROOM DETAILS -->
        <div class="room-info">
            <h3>{{ $room->name }}</h3>
            <div class="info-row">
                <span class="label">Price per night</span>
                <span class="value">৳ {{ number_format($room->price) }}</span>
            </div>
            <div class="info-row">
                <span class="label">Number of nights</span>
                <span class="value">{{ $nights }} night{{ $nights > 1 ? 's' : '' }}</span>
            </div>
        </div>

        <!-- TOTAL -->
        <div class="total-box">
            <p class="total-label">Total Cost</p>
            <p class="total-amount">৳ {{ number_format($total) }}</p>
            <p class="total-sub">{{ $room->name }} &times; {{ $nights }} night{{ $nights > 1 ? 's' : '' }}</p>
        </div>

        <!-- ACTIONS -->
        <div class="actions">
            <a href="/cost" class="btn-secondary">← Calculate Again</a>
            <a href="/rooms" class="btn-primary" style="background: #6b7280;">View All Rooms</a>
        </div>
        <div class="actions" style="margin-top: 12px;">
            <a href="{{ route('room.booking') }}?room_id={{ $room->id }}" class="btn-primary" style="background: #0f766e;">Book Now</a>
        </div>

    </div>
</div>

</body>
</html>