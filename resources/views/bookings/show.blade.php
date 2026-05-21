@extends('layouts.app')
@section('title', 'Reservation Details')
@section('content')
<div class="page-header">
    <div>
        <h1>Reservation #{{ $booking->id }}</h1>
        <p>{{ $booking->guest->full_name }} · Room {{ $booking->room->room_number }} · {{ $booking->nights }} night(s)</p>
    </div>
    <div style="display:flex; gap:10px;">
        <a href="{{ route('bookings.index') }}" class="btn btn-secondary">Back</a>
    </div>
</div>

<div class="grid-2">
    <div class="card">
        @if($booking->room->image_url)
            <img src="{{ $booking->room->image_url }}" alt="Room {{ $booking->room->room_number }} photo" class="reservation-room-photo">
        @else
            <div class="reservation-room-photo reservation-room-placeholder">
                <i class="fas fa-bed"></i>
            </div>
        @endif
        <div class="card-title">Reservation Summary</div>
        <div style="display:grid; gap:10px; margin-top:16px;">
            <div><strong>Status:</strong> <span class="badge badge-{{ $booking->status_color }}">{{ str_replace('_', ' ', $booking->status) }}</span></div>
            <div><strong>Room:</strong> Room {{ $booking->room->room_number }}</div>
            <div><strong>Stay Dates:</strong> {{ $booking->check_in->format('M d, Y') }} to {{ $booking->check_out->format('M d, Y') }}</div>
            <div><strong>Room Type:</strong> {{ $booking->room->typeLabel }}</div>
            <div><strong>Rate:</strong> P{{ number_format($booking->room->price_per_night, 2) }}/night</div>
            <div><strong>Total Amount:</strong> P{{ number_format($booking->total_amount, 2) }}</div>
            <div><strong>Special Requests:</strong> {{ $booking->special_requests ?: 'None' }}</div>
        </div>
    </div>

    <div class="card">
        <div class="card-title">Billing Record</div>
        @if($booking->invoice)
            <div style="display:grid; gap:10px; margin-top:16px;">
                <div><strong>Invoice:</strong> #INV-{{ str_pad($booking->invoice->id, 4, '0', STR_PAD_LEFT) }}</div>
                <div><strong>Status:</strong> <span class="badge badge-{{ $booking->invoice->status === 'paid' ? 'success' : ($booking->invoice->status === 'partially_paid' ? 'info' : 'warning') }}">{{ str_replace('_', ' ', $booking->invoice->status) }}</span></div>
                <div><strong>Total:</strong> P{{ number_format($booking->invoice->total_amount, 2) }}</div>
                <div><strong>Paid:</strong> P{{ number_format($booking->invoice->paid_amount, 2) }}</div>
                <div><strong>Balance:</strong> P{{ number_format($booking->invoice->balance, 2) }}</div>
            </div>
        @else
            <div class="empty-state">
                <i class="fas fa-file-invoice-dollar"></i>
                <div>Invoice details will appear once the reservation is synced.</div>
            </div>
        @endif
    </div>
</div>
@endsection
