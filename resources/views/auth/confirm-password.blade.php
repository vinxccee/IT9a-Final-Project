@extends('layouts.guest')
@section('title', 'Confirm Password')
@section('content')
<span class="panel-eyebrow"><i class="fas fa-lock"></i> Secure Access</span>
<h2 class="panel-title">Confirm your password</h2>
<p class="panel-copy">This area requires password confirmation before you can continue.</p>

<div class="auth-card">
    @if ($errors->any())
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf
        <div class="form-group">
            <label class="form-label">Password</label>
            <input id="password" type="password" name="password" class="form-control" required autocomplete="current-password">
        </div>
        <button type="submit" class="btn-primary">
            <i class="fas fa-shield-check"></i> Confirm
        </button>
    </form>
</div>
@endsection
