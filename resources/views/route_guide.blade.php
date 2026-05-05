<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Route Guide</title>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,400;0,9..144,600;1,9..144,400&family=Syne:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
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
                repeating-linear-gradient(
                    0deg,
                    transparent,
                    transparent 39px,
                    rgba(255,255,255,.03) 39px,
                    rgba(255,255,255,.03) 40px
                ),
                repeating-linear-gradient(
                    90deg,
                    transparent,
                    transparent 39px,
                    rgba(255,255,255,.03) 39px,
                    rgba(255,255,255,.03) 40px
                );
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
        .hero-left {}
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
        .hero h1 em {
            font-style: italic; color: #e8a87c;
        }
        .hero-desc {
            font-size: 14px; color: rgba(255,255,255,.45);
            max-width: 380px; line-height: 1.65;
        }
        .hero-stat {
            display: flex; flex-direction: column; align-items: flex-end;
            flex-shrink: 0;
        }
        .hero-stat-num {
            font-family: 'Fraunces', serif;
            font-size: 52px; font-weight: 400; color: rgba(255,255,255,.08);
            line-height: 1;
        }
        .hero-stat-label {
            font-size: 11px; color: rgba(255,255,255,.2);
            letter-spacing: 1px;
        }

        /* ── LAYOUT ── */
        .page {
            max-width: 1100px; margin: 0 auto;
            padding: 40px 48px 80px;
        }

        .layout {
            display: grid;
            grid-template-columns: 380px 1fr;
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
        .form-card {}
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
        .select-wrap { position: relative; }
        .select-wrap::after {
            content: '▾';
            position: absolute; right: 14px; top: 50%;
            transform: translateY(-50%);
            color: var(--ink-soft); pointer-events: none; font-size: 12px;
        }
        .form-group select {
            font-family: 'Syne', sans-serif;
            font-size: 14px; font-weight: 400;
            padding: 12px 40px 12px 16px;
            border: 1.5px solid var(--warm);
            border-radius: var(--radius-sm);
            background: var(--cream);
            color: var(--ink);
            outline: none; width: 100%; appearance: none;
            transition: border-color .2s, background .2s, box-shadow .2s;
            cursor: pointer;
        }
        .form-group select:hover { border-color: #cbc5bb; }
        .form-group select:focus {
            border-color: var(--rust);
            background: var(--white);
            box-shadow: 0 0 0 3px rgba(194,107,58,.12);
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
        .right-col {
            display: flex; flex-direction: column; gap: 20px;
        }

        /* ── RESULT CARD ── */
        .result-card {}
        .result-header {
            padding: 24px 24px 18px;
            border-bottom: 1px solid var(--warm);
            display: flex; align-items: center; justify-content: space-between;
        }
        .result-route-from { font-size: 13px; color: var(--ink-soft); }
        .result-route-name { font-family: 'Fraunces', serif; font-size: 16px; font-weight: 600; }
        .result-dist-badge {
            background: var(--rust-lt);
            border: 1px solid rgba(194,107,58,.25);
            border-radius: 100px;
            padding: 6px 14px;
            font-size: 13px; font-weight: 600; color: var(--rust-dk);
            white-space: nowrap;
        }

        .transport-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1px;
            background: var(--warm);
        }
        .transport-item {
            background: var(--white);
            padding: 18px 20px;
            display: flex; align-items: center; gap: 14px;
            transition: background .15s;
        }
        .transport-item:hover { background: #fdfbf9; }
        .transport-item.featured {
            background: var(--rust-lt);
        }
        .transport-item.featured:hover { background: #edddd0; }
        .t-icon {
            font-size: 22px; line-height: 1; flex-shrink: 0;
            width: 42px; height: 42px;
            background: var(--cream);
            border-radius: var(--radius-sm);
            display: flex; align-items: center; justify-content: center;
        }
        .transport-item.featured .t-icon {
            background: rgba(194,107,58,.15);
        }
        .t-info { flex: 1; min-width: 0; }
        .t-label {
            font-size: 11px; font-weight: 600; letter-spacing: .5px;
            text-transform: uppercase; color: var(--ink-soft); margin-bottom: 4px;
        }
        .transport-item.featured .t-label { color: var(--rust-dk); }
        .t-time {
            font-family: 'Fraunces', serif;
            font-size: 16px; font-weight: 600; color: var(--ink);
            line-height: 1.1;
        }
        .t-fare {
            font-size: 12px; color: var(--ink-soft); margin-top: 2px;
        }
        .transport-item.featured .t-fare { color: var(--rust-dk); }
        .t-badge {
            font-size: 9px; font-weight: 700; letter-spacing: 1px;
            text-transform: uppercase; color: var(--white);
            background: var(--rust);
            border-radius: 100px; padding: 3px 8px;
            flex-shrink: 0;
        }

        /* ── HIGHLIGHTS ROW ── */
        .highlights {
            display: grid; grid-template-columns: 1fr 1fr;
            gap: 1px; background: var(--warm);
            border-top: 1px solid var(--warm);
        }
        .highlight-item {
            background: var(--ink);
            padding: 16px 20px;
            display: flex; align-items: center; gap: 12px;
        }
        .hi-icon {
            font-size: 18px; flex-shrink: 0;
            width: 36px; height: 36px;
            background: rgba(255,255,255,.07);
            border-radius: var(--radius-sm);
            display: flex; align-items: center; justify-content: center;
        }
        .hi-label {
            font-size: 10px; font-weight: 600; letter-spacing: 1.5px;
            text-transform: uppercase; color: rgba(255,255,255,.4);
            margin-bottom: 3px;
        }
        .hi-val {
            font-family: 'Fraunces', serif;
            font-size: 15px; font-weight: 600; color: var(--white);
        }

        /* ── MAP CARD ── */
        .map-card {}
        .map-header {
            padding: 18px 24px;
            border-bottom: 1px solid var(--warm);
            display: flex; align-items: center; gap: 10px;
        }
        .map-header-icon {
            width: 8px; height: 8px; border-radius: 50%;
            background: var(--rust); flex-shrink: 0;
            box-shadow: 0 0 0 3px var(--rust-lt);
        }
        .map-header-title { font-size: 13px; font-weight: 600; }
        .map-header-badge {
            margin-left: auto;
            font-size: 10px; color: var(--ink-soft);
            background: var(--cream);
            border: 1px solid var(--warm);
            border-radius: 100px;
            padding: 3px 10px; letter-spacing: .3px;
        }
        #map {
            height: 380px;
            border-radius: 0;
        }
        .map-footer {
            padding: 10px 18px;
            border-top: 1px solid var(--warm);
            display: flex; align-items: center; gap: 8px;
        }
        .map-footer-tag {
            font-size: 10px; font-weight: 600; letter-spacing: 1px;
            text-transform: uppercase; color: var(--ink-soft);
            background: var(--cream);
            border: 1px solid var(--warm);
            border-radius: 4px; padding: 2px 8px;
        }
        .map-footer-text { font-size: 11px; color: var(--ink-soft); }

        /* ── EMPTY STATE ── */
        .empty-state {
            padding: 48px 24px;
            text-align: center;
        }
        .empty-icon {
            font-size: 40px; margin-bottom: 14px; opacity: .35;
        }
        .empty-title {
            font-family: 'Fraunces', serif;
            font-size: 18px; color: var(--ink-soft); margin-bottom: 6px;
        }
        .empty-sub { font-size: 13px; color: var(--ink-soft); opacity: .7; }

        /* ── RESPONSIVE ── */
        @media (max-width: 860px) {
            .hero-inner { flex-direction: column; align-items: flex-start; gap: 0; }
            .hero-stat { display: none; }
            .layout { grid-template-columns: 1fr; }
            .page { padding: 28px 20px 60px; }
            .hero-inner { padding: 0 24px; }
        }
        @media (max-width: 480px) {
            .transport-grid { grid-template-columns: 1fr; }
            .highlights { grid-template-columns: 1fr; }
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
            <div class="hero-eyebrow">Navigation</div>
            <h1>Plan your<br><em>route</em> around<br>the city.</h1>
            <p class="hero-desc">Calculate travel time and fare from any hotel to top sightseeing destinations in Dhaka.</p>
        </div>
        <div class="hero-stat">
            <span class="hero-stat-num">6</span>
            <span class="hero-stat-label">Transport modes</span>
        </div>
    </div>
</div>

<!-- MAIN -->
<div class="page">
    <div class="layout">

        <!-- LEFT: FORM -->
        <div class="card form-card">
            <div class="form-card-header">
                <p class="card-label">Route planner</p>
                <h2 class="card-title">Where are you going?</h2>
            </div>
            <div class="form-body">
                <form method="POST" action="/route-guide/calculate">
                    @csrf

                    <div class="form-group">
                        <label>Starting hotel</label>
                        <div class="select-wrap">
                            <select name="hotel_id" required>
                                <option value="">Choose your hotel...</option>
                                @foreach($hotels as $h)
                                    <option value="{{ $h->id }}" {{ isset($selected_hotel) && $selected_hotel->id == $h->id ? 'selected' : '' }}>
                                        {{ $h->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="divider">
                        <div class="divider-line"></div>
                        <div class="divider-icon">↓</div>
                        <div class="divider-line"></div>
                    </div>

                    <div class="form-group">
                        <label>Destination</label>
                        <div class="select-wrap">
                            <select name="destination_id" required>
                                <option value="">Choose a destination...</option>
                                @foreach($destinations as $d)
                                    <option value="{{ $d->id }}" {{ isset($selected_destination) && $selected_destination->id == $d->id ? 'selected' : '' }}>
                                        {{ $d->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <button type="submit" class="submit-btn">
                        Calculate route
                        <div class="btn-arrow">→</div>
                    </button>
                </form>
            </div>
        </div>

        <!-- RIGHT: RESULTS + MAP -->
        <div class="right-col">

            @isset($result)
            <!-- RESULT CARD -->
            <div class="card result-card">
                <div class="result-header">
                    <div>
                        <div class="result-route-from">{{ $selected_hotel->name }}</div>
                        <div class="result-route-name">{{ $selected_destination->name }}</div>
                    </div>
                    <div class="result-dist-badge">{{ $result['distance'] }} km</div>
                </div>

                <div class="transport-grid">
                    <div class="transport-item {{ $result['fastest'] === 'car' ? 'featured' : '' }}">
                        <div class="t-icon">🚗</div>
                        <div class="t-info">
                            <div class="t-label">Taxi</div>
                            <div class="t-time">{{ $result['car']['time'] }} hrs</div>
                            <div class="t-fare">{{ $result['car']['fare'] }} BDT</div>
                        </div>
                        @if($result['fastest'] === 'car')
                            <div class="t-badge">Fastest</div>
                        @endif
                    </div>

                    <div class="transport-item {{ $result['cheapest'] === 'bus' ? 'featured' : '' }}">
                        <div class="t-icon">🚌</div>
                        <div class="t-info">
                            <div class="t-label">Bus</div>
                            <div class="t-time">{{ $result['bus']['time'] }} hrs</div>
                            <div class="t-fare">{{ $result['bus']['fare'] }} BDT</div>
                        </div>
                        @if($result['cheapest'] === 'bus')
                            <div class="t-badge">Cheapest</div>
                        @endif
                    </div>

                    <div class="transport-item {{ $result['cheapest'] === 'rickshaw' ? 'featured' : '' }}">
                        <div class="t-icon">🛺</div>
                        <div class="t-info">
                            <div class="t-label">Rickshaw</div>
                            <div class="t-time">{{ $result['rickshaw']['time'] }} hrs</div>
                            <div class="t-fare">{{ $result['rickshaw']['fare'] }} BDT</div>
                        </div>
                        @if($result['cheapest'] === 'rickshaw')
                            <div class="t-badge">Cheapest</div>
                        @endif
                    </div>

                    <div class="transport-item">
                        <div class="t-icon">🚶</div>
                        <div class="t-info">
                            <div class="t-label">Walking</div>
                            <div class="t-time">{{ $result['walk']['time'] }} hrs</div>
                            <div class="t-fare">Free</div>
                        </div>
                    </div>

                    <div class="transport-item">
                        <div class="t-icon">🚴</div>
                        <div class="t-info">
                            <div class="t-label">Cycle</div>
                            <div class="t-time">{{ $result['cycle']['time'] }} hrs</div>
                            <div class="t-fare">Free</div>
                        </div>
                    </div>
                </div>

                <div class="highlights">
                    <div class="highlight-item">
                        <div class="hi-icon">💰</div>
                        <div>
                            <div class="hi-label">Cheapest</div>
                            <div class="hi-val">{{ ucfirst($result['cheapest']) }}</div>
                        </div>
                    </div>
                    <div class="highlight-item">
                        <div class="hi-icon">⚡</div>
                        <div>
                            <div class="hi-label">Fastest</div>
                            <div class="hi-val">{{ ucfirst($result['fastest']) }}</div>
                        </div>
                    </div>
                </div>
            </div>
            @else
            <!-- EMPTY STATE when no result yet -->
            <div class="card">
                <div class="empty-state">
                    <div class="empty-icon">🗺️</div>
                    <div class="empty-title">No route yet</div>
                    <div class="empty-sub">Select a hotel and destination to see travel times and fares.</div>
                </div>
            </div>
            @endisset

            <!-- MAP CARD -->
            <div class="card map-card">
                <div class="map-header">
                    <div class="map-header-icon"></div>
                    <span class="map-header-title">Route map</span>
                    <span class="map-header-badge">OpenStreetMap · OSRM</span>
                </div>
                <div id="map"></div>
                <div class="map-footer">
                    <span class="map-footer-tag">Live</span>
                    <span class="map-footer-text">Real-time routing via OSRM driving profile</span>
                </div>
            </div>

        </div><!-- /right-col -->
    </div><!-- /layout -->
</div><!-- /page -->

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    const map = L.map('map', { zoomControl: true }).setView([23.6850, 90.3563], 11);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
        maxZoom: 19
    }).addTo(map);

    const hotelIcon = L.divIcon({
        className: '',
        html: `<div style="
            width:34px;height:34px;
            background:#18181A;
            border:3px solid #fff;
            border-radius:50% 50% 50% 0;
            transform:rotate(-45deg);
            box-shadow:0 2px 8px rgba(0,0,0,.3);
            display:flex;align-items:center;justify-content:center;
        "><span style="transform:rotate(45deg);font-size:14px;">🏨</span></div>`,
        iconSize: [34, 34],
        iconAnchor: [17, 34],
        popupAnchor: [0, -36]
    });

    const destIcon = L.divIcon({
        className: '',
        html: `<div style="
            width:34px;height:34px;
            background:#C26B3A;
            border:3px solid #fff;
            border-radius:50% 50% 50% 0;
            transform:rotate(-45deg);
            box-shadow:0 2px 8px rgba(194,107,58,.4);
            display:flex;align-items:center;justify-content:center;
        "><span style="transform:rotate(45deg);font-size:14px;">📍</span></div>`,
        iconSize: [34, 34],
        iconAnchor: [17, 34],
        popupAnchor: [0, -36]
    });

    let bounds = [];

    @if(isset($selected_hotel))
        const hotel = @json($selected_hotel);
        const hLat = parseFloat(hotel.latitude);
        const hLng = parseFloat(hotel.longitude);
        L.marker([hLat, hLng], { icon: hotelIcon })
            .addTo(map)
            .bindPopup(`<strong>${hotel.name}</strong><br><small>Your hotel</small>`);
        bounds.push([hLat, hLng]);
    @endif

    @if(isset($selected_destination))
        const dest = @json($selected_destination);
        const dLat = parseFloat(dest.latitude);
        const dLng = parseFloat(dest.longitude);
        L.marker([dLat, dLng], { icon: destIcon })
            .addTo(map)
            .bindPopup(`<strong>${dest.name}</strong><br><small>Destination</small>`);
        bounds.push([dLat, dLng]);
    @endif

    @if(isset($selected_hotel) && isset($selected_destination))
        const hotelLat = parseFloat(@json($selected_hotel->latitude));
        const hotelLng = parseFloat(@json($selected_hotel->longitude));
        const destLat  = parseFloat(@json($selected_destination->latitude));
        const destLng  = parseFloat(@json($selected_destination->longitude));

        const osrmUrl =
            `https://router.project-osrm.org/route/v1/driving/` +
            `${hotelLng},${hotelLat};${destLng},${destLat}` +
            `?overview=full&geometries=geojson`;

        fetch(osrmUrl)
            .then(r => r.json())
            .then(data => {
                if (!data.routes || !data.routes.length) return;
                const coords = data.routes[0].geometry.coordinates;
                const route  = coords.map(c => [c[1], c[0]]);

                // shadow line
                L.polyline(route, {
                    color: 'rgba(0,0,0,.15)',
                    weight: 8,
                    lineCap: 'round',
                    lineJoin: 'round'
                }).addTo(map);

                // main route line
                L.polyline(route, {
                    color: 'red',
                    weight: 4,
                    lineCap: 'round',
                    lineJoin: 'round'
                }).addTo(map);

                map.fitBounds(L.polyline(route).getBounds(), { padding: [50, 50] });
            })
            .catch(() => {
                if (bounds.length > 1) map.fitBounds(bounds, { padding: [60, 60] });
            });
    @else
        if (bounds.length > 0) {
            map.fitBounds(bounds, { padding: [60, 60] });
        }
    @endif
</script>

</body>
</html>