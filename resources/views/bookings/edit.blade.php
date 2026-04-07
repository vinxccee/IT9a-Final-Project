@extends('layouts.app')
@section('title', 'Edit Booking')
@section('content')

<div class="page-header">
    <div><h1>Edit Booking #{{ $booking->id }}</h1><p>Update reservation details</p></div>
    <a href="{{ route('bookings.index') }}" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Back</a>
</div>

<div class="card" style="max-width:800px;">
    <form method="POST" action="{{ route('bookings.update',$booking) }}">
        @csrf @method('PUT')
        <div class="form-grid">
            <div class="form-group">
                <label class="form-label">Guest</label>
                <select name="guest_id" class="form-control" required>
                    @foreach($guests as $g)
                    <option value="{{ $g->id }}" {{ (old('guest_id',$booking->guest_id)==$g->id)?'selected':'' }}>{{ $g->full_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Room</label>
                <select name="room_id" class="form-control" required>
                    @foreach($rooms as $r)
                    <option value="{{ $r->id }}" {{ (old('room_id',$booking->room_id)==$r->id)?'selected':'' }}>
                        Room {{ $r->room_number }} — {{ ucfirst($r->type) }} — ₱{{ number_format($r->price_per_night,2) }}/night
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Check In Date</label>
                <input type="date" name="check_in" class="form-control" value="{{ old('check_in',$booking->check_in->format('Y-m-d')) }}" required>
            </div>
            <div class="form-group">
                <label class="form-label">Check Out Date</label>
                <input type="date" name="check_out" class="form-control" value="{{ old('check_out',$booking->check_out->format('Y-m-d')) }}" required>
            </div>
            <div class="form-group">
                <label class="form-label">Number of Guests</label>
                <input type="number" name="num_guests" class="form-control" value="{{ old('num_guests',$booking->num_guests) }}" min="1" required>
            </div>
            <div class="form-group">
                <label class="form-label">Status</label>
                <select name="status" class="form-control" required>
                    @foreach(['pending','confirmed','checked_in','checked_out','cancelled'] as $s)
                    <option value="{{ $s }}" {{ (old('status',$booking->status)==$s)?'selected':'' }}>{{ ucfirst(str_replace('_',' ',$s)) }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="form-label">Special Requests</label>
            <textarea name="special_requests" class="form-control">{{ old('special_requests',$booking->special_requests) }}</textarea>
        </div>
        <div style="display:flex;gap:10px;">
            <button type="submit" class="btn btn-gold"><i class="fas fa-save"></i> Update Booking</button>
            <a href="{{ route('bookings.index') }}" class="btn btn-outline">Cancel</a>
        </div>
    </form>
</div>
@endsection