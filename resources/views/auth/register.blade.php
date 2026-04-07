@extends('layouts.guest')
@section('title', 'Register')
@section('content')
<div class="auth-card">
    <h2>Create Account</h2>

    @if ($errors->any())
        <div style="background:rgba(248,81,73,.1);border:1px solid rgba(248,81,73,.25);color:#F85149;padding:10px 14px;border-radius:8px;font-size:.82rem;margin-bottom:1rem;">
            @foreach ($errors->all() as $error) <div>{{ $error }}</div> @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}">
        @csrf
        <div class="form-group">
            <label class="form-label">Full Name</label>
            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required autofocus>
        </div>
        <div class="form-group">
            <label class="form-label">Email Address</label>
            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
        </div>
        <div class="form-group">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <div class="form-group">
            <label class="form-label">Confirm Password</label>
            <input type="password" name="password_confirmation" class="form-control" required>
        </div>
        <button type="submit" class="btn-gold">Create Account</button>
    </form>
    <div class="auth-link">
        Already have an account? <a href="{{ route('login') }}">Sign in</a>
    </div>
</div>
@endsection