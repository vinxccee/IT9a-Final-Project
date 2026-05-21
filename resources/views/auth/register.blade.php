@extends('layouts.guest')
@section('title', 'Register')
@section('content')
<span class="panel-eyebrow"><i class="fas fa-user-plus"></i> Guest Registration</span>
<h2 class="panel-title">Create your account</h2>
<p class="panel-copy">Register as a guest to browse rooms, make reservations, and review billing activity.</p>

<div class="auth-card">
    @if ($errors->any())
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
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
            <label class="form-label">Phone Number</label>
            <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" required>
        </div>
        <div class="form-group">
            <label class="form-label">ID Type</label>
            <select name="id_type" class="form-control">
                <option value="">Select ID type</option>
                @foreach(["Passport","Driver's License","SSS ID","PhilHealth ID","Voter's ID","National ID"] as $id)
                <option value="{{ $id }}" {{ old('id_type')==$id?'selected':'' }}>{{ $id }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label class="form-label">ID Number</label>
            <input type="text" name="id_number" class="form-control" value="{{ old('id_number') }}">
        </div>
        <div class="form-group">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <div class="form-group">
            <label class="form-label">Confirm Password</label>
            <input type="password" name="password_confirmation" class="form-control" required>
        </div>
        <button type="submit" class="btn-primary">
            <i class="fas fa-user-check"></i> Create Account
        </button>
    </form>

    <div class="auth-link">
        Already registered? <a href="{{ route('login') }}">Sign in here</a>
    </div>

    <div class="demo-card">
        <strong><i class="fas fa-circle-info"></i> Demo Access</strong>
        <div class="demo-grid">
            <div class="demo-item"><span>Admin</span><span>admin@hotel.com / password</span></div>
            <div class="demo-item"><span>Receptionist</span><span>reception@hotel.com / password</span></div>
            <div class="demo-item"><span>Housekeeping</span><span>housekeeping@hotel.com / password</span></div>
            <div class="demo-item"><span>Guest</span><span>guest@hotel.com / password</span></div>
        </div>
    </div>
</div>
@endsection
