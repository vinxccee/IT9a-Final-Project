@extends('layouts.app')
@section('title', 'New Booking')
@section('content')

<div class="page-header">
    <div><h1>New Booking</h1><p>Create a room reservation</p></div>
    <a href="{{ route('bookings.index') }}" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Back</a>
</div>

<div class="card" style="max-width:800px;">
    <form method="POST" action="{{ route('bookings.store') }}">
        @csrf
        <div class="form-grid">
            <div class="form-group">
                <label class="form-label">Guest</label>
                <select name="guest_id" class="form-control" required>
                    <option value="">Select guest</option>
                    @foreach($guests as $g)
                    <option value="{{ $g->id }}" {{ old('guest_id')==$g->id?'selected':'' }}>{{ $g->full_name }}</option>
                    @endforeach
                </select>
                @error('guest_id')<div class="form-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Room (Available Only)</label>
                <select name="room_id" class="form-control" required>
                    <option value="">Select room</option>
                    @foreach($rooms as $r)
                    <option value="{{ $r->id }}" {{ old('room_id')==$r->id?'selected':'' }}>
                        Room {{ $r->room_number }} — {{ ucfirst($r->type) }} — ₱{{ number_format($r->price_per_night,2) }}/night
                    </option>
                    @endforeach
                </select>
                @error('room_id')<div class="form-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Check In Date</label>
                <input type="date" name="check_in" class="form-control" value="{{ old('check_in') }}" required>
                @error('check_in')<div class="form-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Check Out Date</label>
                <input type="date" name="check_out" class="form-control" value="{{ old('check_out') }}" required>
                @error('check_out')<div class="form-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Number of Guests</label>
                <input type="number" name="num_guests" class="form-control" value="{{ old('num_guests',1) }}" min="1" required>
            </div>
            <div class="form-group">
                <label class="form-label">Status</label>
                <select name="status" class="form-control" required>
                    @foreach(['pending','confirmed','checked_in','checked_out','cancelled'] as $s)
                    <option value="{{ $s }}" {{ old('status')==$s?'selected':'' }}>{{ ucfirst(str_replace('_',' ',$s)) }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="form-label">Special Requests</label>
            <textarea name="special_requests" class="form-control" placeholder="Any special requests from the guest...">{{ old('special_requests') }}</textarea>
        </div>
        <div style="background:#1C2330;border:1px solid #30363D;border-radius:8px;padding:12px;font-size:.82rem;color:#8B949E;margin-bottom:1rem;">
            <i class="fas fa-info-circle" style="color:#58A6FF;"></i>
            Total amount will be automatically calculated based on room price × number of nights.
        </div>
        <div style="display:flex;gap:10px;">
            <button type="submit" class="btn btn-gold"><i class="fas fa-save"></i> Create Booking</button>
            <a href="{{ route('bookings.index') }}" class="btn btn-outline">Cancel</a>
        </div>
    </form>
</div>
@endsection