@extends('layouts.guest')
@section('title', 'Login')
@section('content')
<div class="auth-card">
    <h2>Welcome back</h2>

    @if ($errors->any())
        <div style="background:rgba(248,81,73,.1);border:1px solid rgba(248,81,73,.25);color:#F85149;padding:10px 14px;border-radius:8px;font-size:.82rem;margin-bottom:1rem;">
            @foreach ($errors->all() as $error) <div>{{ $error }}</div> @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="form-group">
            <label class="form-label">Email Address</label>
            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus>
        </div>
        <div class="form-group">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1rem;font-size:.8rem;">
            <label style="display:flex;align-items:center;gap:6px;color:#8B949E;cursor:pointer;">
                <input type="checkbox" name="remember"> Remember me
            </label>
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" style="color:#C9A84C;text-decoration:none;">Forgot password?</a>
            @endif
        </div>
        <button type="submit" class="btn-gold">Sign In</button>
    </form>
    <div class="auth-link">
        Don't have an account? <a href="{{ route('register') }}">Register here</a>
    </div>
    <div style="margin-top:1.5rem;padding-top:1rem;border-top:1px solid #30363D;font-size:.75rem;color:#8B949E;">
        <strong style="color:#C9A84C;">Demo Accounts:</strong><br>
        Admin: admin@hotel.com / password<br>
        Staff: staff@hotel.com / password<br>
        Guest: guest@hotel.com / password
    </div>
</div>
@endsection