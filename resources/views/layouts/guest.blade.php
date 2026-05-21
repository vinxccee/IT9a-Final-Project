<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Access Portal') | Grand Azure Hotel</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Cormorant+Garamond:wght@500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script>
        if (localStorage.getItem('grandAzureTheme') === 'dark') {
            document.documentElement.classList.add('theme-dark');
        }
    </script>
    <style>
        :root {
            --bg: #f4efe7;
            --surface: rgba(255, 250, 243, 0.9);
            --surface-strong: #fffaf4;
            --ink: #1e2430;
            --ink-soft: #5d6470;
            --line: rgba(30, 36, 48, 0.12);
            --gold: #c48a3a;
            --gold-deep: #8a5520;
            --teal: #2f7c78;
            --red: #c45a46;
            --green: #4e6847;
            --shadow: 0 24px 60px rgba(74, 52, 28, 0.12);
        }

        * { box-sizing: border-box; }
        body {
            margin: 0;
            min-height: 100vh;
            font-family: 'Manrope', sans-serif;
            color: var(--ink);
            background:
                radial-gradient(circle at top left, rgba(196, 138, 58, 0.18), transparent 30%),
                radial-gradient(circle at top right, rgba(47, 124, 120, 0.14), transparent 28%),
                linear-gradient(180deg, #f8f3eb 0%, #efe6d9 100%);
        }
        html.theme-dark body,
        body.dark-preview {
            --bg: #171613;
            --surface: rgba(18, 18, 18, 0.9);
            --surface-strong: #0f0f0f;
            --ink: #ffffff;
            --ink-soft: #dedede;
            --line: rgba(255, 247, 236, 0.14);
            background:
                radial-gradient(circle at top left, rgba(196, 138, 58, 0.16), transparent 30%),
                radial-gradient(circle at top right, rgba(47, 124, 120, 0.12), transparent 28%),
                linear-gradient(180deg, #050505 0%, #111111 100%);
        }

        .auth-shell {
            min-height: 100vh;
            display: grid;
            grid-template-columns: minmax(320px, 1.05fr) minmax(360px, 0.95fr);
        }
        .auth-showcase {
            position: relative;
            padding: 48px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            overflow: hidden;
            background:
                linear-gradient(135deg, rgba(255, 250, 243, 0.9), rgba(247, 240, 228, 0.85)),
                linear-gradient(135deg, rgba(196, 138, 58, 0.08), rgba(47, 124, 120, 0.08));
            border-right: 1px solid rgba(30, 36, 48, 0.08);
        }
        .auth-showcase::after {
            content: "";
            position: absolute;
            width: 320px;
            height: 320px;
            right: -80px;
            bottom: -100px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(196, 138, 58, 0.18), transparent 68%);
            pointer-events: none;
        }
        .brand-badge {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 10px 14px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.72);
            border: 1px solid rgba(30, 36, 48, 0.08);
            color: var(--gold-deep);
            font-size: 0.76rem;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            width: fit-content;
        }
        .showcase-title {
            margin: 18px 0 14px;
            font-family: 'Cormorant Garamond', serif;
            font-size: clamp(3rem, 5vw, 4.8rem);
            line-height: 0.95;
            max-width: 8.5ch;
        }
        .showcase-copy {
            max-width: 58ch;
            color: var(--ink-soft);
            line-height: 1.75;
            font-size: 1rem;
        }
        .showcase-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 16px;
            margin-top: 32px;
        }
        .showcase-card {
            padding: 20px;
            border-radius: 22px;
            background: rgba(255, 255, 255, 0.72);
            border: 1px solid rgba(30, 36, 48, 0.08);
            box-shadow: 0 18px 42px rgba(74, 52, 28, 0.08);
        }
        .showcase-card i {
            color: var(--teal);
            margin-bottom: 12px;
            font-size: 1.05rem;
        }
        .showcase-card h3 {
            margin: 0 0 8px;
            font-size: 0.98rem;
        }
        .showcase-card p {
            margin: 0;
            color: var(--ink-soft);
            line-height: 1.6;
            font-size: 0.88rem;
        }
        .showcase-footer {
            margin-top: 24px;
            color: var(--ink-soft);
            font-size: 0.92rem;
        }

        .auth-stage {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 36px;
        }
        .auth-panel {
            width: 100%;
            max-width: 520px;
            padding: 30px;
            border-radius: 30px;
            background: var(--surface);
            border: 1px solid rgba(30, 36, 48, 0.08);
            box-shadow: var(--shadow);
            backdrop-filter: blur(16px);
        }
        .auth-theme-toggle {
            position: fixed;
            top: 22px;
            right: 22px;
            z-index: 20;
            width: 44px;
            height: 44px;
            border: 1px solid var(--line);
            border-radius: 50%;
            display: inline-grid;
            place-items: center;
            background: rgba(255, 255, 255, 0.74);
            color: var(--ink);
            cursor: pointer;
            box-shadow: 0 16px 34px rgba(74, 52, 28, 0.12);
            backdrop-filter: blur(14px);
        }
        .auth-theme-toggle:hover {
            transform: translateY(-1px);
            color: var(--gold-deep);
        }
        .panel-eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            border-radius: 999px;
            background: rgba(196, 138, 58, 0.12);
            color: var(--gold-deep);
            font-size: 0.75rem;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }
        .panel-title {
            margin: 18px 0 8px;
            font-family: 'Cormorant Garamond', serif;
            font-size: 2.4rem;
            line-height: 1;
        }
        .panel-copy {
            margin: 0 0 24px;
            color: var(--ink-soft);
            line-height: 1.7;
        }
        .auth-card {
            margin-top: 10px;
        }

        .form-group { margin-bottom: 16px; }
        .form-label {
            display: block;
            margin-bottom: 8px;
            color: var(--ink-soft);
            font-size: 0.79rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }
        .form-control {
            width: 100%;
            padding: 13px 14px;
            border-radius: 16px;
            border: 1px solid rgba(30, 36, 48, 0.12);
            background: rgba(255, 255, 255, 0.95);
            color: var(--ink);
            font: inherit;
        }
        .form-control:focus {
            outline: 2px solid rgba(196, 138, 58, 0.2);
            border-color: rgba(196, 138, 58, 0.5);
        }
        .form-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            margin-bottom: 18px;
            font-size: 0.88rem;
        }
        .checkbox-row {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: var(--ink-soft);
        }
        .form-link {
            color: var(--gold-deep);
            text-decoration: none;
            font-weight: 700;
        }
        .form-link:hover { text-decoration: underline; }
        .btn-primary {
            width: 100%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 13px 16px;
            border: 0;
            border-radius: 999px;
            background: linear-gradient(135deg, var(--gold), var(--gold-deep));
            color: white;
            font: inherit;
            font-weight: 800;
            cursor: pointer;
        }
        .btn-secondary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 12px 16px;
            border-radius: 999px;
            border: 1px solid var(--line);
            background: white;
            color: var(--ink);
            text-decoration: none;
            font-weight: 700;
        }
        .auth-link {
            margin-top: 18px;
            text-align: center;
            color: var(--ink-soft);
            font-size: 0.92rem;
        }
        .auth-link a {
            color: var(--gold-deep);
            font-weight: 700;
            text-decoration: none;
        }
        .auth-link a:hover { text-decoration: underline; }
        .alert {
            margin-bottom: 18px;
            padding: 14px 16px;
            border-radius: 18px;
            border: 1px solid rgba(30, 36, 48, 0.08);
            background: rgba(255, 255, 255, 0.78);
            font-size: 0.92rem;
        }
        .alert-danger { color: var(--red); border-color: rgba(196, 90, 70, 0.18); }
        .alert-success { color: var(--green); border-color: rgba(78, 104, 71, 0.18); }
        .demo-card {
            margin-top: 22px;
            padding: 18px;
            border-radius: 22px;
            background: rgba(255, 255, 255, 0.76);
            border: 1px solid rgba(30, 36, 48, 0.08);
        }
        .demo-card strong {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 12px;
            color: var(--gold-deep);
        }
        .demo-grid {
            display: grid;
            gap: 10px;
        }
        .demo-item {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            padding: 12px 14px;
            border-radius: 16px;
            background: rgba(244, 239, 231, 0.92);
            border: 1px solid rgba(30, 36, 48, 0.06);
            font-size: 0.88rem;
        }
        .demo-item span:last-child {
            color: var(--ink-soft);
            font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, monospace;
            text-align: right;
        }
        html.theme-dark .auth-showcase,
        html.theme-dark .auth-panel,
        html.theme-dark .showcase-card,
        html.theme-dark .demo-card,
        html.theme-dark .alert,
        body.dark-preview .auth-showcase,
        body.dark-preview .auth-panel,
        body.dark-preview .showcase-card,
        body.dark-preview .demo-card,
        body.dark-preview .alert {
            background: rgba(10, 10, 10, 0.88);
            border-color: rgba(255, 255, 255, 0.14);
            color: #ffffff;
            box-shadow: 0 24px 60px rgba(0, 0, 0, 0.36);
        }
        html.theme-dark .brand-badge,
        html.theme-dark .btn-secondary,
        html.theme-dark .form-control,
        html.theme-dark .demo-item,
        html.theme-dark .auth-theme-toggle,
        body.dark-preview .brand-badge,
        body.dark-preview .btn-secondary,
        body.dark-preview .form-control,
        body.dark-preview .demo-item,
        body.dark-preview .auth-theme-toggle {
            background: #000000;
            border-color: rgba(255, 255, 255, 0.18);
            color: #ffffff;
        }
        html.theme-dark .showcase-copy,
        html.theme-dark .showcase-card p,
        html.theme-dark .showcase-footer,
        html.theme-dark .panel-copy,
        html.theme-dark .form-label,
        html.theme-dark .checkbox-row,
        html.theme-dark .auth-link,
        html.theme-dark .demo-item span:last-child,
        body.dark-preview .showcase-copy,
        body.dark-preview .showcase-card p,
        body.dark-preview .showcase-footer,
        body.dark-preview .panel-copy,
        body.dark-preview .form-label,
        body.dark-preview .checkbox-row,
        body.dark-preview .auth-link,
        body.dark-preview .demo-item span:last-child {
            color: #dedede;
        }
        html.theme-dark .form-control::placeholder,
        body.dark-preview .form-control::placeholder {
            color: rgba(255, 255, 255, 0.68);
        }

        @media (max-width: 1100px) {
            .auth-shell { grid-template-columns: 1fr; }
            .auth-showcase { display: none; }
            .auth-stage { min-height: 100vh; }
        }
    </style>
    @stack('styles')
