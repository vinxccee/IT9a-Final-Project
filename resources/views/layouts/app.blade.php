<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Grand Azure Hotel') | Hotel Management</title>
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
            --surface: rgba(255, 250, 243, 0.82);
            --surface-strong: #fffaf4;
            --ink: #1e2430;
            --ink-soft: #5d6470;
            --line: rgba(30, 36, 48, 0.12);
            --gold: #c48a3a;
            --gold-deep: #8a5520;
            --sage: #6f8568;
            --teal: #2f7c78;
            --red: #c45a46;
            --shadow: 0 24px 60px rgba(74, 52, 28, 0.12);
            --radius: 24px;
            --radius-sm: 16px;
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
            --surface: rgba(18, 18, 18, 0.88);
            --surface-strong: #0f0f0f;
            --ink: #ffffff;
            --ink-soft: #dedede;
            --line: rgba(255, 247, 236, 0.14);
            background:
                radial-gradient(circle at top left, rgba(196, 138, 58, 0.16), transparent 30%),
                radial-gradient(circle at top right, rgba(47, 124, 120, 0.12), transparent 28%),
                linear-gradient(180deg, #050505 0%, #111111 100%);
        }

        a { color: inherit; }
        .shell { display: grid; grid-template-columns: 300px minmax(0, 1fr); min-height: 100vh; gap: 0; }
        .sidebar {
            padding: 32px 24px;
            border-right: 1px solid rgba(30, 36, 48, 0.08);
            background: rgba(255, 248, 239, 0.74);
            backdrop-filter: blur(18px);
            position: sticky;
            top: 0;
            height: 100vh;
            overflow-y: auto;
        }
        .brand {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 14px 16px;
            border-radius: 22px;
            background: linear-gradient(135deg, rgba(196, 138, 58, 0.18), rgba(47, 124, 120, 0.08));
            text-decoration: none;
            box-shadow: var(--shadow);
        }
        .brand-mark {
            width: 80px;
            height: 80px;
            border-radius: 18px;
            display: grid;
            place-items: center;
            background: linear-gradient(135deg, rgba(196, 138, 58, 0.08), rgba(47, 124, 120, 0.06));
            color: white;
            font-size: 1.1rem;
            overflow: hidden;
            border: 1.5px solid rgba(196, 138, 58, 0.2);
        }
        .brand-mark img {
            width: 90%;
            height: 90%;
            object-fit: contain;
        }
        .brand-title {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.8rem;
            font-weight: 700;
            line-height: 1;
        }
        .brand-subtitle {
            color: var(--ink-soft);
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: 0.2em;
            margin-top: 6px;
        }
        .brand-info {
            margin-top: 26px;
            padding-top: 20px;
            border-top: 1px solid rgba(30, 36, 48, 0.08);
            font-size: 0.9rem;
        }
        .brand-info h4 {
            margin: 0 0 10px;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: var(--gold-deep);
            font-weight: 800;
        }
        .brand-info p {
            margin: 0 0 14px;
            color: var(--ink-soft);
            line-height: 1.6;
        }
        .brand-highlight {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            border-radius: 12px;
            background: rgba(47, 124, 120, 0.08);
            color: var(--teal);
            font-size: 0.88rem;
            margin-bottom: 8px;
            font-weight: 600;
        }
        .brand-highlight i {
            color: var(--teal);
        }
        .review-widget + .brand-highlight { margin-top: 22px; }
        .sidebar-section { margin-top: 28px; }
        .section-label {
            color: var(--ink-soft);
            font-size: 0.72rem;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            margin: 0 0 12px 14px;
        }
        .nav-list { display: grid; gap: 8px; }
        .nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 16px;
            border-radius: 18px;
            text-decoration: none;
            color: var(--ink-soft);
            transition: 0.2s ease;
        }
        .nav-link:hover, .nav-link.active {
            background: rgba(255, 255, 255, 0.7);
            color: var(--ink);
            transform: translateX(2px);
            box-shadow: 0 16px 30px rgba(74, 52, 28, 0.08);
        }
        .nav-link.active { border: 1px solid rgba(196, 138, 58, 0.22); }
        .nav-link i { width: 18px; text-align: center; color: var(--gold-deep); }
        .nav-link .notification-badge {
            margin-left: auto;
            min-width: 20px;
            height: 20px;
            padding: 0 6px;
            border-radius: 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: var(--red);
            color: white;
            font-size: 0.7rem;
            font-weight: 800;
            box-shadow: 0 2px 4px rgba(196, 90, 70, 0.3);
        }
        .nav-link .nav-count {
            margin-left: auto;
            min-width: 24px;
            height: 24px;
            padding: 0 8px;
            border-radius: 999px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: rgba(196, 90, 70, 0.14);
            color: var(--red);
            font-size: 0.72rem;
            font-weight: 800;
        }
        .sidebar-card {
            margin-top: 26px;
            padding: 18px;
            border-radius: 20px;
            background: linear-gradient(180deg, rgba(47, 124, 120, 0.10), rgba(196, 138, 58, 0.10));
            border: 1px solid rgba(30, 36, 48, 0.08);
        }
        .sidebar-card strong {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 0.95rem;
        }
        .sidebar-card p { margin: 8px 0 0; font-size: 0.9rem; color: var(--ink-soft); line-height: 1.55; }

        .content { padding: 24px 28px 36px; }
        .topbar {
            position: sticky;
            top: 0;
            z-index: 30;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            margin-bottom: 24px;
            padding: 16px 20px;
            border-radius: 24px;
            background: rgba(255, 250, 243, 0.88);
            border: 1px solid rgba(30, 36, 48, 0.08);
            backdrop-filter: blur(20px);
            box-shadow: 0 18px 50px rgba(74, 52, 28, 0.08);
        }
        .topbar h1 { margin: 0; font-size: 1rem; }
        .topbar p { margin: 4px 0 0; color: var(--ink-soft); font-size: 0.9rem; }
        .user-cluster { display: flex; align-items: center; gap: 12px; }
        .user-pill {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 8px 16px 8px 8px;
            background: white;
            border-radius: 999px;
            border: 1px solid var(--line);
        }
        .avatar {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            display: grid;
            place-items: center;
            background: linear-gradient(135deg, var(--gold), var(--teal));
            color: white;
            font-weight: 800;
        }
        .role-chip {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 10px;
            border-radius: 999px;
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            background: rgba(196, 138, 58, 0.14);
            color: var(--gold-deep);
        }

        .main { 
            max-width: 1400px; 
            margin: 0 auto; 
            padding: 32px 32px 64px;
            width: 100%;
            box-sizing: border-box;
        }
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            gap: 20px;
            margin-bottom: 32px;
            padding-bottom: 16px;
            border-bottom: 1px solid var(--line);
        }
        .page-header h1 {
            margin: 0;
            font-family: 'Cormorant Garamond', serif;
            font-size: 2.5rem;
            line-height: 1;
        }
        .page-header p { margin: 8px 0 0; color: var(--ink-soft); }

        .card, .stat-card {
            background: var(--surface);
            backdrop-filter: blur(18px);
            border: 1px solid rgba(30, 36, 48, 0.08);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
        }
        .card { padding: 24px; }
        .card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 18px;
        }
        .card-title {
            margin: 0;
            font-size: 1.1rem;
            font-weight: 800;
        }
        .eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.72);
            border: 1px solid rgba(30, 36, 48, 0.08);
            color: var(--gold-deep);
            font-size: 0.74rem;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }
        .muted { color: var(--ink-soft); }
        .stats-grid, .grid-2, .grid-3 {
            display: grid;
            gap: 18px;
        }
        .stats-grid { 
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); 
            margin-bottom: 32px; 
            gap: 20px;
        }
        .grid-2 { grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); }
        .grid-3 { grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); }
        .stat-card { 
            padding: 28px; 
            display: flex;
            align-items: center;
            gap: 18px;
            min-height: 110px;
        }
        .stat-card i { color: var(--gold-deep); }
        .stat-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }
        .stat-icon {
            width: 42px;
            height: 42px;
            border-radius: 14px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: rgba(196, 138, 58, 0.12);
            color: var(--gold-deep);
        }
        .stat-label { color: var(--ink-soft); font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.12em; }
        .stat-value {
            margin-top: 10px;
            font-size: 2rem;
            font-weight: 800;
        }
        .hero-panel {
            position: relative;
            overflow: hidden;
            padding: 34px;
            border-radius: 32px;
            background:
                linear-gradient(135deg, rgba(255, 250, 243, 0.94), rgba(247, 240, 228, 0.92)),
                linear-gradient(135deg, rgba(196, 138, 58, 0.08), rgba(47, 124, 120, 0.06));
            border: 1px solid rgba(30, 36, 48, 0.08);
            box-shadow: var(--shadow);
        }
        .hero-panel::after {
            content: "";
            position: absolute;
            inset: auto -40px -60px auto;
            width: 220px;
            height: 220px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(196, 138, 58, 0.18), transparent 68%);
            pointer-events: none;
        }
        .hero-title {
            margin: 14px 0 12px;
            font-family: 'Cormorant Garamond', serif;
            font-size: clamp(2.8rem, 4vw, 4.4rem);
            line-height: 0.95;
            max-width: 10ch;
        }
        .hero-copy {
            max-width: 58ch;
            color: var(--ink-soft);
            line-height: 1.7;
            font-size: 1rem;
        }
        .hero-actions {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            margin-top: 22px;
        }
        .feature-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 16px;
        }
        .accommodation-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 18px;
        }
        .accommodation-grid-landscape {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(420px, 1fr));
            gap: 20px;
        }
        .room-card {
            display: flex;
            flex-direction: column;
            border-radius: 28px;
            overflow: hidden;
            background: rgba(255, 255, 255, 0.84);
            border: 1px solid rgba(30, 36, 48, 0.08);
            box-shadow: 0 24px 60px rgba(74, 52, 28, 0.08);
        }
        .room-card-landscape {
            display: flex;
            flex-direction: row;
            border-radius: 28px;
            overflow: hidden;
            background: rgba(255, 255, 255, 0.86);
            border: 1px solid rgba(30, 36, 48, 0.08);
            box-shadow: 0 24px 60px rgba(74, 52, 28, 0.09);
            transition: 0.3s ease;
        }
        .room-card-landscape:hover {
            transform: translateY(-4px);
            box-shadow: 0 32px 80px rgba(74, 52, 28, 0.14);
        }
        .room-photo {
            min-height: 220px;
            background-size: cover;
            background-position: center;
            position: relative;
        }
        .room-photo::after {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, rgba(0, 0, 0, 0.18), rgba(0, 0, 0, 0.05));
        }
        .room-photo-landscape {
            min-width: 280px;
            min-height: 280px;
            background-size: cover;
            background-position: center;
            position: relative;
            flex-shrink: 0;
        }
        .room-photo-landscape::after {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(90deg, rgba(0, 0, 0, 0.08), rgba(0, 0, 0, 0.02));
        }
        .room-photo-deluxe { background: linear-gradient(135deg, rgba(62, 98, 145, 0.92), rgba(203, 156, 77, 0.65)); }
        .room-photo-suite { background: linear-gradient(135deg, rgba(114, 80, 59, 0.92), rgba(225, 204, 171, 0.75)); }
        .room-photo-twin { background: linear-gradient(135deg, rgba(88, 129, 119, 0.92), rgba(243, 221, 179, 0.7)); }
        .room-choice-gallery {
            margin-bottom: 24px;
        }
        .room-choice-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 18px;
        }
        .room-choice-card {
            overflow: hidden;
            border-radius: 22px;
            background: rgba(255, 255, 255, 0.86);
            border: 1px solid rgba(30, 36, 48, 0.08);
            box-shadow: 0 18px 44px rgba(74, 52, 28, 0.08);
            transition: 0.2s ease;
        }
        .room-choice-card.is-selected {
            border-color: rgba(196, 138, 58, 0.72);
            box-shadow: 0 20px 50px rgba(196, 138, 58, 0.18);
        }
        .room-choice-image {
            display: block;
            width: 100%;
            height: 180px;
            object-fit: cover;
            background: linear-gradient(135deg, rgba(47, 124, 120, 0.20), rgba(196, 138, 58, 0.26));
        }
        .room-choice-placeholder,
        .reservation-room-placeholder,
        .table-room-placeholder {
            display: grid;
            place-items: center;
            color: var(--gold-deep);
        }
        .room-choice-placeholder i,
        .reservation-room-placeholder i {
            font-size: 2rem;
        }
        .room-choice-body {
            display: grid;
            gap: 12px;
            padding: 18px;
        }
        .room-choice-body h3 {
            margin: 0;
            font-size: 1.08rem;
        }
        .room-choice-facts {
            display: flex;
            flex-wrap: wrap;
            gap: 10px 14px;
            color: var(--ink-soft);
            font-size: 0.9rem;
        }
        .room-choice-facts i {
            color: var(--gold-deep);
            margin-right: 5px;
        }
        .reservation-room-photo {
            display: block;
            width: 100%;
            height: 260px;
            object-fit: cover;
            border-radius: 18px;
            margin-bottom: 18px;
            background: linear-gradient(135deg, rgba(47, 124, 120, 0.20), rgba(196, 138, 58, 0.26));
        }
        .table-room-photo {
            width: 88px;
            height: 58px;
            border-radius: 12px;
            object-fit: cover;
            background: linear-gradient(135deg, rgba(47, 124, 120, 0.20), rgba(196, 138, 58, 0.26));
        }
        .room-form-preview {
            display: block;
            width: min(100%, 360px);
            height: 210px;
            object-fit: cover;
            border-radius: 18px;
            margin-bottom: 12px;
            border: 1px solid var(--line);
        }
        .room-details {
            padding: 22px;
            display: flex;
            flex-direction: column;
            gap: 14px;
        }
        .room-details-landscape {
            padding: 22px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            gap: 12px;
            flex: 1;
        }
        .room-footer-landscape {
            display: flex;
            flex-direction: column;
            gap: 12px;
            margin-top: auto;
        }
        .room-tag {
            display: inline-flex;
            align-items: center;
            padding: 8px 12px;
            border-radius: 999px;
            font-size: 0.75rem;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            background: rgba(196, 138, 58, 0.12);
            color: var(--gold-deep);
            width: fit-content;
        }
        .room-card h3 {
            margin: 0;
            font-size: 1.2rem;
            line-height: 1.2;
        }
        .room-price {
            font-size: 1rem;
            font-weight: 800;
            color: var(--gold-deep);
        }
        .room-info {
            margin: 0;
            color: var(--ink-soft);
            line-height: 1.7;
        }
        .room-meta-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }
        .room-status {
            padding: 8px 12px;
            border-radius: 999px;
            font-size: 0.78rem;
            text-transform: uppercase;
            font-weight: 700;
            letter-spacing: 0.08em;
            background: rgba(47, 124, 120, 0.12);
            color: var(--teal);
        }
        .room-status.available { background: rgba(47, 124, 120, 0.14); color: var(--teal); }
        .room-status.booked { background: rgba(196, 90, 70, 0.12); color: var(--red); }
        .amenities-list {
            margin: 0;
            padding-left: 18px;
            color: var(--ink-soft);
            line-height: 1.8;
            font-size: 0.95rem;
        }
        .amenities-list li { margin-bottom: 8px; }
        
        /* Modal Styles */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            backdrop-filter: blur(6px);
        }
        .modal-content {
            position: relative;
            background: white;
            border-radius: 28px;
            padding: 32px;
            max-width: 420px;
            box-shadow: 0 32px 80px rgba(74, 52, 28, 0.2);
            animation: slideUp 0.3s ease;
        }
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .modal-close {
            position: absolute;
            top: 16px;
            right: 16px;
            width: 36px;
            height: 36px;
            border: none;
            background: rgba(30, 36, 48, 0.08);
            border-radius: 50%;
            cursor: pointer;
            font-size: 1.2rem;
            transition: 0.2s ease;
        }
        .modal-close:hover {
            background: rgba(30, 36, 48, 0.14);
        }
        .modal-content h2 {
            margin: 0 0 8px;
            font-size: 1.4rem;
            line-height: 1.3;
        }
        .modal-price {
            color: var(--gold-deep);
            font-size: 1.1rem;
            font-weight: 700;
            margin-bottom: 20px;
        }
        .modal-actions {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        .modal-actions .btn {
            justify-content: center;
            padding: 12px 16px;
        }
        .hero-panel {
            position: relative;
            overflow: hidden;
            padding: 34px;
            border-radius: 32px;
            background:
                linear-gradient(135deg, rgba(255, 250, 243, 0.94), rgba(247, 240, 228, 0.92)),
                linear-gradient(135deg, rgba(196, 138, 58, 0.08), rgba(47, 124, 120, 0.06));
            border: 1px solid rgba(30, 36, 48, 0.08);
            box-shadow: var(--shadow);
        }
        .feature-icon {
            width: 46px;
            height: 46px;
            border-radius: 14px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: rgba(47, 124, 120, 0.1);
            color: var(--teal);
            margin-bottom: 14px;
        }
        .feature-card h3 {
            margin: 0 0 8px;
            font-size: 1rem;
        }
        .feature-card p {
            margin: 0;
            color: var(--ink-soft);
            line-height: 1.6;
            font-size: 0.92rem;
        }
        .module-card {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-height: 200px;
        }
        .module-card.is-disabled {
            opacity: 0.64;
            filter: saturate(0.7);
        }
        .module-icon {
            width: 44px;
            height: 44px;
            border-radius: 14px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: rgba(196, 138, 58, 0.12);
            color: var(--gold-deep);
            margin-bottom: 14px;
        }
        .section-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 18px;
        }
        .section-header h2 {
            margin: 0;
            font-size: 1.1rem;
            font-weight: 800;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 11px 16px;
            border: 1px solid transparent;
            border-radius: 999px;
            text-decoration: none;
            font-weight: 700;
            cursor: pointer;
            font-family: inherit;
            transition: 0.2s ease;
        }
        .btn:hover { transform: translateY(-1px); }
        .btn-primary {
            background: linear-gradient(135deg, var(--gold), var(--gold-deep));
            color: white;
            box-shadow: 0 14px 28px rgba(138, 85, 32, 0.18);
        }
        .btn-secondary {
            background: white;
            border-color: var(--line);
            color: var(--ink);
        }
        .btn-ghost {
            background: rgba(255, 255, 255, 0.48);
            border-color: rgba(255, 255, 255, 0.34);
            color: var(--ink);
            backdrop-filter: blur(14px);
        }
        .btn-lg { padding: 13px 18px; }
        .btn-danger {
            background: rgba(196, 90, 70, 0.12);
            border-color: rgba(196, 90, 70, 0.22);
            color: var(--red);
        }
        .btn-sm { padding: 8px 12px; font-size: 0.85rem; }

        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 14px 10px; text-align: left; border-bottom: 1px solid rgba(30, 36, 48, 0.08); }
        th {
            color: var(--ink-soft);
            text-transform: uppercase;
            letter-spacing: 0.1em;
            font-size: 0.73rem;
        }
        .table-wrap { overflow-x: auto; }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 10px;
            border-radius: 999px;
            font-size: 0.76rem;
            font-weight: 700;
        }
        .badge-success { background: rgba(111, 133, 104, 0.14); color: #4e6847; }
        .badge-warning { background: rgba(196, 138, 58, 0.14); color: var(--gold-deep); }
        .badge-danger { background: rgba(196, 90, 70, 0.14); color: var(--red); }
        .badge-info { background: rgba(47, 124, 120, 0.14); color: var(--teal); }
        .badge-secondary { background: rgba(30, 36, 48, 0.08); color: var(--ink-soft); }

        .alert {
            margin-bottom: 18px;
            padding: 14px 18px;
            border-radius: 18px;
            border: 1px solid var(--line);
            background: rgba(255, 255, 255, 0.72);
        }
        .alert-success { border-color: rgba(111, 133, 104, 0.22); color: #4e6847; }
        .alert-danger { border-color: rgba(196, 90, 70, 0.22); color: var(--red); }

        .form-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px; }
        .form-group { margin-bottom: 16px; }
        .form-label {
            display: block;
            margin-bottom: 8px;
            font-size: 0.8rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--ink-soft);
        }
        .form-control {
            width: 100%;
            padding: 13px 14px;
            border-radius: 16px;
            border: 1px solid rgba(30, 36, 48, 0.12);
            background: rgba(255, 255, 255, 0.92);
            font: inherit;
            color: var(--ink);
        }
        .form-control:focus { outline: 2px solid rgba(196, 138, 58, 0.2); border-color: rgba(196, 138, 58, 0.5); }
        .form-error { margin-top: 6px; color: var(--red); font-size: 0.86rem; }
        textarea.form-control { min-height: 120px; resize: vertical; }

        .empty-state {
            padding: 36px 18px;
            text-align: center;
            color: var(--ink-soft);
        }
        .empty-state i { font-size: 2rem; color: var(--gold-deep); margin-bottom: 12px; }

        .pagination { margin-top: 18px; }
        .pagination nav > div:first-child { display: none; }
        .pagination span, .pagination a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 40px;
            height: 40px;
            margin-right: 8px;
            border-radius: 999px;
            background: white;
            border: 1px solid var(--line);
            text-decoration: none;
        }

        @media (max-width: 1080px) {
            .shell { grid-template-columns: 1fr; }
            .sidebar { 
                position: static; 
                height: auto; 
                border-right: 0; 
                border-bottom: 1px solid rgba(30, 36, 48, 0.08);
                padding: 24px 20px;
            }
            .main { padding: 24px 20px 48px; }
        }

        @media (max-width: 768px) {
            .stats-grid { grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 16px; }
            .stat-card { padding: 20px; min-height: 90px; }
            .modules-grid { grid-template-columns: 1fr; gap: 16px; }
            .quick-actions { grid-template-columns: repeat(auto-fit, minmax(120px, 1fr)); gap: 12px; }
            .action-btn { min-height: 80px; padding: 16px 12px; }
            .main { padding: 20px 16px 40px; }
        }

        @media (max-width: 480px) {
            .stats-grid { grid-template-columns: 1fr; }
            .quick-actions { grid-template-columns: repeat(2, 1fr); }
            .module-card { flex-direction: column; text-align: center; padding: 20px; }
            .module-icon { margin-right: 0; margin-bottom: 16px; }
        }

        /* Tab Styles */
        .tabs {
            display: flex;
            background: var(--surface);
            border-radius: var(--radius-sm);
            padding: 4px;
            margin-bottom: 24px;
            box-shadow: var(--shadow);
        }
        .tab-btn {
            flex: 1;
            padding: 12px 20px;
            border: none;
            background: transparent;
            color: var(--ink-soft);
            font-weight: 500;
            border-radius: calc(var(--radius-sm) - 4px);
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .tab-btn.active {
            background: var(--gold);
            color: white;
            box-shadow: 0 4px 12px rgba(196, 138, 58, 0.3);
        }
        .tab-btn:hover:not(.active) {
            background: rgba(196, 138, 58, 0.1);
            color: var(--gold-deep);
        }
        .tab-content {
            display: none;
        }
        .tab-content.active {
            display: block;
        }

        /* Form Grid */
        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 16px;
        }
        .form-group {
            display: flex;
            flex-direction: column;
        }
        .form-group.full-width {
            grid-column: 1 / -1;
        }
        .form-group label {
            font-weight: 600;
            margin-bottom: 8px;
            color: var(--ink);
        }
        .form-control {
            padding: 12px 16px;
            border: 2px solid var(--line);
            border-radius: var(--radius-sm);
            font-size: 14px;
            transition: border-color 0.3s ease;
        }
        .form-control:focus {
            outline: none;
            border-color: var(--gold);
            box-shadow: 0 0 0 3px rgba(196, 138, 58, 0.1);
        }

        .sr-only {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            white-space: nowrap;
            border: 0;
        }
        .btn:focus-visible,
        .nav-link:focus-visible,
        .topbar-icon-btn:focus-visible,
        .topbar-search input:focus-visible,
        .landing-search input:focus-visible {
            outline: 3px solid rgba(196, 138, 58, 0.32);
            outline-offset: 3px;
        }
        .topbar-left {
            display: flex;
            align-items: center;
            gap: 16px;
            min-width: 0;
        }
        .topbar-brand-title {
            min-width: 172px;
            flex: 0 0 auto;
        }
        .topbar-brand-title h1 {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.35rem;
            line-height: 1;
        }
        .topbar-brand-title p {
            white-space: nowrap;
        }
        .topbar-search {
            width: min(34vw, 420px);
            min-width: 240px;
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 14px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.72);
            border: 1px solid rgba(30, 36, 48, 0.08);
        }
        .topbar-search i { color: var(--gold-deep); }
        .topbar-search input {
            width: 100%;
            border: 0;
            outline: 0;
            background: transparent;
            color: var(--ink);
            font: inherit;
            font-size: 0.92rem;
        }
        .topbar-icon-btn {
            width: 42px;
            height: 42px;
            border: 1px solid var(--line);
            border-radius: 50%;
            display: inline-grid;
            place-items: center;
            background: rgba(255, 255, 255, 0.72);
            color: var(--ink);
            cursor: pointer;
            position: relative;
            transition: 0.2s ease;
        }
        .topbar-icon-btn:hover {
            transform: translateY(-1px);
            color: var(--gold-deep);
            box-shadow: 0 12px 24px rgba(74, 52, 28, 0.10);
        }
        .topbar-dot {
            position: absolute;
            top: 9px;
            right: 9px;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: var(--red);
            border: 2px solid white;
        }
        .language-select {
            border: 1px solid var(--line);
            border-radius: 999px;
            padding: 10px 12px;
            background: rgba(255, 255, 255, 0.72);
            color: var(--ink);
            font: inherit;
            font-weight: 700;
        }
        .sidebar-stats {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin-top: 18px;
        }
        .sidebar-stat {
            padding: 13px;
            border-radius: 16px;
            background: rgba(255, 255, 255, 0.62);
            border: 1px solid rgba(30, 36, 48, 0.08);
            transition: 0.2s ease;
        }
        .sidebar-stat:hover {
            transform: translateY(-2px);
            box-shadow: 0 16px 32px rgba(74, 52, 28, 0.09);
        }
        .sidebar-stat strong {
            display: block;
            font-size: 1.08rem;
            color: var(--ink);
        }
        .sidebar-stat span {
            display: block;
            margin-top: 4px;
            color: var(--ink-soft);
            font-size: 0.72rem;
            line-height: 1.35;
        }
        .weather-widget,
        .review-widget {
            margin-top: 12px;
            padding: 14px;
            border-radius: 18px;
            background: rgba(255, 255, 255, 0.58);
            border: 1px solid rgba(30, 36, 48, 0.08);
        }
        .weather-widget {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }
        .weather-widget i {
            color: var(--gold-deep);
            font-size: 1.25rem;
        }
        .weather-widget strong,
        .review-widget strong { display: block; font-size: 0.92rem; }
        .weather-widget span,
        .review-widget span { color: var(--ink-soft); font-size: 0.8rem; }

        .fade-up {
            opacity: 0;
            animation: fadeUp 0.75s ease forwards;
            animation-delay: var(--delay, 0s);
        }
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(18px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .luxury-hero {
            display: grid;
            grid-template-columns: minmax(0, 0.92fr) minmax(420px, 1.08fr);
            gap: 28px;
            align-items: stretch;
            margin-bottom: 24px;
        }
        .hero-copy-panel,
        .hero-visual-panel {
            position: relative;
            min-height: 640px;
            border-radius: 34px;
            overflow: hidden;
            border: 1px solid rgba(30, 36, 48, 0.08);
            box-shadow: var(--shadow);
        }
        .hero-copy-panel {
            padding: clamp(28px, 4vw, 52px);
            display: flex;
            flex-direction: column;
            justify-content: center;
            background:
                linear-gradient(140deg, rgba(255, 250, 243, 0.96), rgba(244, 235, 222, 0.88)),
                radial-gradient(circle at 20% 15%, rgba(196, 138, 58, 0.2), transparent 34%);
        }
        .hero-copy-panel::after {
            content: "";
            position: absolute;
            right: -80px;
            bottom: -100px;
            width: 280px;
            height: 280px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(47, 124, 120, 0.12), transparent 70%);
            pointer-events: none;
        }
        .hero-copy-panel h1 {
            margin: 18px 0 16px;
            max-width: 11ch;
            font-family: 'Cormorant Garamond', serif;
            font-size: clamp(3rem, 5.6vw, 5.9rem);
            line-height: 0.92;
            letter-spacing: 0;
        }
        .hero-copy-panel p {
            max-width: 62ch;
            margin: 0;
            color: var(--ink-soft);
            font-size: 1.04rem;
            line-height: 1.75;
        }
        .hero-metrics {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 12px;
            margin-top: 32px;
        }
        .hero-metrics div {
            padding: 16px;
            border-radius: 18px;
            background: rgba(255, 255, 255, 0.68);
            border: 1px solid rgba(30, 36, 48, 0.08);
        }
        .hero-metrics strong {
            display: block;
            color: var(--gold-deep);
            font-size: 1.55rem;
            line-height: 1;
        }
        .hero-metrics span {
            display: block;
            margin-top: 8px;
            color: var(--ink-soft);
            font-size: 0.78rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            line-height: 1.35;
        }
        .hero-visual-panel { background: #211910; }
        .hero-carousel,
        .carousel-slide {
            position: absolute;
            inset: 0;
        }
        .carousel-slide {
            opacity: 0;
            animation: heroSlide 18s infinite;
        }
        .carousel-slide:nth-child(2) { animation-delay: 6s; }
        .carousel-slide:nth-child(3) { animation-delay: 12s; }
        .carousel-slide img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            filter: saturate(0.95) contrast(1.02);
            transform: scale(1.02);
            animation: imageDrift 18s ease-in-out infinite;
        }
        .hero-visual-panel::after {
            content: "";
            position: absolute;
            inset: 0;
            background:
                linear-gradient(180deg, rgba(30, 24, 18, 0.08), rgba(30, 24, 18, 0.44)),
                radial-gradient(circle at 78% 18%, rgba(255, 223, 170, 0.28), transparent 28%);
            pointer-events: none;
        }
        @keyframes heroSlide {
            0%, 28% { opacity: 1; }
            34%, 100% { opacity: 0; }
        }
        @keyframes imageDrift {
            50% { transform: scale(1.08); }
        }
        .floating-reservation {
            position: absolute;
            left: 24px;
            right: 24px;
            bottom: 24px;
            z-index: 2;
            display: grid;
            gap: 16px;
            padding: 20px;
            border-radius: 26px;
            background: rgba(255, 250, 243, 0.82);
            border: 1px solid rgba(255, 255, 255, 0.44);
            backdrop-filter: blur(18px);
            box-shadow: 0 28px 60px rgba(0, 0, 0, 0.18);
        }
        .floating-reservation h2 {
            margin: 10px 0 0;
            font-family: 'Cormorant Garamond', serif;
            font-size: 2rem;
            line-height: 1;
        }
        .availability-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }
        .availability-grid label {
            display: grid;
            gap: 7px;
            color: var(--ink-soft);
            font-size: 0.74rem;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }
        .availability-grid input,
        .availability-grid select {
            width: 100%;
            border: 1px solid rgba(30, 36, 48, 0.10);
            border-radius: 15px;
            padding: 12px;
            background: rgba(255, 255, 255, 0.9);
            color: var(--ink);
            font: inherit;
            text-transform: none;
            letter-spacing: 0;
            font-weight: 700;
        }
        .suite-orbit-card {
            position: absolute;
            top: 28px;
            right: 28px;
            z-index: 2;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 12px 14px;
            border-radius: 999px;
            background: rgba(255, 250, 243, 0.78);
            border: 1px solid rgba(255, 255, 255, 0.45);
            backdrop-filter: blur(14px);
            color: var(--gold-deep);
            font-size: 0.8rem;
            font-weight: 800;
            animation: floatSoft 4s ease-in-out infinite;
        }
        @keyframes floatSoft {
            50% { transform: translateY(-8px); }
        }
        .tour-pulse { animation: tourPulse 1.4s ease; }
        @keyframes tourPulse {
            50% { box-shadow: 0 0 0 8px rgba(196, 138, 58, 0.20), var(--shadow); }
        }
        .experience-strip {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 14px;
            margin-bottom: 34px;
        }
        .experience-strip article,
        .management-card,
        .room-showcase-card {
            transition: transform 0.22s ease, box-shadow 0.22s ease, border-color 0.22s ease;
        }
        .experience-strip article {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 16px;
            border-radius: 20px;
            background: rgba(255, 250, 243, 0.72);
            border: 1px solid rgba(30, 36, 48, 0.08);
            box-shadow: 0 18px 42px rgba(74, 52, 28, 0.07);
        }
        .experience-strip article:hover,
        .management-card:hover,
        .room-showcase-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 30px 70px rgba(74, 52, 28, 0.12);
            border-color: rgba(196, 138, 58, 0.22);
        }
        .experience-strip i {
            width: 42px;
            height: 42px;
            border-radius: 14px;
            display: inline-grid;
            place-items: center;
            background: rgba(196, 138, 58, 0.12);
            color: var(--gold-deep);
            flex: 0 0 auto;
        }
        .experience-strip span {
            display: block;
            color: var(--ink-soft);
            font-size: 0.74rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }
        .experience-strip strong {
            display: block;
            margin-top: 3px;
            font-size: 0.94rem;
        }
        .section-heading {
            display: flex;
            justify-content: space-between;
            align-items: end;
            gap: 20px;
            margin: 34px 0 18px;
        }
        .section-heading h2 {
            margin: 12px 0 0;
            font-family: 'Cormorant Garamond', serif;
            font-size: clamp(2rem, 3vw, 3rem);
            line-height: 1;
        }
        .landing-search {
            width: min(100%, 420px);
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 15px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.74);
            border: 1px solid rgba(30, 36, 48, 0.08);
            box-shadow: 0 14px 34px rgba(74, 52, 28, 0.07);
        }
        .landing-search i { color: var(--gold-deep); }
        .landing-search input {
            width: 100%;
            border: 0;
            outline: 0;
            background: transparent;
            color: var(--ink);
            font: inherit;
        }
        .room-showcase-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 20px;
        }
        .room-showcase-card {
            overflow: hidden;
            border-radius: 28px;
            background: rgba(255, 250, 243, 0.78);
            border: 1px solid rgba(30, 36, 48, 0.08);
            box-shadow: 0 24px 60px rgba(74, 52, 28, 0.08);
            backdrop-filter: blur(18px);
        }
        .room-showcase-card img {
            display: block;
            width: 100%;
            height: 260px;
            object-fit: cover;
            transition: transform 0.5s ease;
        }
        .room-showcase-card:hover img { transform: scale(1.04); }
        .room-showcase-body {
            display: grid;
            gap: 14px;
            padding: 20px;
        }
        .room-showcase-body h3 {
            margin: 0;
            font-size: 1.2rem;
        }
        .room-showcase-body p {
            margin: 0;
            color: var(--ink-soft);
            line-height: 1.6;
        }
        .amenity-chips {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }
        .amenity-chips span {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 10px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.68);
            border: 1px solid rgba(30, 36, 48, 0.07);
            color: var(--ink-soft);
            font-size: 0.82rem;
        }
        .amenity-chips i { color: var(--gold-deep); }
        .room-showcase-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            padding-top: 6px;
        }
        .room-showcase-footer strong { color: var(--gold-deep); }
        .management-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 16px;
        }
        .management-card {
            padding: 20px;
            border-radius: 22px;
            background: rgba(255, 250, 243, 0.72);
            border: 1px solid rgba(30, 36, 48, 0.08);
            box-shadow: 0 18px 42px rgba(74, 52, 28, 0.07);
        }
        .management-card > i {
            width: 46px;
            height: 46px;
            border-radius: 15px;
            display: inline-grid;
            place-items: center;
            background: rgba(47, 124, 120, 0.10);
            color: var(--teal);
            margin-bottom: 15px;
        }
        .management-card h3 {
            margin: 0 0 8px;
            font-size: 1rem;
        }
        .management-card p {
            margin: 0;
            color: var(--ink-soft);
            line-height: 1.6;
            font-size: 0.9rem;
        }
        .dashboard-command {
            display: grid;
            grid-template-columns: minmax(0, 1fr) 360px;
            gap: 22px;
            align-items: stretch;
            margin-bottom: 28px;
            padding: clamp(24px, 4vw, 38px);
            border-radius: 32px;
            background:
                linear-gradient(135deg, rgba(255, 250, 243, 0.92), rgba(244, 235, 222, 0.84)),
                radial-gradient(circle at 88% 0%, rgba(47, 124, 120, 0.14), transparent 30%);
            border: 1px solid rgba(30, 36, 48, 0.08);
            box-shadow: var(--shadow);
            overflow: hidden;
            position: relative;
        }
        .dashboard-command::after {
            content: "";
            position: absolute;
            width: 220px;
            height: 220px;
            right: -70px;
            bottom: -90px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(196, 138, 58, 0.18), transparent 70%);
            pointer-events: none;
        }
        .dashboard-command h1 {
            margin: 14px 0 12px;
            max-width: 15ch;
            font-family: 'Cormorant Garamond', serif;
            font-size: clamp(2.7rem, 4vw, 4.6rem);
            line-height: 0.95;
        }
        .dashboard-command p {
            max-width: 66ch;
            margin: 0;
            color: var(--ink-soft);
            line-height: 1.75;
        }
        .command-stack {
            position: relative;
            z-index: 1;
            display: grid;
            gap: 12px;
            align-content: center;
        }
        .command-stack article {
            padding: 18px;
            border-radius: 20px;
            background: rgba(255, 255, 255, 0.66);
            border: 1px solid rgba(30, 36, 48, 0.08);
            backdrop-filter: blur(12px);
        }
        .command-stack span {
            color: var(--ink-soft);
            font-size: 0.74rem;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }
        .command-stack strong {
            display: block;
            margin: 8px 0 12px;
            font-size: 1rem;
        }
        .progress-line {
            height: 8px;
            overflow: hidden;
            border-radius: 999px;
            background: rgba(30, 36, 48, 0.08);
        }
        .progress-line span {
            display: block;
            height: 100%;
            border-radius: inherit;
            background: linear-gradient(90deg, var(--gold), var(--teal));
        }
        html.theme-dark .sidebar,
        html.theme-dark .topbar,
        html.theme-dark .hero-copy-panel,
        html.theme-dark .dashboard-command,
        html.theme-dark .card,
        html.theme-dark .stat-card,
        html.theme-dark .experience-strip article,
        html.theme-dark .management-card,
        html.theme-dark .room-showcase-card,
        html.theme-dark .floating-reservation,
        html.theme-dark .sidebar-card,
        html.theme-dark .sidebar-stat,
        html.theme-dark .weather-widget,
        html.theme-dark .review-widget,
        html.theme-dark .command-stack article,
        html.theme-dark .brand,
        body.dark-preview .sidebar,
        body.dark-preview .topbar,
        body.dark-preview .hero-copy-panel,
        body.dark-preview .dashboard-command,
        body.dark-preview .card,
        body.dark-preview .stat-card,
        body.dark-preview .experience-strip article,
        body.dark-preview .management-card,
        body.dark-preview .room-showcase-card,
        body.dark-preview .floating-reservation,
        body.dark-preview .sidebar-card,
        body.dark-preview .sidebar-stat,
        body.dark-preview .weather-widget,
        body.dark-preview .review-widget,
        body.dark-preview .command-stack article,
        body.dark-preview .brand {
            background: rgba(10, 10, 10, 0.88);
            border-color: rgba(255, 255, 255, 0.14);
            color: #ffffff;
            box-shadow: 0 24px 60px rgba(0, 0, 0, 0.36);
        }
        html.theme-dark .topbar-search,
        html.theme-dark .landing-search,
        html.theme-dark .user-pill,
        html.theme-dark .language-select,
        html.theme-dark .topbar-icon-btn,
        html.theme-dark .btn-secondary,
        html.theme-dark .btn-ghost,
        html.theme-dark .availability-grid input,
        html.theme-dark .availability-grid select,
        html.theme-dark .amenity-chips span,
        html.theme-dark .hero-metrics div,
        html.theme-dark .form-control,
        html.theme-dark .alert,
        html.theme-dark .demo-card,
        html.theme-dark .demo-item,
        html.theme-dark .modal-content,
        html.theme-dark .pagination span,
        html.theme-dark .pagination a,
        body.dark-preview .topbar-search,
        body.dark-preview .landing-search,
        body.dark-preview .user-pill,
        body.dark-preview .language-select,
        body.dark-preview .topbar-icon-btn,
        body.dark-preview .btn-secondary,
        body.dark-preview .btn-ghost,
        body.dark-preview .availability-grid input,
        body.dark-preview .availability-grid select,
        body.dark-preview .amenity-chips span,
        body.dark-preview .hero-metrics div,
        body.dark-preview .form-control,
        body.dark-preview .alert,
        body.dark-preview .demo-card,
        body.dark-preview .demo-item,
        body.dark-preview .modal-content,
        body.dark-preview .pagination span,
        body.dark-preview .pagination a {
            background: #000000;
            border-color: rgba(255, 255, 255, 0.18);
            color: #ffffff;
        }
        html.theme-dark .topbar-search input,
        html.theme-dark .landing-search input,
        body.dark-preview .topbar-search input,
        body.dark-preview .landing-search input {
            color: #ffffff;
        }
        html.theme-dark .topbar-search input::placeholder,
        html.theme-dark .landing-search input::placeholder,
        html.theme-dark .form-control::placeholder,
        body.dark-preview .topbar-search input::placeholder,
        body.dark-preview .landing-search input::placeholder,
        body.dark-preview .form-control::placeholder {
            color: rgba(255, 255, 255, 0.68);
        }
        html.theme-dark .brand-highlight,
        html.theme-dark .module-icon,
        html.theme-dark .feature-icon,
        html.theme-dark .stat-icon,
        html.theme-dark .experience-strip i,
        html.theme-dark .management-card > i,
        body.dark-preview .brand-highlight,
        body.dark-preview .module-icon,
        body.dark-preview .feature-icon,
        body.dark-preview .stat-icon,
        body.dark-preview .experience-strip i,
        body.dark-preview .management-card > i {
            background: rgba(196, 138, 58, 0.18);
            color: #f0c47d;
        }
        html.theme-dark .muted,
        html.theme-dark .brand-info p,
        html.theme-dark .brand-subtitle,
        html.theme-dark .topbar p,
        html.theme-dark .room-showcase-body p,
        html.theme-dark .management-card p,
        html.theme-dark .experience-strip span,
        html.theme-dark .review-widget span,
        html.theme-dark .weather-widget span,
        html.theme-dark .hero-copy-panel p,
        html.theme-dark .dashboard-command p,
        body.dark-preview .muted,
        body.dark-preview .brand-info p,
        body.dark-preview .brand-subtitle,
        body.dark-preview .topbar p,
        body.dark-preview .room-showcase-body p,
        body.dark-preview .management-card p,
        body.dark-preview .experience-strip span,
        body.dark-preview .review-widget span,
        body.dark-preview .weather-widget span,
        body.dark-preview .hero-copy-panel p,
        body.dark-preview .dashboard-command p {
            color: #dedede;
        }
        html.theme-dark table th,
        html.theme-dark table td,
        body.dark-preview table th,
        body.dark-preview table td {
            border-color: rgba(255, 255, 255, 0.12);
        }

        @media (prefers-reduced-motion: reduce) {
            *,
            *::before,
            *::after {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                scroll-behavior: auto !important;
                transition-duration: 0.01ms !important;
            }
        }
        @media (max-width: 1080px) {
            .luxury-hero { grid-template-columns: 1fr; }
            .dashboard-command { grid-template-columns: 1fr; }
            .hero-copy-panel,
            .hero-visual-panel { min-height: auto; }
            .hero-visual-panel { min-height: 560px; }
            .experience-strip,
            .management-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .room-showcase-grid { grid-template-columns: 1fr; }
        }
        @media (max-width: 768px) {
            .content { padding: 16px; }
            .topbar {
                position: static;
                align-items: flex-start;
                flex-direction: column;
                border-radius: 22px;
            }
            .topbar-left,
            .user-cluster {
                width: 100%;
                flex-wrap: wrap;
            }
            .topbar-brand-title {
                width: 100%;
                min-width: 0;
            }
            .topbar-brand-title p {
                white-space: normal;
            }
            .topbar-search {
                order: 2;
                width: 100%;
                min-width: 0;
            }
            .language-select { display: none; }
            .luxury-hero { gap: 18px; }
            .hero-copy-panel { padding: 28px; border-radius: 26px; }
            .hero-copy-panel h1 { max-width: 12ch; }
            .hero-actions,
            .hero-metrics,
            .availability-grid,
            .experience-strip,
            .management-grid {
                grid-template-columns: 1fr;
            }
            .hero-actions .btn {
                width: 100%;
                justify-content: center;
            }
            .hero-visual-panel { min-height: 520px; border-radius: 26px; }
            .floating-reservation {
                left: 14px;
                right: 14px;
                bottom: 14px;
            }
            .suite-orbit-card {
                top: 16px;
                right: 16px;
                max-width: calc(100% - 32px);
            }
            .section-heading {
                align-items: stretch;
                flex-direction: column;
            }
            .landing-search { width: 100%; }
            .room-showcase-card img { height: 220px; }
            .room-showcase-footer {
                align-items: stretch;
                flex-direction: column;
            }
            .room-showcase-footer .btn { justify-content: center; }
        }
        @media (max-width: 480px) {
            .brand { align-items: flex-start; }
            .brand-mark { width: 62px; height: 62px; }
            .brand-title { font-size: 1.45rem; }
            .hero-copy-panel h1 { font-size: 3rem; }
            .hero-copy-panel p { font-size: 0.96rem; }
            .user-pill { width: 100%; }
        }
    </style>
    @stack('styles')
