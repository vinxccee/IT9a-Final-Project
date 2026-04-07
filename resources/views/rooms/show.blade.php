@extends('layouts.app')
@section('title', 'Room Details')
@section('content')

<div class="page-header">
    <div><h1>Room {{ $room->room_number }}</h1><p>{{ ucfirst($room->type) }} Room Details</p></div>
    <div style="display:flex;gap:8px;">
        @if(auth()->user()->isAdmin() || auth()->user()->isStaff())
        <a href="{{ route('rooms.edit',$room) }}" class="btn btn-warning"><i class="fas fa-pen"></i> Edit</a>
        @endif
        <a href="{{ route('rooms.index') }}" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Back</a>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 2fr;gap:1.5rem;flex-wrap:wrap;">
    <div class="card">
        <div class="card-header"><div class="card-title">Room Info</div><span class="badge badge-{{ $room->status_color }}">{{ $room->status }}</span></div>
        <dl class="detail-list">
            <dt>Room Number</dt><dd>{{ $room->room_number }}</dd>
            <dt>Type</dt><dd>{{ ucfirst($room->type) }}</dd>
            <dt>Price Per Night</dt><dd style="color:#C9A84C;font-weight:600;">₱{{ number_format($room->price_per_night,2) }}</dd>
            <dt>Capacity</dt><dd>{{ $room->capacity }} persons</dd>
            <dt>Description</dt><dd>{{ $room->description ?: '—' }}</dd>
        </dl>
    </div>
    <div class="card">
        <div class="card-header"><div class="card-title">Booking History</div></div>
        @if($room->bookings->count())
        <div class="table-wrap">
            <table>
                <thead><tr><th>Guest</th><th>Check In</th><th>Check Out</th><th>Amount</th><th>Status</th></tr></thead>
                <tbody>
                @foreach($room->bookings as $b)
                <tr>
                    <td>{{ $b->guest->full_name }}</td>
                    <td>{{ $b->check_in->format('M d, Y') }}</td>
                    <td>{{ $b->check_out->format('M d, Y') }}</td>
                    <td>₱{{ number_format($b->total_amount,2) }}</td>
                    <td><span class="badge badge-{{ $b->status_color }}">{{ $b->status }}</span></td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="empty-state"><i class="fas fa-calendar-xmark"></i><p>No bookings for this room.</p></div>
        @endif
    </div>
</div>
@endsection