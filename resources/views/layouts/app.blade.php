<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Grand Azure') — Hotel Management</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root {
            --gold: #C9A84C; --gold-light: #E8C97A;
            --dark: #0D1117; --dark-2: #161B22; --dark-3: #1C2330;
            --text: #E6EDF3; --muted: #8B949E; --border: #30363D;
            --danger: #F85149; --success: #3FB950; --info: #58A6FF; --warning: #D29922;
            --radius: 10px;
        }
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family:'DM Sans',sans-serif; background:var(--dark); color:var(--text); min-height:100vh; }

        /* NAVBAR */
        .navbar {
            background:var(--dark-2); border-bottom:1px solid var(--border);
            padding:0 2rem; display:flex; align-items:center;
            justify-content:space-between; height:66px;
            position:sticky; top:0; z-index:999;
        }
        .navbar-brand { display:flex; align-items:center; gap:10px; text-decoration:none; }
        .brand-icon {
            width:36px; height:36px;
            background:linear-gradient(135deg,var(--gold),var(--gold-light));
            border-radius:8px; display:flex; align-items:center;
            justify-content:center; font-size:16px; color:var(--dark);
        }
        .brand-text { line-height:1.1; }
        .brand-name { font-family:'Playfair Display',serif; font-size:1.2rem; color:var(--gold); font-weight:700; }
        .brand-sub { font-size:0.6rem; color:var(--muted); letter-spacing:2px; text-transform:uppercase; }

        .navbar-nav { display:flex; align-items:center; gap:2px; list-style:none; }
        .nav-link {
            color:var(--muted); text-decoration:none; padding:7px 12px;
            border-radius:8px; font-size:0.85rem; font-weight:500;
            transition:all .2s; display:flex; align-items:center; gap:6px;
        }
        .nav-link:hover { color:var(--text); background:var(--dark-3); }
        .nav-link.active {
            color:var(--gold); background:rgba(201,168,76,.12);
            border:1px solid rgba(201,168,76,.25);
        }
        .nav-divider { width:1px; height:20px; background:var(--border); margin:0 4px; }

        .user-pill {
            display:flex; align-items:center; gap:8px;
            padding:5px 12px 5px 5px; background:var(--dark-3);
            border:1px solid var(--border); border-radius:50px;
        }
        .user-avatar {
            width:28px; height:28px;
            background:linear-gradient(135deg,var(--gold),var(--gold-light));
            border-radius:50%; display:flex; align-items:center;
            justify-content:center; font-size:11px; font-weight:700; color:var(--dark);
        }
        .user-name { font-size:0.8rem; font-weight:600; }
        .user-role { font-size:0.6rem; color:var(--gold); text-transform:uppercase; letter-spacing:.5px; }
        .btn-logout {
            background:transparent; border:1px solid var(--border);
            color:var(--muted); padding:6px 14px; border-radius:8px;
            cursor:pointer; font-size:0.8rem; font-family:'DM Sans',sans-serif;
            transition:all .2s; text-decoration:none; display:inline-flex; align-items:center; gap:5px;
        }
        .btn-logout:hover { border-color:var(--danger); color:var(--danger); }

        /* MAIN */
        .main { padding:2rem; max-width:1300px; margin:0 auto; }

        /* PAGE HEADER */
        .page-header { margin-bottom:1.5rem; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:1rem; }
        .page-header h1 { font-family:'Playfair Display',serif; font-size:1.8rem; }
        .page-header p { color:var(--muted); font-size:0.875rem; margin-top:2px; }

        /* CARDS */
        .card { background:var(--dark-2); border:1px solid var(--border); border-radius:var(--radius); padding:1.5rem; }
        .card-header { margin-bottom:1.25rem; padding-bottom:1rem; border-bottom:1px solid var(--border); display:flex; align-items:center; justify-content:space-between; }
        .card-title { font-family:'Playfair Display',serif; font-size:1.1rem; }

        /* STATS GRID */
        .stats-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(180px,1fr)); gap:1rem; margin-bottom:1.5rem; }
        .stat-card {
            background:var(--dark-2); border:1px solid var(--border);
            border-radius:var(--radius); padding:1.25rem;
            display:flex; flex-direction:column; gap:8px;
            transition:border-color .2s;
        }
        .stat-card:hover { border-color:var(--gold); }
        .stat-icon { font-size:1.4rem; }
        .stat-value { font-size:1.8rem; font-weight:700; font-family:'Playfair Display',serif; }
        .stat-label { font-size:0.78rem; color:var(--muted); text-transform:uppercase; letter-spacing:.5px; }

        /* BUTTONS */
        .btn {
            display:inline-flex; align-items:center; gap:6px;
            padding:9px 18px; border-radius:8px; font-size:0.85rem;
            font-weight:500; cursor:pointer; border:none; text-decoration:none;
            font-family:'DM Sans',sans-serif; transition:all .2s;
        }
        .btn-gold { background:linear-gradient(135deg,var(--gold),var(--gold-light)); color:var(--dark); font-weight:600; }
        .btn-gold:hover { opacity:.9; }
        .btn-outline { background:transparent; border:1px solid var(--border); color:var(--text); }
        .btn-outline:hover { border-color:var(--gold); color:var(--gold); }
        .btn-danger { background:rgba(248,81,73,.15); border:1px solid rgba(248,81,73,.3); color:var(--danger); }
        .btn-danger:hover { background:var(--danger); color:#fff; }
        .btn-sm { padding:5px 12px; font-size:0.78rem; }
        .btn-info { background:rgba(88,166,255,.15); border:1px solid rgba(88,166,255,.3); color:var(--info); }
        .btn-info:hover { background:var(--info); color:#fff; }
        .btn-warning { background:rgba(210,153,34,.15); border:1px solid rgba(210,153,34,.3); color:var(--warning); }

        /* TABLE */
        .table-wrap { overflow-x:auto; }
        table { width:100%; border-collapse:collapse; font-size:0.875rem; }
        thead th {
            text-align:left; padding:10px 14px;
            font-size:0.72rem; text-transform:uppercase; letter-spacing:.5px;
            color:var(--muted); border-bottom:1px solid var(--border); white-space:nowrap;
        }
        tbody tr { border-bottom:1px solid rgba(48,54,61,.5); transition:background .15s; }
        tbody tr:hover { background:rgba(255,255,255,.03); }
        tbody td { padding:12px 14px; vertical-align:middle; }

        /* BADGE */
        .badge {
            padding:3px 10px; border-radius:50px; font-size:0.72rem;
            font-weight:600; letter-spacing:.3px; text-transform:capitalize;
        }
        .badge-success { background:rgba(63,185,80,.15); color:var(--success); border:1px solid rgba(63,185,80,.25); }
        .badge-danger { background:rgba(248,81,73,.15); color:var(--danger); border:1px solid rgba(248,81,73,.25); }
        .badge-warning { background:rgba(210,153,34,.15); color:var(--warning); border:1px solid rgba(210,153,34,.25); }
        .badge-info { background:rgba(88,166,255,.15); color:var(--info); border:1px solid rgba(88,166,255,.25); }
        .badge-secondary { background:rgba(139,148,158,.15); color:var(--muted); border:1px solid rgba(139,148,158,.25); }

        /* FORMS */
        .form-group { margin-bottom:1.2rem; }
        .form-label { display:block; font-size:0.82rem; font-weight:500; color:var(--muted); margin-bottom:6px; text-transform:uppercase; letter-spacing:.4px; }
        .form-control {
            width:100%; padding:10px 14px; background:var(--dark-3);
            border:1px solid var(--border); border-radius:8px;
            color:var(--text); font-size:0.875rem; font-family:'DM Sans',sans-serif;
            transition:border-color .2s;
        }
        .form-control:focus { outline:none; border-color:var(--gold); }
        .form-control option { background:var(--dark-3); }
        textarea.form-control { resize:vertical; min-height:100px; }
        .form-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(240px,1fr)); gap:1rem; }
        .form-error { color:var(--danger); font-size:0.78rem; margin-top:4px; }

        /* ALERTS */
        .alert { padding:12px 16px; border-radius:8px; margin-bottom:1rem; font-size:0.875rem; display:flex; align-items:center; gap:8px; }
        .alert-success { background:rgba(63,185,80,.1); border:1px solid rgba(63,185,80,.25); color:var(--success); }
        .alert-danger { background:rgba(248,81,73,.1); border:1px solid rgba(248,81,73,.25); color:var(--danger); }
        .alert-info { background:rgba(88,166,255,.1); border:1px solid rgba(88,166,255,.25); color:var(--info); }

        /* PAGINATION */
        .pagination { display:flex; gap:4px; justify-content:center; margin-top:1.5rem; }
        .pagination a, .pagination span {
            padding:6px 12px; border-radius:8px; font-size:0.8rem;
            background:var(--dark-2); border:1px solid var(--border); color:var(--muted); text-decoration:none;
        }
        .pagination .active span { background:var(--gold); color:var(--dark); border-color:var(--gold); font-weight:600; }
        .pagination a:hover { border-color:var(--gold); color:var(--gold); }

        /* ROLE CHIP */
        .role-admin { color:var(--gold); font-weight:600; }
        .role-staff { color:var(--info); }
        .role-guest { color:var(--muted); }

        /* DETAIL LIST */
        .detail-list dt { font-size:0.75rem; color:var(--muted); text-transform:uppercase; letter-spacing:.4px; margin-bottom:2px; }
        .detail-list dd { font-size:0.95rem; margin-bottom:1rem; }

        /* EMPTY STATE */
        .empty-state { text-align:center; padding:3rem; color:var(--muted); }
        .empty-state i { font-size:3rem; margin-bottom:1rem; opacity:.4; display:block; }
    </style>
    @stack('styles')
