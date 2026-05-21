@extends('layouts.guest')
@section('title', 'Login')
@section('content')
<span class="panel-eyebrow"><i class="fas fa-right-to-bracket"></i> Sign In</span>
<h2 class="panel-title">Welcome back</h2>
<p class="panel-copy">Access the Grand Azure Hotel platform using your assigned account credentials.</p>

<div class="auth-card">
    @if ($errors->any())
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
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
        <div class="form-row">
            <label class="checkbox-row">
                <input type="checkbox" name="remember">
                <span>Remember me</span>
            </label>
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="form-link">Forgot password?</a>
            @endif
        </div>
        <button type="submit" class="btn-primary">
            <i class="fas fa-arrow-right-to-bracket"></i> Sign In
        </button>
    </form>

    <div class="auth-link">
        Need an account? <a href="{{ route('register') }}">Create one here</a>
    </div>

    <div class="demo-card">
        <strong><i class="fas fa-id-card"></i> Demo Accounts</strong>
        <div class="demo-grid">
            <div class="demo-item"><span>Admin</span><span>admin@hotel.com / password</span></div>
            <div class="demo-item"><span>Receptionist</span><span>reception@hotel.com / password</span></div>
            <div class="demo-item"><span>Housekeeping</span><span>housekeeping@hotel.com / password</span></div>
            <div class="demo-item"><span>Guest</span><span>guest@hotel.com / password</span></div>
        </div>
    </div>
</div>
@endsection
