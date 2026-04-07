@extends('layouts.app')
@section('title', 'Booking Details')
@section('content')

<div class="page-header">
    <div><h1>Booking #{{ $booking->id }}</h1><p>Reservation details</p></div>
    <div style="display:flex;gap:8px;">
        @if(auth()->user()->isAdmin() || auth()->user()->isStaff())
        <a href="{{ route('bookings.edit',$booking) }}" class="btn btn-warning"><i class="fas fa-pen"></i> Edit</a>
        @endif
        @if(auth()->user()->isAdmin())
        <form method="POST" action="{{ route('bookings.destroy',$booking) }}" onsubmit="return confirm('Delete this booking?')">
            @csrf @method('DELETE')
            <button class="btn btn-danger"><i class="fas fa-trash"></i> Delete</button>
        </form>
        @endif
        <a href="{{ route('bookings.index') }}" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Back</a>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;">
    <div class="card">
        <div class="card-header"><div class="card-title">Booking Info</div><span class="badge badge-{{ $booking->status_color }}">{{ $booking->status }}</span></div>
        <dl class="detail-list">
            <dt>Booking ID</dt><dd>#{{ $booking->id }}</dd>
            <dt>Check In</dt><dd>{{ $booking->check_in->format('F d, Y') }}</dd>
            <dt>Check Out</dt><dd>{{ $booking->check_out->format('F d, Y') }}</dd>
            <dt>Nights</dt><dd>{{ $booking->nights }} night(s)</dd>
            <dt>Number of Guests</dt><dd>{{ $booking->num_guests }} person(s)</dd>
            <dt>Total Amount</dt><dd style="color:#C9A84C;font-size:1.2rem;font-weight:700;">₱{{ number_format($booking->total_amount,2) }}</dd>
            <dt>Special Requests</dt><dd>{{ $booking->special_requests ?: 'None' }}</dd>
            <dt>Booked By</dt><dd>{{ $booking->user->name }}</dd>
            <dt>Created</dt><dd>{{ $booking->created_at->format('M d, Y h:i A') }}</dd>
        </dl>
    </div>
    <div style="display:flex;flex-direction:column;gap:1.5rem;">
        <div class="card">
            <div class="card-header"><div class="card-title">Guest Details</div></div>
            <dl class="detail-list">
                <dt>Name</dt><dd>{{ $booking->guest->full_name }}</dd>
                <dt>Email</dt><dd>{{ $booking->guest->email }}</dd>
                <dt>Phone</dt><dd>{{ $booking->guest->phone }}</dd>
                <dt>Address</dt><dd>{{ $booking->guest->address ?: '—' }}</dd>
            </dl>
        </div>
        <div class="card">
            <div class="card-header"><div class="card-title">Room Details</div></div>
            <dl class="detail-list">
                <dt>Room Number</dt><dd>{{ $booking->room->room_number }}</dd>
                <dt>Type</dt><dd>{{ ucfirst($booking->room->type) }}</dd>
                <dt>Price Per Night</dt><dd>₱{{ number_format($booking->room->price_per_night,2) }}</dd>
                <dt>Capacity</dt><dd>{{ $booking->room->capacity }} persons</dd>
            </dl>
        </div>
    </div>
</div>
@endsection