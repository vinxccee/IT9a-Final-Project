@extends('layouts.guest')
@section('title', 'Verify Email')
@section('content')
<span class="panel-eyebrow"><i class="fas fa-envelope-open-text"></i> Email Verification</span>
<h2 class="panel-title">Verify your email</h2>
<p class="panel-copy">Before continuing, please confirm your email address using the verification link we sent you.</p>

<div class="auth-card">
    @if (session('status') === 'verification-link-sent')
        <div class="alert alert-success">
            A new verification link has been sent to the email address you provided during registration.
        </div>
    @endif

    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button type="submit" class="btn-primary">
            <i class="fas fa-paper-plane"></i> Resend Verification Email
        </button>
    </form>

    <div class="auth-link" style="margin-top:16px;">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn-secondary" style="width:100%;">Log Out</button>
        </form>
    </div>
</div>
@endsection