</head>
<body>
@php
    $frontDeskPendingCount = 0;
    $billingNotificationCount = 0;

    if (auth()->check() && auth()->user()->hasRole(['receptionist', 'admin'])) {
        $frontDeskPendingCount = \App\Models\Booking::where('status', 'pending')->count();
        $billingNotificationCount = \App\Models\Invoice::whereIn('status', ['unpaid', 'partially_paid', 'overdue'])->count();
    }
@endphp
<div class="shell">
    <aside class="sidebar">
        <a href="{{ route('home') }}" class="brand">
            <div class="brand-mark"><img src="{{ asset('images/logo.png') }}" alt="Grand Azure Hotel"></div>
            <div>
                <div class="brand-title">Grand Azure</div>
                <div class="brand-subtitle">Hotel Operations Suite</div>
            </div>
        </a>

        <!-- Hotel Highlights -->
        <div class="brand-info">
            <h4>Welcome to Grand Azure</h4>
            <p>Premium hospitality meets professional operations. Experience seamless booking, elegant rooms, and world-class service.</p>
            <div class="sidebar-stats" aria-label="Hotel statistics">
                <div class="sidebar-stat">
                    <strong>120+</strong>
                    <span>Luxury Rooms</span>
                </div>
                <div class="sidebar-stat">
                    <strong>4.9</strong>
                    <span>Guest Satisfaction</span>
                </div>
                <div class="sidebar-stat">
                    <strong>38</strong>
                    <span>Rooms Available</span>
                </div>
                <div class="sidebar-stat">
                    <strong>24/7</strong>
                    <span>Concierge Service</span>
                </div>
            </div>
            <div class="weather-widget">
                <div>
                    <strong>Manila Bay</strong>
                    <span>29 C clear evening</span>
                </div>
                <i class="fas fa-cloud-sun"></i>
            </div>
            <div class="review-widget">
                <strong><i class="fas fa-star" style="color: var(--gold-deep);"></i> "Impeccable service and calm check-in."</strong>
                <span>Recent verified guest review</span>
            </div>
            <div class="brand-highlight">
                <i class="fas fa-crown"></i>
                <span>Luxury Collection</span>
            </div>
            <div class="brand-highlight">
                <i class="fas fa-star"></i>
                <span>5-Star Service</span>
            </div>
            <div class="brand-highlight">
                <i class="fas fa-globe"></i>
                <span>Global Standards</span>
            </div>
        </div>

        @auth
            <div class="sidebar-section">
                <div class="section-label">Navigation</div>
                <div class="nav-list">
                    <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="fas fa-chart-line"></i> Dashboard
                    </a>

                    @if(auth()->user()->canAccessModule('room_management'))
                        <a href="{{ route('rooms.index') }}" class="nav-link {{ request()->routeIs('rooms.*') ? 'active' : '' }}">
                            <i class="fas fa-door-open"></i> Rooms
                        </a>
                    @endif

                    @if(auth()->user()->canAccessModule('reservation_booking'))
                        <a href="{{ route('bookings.index') }}" class="nav-link {{ request()->routeIs('bookings.*') ? 'active' : '' }}">
                            <i class="fas fa-calendar-check"></i> Reservations
                            @if($frontDeskPendingCount > 0)
                                <span class="nav-count">{{ $frontDeskPendingCount }}</span>
                            @endif
                        </a>
                    @endif

                    @if(auth()->user()->canAccessModule('billing_payments'))
                        <a href="{{ route('billing.index') }}" class="nav-link {{ request()->routeIs('billing.*') ? 'active' : '' }}">
                            <i class="fas fa-file-invoice-dollar"></i> Billing
                            @if($billingNotificationCount > 0)
                                <span class="nav-count">{{ $billingNotificationCount }}</span>
                            @endif
                        </a>
                    @endif

                    @if(auth()->user()->canAccessModule('guest_history'))
                        <a href="{{ route('guests.index') }}" class="nav-link {{ request()->routeIs('guests.*') ? 'active' : '' }}">
                            <i class="fas fa-address-book"></i> Guest History
                        </a>
                    @endif

                    @if(auth()->user()->canAccessModule('checkin_checkout'))
                        <a href="{{ route('operations.checkins.index') }}" class="nav-link {{ request()->routeIs('operations.*') ? 'active' : '' }}">
                            <i class="fas fa-key"></i> Check-In / Out
                        </a>
                    @endif

                    @if(auth()->user()->canAccessModule('housekeeping_maintenance'))
                        <a href="{{ route('housekeeping.index') }}" class="nav-link {{ request()->routeIs('housekeeping.*') ? 'active' : '' }}">
                            <i class="fas fa-broom"></i> Housekeeping
                            @if(auth()->user()->isHousekeepingStaff())
                                @php
                                    $pendingCount = \App\Models\HousekeepingTask::where('status', 'pending')
                                        ->whereHas('staff', function($query) {
                                            $query->where('user_id', auth()->id());
                                        })->count();
                                @endphp
                                @if($pendingCount > 0)
                                    <span class="notification-badge">{{ $pendingCount }}</span>
                                @endif
                            @endif
                        </a>
                    @endif

                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('staff.index') }}" class="nav-link {{ request()->routeIs('staff.*') ? 'active' : '' }}">
                            <i class="fas fa-id-badge"></i> Staff
                        </a>
                        <a href="{{ route('admin.users') }}" class="nav-link {{ request()->routeIs('admin.*') ? 'active' : '' }}">
                            <i class="fas fa-shield-halved"></i> Admin
                        </a>
                    @endif
                </div>
            </div>

            <div class="sidebar-card">
                <strong><i class="fas fa-briefcase"></i> {{ auth()->user()->roleLabel() }}</strong>
                <p>Module access is now aligned with the hotel operations design: guests handle their own bookings, receptionists manage all front desk operations including reservations, check-ins, billing, and housekeeping coordination, while admins have full system access.</p>
            </div>
        @endif
    </aside>

    <div class="content">
        <div class="topbar">
            <div class="topbar-left">
                <div class="topbar-brand-title">
                    <h1>Grand Azure</h1>
                    <p>Hotel Operations - {{ now()->format('M d, Y') }}</p>
                </div>
                <label class="topbar-search" for="global-search">
                    <i class="fas fa-magnifying-glass"></i>
                    <input id="global-search" type="search" placeholder="Search rooms, guests, invoices...">
                </label>
            </div>

            @auth
                <div class="user-cluster">
                    @if(auth()->user()->canAccessModule('reservation_booking'))
                        <a href="{{ route('bookings.index') }}" class="btn btn-primary btn-sm"><i class="fas fa-calendar-check"></i> Reservations</a>
                    @endif
                    <button type="button" class="topbar-icon-btn" aria-label="View notifications">
                        <i class="fas fa-bell"></i>
                        @if(($frontDeskPendingCount + $billingNotificationCount) > 0)
                            <span class="topbar-dot"></span>
                        @endif
                    </button>
                    <button type="button" class="topbar-icon-btn js-theme-toggle" aria-label="Toggle dark mode" onclick="window.toggleGrandAzureTheme?.()">
                        <i class="fas fa-moon"></i>
                    </button>
                    <select class="language-select" aria-label="Language selector">
                        <option>EN</option>
                        <option>PH</option>
                    </select>
                    <a href="{{ route('profile.edit') }}" class="btn btn-secondary btn-sm"><i class="fas fa-user-gear"></i> Profile</a>
                    <div class="user-pill">
                        <div class="avatar">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</div>
                        <div>
                            <div style="font-weight: 800;">{{ auth()->user()->name }}</div>
                            <div class="role-chip"><i class="fas fa-user-tie"></i> {{ auth()->user()->roleLabel() }}</div>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-secondary btn-sm"><i class="fas fa-right-from-bracket"></i> Logout</button>
                    </form>
                </div>
            @else
                <div class="user-cluster">
                    <a href="{{ route('login') }}" class="btn btn-secondary btn-sm"><i class="fas fa-right-to-bracket"></i> Login</a>
                    <a href="{{ route('register') }}" class="btn btn-primary btn-sm"><i class="fas fa-calendar-check"></i> Reserve Access</a>
                    <button type="button" class="topbar-icon-btn js-theme-toggle" aria-label="Toggle dark mode" onclick="window.toggleGrandAzureTheme?.()">
                        <i class="fas fa-moon"></i>
                    </button>
                    <select class="language-select" aria-label="Language selector">
                        <option>EN</option>
                        <option>PH</option>
                    </select>
                </div>
            @endauth
        </div>

        <main class="main">
            @if(session('success'))
                <div class="alert alert-success"><i class="fas fa-circle-check"></i> {{ session('success') }}</div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger"><i class="fas fa-circle-exclamation"></i> {{ session('error') }}</div>
            @endif

            @yield('content')
        </main>
    </div>
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
