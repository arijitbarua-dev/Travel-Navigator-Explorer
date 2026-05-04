<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book a Tour Guide — Travel Navigator Explorer</title>
    <meta name="description" content="Book a professional local tour guide for your Cox's Bazar trip. Secure online payment.">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --ink:       #1a1a18;
            --cream:     #f7f4ef;
            --warm:      #ede8df;
            --accent:    #c8753a;
            --accent-lt: #f0e0d0;
            --accent-dk: #b5622c;
            --muted:     #7a7671;
            --white:     #ffffff;
            --success:   #2d8a4e;
            --success-lt:#e6f4ec;
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
            height: 280px;
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
            content: '🧭';
            position: absolute;
            font-size: 16rem; opacity: .04;
            right: -30px; bottom: -50px; line-height: 1;
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
            max-width: 1000px;
            margin: 0 auto;
            padding: 48px 40px 80px;
        }

        /* ── BACK LINK ── */
        .back-link {
            display: inline-flex; align-items: center; gap: 6px;
            font-size: .85rem; font-weight: 500;
            color: var(--muted); text-decoration: none;
            margin-bottom: 32px;
            transition: color .18s;
        }
        .back-link:hover { color: var(--accent); }

        /* ── ALERT ── */
        .alert {
            padding: 14px 20px;
            border-radius: 8px;
            margin-bottom: 24px;
            font-size: .9rem;
        }
        .alert-error {
            background: #fef2f2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }
        .alert-success {
            background: var(--success-lt);
            color: var(--success);
            border: 1px solid #bbf0ce;
        }

        /* ── SECTION TITLES ── */
        .section-title {
            font-family: 'Playfair Display', serif;
            font-size: 1.4rem; font-weight: 700;
            margin-bottom: 8px;
        }
        .section-subtitle {
            font-size: .88rem; color: var(--muted);
            margin-bottom: 28px;
        }

        /* ── GUIDE CARDS ── */
        .guides-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px;
            margin-bottom: 48px;
        }
        .guide-card {
            background: var(--white);
            border: 2px solid var(--warm);
            border-radius: var(--radius);
            padding: 24px;
            cursor: pointer;
            transition: border-color .2s, box-shadow .2s, transform .2s;
            position: relative;
        }
        .guide-card:hover {
            box-shadow: var(--shadow);
            transform: translateY(-3px);
        }
        .guide-card.selected {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px var(--accent-lt), var(--shadow);
        }
        .guide-card .check-badge {
            position: absolute; top: 12px; right: 12px;
            width: 26px; height: 26px;
            background: var(--accent);
            color: var(--white);
            border-radius: 50%;
            display: none;
            align-items: center; justify-content: center;
            font-size: .75rem; font-weight: 700;
        }
        .guide-card.selected .check-badge { display: flex; }

        .guide-photo {
            width: 72px; height: 72px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 14px;
            border: 3px solid var(--warm);
        }
        .guide-name {
            font-family: 'Playfair Display', serif;
            font-size: 1.1rem; font-weight: 700;
            margin-bottom: 4px;
        }
        .guide-speciality {
            font-size: .82rem; color: var(--muted);
            margin-bottom: 10px;
        }
        .guide-meta {
            display: flex; gap: 12px; align-items: center;
            margin-bottom: 10px;
        }
        .guide-price {
            font-size: .95rem; font-weight: 600;
            color: var(--accent);
        }
        .guide-rating {
            font-size: .82rem; color: var(--muted);
            display: flex; align-items: center; gap: 4px;
        }
        .guide-desc {
            font-size: .82rem; color: var(--muted);
            line-height: 1.5;
        }

        /* ── BOOKING FORM ── */
        .form-card {
            background: var(--white);
            border: 1px solid var(--warm);
            border-radius: var(--radius);
            padding: 36px;
            box-shadow: var(--shadow);
        }
        .form-card h2 {
            font-family: 'Playfair Display', serif;
            font-size: 1.3rem; font-weight: 700;
            margin-bottom: 8px;
        }
        .form-card .subtitle {
            font-size: .88rem; color: var(--muted);
            margin-bottom: 28px;
            padding-bottom: 20px;
            border-bottom: 1px solid var(--warm);
        }
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 18px;
        }
        .form-group {
            display: flex; flex-direction: column; gap: 6px;
        }
        .form-group.full { grid-column: 1 / -1; }
        .form-group label {
            font-size: .82rem; font-weight: 500;
            color: var(--muted); letter-spacing: .3px;
        }
        .form-group input,
        .form-group select {
            font-family: 'DM Sans', sans-serif;
            font-size: .93rem;
            padding: 12px 14px;
            border: 1.5px solid var(--warm);
            border-radius: 8px;
            background: var(--cream);
            color: var(--ink);
            outline: none;
            transition: border-color .18s, background .18s;
            width: 100%;
        }
        .form-group input:focus,
        .form-group select:focus {
            border-color: var(--accent);
            background: var(--white);
        }

        /* ── COST PREVIEW ── */
        .cost-preview {
            margin-top: 24px;
            padding: 20px 24px;
            background: linear-gradient(135deg, #fdf8f3 0%, #f7f0e6 100%);
            border: 1px solid var(--accent-lt);
            border-radius: 10px;
            display: none;
        }
        .cost-preview.visible { display: block; }
        .cost-row {
            display: flex; justify-content: space-between;
            font-size: .9rem; margin-bottom: 8px;
        }
        .cost-row span:last-child { font-weight: 500; }
        .cost-total {
            display: flex; justify-content: space-between;
            align-items: center;
            padding-top: 12px; margin-top: 8px;
            border-top: 1.5px solid var(--accent-lt);
        }
        .cost-total .label {
            font-family: 'Playfair Display', serif;
            font-size: 1rem; font-weight: 700;
        }
        .cost-total .amount {
            font-family: 'Playfair Display', serif;
            font-size: 1.5rem; font-weight: 700;
            color: var(--accent);
        }

        /* ── SUBMIT ── */
        .submit-btn {
            width: 100%; padding: 15px;
            background: var(--accent); color: var(--white);
            border: none; border-radius: 8px;
            font-family: 'DM Sans', sans-serif;
            font-size: 1rem; font-weight: 600;
            cursor: pointer; margin-top: 24px;
            transition: background .18s, transform .1s;
            display: flex; align-items: center; justify-content: center; gap: 8px;
        }
        .submit-btn:hover { background: var(--accent-dk); }
        .submit-btn:active { transform: scale(.98); }
        .submit-btn:disabled {
            background: var(--muted);
            cursor: not-allowed;
        }
        .submit-btn .lock-icon { font-size: 1rem; }

        /* ── RESPONSIVE ── */
        @media (max-width: 700px) {
            .hero-content { padding: 0 24px; }
            .main { padding: 32px 20px 60px; }
            .form-grid { grid-template-columns: 1fr; }
            .form-card { padding: 24px 20px; }
        }
    </style>
</head>
<body>

<!-- HERO -->
<div class="hero">
    <div class="hero-content">
        <span class="hero-tag">Secure Booking</span>
        <h1>Book a Tour Guide</h1>
        <p>Choose your guide, pick your dates, and pay securely online.</p>
    </div>
</div>

<!-- MAIN -->
<div class="main">
    <a href="{{ route('home') }}" class="back-link">← Back to Home</a>

    @if(session('error'))
        <div class="alert alert-error">{{ session('error') }}</div>
    @endif
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- STEP 1: SELECT GUIDE -->
    <h2 class="section-title">1. Choose Your Guide</h2>
    <p class="section-subtitle">Select a guide that matches your travel style and budget.</p>

    <div class="guides-grid">
        @foreach($guides as $guide)
        <div class="guide-card {{ $selectedGuideId == $guide->id ? 'selected' : '' }}"
             data-guide-id="{{ $guide->id }}"
             data-guide-price="{{ $guide->price_per_day }}"
             data-guide-name="{{ $guide->name }}"
             onclick="selectGuide(this)">

            <div class="check-badge">✓</div>

            <img src="{{ $guide->photo_url }}" alt="{{ $guide->name }}" class="guide-photo">
            <p class="guide-name">{{ $guide->name }}</p>
            <p class="guide-speciality">{{ $guide->speciality }}</p>
            <div class="guide-meta">
                <span class="guide-price">৳{{ number_format($guide->price_per_day) }}/day</span>
                <span class="guide-rating">⭐ {{ $guide->rating }}</span>
            </div>
            <p class="guide-desc">{{ $guide->description }}</p>
        </div>
        @endforeach
    </div>

    <!-- STEP 2: BOOKING FORM -->
    <div class="form-card">
        <h2>2. Booking Details</h2>
        <p class="subtitle">Fill in your details and trip dates to proceed to secure payment.</p>

        <form method="POST" action="{{ route('booking.checkout') }}" id="bookingForm">
            @csrf

            <input type="hidden" name="guide_id" id="guideIdInput" value="{{ $selectedGuideId }}">

            <div class="form-grid">
                <div class="form-group">
                    <label for="customer_name">Full Name *</label>
                    <input type="text" id="customer_name" name="customer_name"
                           placeholder="e.g. Arijit Barua" required
                           value="{{ old('customer_name', auth()->user()->name ?? '') }}">
                </div>
                <div class="form-group">
                    <label for="customer_email">Email Address *</label>
                    <input type="email" id="customer_email" name="customer_email"
                           placeholder="e.g. arijit@example.com" required
                           value="{{ old('customer_email', auth()->user()->email ?? '') }}">
                </div>
                <div class="form-group">
                    <label for="customer_phone">Phone Number</label>
                    <input type="tel" id="customer_phone" name="customer_phone"
                           placeholder="e.g. +880 1XXXXXXXXX"
                           value="{{ old('customer_phone') }}">
                </div>
                <div class="form-group">
                    <!-- spacer for grid alignment -->
                </div>
                <div class="form-group">
                    <label for="start_date">Start Date *</label>
                    <input type="date" id="start_date" name="start_date"
                           required value="{{ old('start_date') }}"
                           min="{{ date('Y-m-d') }}"
                           onchange="calculateCost()">
                </div>
                <div class="form-group">
                    <label for="end_date">End Date *</label>
                    <input type="date" id="end_date" name="end_date"
                           required value="{{ old('end_date') }}"
                           min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                           onchange="calculateCost()">
                </div>
            </div>

            <!-- COST PREVIEW -->
            <div class="cost-preview" id="costPreview">
                <div class="cost-row">
                    <span>Guide</span>
                    <span id="previewGuideName">—</span>
                </div>
                <div class="cost-row">
                    <span>Rate</span>
                    <span id="previewRate">—</span>
                </div>
                <div class="cost-row">
                    <span>Duration</span>
                    <span id="previewDays">—</span>
                </div>
                <div class="cost-total">
                    <span class="label">Total</span>
                    <span class="amount" id="previewTotal">৳0</span>
                </div>
            </div>

            <button type="submit" class="submit-btn" id="submitBtn" disabled>
                <span class="lock-icon">🔒</span>
                Proceed to Secure Payment →
            </button>
        </form>
    </div>
</div>

<script>
    let selectedGuidePrice = {{ $selectedGuideId ? \App\Models\Guide::find($selectedGuideId)?->price_per_day ?? 0 : 0 }};
    let selectedGuideName = '{{ $selectedGuideId ? \App\Models\Guide::find($selectedGuideId)?->name ?? '' : '' }}';

    function selectGuide(card) {
        // Remove selection from all cards
        document.querySelectorAll('.guide-card').forEach(c => c.classList.remove('selected'));

        // Select this card
        card.classList.add('selected');

        // Update hidden input
        const guideId = card.dataset.guideId;
        document.getElementById('guideIdInput').value = guideId;

        // Store price and name for calculation
        selectedGuidePrice = parseInt(card.dataset.guidePrice);
        selectedGuideName = card.dataset.guideName;

        calculateCost();
    }

    function calculateCost() {
        const startDate = document.getElementById('start_date').value;
        const endDate   = document.getElementById('end_date').value;
        const preview   = document.getElementById('costPreview');
        const submitBtn = document.getElementById('submitBtn');

        if (!selectedGuidePrice || !startDate || !endDate) {
            preview.classList.remove('visible');
            submitBtn.disabled = true;
            return;
        }

        const start = new Date(startDate);
        const end   = new Date(endDate);
        const days  = Math.ceil((end - start) / (1000 * 60 * 60 * 24));

        if (days < 1) {
            preview.classList.remove('visible');
            submitBtn.disabled = true;
            return;
        }

        const total = selectedGuidePrice * days;

        document.getElementById('previewGuideName').textContent = selectedGuideName;
        document.getElementById('previewRate').textContent = '৳' + selectedGuidePrice.toLocaleString() + '/day';
        document.getElementById('previewDays').textContent = days + ' day' + (days > 1 ? 's' : '');
        document.getElementById('previewTotal').textContent = '৳' + total.toLocaleString();

        preview.classList.add('visible');
        submitBtn.disabled = false;
    }

    // Initialize if guide was pre-selected via URL
    @if($selectedGuideId)
        calculateCost();
    @endif
</script>

</body>
</html>
