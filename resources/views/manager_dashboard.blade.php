<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manager Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,400;0,9..144,600;1,9..144,400&family=Syne:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --ink:       #18181A;
            --ink-mid:   #3a3a3c;
            --ink-soft:  #78787e;
            --cream:     #F5F2ED;
            --warm:      #EAE5DC;
            --white:     #ffffff;
            --rust:      #C26B3A;
            --rust-lt:   #F2E4D8;
            --rust-dk:   #9B4E24;
            --radius-sm: 8px;
            --radius-md: 14px;
            --radius-lg: 20px;
        }

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
            max-width: 400px; line-height: 1.65; margin: 0;
        }
        .hero-stat {
            display: flex; flex-direction: column; align-items: flex-end; flex-shrink: 0;
        }
        .hero-stat-num {
            font-family: 'Fraunces', serif;
            font-size: 72px; font-weight: 400; color: rgba(255,255,255,.07);
            line-height: 1;
        }
        .hero-stat-label {
            font-size: 11px; color: rgba(255,255,255,.2); letter-spacing: 1px;
            text-transform: uppercase;
        }

        /* ── PAGE ── */
        .page {
            max-width: 1100px; margin: 0 auto;
            padding: 40px 48px 80px;
        }

        /* ── GRID ── */
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
        }

        /* ── CARD ── */
        .card {
            background: var(--white);
            border: 1px solid var(--warm);
            border-radius: var(--radius-lg);
            padding: 28px 24px 22px;
            display: flex; flex-direction: column;
            text-decoration: none; color: inherit;
            transition: border-color .2s, box-shadow .2s;
        }
        .card:hover {
            border-color: var(--rust);
            box-shadow: 0 0 0 3px rgba(194,107,58,.1);
        }

        .card-icon {
            width: 46px; height: 46px; border-radius: 12px;
            background: var(--cream);
            border: 1px solid var(--warm);
            display: flex; align-items: center; justify-content: center;
            font-size: 18px; margin-bottom: 20px;
        }

        .card-label {
            font-size: 10px; font-weight: 600; letter-spacing: 2.5px;
            text-transform: uppercase; color: var(--ink-soft);
            margin-bottom: 6px;
        }

        .card-title {
            font-family: 'Fraunces', serif;
            font-size: 22px; font-weight: 600; color: var(--ink);
            margin-bottom: 8px;
        }

        .card-desc {
            font-size: 13px; color: var(--ink-soft);
            line-height: 1.6; flex: 1;
            margin-bottom: 22px;
        }

        .card-cta {
            display: inline-flex; align-items: center; gap: 7px;
            font-size: 12px; font-weight: 600; color: var(--ink);
            background: var(--cream);
            border: 1px solid var(--warm);
            border-radius: 100px;
            padding: 6px 14px;
            align-self: flex-start;
            transition: background .15s, color .15s, border-color .15s;
        }
        .card:hover .card-cta {
            background: var(--ink);
            color: var(--white);
            border-color: var(--ink);
        }
        .cta-arrow {
            width: 18px; height: 18px; border-radius: 50%;
            background: rgba(0,0,0,.08);
            display: flex; align-items: center; justify-content: center;
            font-size: 10px;
        }
        .card:hover .cta-arrow { background: rgba(255,255,255,.15); }

        /* ── RESPONSIVE ── */
        @media (max-width: 700px) {
            .hero-inner  { flex-direction: column; align-items: flex-start; padding: 0 24px; }
            .hero-stat   { display: none; }
            .page        { padding: 28px 20px 60px; }
            .dashboard-grid { grid-template-columns: 1fr 1fr; }
        }
        @media (max-width: 460px) {
            .dashboard-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

<!-- HERO -->
<div class="hero">
    <div class="hero-texture"></div>
    <div class="hero-glow"></div>
    <div class="hero-inner">
        <div>
            <div class="hero-eyebrow">Dashboard</div>
            <h1>Manager<br><em>Control Panel</em></h1>
            <p class="hero-desc">Manage hotels, rooms, routes, and guest conversations from one central place.</p>
        </div>
        <div class="hero-stat">
            <span class="hero-stat-num">4</span>
            <span class="hero-stat-label">Modules</span>
        </div>
    </div>
</div>

<!-- CARDS -->
<div class="page">
    <div class="dashboard-grid">

        <a class="card" href="/hotels">
            <div class="card-icon">🏨</div>
            <p class="card-label">Properties</p>
            <h2 class="card-title">Hotels</h2>
            <p class="card-desc">Add and manage hotel listings, details, and availability.</p>
            <span class="card-cta">Open <span class="cta-arrow">→</span></span>
        </a>

        <a class="card" href="/rooms">
            <div class="card-icon">🛏️</div>
            <p class="card-label">Inventory</p>
            <h2 class="card-title">Rooms</h2>
            <p class="card-desc">Configure room types, pricing, and availability status.</p>
            <span class="card-cta">Open <span class="cta-arrow">→</span></span>
        </a>

        <a class="card" href="/route-guide">
            <div class="card-icon">🗺️</div>
            <p class="card-label">Navigation</p>
            <h2 class="card-title">Route Guide</h2>
            <p class="card-desc">Hotel-to-destination directions and travel guides for guests.</p>
            <span class="card-cta">Open <span class="cta-arrow">→</span></span>
        </a>

        <a class="card" href="/chat/1">
            <div class="card-icon">💬</div>
            <p class="card-label">Messaging</p>
            <h2 class="card-title">Chat</h2>
            <p class="card-desc">Live conversations with tourists and hotel guests.</p>
            <span class="card-cta">Open <span class="cta-arrow">→</span></span>
        </a>
		<a class="card" href="/travel-budget">
			<div class="card-icon">💰</div>
			<p class="card-label">Planning</p>
			<h2 class="card-title">Travel Budget</h2>
			<p class="card-desc">Calculate total trip cost including hotel, food, transport, and guide.</p>
			<span class="card-cta">Open <span class="cta-arrow">→</span></span>
		</a>

    </div>
</div>

</body>
</html>