</head>
<body>
    <button type="button" class="auth-theme-toggle js-theme-toggle" aria-label="Toggle dark mode" onclick="window.toggleGrandAzureTheme?.()">
        <i class="fas fa-moon"></i>
    </button>
    <div class="auth-shell">
        <section class="auth-showcase">
            <div>
                <a href="{{ route('home') }}" class="brand-badge" style="text-decoration:none;">
                    <i class="fas fa-hotel"></i> Grand Azure Hotel
                </a>
                <h1 class="showcase-title">Luxury operations with disciplined control.</h1>
                <p class="showcase-copy">
                    Grand Azure Hotel brings reservations, front office activity, billing, and housekeeping into one coordinated workflow built around clear user roles.
                </p>

                <div class="showcase-grid">
                    <article class="showcase-card">
                        <i class="fas fa-calendar-check"></i>
                        <h3>Reservation Control</h3>
                        <p>Protect room inventory with validated booking and status workflows.</p>
                    </article>
                    <article class="showcase-card">
                        <i class="fas fa-file-invoice-dollar"></i>
                        <h3>Billing Visibility</h3>
                        <p>Track invoice balances, payments, and operational handoff in one place.</p>
                    </article>
                    <article class="showcase-card">
                        <i class="fas fa-broom"></i>
                        <h3>Room Readiness</h3>
                        <p>Keep housekeeping and maintenance aligned with front office operations.</p>
                    </article>
                    <article class="showcase-card">
                        <i class="fas fa-shield-halved"></i>
                        <h3>Role Security</h3>
                        <p>Give each team access only to the tools required for their responsibilities.</p>
                    </article>
                </div>
            </div>

            <div class="showcase-footer">
                Access portal for guests, reception, billing staff, housekeeping staff, and administrators.
            </div>
        </section>

        <section class="auth-stage">
            <div class="auth-panel">
                @yield('content')
            </div>
        </section>
    </div>
    <script>
        window.setGrandAzureTheme = function (mode) {
            const isDark = mode === 'dark';
            document.documentElement.classList.toggle('theme-dark', isDark);
            document.body.classList.toggle('dark-preview', isDark);
            localStorage.setItem('grandAzureTheme', isDark ? 'dark' : 'light');
            document.querySelectorAll('.js-theme-toggle i').forEach((icon) => {
                icon.classList.toggle('fa-moon', !isDark);
                icon.classList.toggle('fa-sun', isDark);
            });
        };

        window.toggleGrandAzureTheme = function () {
            const isDark = document.documentElement.classList.contains('theme-dark') || document.body.classList.contains('dark-preview');
            window.setGrandAzureTheme(isDark ? 'light' : 'dark');
        };

        window.setGrandAzureTheme(localStorage.getItem('grandAzureTheme') === 'dark' ? 'dark' : 'light');
    </script>
    @stack('scripts')
</body>
</html>
