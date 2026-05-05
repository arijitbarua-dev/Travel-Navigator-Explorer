<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat System</title>
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
            grid-template-columns: 300px 1fr;
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

        /* ── INFO SIDEBAR ── */
        .card-header {
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

        .participants { padding: 8px 0; }
        .participant {
            display: flex; align-items: center; gap: 14px;
            padding: 14px 24px;
            border-bottom: 1px solid var(--warm);
            transition: background .15s;
        }
        .participant:last-child { border-bottom: none; }
        .participant:hover { background: #fdfbf9; }
        .p-avatar {
            width: 38px; height: 38px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 12px; font-weight: 600; flex-shrink: 0;
        }
        .p-avatar.manager { background: var(--ink); color: var(--white); }
        .p-avatar.user    { background: var(--rust-lt); color: var(--rust-dk); }
        .p-name  { font-size: 13px; font-weight: 600; color: var(--ink); }
        .p-role  { font-size: 11px; color: var(--ink-soft); margin-top: 2px; }
        .p-dot   {
            width: 8px; height: 8px; border-radius: 50%;
            background: #4caf7a; margin-left: auto; flex-shrink: 0;
            box-shadow: 0 0 0 2px rgba(76,175,122,.2);
        }

        .info-footer {
            padding: 18px 24px;
            border-top: 1px solid var(--warm);
            background: var(--cream);
        }
        .stat-row { display: flex; justify-content: space-between; }
        .stat-item { text-align: center; }
        .stat-num {
            font-family: 'Fraunces', serif;
            font-size: 20px; font-weight: 600; color: var(--ink);
        }
        .stat-lbl {
            font-size: 10px; color: var(--ink-soft);
            letter-spacing: 1px; text-transform: uppercase; margin-top: 3px;
        }

        /* ── CHAT COLUMN ── */
        .chat-col { display: flex; flex-direction: column; gap: 0; }

        .chat-header {
            padding: 20px 24px 18px;
            border-bottom: 1px solid var(--warm);
            display: flex; align-items: center; justify-content: space-between;
        }
        .chat-header-left { display: flex; align-items: center; gap: 10px; }
        .live-dot {
            width: 8px; height: 8px; border-radius: 50%;
            background: var(--rust);
            box-shadow: 0 0 0 3px var(--rust-lt);
        }
        .chat-header-title { font-size: 13px; font-weight: 600; color: var(--ink); }
        .chat-header-badge {
            font-size: 10px; color: var(--ink-soft);
            background: var(--cream); border: 1px solid var(--warm);
            border-radius: 100px; padding: 3px 10px; letter-spacing: .3px;
        }

        /* ── MESSAGES ── */
        .messages-area {
            padding: 24px;
            display: flex; flex-direction: column; gap: 16px;
            min-height: 360px; max-height: 460px;
            overflow-y: auto;
            scroll-behavior: smooth;
        }
        .messages-area::-webkit-scrollbar { width: 4px; }
        .messages-area::-webkit-scrollbar-track { background: transparent; }
        .messages-area::-webkit-scrollbar-thumb { background: var(--warm); border-radius: 4px; }

        .ts-divider {
            display: flex; align-items: center; gap: 12px; margin: 4px 0;
        }
        .ts-line { flex: 1; height: 1px; background: var(--warm); }
        .ts-text {
            font-size: 10px; color: var(--ink-soft);
            letter-spacing: .5px; text-transform: uppercase;
        }

        .msg-row { display: flex; gap: 10px; align-items: flex-end; }
        .msg-row.manager { flex-direction: row; }
        .msg-row.user    { flex-direction: row-reverse; }

        .msg-avatar {
            width: 32px; height: 32px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 11px; font-weight: 600; flex-shrink: 0;
        }
        .msg-avatar.manager { background: var(--ink); color: var(--white); }
        .msg-avatar.user    { background: var(--rust-lt); color: var(--rust-dk); }

        .bubble {
            padding: 11px 16px;
            border-radius: var(--radius-md);
            max-width: 75%;
            font-size: 14px; line-height: 1.55;
        }
        .msg-row.manager .bubble {
            background: var(--cream);
            border: 1px solid var(--warm);
            border-bottom-left-radius: 4px;
            color: var(--ink);
        }
        .msg-row.user .bubble {
            background: var(--ink);
            border-bottom-right-radius: 4px;
            color: var(--white);
        }
        .msg-meta {
            font-size: 10px; color: var(--ink-soft);
            margin-top: 5px; letter-spacing: .3px;
        }
        .msg-row.user .msg-meta { text-align: right; }

        /* ── COMPOSE BAR ── */
        .compose-bar {
            padding: 18px 24px;
            border-top: 1px solid var(--warm);
            display: flex; gap: 10px; align-items: center;
            background: var(--white);
        }
        .compose-input {
            flex: 1;
            padding: 12px 16px;
            border: 1.5px solid var(--warm);
            border-radius: var(--radius-sm);
            background: var(--cream);
            font-family: 'Syne', sans-serif;
            font-size: 14px; color: var(--ink);
            outline: none;
            transition: border-color .2s, background .2s, box-shadow .2s;
        }
        .compose-input::placeholder { color: var(--ink-soft); }
        .compose-input:focus {
            border-color: var(--rust);
            background: var(--white);
            box-shadow: 0 0 0 3px rgba(194,107,58,.12);
        }
        .send-btn {
            padding: 12px 22px;
            background: var(--ink); color: var(--white);
            border: none; border-radius: var(--radius-sm);
            font-family: 'Syne', sans-serif;
            font-size: 14px; font-weight: 600;
            cursor: pointer; letter-spacing: .3px;
            display: flex; align-items: center; gap: 8px;
            transition: background .2s, transform .12s;
            white-space: nowrap;
        }
        .send-btn:hover  { background: var(--rust); }
        .send-btn:active { transform: scale(.97); }
        .btn-arrow {
            width: 20px; height: 20px; border-radius: 50%;
            background: rgba(255,255,255,.15);
            display: flex; align-items: center; justify-content: center;
            font-size: 11px;
        }

        /* ── EMPTY STATE ── */
        .empty-state { padding: 48px 24px; text-align: center; }
        .empty-icon  { font-size: 40px; margin-bottom: 14px; opacity: .35; }
        .empty-title { font-family: 'Fraunces', serif; font-size: 18px; color: var(--ink-soft); margin-bottom: 6px; }
        .empty-sub   { font-size: 13px; color: var(--ink-soft); opacity: .7; }

        /* ── RESPONSIVE ── */
        @media (max-width: 860px) {
            .hero-inner { flex-direction: column; align-items: flex-start; gap: 0; }
            .hero-stat  { display: none; }
            .layout     { grid-template-columns: 1fr; }
            .page       { padding: 28px 20px 60px; }
            .hero-inner { padding: 0 24px; }
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
            <div class="hero-eyebrow">Messaging</div>
            <h1>Chat with your<br><em>manager</em></h1>
            <p class="hero-desc">Real-time messaging between manager and user. All conversations are logged and timestamped.</p>
        </div>
        <div class="hero-stat">
            <span class="hero-stat-num">{{ $messages->count() }}</span>
            <span class="hero-stat-label">Messages</span>
        </div>
    </div>
</div>

<!-- MAIN -->
<div class="page">
    <div class="layout">

        <!-- LEFT: SIDEBAR -->
        <div class="card">
            <div class="card-header">
                <p class="card-label">Conversation</p>
                <h2 class="card-title">Participants</h2>
            </div>

            <div class="participants">
                <div class="participant">
                    <div class="p-avatar manager">MG</div>
                    <div>
                        <div class="p-name">Manager</div>
                    
                    </div>
                    <div class="p-dot"></div>
                </div>
                <div class="participant">
                    <div class="p-avatar user">US</div>
                    <div>
                        <div class="p-name">User</div>
                        
                    </div>
                    <div class="p-dot"></div>
                </div>
            </div>

            <div class="info-footer">
                <div class="stat-row">
                    <div class="stat-item">
                        <div class="stat-num">{{ $messages->count() }}</div>
                        <div class="stat-lbl">Total</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-num">2</div>
                        <div class="stat-lbl">Online</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-num">Live</div>
                        <div class="stat-lbl">Status</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- RIGHT: CHAT -->
        <div class="card chat-col">
            <div class="chat-header">
                <div class="chat-header-left">
                    <div class="live-dot"></div>
                    <span class="chat-header-title">Live chat</span>
                </div>
                <span class="chat-header-badge">Manager · User</span>
            </div>

            <div class="messages-area" id="messages-area">

                @if($messages->isEmpty())
                    <div class="empty-state">
                        <div class="empty-icon">💬</div>
                        <div class="empty-title">No messages yet</div>
                        <div class="empty-sub">Start the conversation below.</div>
                    </div>
                @else
                    <div class="ts-divider">
                        <div class="ts-line"></div>
                        <span class="ts-text">Today</span>
                        <div class="ts-line"></div>
                    </div>

                    @foreach($messages as $msg)
                        <div class="msg-row {{ $msg->sender_id == 1 ? 'manager' : 'user' }}">
                            <div class="msg-avatar {{ $msg->sender_id == 1 ? 'manager' : 'user' }}">
                                {{ $msg->sender_id == 1 ? 'MG' : 'US' }}
                            </div>
                            <div>
                                <div class="bubble">{{ $msg->message }}</div>
                                <div class="msg-meta">
                                    {{ $msg->sender_id == 1 ? 'Manager' : 'User' }}
                                    @if($msg->created_at)
                                        · {{ $msg->created_at->format('g:i A') }}
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif

            </div>

            <form method="POST" action="/chat/send" class="compose-bar" id="chat-form">
                @csrf
                <input type="hidden" name="sender_id" value="1">
                <input type="hidden" name="receiver_id" value="{{ $user_id }}">
                <input
                    type="text"
                    name="message"
                    class="compose-input"
                    placeholder="Type a message..."
                    id="message-input"
                    required
                    autocomplete="off"
                >
                <button type="submit" class="send-btn">
                    Send
                    <div class="btn-arrow">→</div>
                </button>
            </form>
        </div>

    </div>
</div>

<script>
    // Auto-scroll to latest message on load
    const area = document.getElementById('messages-area');
    if (area) area.scrollTop = area.scrollHeight;

    // Submit on Enter key
    document.getElementById('message-input').addEventListener('keydown', function(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            document.getElementById('chat-form').submit();
        }
    });
</script>

</body>
</html>