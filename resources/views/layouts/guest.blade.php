<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') — Grand Azure Hotel</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root {
            --gold:#C9A84C; --gold-light:#E8C97A;
            --dark:#0D1117; --dark-2:#161B22; --dark-3:#1C2330;
            --text:#E6EDF3; --muted:#8B949E; --border:#30363D;
            --danger:#F85149; --success:#3FB950;
        }
        * { margin:0; padding:0; box-sizing:border-box; }
        body {
            font-family:'DM Sans',sans-serif; background:var(--dark);
            color:var(--text); min-height:100vh;
            display:flex; align-items:center; justify-content:center;
            background-image:
                radial-gradient(ellipse at 20% 50%, rgba(201,168,76,.06) 0%, transparent 60%),
                radial-gradient(ellipse at 80% 20%, rgba(201,168,76,.04) 0%, transparent 50%);
        }
        .auth-container { width:100%; max-width:440px; padding:1.5rem; }
        .auth-brand { text-align:center; margin-bottom:2rem; }
        .auth-brand .icon {
            width:56px; height:56px; margin:0 auto 12px;
            background:linear-gradient(135deg,var(--gold),var(--gold-light));
            border-radius:14px; display:flex; align-items:center;
            justify-content:center; font-size:26px;
        }
        .auth-brand h1 { font-family:'Playfair Display',serif; font-size:1.8rem; color:var(--gold); }
        .auth-brand p { color:var(--muted); font-size:0.82rem; letter-spacing:1.5px; text-transform:uppercase; margin-top:4px; }
        .auth-card {
            background:var(--dark-2); border:1px solid var(--border);
            border-radius:14px; padding:2rem;
        }
        .auth-card h2 { font-family:'Playfair Display',serif; font-size:1.4rem; margin-bottom:1.5rem; }
        .form-group { margin-bottom:1.1rem; }
        .form-label { display:block; font-size:0.78rem; font-weight:500; color:var(--muted); margin-bottom:6px; text-transform:uppercase; letter-spacing:.4px; }
        .form-control {
            width:100%; padding:10px 14px; background:var(--dark-3);
            border:1px solid var(--border); border-radius:8px;
            color:var(--text); font-size:0.875rem; font-family:'DM Sans',sans-serif;
            transition:border-color .2s;
        }
        .form-control:focus { outline:none; border-color:var(--gold); }
        .form-error { color:var(--danger); font-size:0.75rem; margin-top:4px; }
        .btn-gold {
            width:100%; padding:11px; background:linear-gradient(135deg,var(--gold),var(--gold-light));
            color:var(--dark); font-weight:700; border:none; border-radius:8px;
            font-size:0.9rem; cursor:pointer; font-family:'DM Sans',sans-serif;
            margin-top:.5rem; transition:opacity .2s;
        }
        .btn-gold:hover { opacity:.9; }
        .auth-link { text-align:center; margin-top:1.25rem; font-size:0.82rem; color:var(--muted); }
        .auth-link a { color:var(--gold); text-decoration:none; }
        .auth-link a:hover { text-decoration:underline; }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="auth-brand">
            <div class="icon">🏨</div>
            <h1>Grand Azure</h1>
            <p>Hotel Management System</p>
        </div>
        @yield('content')
    </div>
</body>
</html>