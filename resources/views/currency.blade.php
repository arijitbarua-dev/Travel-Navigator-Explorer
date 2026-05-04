<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Currency Converter — Travel Navigator Explorer</title>
    <meta name="description" content="Convert Bangladeshi Taka to destination currencies for your Cox's Bazar trip.">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
        :root{--ink:#1a1a18;--cream:#f7f4ef;--warm:#ede8df;--accent:#c8753a;--accent-lt:#f0e0d0;--accent-dk:#b5622c;--muted:#7a7671;--white:#fff;--radius:12px;--shadow:0 4px 24px rgba(26,26,24,.08)}
        body{font-family:'DM Sans',sans-serif;background:var(--cream);color:var(--ink);min-height:100vh}

        /* HERO */
        .hero{position:relative;height:260px;background:var(--ink);overflow:hidden}
        .hero::before{content:'';position:absolute;inset:0;background:linear-gradient(135deg,#2a2420 0%,#3d2e1e 50%,#1a1a18 100%);opacity:.9}
        .hero::after{content:'💱';position:absolute;font-size:16rem;opacity:.04;right:-30px;bottom:-50px;line-height:1}
        .hero-content{position:relative;z-index:1;height:100%;display:flex;flex-direction:column;justify-content:center;padding:0 56px}
        .hero-tag{font-size:.72rem;font-weight:500;letter-spacing:3px;text-transform:uppercase;color:var(--accent-lt);margin-bottom:12px}
        .hero h1{font-family:'Playfair Display',serif;font-size:clamp(1.8rem,3.5vw,2.6rem);color:var(--white);line-height:1.2;margin-bottom:12px}
        .hero p{font-size:.93rem;color:rgba(255,255,255,.6)}

        /* MAIN */
        .main{max-width:900px;margin:0 auto;padding:48px 40px 80px}
        .back-link{display:inline-flex;align-items:center;gap:6px;font-size:.85rem;font-weight:500;color:var(--muted);text-decoration:none;margin-bottom:32px;transition:color .18s}
        .back-link:hover{color:var(--accent)}
        .content-grid{display:grid;grid-template-columns:1fr 300px;gap:28px;align-items:start}

        /* CONVERTER CARD */
        .converter-card{background:var(--white);border:1px solid var(--warm);border-radius:var(--radius);padding:36px;box-shadow:var(--shadow)}
        .converter-card h2{font-family:'Playfair Display',serif;font-size:1.3rem;font-weight:700;margin-bottom:8px}
        .converter-card .subtitle{font-size:.88rem;color:var(--muted);margin-bottom:28px;padding-bottom:20px;border-bottom:1px solid var(--warm)}

        .form-group{display:flex;flex-direction:column;gap:6px;margin-bottom:20px}
        .form-group label{font-size:.82rem;font-weight:500;color:var(--muted);letter-spacing:.3px}
        .form-group input,.form-group select{font-family:'DM Sans',sans-serif;font-size:.93rem;padding:12px 14px;border:1.5px solid var(--warm);border-radius:8px;background:var(--cream);color:var(--ink);outline:none;transition:border-color .18s,background .18s;width:100%}
        .form-group input:focus,.form-group select:focus{border-color:var(--accent);background:var(--white)}

        .currency-row{display:grid;grid-template-columns:1fr auto 1fr;gap:12px;align-items:end;margin-bottom:20px}
        .swap-btn{width:44px;height:44px;border:1.5px solid var(--warm);border-radius:50%;background:var(--white);cursor:pointer;display:flex;align-items:center;justify-content:center;font-size:1.1rem;transition:all .25s;margin-bottom:6px}
        .swap-btn:hover{border-color:var(--accent);background:var(--accent-lt);transform:rotate(180deg)}

        .convert-btn{width:100%;padding:14px;background:var(--accent);color:var(--white);border:none;border-radius:8px;font-family:'DM Sans',sans-serif;font-size:.95rem;font-weight:600;cursor:pointer;transition:background .18s;display:flex;align-items:center;justify-content:center;gap:8px}
        .convert-btn:hover{background:var(--accent-dk)}
        .convert-btn:disabled{background:var(--muted);cursor:wait}

        /* RESULT */
        .result-box{margin-top:28px;padding:24px;background:linear-gradient(135deg,#fdf8f3 0%,#f7f0e6 100%);border:1px solid var(--accent-lt);border-radius:10px;display:none;animation:fadeIn .3s}
        .result-box.visible{display:block}
        @keyframes fadeIn{from{opacity:0;transform:translateY(8px)}to{opacity:1;transform:translateY(0)}}
        .result-amount{font-family:'Playfair Display',serif;font-size:2rem;font-weight:700;color:var(--accent);margin-bottom:8px;word-break:break-all}
        .result-detail{font-size:.85rem;color:var(--muted);line-height:1.6}
        .result-rate{font-size:.82rem;color:var(--muted);margin-top:8px;padding-top:10px;border-top:1px solid var(--accent-lt)}

        /* POPULAR RATES SIDEBAR */
        .rates-card{background:var(--white);border:1px solid var(--warm);border-radius:var(--radius);padding:28px 24px;box-shadow:var(--shadow)}
        .rates-card h3{font-family:'Playfair Display',serif;font-size:1.1rem;font-weight:700;margin-bottom:6px}
        .rates-card .rates-sub{font-size:.82rem;color:var(--muted);margin-bottom:20px;padding-bottom:16px;border-bottom:1px solid var(--warm)}
        .rate-item{display:flex;justify-content:space-between;align-items:center;padding:10px 0;border-bottom:1px solid rgba(237,232,223,.5);font-size:.88rem}
        .rate-item:last-child{border-bottom:none}
        .rate-item .currency-code{font-weight:600;display:flex;align-items:center;gap:8px}
        .rate-item .currency-code .flag{font-size:1.1rem}
        .rate-item .rate-value{color:var(--accent);font-weight:500}

        /* ALERT */
        .alert-error{padding:14px 20px;border-radius:8px;margin-bottom:24px;font-size:.9rem;background:#fef2f2;color:#991b1b;border:1px solid #fecaca}

        /* RESPONSIVE */
        @media(max-width:768px){
            .hero-content{padding:0 24px}
            .main{padding:32px 20px 60px}
            .content-grid{grid-template-columns:1fr}
            .converter-card{padding:28px 20px}
            .currency-row{grid-template-columns:1fr;gap:8px}
            .swap-btn{margin:0 auto;transform:rotate(90deg)}
            .swap-btn:hover{transform:rotate(270deg)}
        }
    </style>
</head>
<body>

<div class="hero">
    <div class="hero-content">
        <span class="hero-tag">Travel Tools</span>
        <h1>Currency Converter</h1>
        <p>Convert Bangladeshi Taka to your destination currency instantly.</p>
    </div>
</div>

<div class="main">
    <a href="{{ route('home') }}" class="back-link">← Back to Home</a>

    @if(session('error'))
        <div class="alert-error">{{ session('error') }}</div>
    @endif

    <div class="content-grid">
        <!-- CONVERTER -->
        <div class="converter-card">
            <h2>Convert Currency</h2>
            <p class="subtitle">Real-time exchange rates powered by ExchangeRate API.</p>

            <div class="form-group">
                <label for="amount">Amount</label>
                <input type="number" id="amount" name="amount" min="0.01" step="0.01"
                       placeholder="Enter amount" value="{{ isset($result) ? $result['amount'] : '1000' }}">
            </div>

            <div class="currency-row">
                <div class="form-group" style="margin-bottom:0">
                    <label for="fromCurrency">From</label>
                    <select id="fromCurrency">
                        @foreach($currencies as $code => $name)
                        <option value="{{ $code }}" {{ (isset($result) ? $result['from'] : 'BDT') == $code ? 'selected' : '' }}>
                            {{ $code }} — {{ $name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <button type="button" class="swap-btn" onclick="swapCurrencies()" title="Swap currencies">⇄</button>

                <div class="form-group" style="margin-bottom:0">
                    <label for="toCurrency">To</label>
                    <select id="toCurrency">
                        @foreach($currencies as $code => $name)
                        <option value="{{ $code }}" {{ (isset($result) ? $result['to'] : 'USD') == $code ? 'selected' : '' }}>
                            {{ $code }} — {{ $name }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <button type="button" class="convert-btn" id="convertBtn" onclick="convertCurrency()">
                Convert →
            </button>

            <!-- RESULT -->
            <div class="result-box {{ isset($result) ? 'visible' : '' }}" id="resultBox">
                <p class="result-amount" id="resultAmount">
                    @if(isset($result))
                        {{ number_format($result['converted_amount'], 2) }} {{ $result['to'] }}
                    @endif
                </p>
                <p class="result-detail" id="resultDetail">
                    @if(isset($result))
                        {{ number_format($result['amount'], 2) }} {{ $result['from'] }} =
                        {{ number_format($result['converted_amount'], 2) }} {{ $result['to'] }}
                    @endif
                </p>
                <p class="result-rate" id="resultRate">
                    @if(isset($result))
                        1 {{ $result['from'] }} = {{ $result['rate'] }} {{ $result['to'] }}
                        <br>Last updated: {{ $result['last_updated'] }}
                    @endif
                </p>
            </div>
        </div>

        <!-- POPULAR RATES SIDEBAR -->
        <div class="rates-card">
            <h3>Popular Rates</h3>
            <p class="rates-sub">1 BDT equals</p>

            @php
                $flags = ['USD'=>'🇺🇸','EUR'=>'🇪🇺','GBP'=>'🇬🇧','INR'=>'🇮🇳','THB'=>'🇹🇭','SGD'=>'🇸🇬'];
            @endphp

            @foreach($popularRates as $code => $rate)
            <div class="rate-item">
                <span class="currency-code">
                    <span class="flag">{{ $flags[$code] ?? '🌍' }}</span>
                    {{ $code }}
                </span>
                <span class="rate-value">{{ number_format($rate, 4) }}</span>
            </div>
            @endforeach

            @if(empty($popularRates))
            <p style="font-size:.85rem;color:var(--muted);text-align:center;padding:20px 0">
                Rates unavailable. Try converting above.
            </p>
            @endif
        </div>
    </div>
</div>

<script>
function swapCurrencies() {
    const from = document.getElementById('fromCurrency');
    const to = document.getElementById('toCurrency');
    const temp = from.value;
    from.value = to.value;
    to.value = temp;
}

async function convertCurrency() {
    const amount = document.getElementById('amount').value;
    const from = document.getElementById('fromCurrency').value;
    const to = document.getElementById('toCurrency').value;
    const btn = document.getElementById('convertBtn');
    const resultBox = document.getElementById('resultBox');

    if (!amount || amount <= 0) {
        alert('Please enter a valid amount.');
        return;
    }

    btn.disabled = true;
    btn.textContent = 'Converting...';

    try {
        const response = await fetch('{{ route("currency.convert") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ amount, from, to })
        });

        const data = await response.json();

        if (data.error) {
            alert(data.error);
            return;
        }

        document.getElementById('resultAmount').textContent =
            parseFloat(data.converted_amount).toLocaleString(undefined, {minimumFractionDigits:2, maximumFractionDigits:2}) + ' ' + data.to;
        document.getElementById('resultDetail').textContent =
            parseFloat(data.amount).toLocaleString(undefined, {minimumFractionDigits:2}) + ' ' + data.from + ' = ' +
            parseFloat(data.converted_amount).toLocaleString(undefined, {minimumFractionDigits:2, maximumFractionDigits:2}) + ' ' + data.to;
        document.getElementById('resultRate').innerHTML =
            '1 ' + data.from + ' = ' + data.rate + ' ' + data.to +
            '<br>Last updated: ' + data.last_updated;

        resultBox.classList.add('visible');
    } catch (err) {
        alert('Conversion failed. Please try again.');
    } finally {
        btn.disabled = false;
        btn.textContent = 'Convert →';
    }
}
</script>
</body>
</html>
