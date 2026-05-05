<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Travel Budget Calculator</title>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,400;0,9..144,600;1,9..144,400&family=Syne:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --ink:        #18181A;
            --ink-mid:    #3a3a3c;
            --ink-soft:   #78787e;
            --cream:      #F5F2ED;
            --warm:       #EAE5DC;
            --white:      #ffffff;
            --rust:       #C26B3A;
            --rust-lt:    #F2E4D8;
            --rust-dk:    #9B4E24;
            --radius-sm:  8px;
            --radius-md:  14px;
            --radius-lg:  20px;
        }

        html { scroll-behavior: smooth; }

        body {
            font-family: 'Syne', sans-serif;
            background: var(--cream);
            color: var(--ink);
            min-height: 100vh;
        }

        /* ── HERO ── */
        .hero {
            background: var(--ink);
            padding: 64px 0 56px;
            position: relative;
            overflow: hidden;
        }
        .hero-texture {
            position: absolute; inset: 0;
            background-image:
                repeating-linear-gradient(0deg, transparent, transparent 39px, rgba(255,255,255,.03) 39px, rgba(255,255,255,.03) 40px),
                repeating-linear-gradient(90deg, transparent, transparent 39px, rgba(255,255,255,.03) 39px, rgba(255,255,255,.03) 40px);
        }
        .hero-glow {
            position: absolute;
            width: 400px; height: 400px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(194,107,58,.18) 0%, transparent 70%);
            right: -80px; top: -80px;
            pointer-events: none;
        }
        .hero-inner {
            position: relative; z-index: 1;
            max-width: 1100px; margin: 0 auto; padding: 0 48px;
            display: flex; align-items: flex-end; justify-content: space-between; gap: 40px;
        }
        .hero-eyebrow {
            display: inline-flex; align-items: center; gap: 8px;
            background: rgba(194,107,58,.15);
            border: 1px solid rgba(194,107,58,.3);
            border-radius: 100px;
            padding: 5px 14px;
            font-size: 11px; font-weight: 500; letter-spacing: 2px;
            text-transform: uppercase; color: #e8a87c;
            margin-bottom: 20px;
        }
        .hero-eyebrow::before {
            content: '';
            width: 6px; height: 6px; border-radius: 50%;
            background: var(--rust); flex-shrink: 0;
        }
        .hero h1 {
            font-family: 'Fraunces', serif;
            font-size: clamp(2.4rem, 4.5vw, 3.6rem);
            font-weight: 600; color: var(--white);
            line-height: 1.1; margin-bottom: 14px;
        }
        .hero h1 em { font-style: italic; color: #e8a87c; }
        .hero-desc {
            font-size: 14px; color: rgba(255,255,255,.45);
            max-width: 380px; line-height: 1.65;
        }
        .hero-stat {
            display: flex; flex-direction: column; align-items: flex-end; flex-shrink: 0;
        }
        .hero-stat-num {
            font-family: 'Fraunces', serif;
            font-size: 52px; font-weight: 400; color: rgba(255,255,255,.08);
            line-height: 1;
        }
        .hero-stat-label { font-size: 11px; color: rgba(255,255,255,.2); letter-spacing: 1px; }

        /* ── LAYOUT ── */
        .page {
            max-width: 1100px; margin: 0 auto;
            padding: 40px 48px 80px;
        }
        .layout {
            display: grid;
            grid-template-columns: 400px 1fr;
            gap: 24px;
            align-items: start;
        }

        /* ── CARD BASE ── */
        .card {
            background: var(--white);
            border: 1px solid var(--warm);
            border-radius: var(--radius-lg);
            overflow: hidden;
        }

        /* ── FORM CARD ── */
        .form-card-header {
            padding: 28px 28px 20px;
            border-bottom: 1px solid var(--warm);
        }
        .card-label {
            font-size: 10px; font-weight: 600; letter-spacing: 2.5px;
            text-transform: uppercase; color: var(--ink-soft);
            margin-bottom: 6px;
        }
        .card-title {
            font-family: 'Fraunces', serif;
            font-size: 20px; font-weight: 600; color: var(--ink);
        }
        .form-body { padding: 24px 28px 28px; }

        .form-group { margin-bottom: 18px; }
        .form-group label {
            display: block;
            font-size: 11px; font-weight: 600;
            letter-spacing: 1px; text-transform: uppercase;
            color: var(--ink-soft); margin-bottom: 8px;
        }
        .form-group input {
            font-family: 'Syne', sans-serif;
            font-size: 14px;
            padding: 12px 16px;
            border: 1.5px solid var(--warm);
            border-radius: var(--radius-sm);
            background: var(--cream);
            color: var(--ink);
            outline: none; width: 100%;
            transition: border-color .2s, background .2s, box-shadow .2s;
        }
        .form-group input:hover { border-color: #cbc5bb; }
        .form-group input:focus {
            border-color: var(--rust);
            background: var(--white);
            box-shadow: 0 0 0 3px rgba(194,107,58,.12);
        }
        .form-group input::placeholder { color: var(--ink-soft); opacity: .6; }
        .form-hint {
            font-size: 11px; color: var(--ink-soft);
            margin-top: 5px;
        }

        .divider {
            display: flex; align-items: center; gap: 12px;
            margin: 4px 0 18px;
        }
        .divider-line { flex: 1; height: 1px; background: var(--warm); }
        .divider-icon {
            width: 30px; height: 30px; border-radius: 50%;
            background: var(--rust-lt);
            border: 1px solid rgba(194,107,58,.25);
            display: flex; align-items: center; justify-content: center;
            font-size: 13px; color: var(--rust); flex-shrink: 0;
        }

        .submit-btn {
            width: 100%; padding: 14px 20px;
            background: var(--ink); color: var(--white);
            border: none; border-radius: var(--radius-sm);
            font-family: 'Syne', sans-serif;
            font-size: 14px; font-weight: 600;
            cursor: pointer; letter-spacing: .3px;
            transition: background .2s, transform .12s;
            display: flex; align-items: center; justify-content: center; gap: 8px;
        }
        .submit-btn:hover { background: var(--rust); }
        .submit-btn:active { transform: scale(.98); }
        .btn-arrow {
            width: 20px; height: 20px; border-radius: 50%;
            background: rgba(255,255,255,.15);
            display: flex; align-items: center; justify-content: center;
            font-size: 11px;
        }

        /* ── RIGHT COLUMN ── */
        .right-col { display: flex; flex-direction: column; gap: 20px; }

        /* ── RESULT CARD ── */
        .result-header {
            padding: 24px 24px 18px;
            border-bottom: 1px solid var(--warm);
            display: flex; align-items: center; justify-content: space-between;
        }
        .result-route-from { font-size: 13px; color: var(--ink-soft); }
        .result-route-name {
            font-family: 'Fraunces', serif;
            font-size: 16px; font-weight: 600;
        }
        .result-total-badge {
            background: var(--rust-lt);
            border: 1px solid rgba(194,107,58,.25);
            border-radius: 100px;
            padding: 6px 14px;
            font-family: 'Fraunces', serif;
            font-size: 15px; font-weight: 600; color: var(--rust-dk);
            white-space: nowrap;
        }

        /* ── BREAKDOWN GRID ── */
        .breakdown-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1px;
            background: var(--warm);
        }
        .breakdown-item {
            background: var(--white);
            padding: 18px 20px;
            display: flex; align-items: center; gap: 14px;
            transition: background .15s;
        }
        .breakdown-item:hover { background: #fdfbf9; }
        .b-icon {
            font-size: 20px; line-height: 1; flex-shrink: 0;
            width: 42px; height: 42px;
            background: var(--cream);
            border-radius: var(--radius-sm);
            display: flex; align-items: center; justify-content: center;
        }
        .b-info { flex: 1; min-width: 0; }
        .b-label {
            font-size: 11px; font-weight: 600; letter-spacing: .5px;
            text-transform: uppercase; color: var(--ink-soft); margin-bottom: 4px;
        }
        .b-desc {
            font-size: 12px; color: var(--ink-soft); margin-bottom: 4px;
        }
        .b-amount {
            font-family: 'Fraunces', serif;
            font-size: 17px; font-weight: 600; color: var(--ink);
            line-height: 1.1;
        }

        /* ── TOTAL ROW ── */
        .total-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1px;
            background: var(--warm);
            border-top: 1px solid var(--warm);
        }
        .total-item {
            background: var(--ink);
            padding: 20px 22px;
            display: flex; align-items: center; gap: 12px;
        }
        .t-icon {
            font-size: 18px; flex-shrink: 0;
            width: 38px; height: 38px;
            background: rgba(255,255,255,.07);
            border-radius: var(--radius-sm);
            display: flex; align-items: center; justify-content: center;
        }
        .t-label {
            font-size: 10px; font-weight: 600; letter-spacing: 1.5px;
            text-transform: uppercase; color: rgba(255,255,255,.4);
            margin-bottom: 3px;
        }
        .t-val {
            font-family: 'Fraunces', serif;
            font-size: 16px; font-weight: 600; color: var(--white);
        }
        .t-val.accent { color: #e8a87c; }

        /* ── EMPTY STATE ── */
        .empty-state { padding: 48px 24px; text-align: center; }
        .empty-icon  { font-size: 40px; margin-bottom: 14px; opacity: .35; }
        .empty-title {
            font-family: 'Fraunces', serif;
            font-size: 18px; color: var(--ink-soft); margin-bottom: 6px;
        }
        .empty-sub { font-size: 13px; color: var(--ink-soft); opacity: .7; }

        /* ── TIPS CARD ── */
        .tips-header {
            padding: 20px 24px 16px;
            border-bottom: 1px solid var(--warm);
            display: flex; align-items: center; gap: 10px;
        }
        .tips-dot {
            width: 8px; height: 8px; border-radius: 50%;
            background: var(--rust);
            box-shadow: 0 0 0 3px var(--rust-lt);
        }
        .tips-title { font-size: 13px; font-weight: 600; }
        .tips-body { padding: 16px 24px 20px; display: flex; flex-direction: column; gap: 12px; }
        .tip-item {
            display: flex; align-items: flex-start; gap: 12px;
        }
        .tip-num {
            font-family: 'Fraunces', serif;
            font-size: 22px; font-weight: 400; color: rgba(194,107,58,.3);
            line-height: 1; flex-shrink: 0; width: 24px;
        }
        .tip-text { font-size: 13px; color: var(--ink-soft); line-height: 1.55; padding-top: 2px; }

        /* ── RESPONSIVE ── */
        @media (max-width: 860px) {
            .hero-inner { flex-direction: column; align-items: flex-start; gap: 0; }
            .hero-stat  { display: none; }
            .layout     { grid-template-columns: 1fr; }
            .page       { padding: 28px 20px 60px; }
            .hero-inner { padding: 0 24px; }
        }
        @media (max-width: 480px) {
            .breakdown-grid { grid-template-columns: 1fr; }
            .total-row      { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

<!-- HERO -->
<div class="hero">
    <div class="hero-texture"></div>
    <div class="hero-glow"></div>
    <div class="hero-inner">
        <div class="hero-left">
            <div class="hero-eyebrow">Plan Your Trip</div>
            <h1>Travel <em>budget</em><br>calculator.</h1>
            <p class="hero-desc">Estimate your total travel cost — hotel, food, and transport — before you go.</p>
        </div>
        <div class="hero-stat">
            <span class="hero-stat-num">৳</span>
            <span class="hero-stat-label">BDT costs</span>
        </div>
    </div>
</div>

<!-- MAIN -->
<div class="page">
    <div class="layout">

        <!-- LEFT: FORM -->
        <div class="card">
            <div class="form-card-header">
                <p class="card-label">Budget planner</p>
                <h2 class="card-title">Enter your trip details</h2>
            </div>
            <div class="form-body">
                <form method="POST" action="/travel-budget/calculate">
                    @csrf

                    <div class="form-group">
                        <label for="hotel_cost">Hotel cost per night (৳)</label>
                        <input type="number" name="hotel_cost" id="hotel_cost" min="0" placeholder="e.g. 2000" value="{{ old('hotel_cost') }}" required>
                    </div>

                    <div class="form-group">
                        <label for="nights">Number of nights</label>
                        <input type="number" name="nights" id="nights" min="1" placeholder="e.g. 3" value="{{ old('nights') }}" required>
                        <span class="form-hint">Minimum 1 night</span>
                    </div>

                    <div class="divider">
                        <div class="divider-line"></div>
                        <div class="divider-icon">+</div>
                        <div class="divider-line"></div>
                    </div>

                    <div class="form-group">
                        <label for="food_per_day">Food cost per day (৳)</label>
                        <input type="number" name="food_per_day" id="food_per_day" min="0" placeholder="e.g. 500" value="{{ old('food_per_day') }}" required>
                    </div>

                    <div class="form-group">
                        <label for="transport_cost">Total transport cost (৳)</label>
                        <input type="number" name="transport_cost" id="transport_cost" min="0" placeholder="e.g. 1000" value="{{ old('transport_cost') }}" required>
                    </div>

                    <button type="submit" class="submit-btn">
                        Calculate total
                        <div class="btn-arrow">→</div>
                    </button>
                </form>
            </div>
        </div>

        <!-- RIGHT: RESULTS -->
        <div class="right-col">

            @isset($result)
            <div class="card">
                <div class="result-header">
                    <div>
                        <div class="result-route-from">{{ $result['nights'] }} night{{ $result['nights'] > 1 ? 's' : '' }} trip</div>
                        <div class="result-route-name">Cost breakdown</div>
                    </div>
                    <div class="result-total-badge">৳{{ number_format($result['total']) }}</div>
                </div>

                <div class="breakdown-grid">
                    <div class="breakdown-item">
                        <div class="b-icon">🏨</div>
                        <div class="b-info">
                            <div class="b-label">Hotel</div>
                            <div class="b-desc">{{ $result['nights'] }} night{{ $result['nights'] > 1 ? 's' : '' }} × ৳{{ number_format($result['hotel_cost']) }}</div>
                            <div class="b-amount">৳{{ number_format($result['hotel_total']) }}</div>
                        </div>
                    </div>

                    <div class="breakdown-item">
                        <div class="b-icon">🍽️</div>
                        <div class="b-info">
                            <div class="b-label">Food</div>
                            <div class="b-desc">{{ $result['nights'] }} day{{ $result['nights'] > 1 ? 's' : '' }} × ৳{{ number_format($result['food_per_day']) }}</div>
                            <div class="b-amount">৳{{ number_format($result['food_total']) }}</div>
                        </div>
                    </div>

                    <div class="breakdown-item">
                        <div class="b-icon">🚌</div>
                        <div class="b-info">
                            <div class="b-label">Transport</div>
                            <div class="b-desc">One-time cost</div>
                            <div class="b-amount">৳{{ number_format($result['transport_cost']) }}</div>
                        </div>
                    </div>

                    <div class="breakdown-item">
                        <div class="b-icon">📅</div>
                        <div class="b-info">
                            <div class="b-label">Duration</div>
                            <div class="b-desc">Trip length</div>
                            <div class="b-amount">{{ $result['nights'] }} night{{ $result['nights'] > 1 ? 's' : '' }}</div>
                        </div>
                    </div>
                </div>

                <div class="total-row">
                    <div class="total-item">
                        <div class="t-icon">💰</div>
                        <div>
                            <div class="t-label">Total budget</div>
                            <div class="t-val accent">৳{{ number_format($result['total']) }}</div>
                        </div>
                    </div>
                    <div class="total-item">
                        <div class="t-icon">📊</div>
                        <div>
                            <div class="t-label">Per day avg</div>
                            <div class="t-val">৳{{ number_format($result['total'] / max($result['nights'], 1)) }}</div>
                        </div>
                    </div>
                </div>
            </div>
            @else
            <div class="card">
                <div class="empty-state">
                    <div class="empty-icon">🧳</div>
                    <div class="empty-title">No estimate yet</div>
                    <div class="empty-sub">Fill in your trip details to see a full cost breakdown.</div>
                </div>
            </div>
            @endisset

            <!-- TIPS CARD -->
            <div class="card">
                <div class="tips-header">
                    <div class="tips-dot"></div>
                    <span class="tips-title">Budget tips</span>
                </div>
                <div class="tips-body">
                    <div class="tip-item">
                        <span class="tip-num">1</span>
                        <span class="tip-text">Book hotels in advance — rates drop significantly 2–3 weeks before check-in.</span>
                    </div>
                    <div class="tip-item">
                        <span class="tip-num">2</span>
                        <span class="tip-text">Local street food can cut your daily food cost by 60% without sacrificing taste.</span>
                    </div>
                    <div class="tip-item">
                        <span class="tip-num">3</span>
                        <span class="tip-text">Add a 10–15% buffer to your total for unexpected expenses and tips.</span>
                    </div>
                </div>
            </div>

        </div><!-- /right-col -->
    </div><!-- /layout -->
</div><!-- /page -->

</body>
</html>