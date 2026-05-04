<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Cancelled — Travel Navigator Explorer</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
        :root{--ink:#1a1a18;--cream:#f7f4ef;--warm:#ede8df;--accent:#c8753a;--muted:#7a7671;--white:#fff;--radius:12px;--shadow:0 4px 24px rgba(26,26,24,.08)}
        body{font-family:'DM Sans',sans-serif;background:var(--cream);color:var(--ink);min-height:100vh;display:flex;align-items:center;justify-content:center;padding:40px 20px}
        .card{background:var(--white);border:1px solid var(--warm);border-radius:16px;padding:48px 40px;max-width:460px;width:100%;box-shadow:var(--shadow);text-align:center}
        .cancel-icon{width:80px;height:80px;background:#fef2f2;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 24px;font-size:2.2rem;animation:popIn .5s cubic-bezier(.17,.67,.35,1.25)}
        @keyframes popIn{0%{transform:scale(0);opacity:0}100%{transform:scale(1);opacity:1}}
        .card h1{font-family:'Playfair Display',serif;font-size:1.5rem;font-weight:700;color:#991b1b;margin-bottom:8px}
        .card .message{font-size:.93rem;color:var(--muted);margin-bottom:32px;line-height:1.6}
        .btn-group{display:flex;gap:12px}
        .btn{flex:1;padding:13px;border:none;border-radius:8px;font-family:'DM Sans',sans-serif;font-size:.9rem;font-weight:500;cursor:pointer;text-decoration:none;text-align:center;transition:background .18s}
        .btn-primary{background:var(--accent);color:var(--white)}
        .btn-primary:hover{background:#b5622c}
        .btn-secondary{background:var(--cream);color:var(--muted);border:1.5px solid var(--warm)}
        .btn-secondary:hover{border-color:var(--accent);color:var(--accent)}
        @media(max-width:480px){.card{padding:32px 24px}.btn-group{flex-direction:column}}
    </style>
</head>
<body>
<div class="card">
    <div class="cancel-icon">❌</div>
    <h1>Payment Cancelled</h1>
    <p class="message">Your payment was not completed. No charges have been made to your account. You can try again or return to the home page.</p>
    <div class="btn-group">
        <a href="{{ route('booking.form') }}" class="btn btn-primary">Try Again →</a>
        <a href="{{ route('home') }}" class="btn btn-secondary">← Back to Home</a>
    </div>
</div>
</body>
</html>