</head>
<body>

{{-- NAVBAR --}}
<nav class="navbar">
    <a href="{{ route('home') }}" class="navbar-brand">
        <div class="brand-icon">🏨</div>
        <div class="brand-text">
            <div class="brand-name">Grand Azure</div>
            <div class="brand-sub">Hotel Management</div>
        </div>
    </a>

    @auth
    <ul class="navbar-nav">
        <li>
            <a href="{{ route('dashboard') }}"
               class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="fas fa-chart-pie"></i> Dashboard
            </a>
        </li>
        <li>
            <a href="{{ route('rooms.index') }}"
               class="nav-link {{ request()->routeIs('rooms.*') ? 'active' : '' }}">
                <i class="fas fa-door-open"></i> Rooms
            </a>
        </li>
        <li>
            <a href="{{ route('bookings.index') }}"
               class="nav-link {{ request()->routeIs('bookings.*') ? 'active' : '' }}">
                <i class="fas fa-calendar-check"></i> Bookings
            </a>
        </li>
        <li>
            <a href="{{ route('guests.index') }}"
               class="nav-link {{ request()->routeIs('guests.*') ? 'active' : '' }}">
                <i class="fas fa-users"></i> Guests
            </a>
        </li>
        @if(auth()->user()->isAdmin() || auth()->user()->isStaff())
        <li>
            <a href="{{ route('staff.index') }}"
               class="nav-link {{ request()->routeIs('staff.*') ? 'active' : '' }}">
                <i class="fas fa-id-badge"></i> Staff
            </a>
        </li>
        @endif
        @if(auth()->user()->isAdmin())
        <div class="nav-divider"></div>
        <li>
            <a href="{{ route('admin.users') }}"
               class="nav-link {{ request()->routeIs('admin.*') ? 'active' : '' }}">
                <i class="fas fa-shield-halved"></i> Admin
            </a>
        </li>
        @endif
    </ul>

    <div style="display:flex;align-items:center;gap:10px;">
        <div class="user-pill">
            <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name,0,2)) }}</div>
            <div>
                <div class="user-name">{{ auth()->user()->name }}</div>
                <div class="user-role">{{ auth()->user()->role }}</div>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn-logout"><i class="fas fa-right-from-bracket"></i> Logout</button>
        </form>
    </div>
    @else
    <div style="display:flex;gap:8px;">
        <a href="{{ route('login') }}" class="nav-link {{ request()->routeIs('login') ? 'active' : '' }}"><i class="fas fa-right-to-bracket"></i> Login</a>
        <a href="{{ route('register') }}" class="nav-link {{ request()->routeIs('register') ? 'active' : '' }}"><i class="fas fa-user-plus"></i> Register</a>
    </div>
    @endauth
</nav>

<main class="main">
    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="alert alert-success"><i class="fas fa-circle-check"></i> {{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger"><i class="fas fa-circle-exclamation"></i> {{ session('error') }}</div>
    @endif

    @yield('content')
</main>

@stack('scripts')
</body>
</html>