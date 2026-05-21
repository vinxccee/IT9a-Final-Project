@extends('layouts.guest')
@section('title', 'Forgot Password')
@section('content')
<span class="panel-eyebrow"><i class="fas fa-envelope"></i> Password Recovery</span>
<h2 class="panel-title">Reset access</h2>
<p class="panel-copy">Enter your email address and we will send a password reset link for your Grand Azure Hotel account.</p>

<div class="auth-card">
    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf
        <div class="form-group">
            <label class="form-label">Email Address</label>
            <input id="email" type="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus>
        </div>
        <button type="submit" class="btn-primary">
            <i class="fas fa-paper-plane"></i> Email Reset Link
        </button>
    </form>

    <div class="auth-link">
        Remembered your password? <a href="{{ route('login') }}">Return to login</a>
    </div>
</div>
@endsection
