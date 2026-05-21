@extends('layouts.guest')
@section('title', 'Reset Password')
@section('content')
<span class="panel-eyebrow"><i class="fas fa-key"></i> New Password</span>
<h2 class="panel-title">Set a new password</h2>
<p class="panel-copy">Create a secure new password for your Grand Azure Hotel account.</p>

<div class="auth-card">
    @if ($errors->any())
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('password.store') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div class="form-group">
            <label class="form-label">Email Address</label>
            <input id="email" type="email" name="email" class="form-control" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username">
        </div>
        <div class="form-group">
            <label class="form-label">Password</label>
            <input id="password" type="password" name="password" class="form-control" required autocomplete="new-password">
        </div>
        <div class="form-group">
            <label class="form-label">Confirm Password</label>
            <input id="password_confirmation" type="password" name="password_confirmation" class="form-control" required autocomplete="new-password">
        </div>
        <button type="submit" class="btn-primary">
            <i class="fas fa-rotate"></i> Reset Password
        </button>
    </form>
</div>
@endsection
