@extends('layouts.app')
@section('title', 'Room Details')
@section('content')
<div class="page-header">
    <div>
        <h1>Room {{ $room->room_number }}</h1>
        <p>{{ $room->typeLabel }} · {{ $room->capacity }} guest(s) · P{{ number_format($room->price_per_night, 2) }}/night</p>
    </div>
    <div style="display:flex; gap:10px;">
        @if(auth()->user()->hasRole(['admin', 'receptionist']))
            <a href="{{ route('rooms.edit', $room) }}" class="btn btn-primary">Edit Room</a>
        @elseif(auth()->user()->isGuest())
            <a href="{{ route('bookings.create', ['room_id' => $room->id]) }}" class="btn btn-primary"><i class="fas fa-calendar-check"></i> Reserve This Room</a>
        @endif
        <a href="{{ route('rooms.index') }}" class="btn btn-secondary">Back</a>
    </div>
</div>

<div class="grid-2">
    <div class="card">
        @if($room->image_url)
            <img src="{{ $room->image_url }}" alt="Room {{ $room->room_number }} photo" class="reservation-room-photo">
        @else
            <div class="reservation-room-photo reservation-room-placeholder">
                <i class="fas fa-bed"></i>
            </div>
        @endif
        <div class="card-title">Overview</div>
        <p class="muted" style="margin:10px 0 18px;">{{ $room->description ?: 'No additional room notes yet.' }}</p>
        <div style="display:grid; gap:10px;">
            <div><strong>Status:</strong> <span class="badge badge-{{ $room->status_color }}">{{ str_replace('_', ' ', $room->status) }}</span></div>
            <div><strong>Type:</strong> {{ $room->typeLabel }}</div>
            <div><strong>Base Rate:</strong> P{{ number_format($room->price_per_night, 2) }}</div>
            <div><strong>Capacity:</strong> {{ $room->capacity }} guest(s)</div>
        </div>
    </div>

    <div class="card">
        @if(auth()->user()->isGuest())
            <div class="card-title">Room Amenities</div>
            <p class="muted" style="margin:10px 0 18px;">Included with this {{ $room->typeLabel }} stay.</p>
            @if(! empty($room->roomType?->amenities))
                <ul class="amenities-list">
                    @foreach($room->roomType->amenities as $amenity)
                        <li>{{ $amenity }}</li>
                    @endforeach
                </ul>
            @else
                <div class="empty-state">
                    <i class="fas fa-concierge-bell"></i>
                    <div>Amenities will be updated soon.</div>
                </div>
            @endif
        @else
            <div class="card-title">Recent Reservations</div>
            @if($room->bookings->isNotEmpty())
            <div class="table-wrap" style="margin-top:16px;">
                <table>
                    <thead>
                        <tr>
                            <th>Guest</th>
                            <th>Stay</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($room->bookings->take(6) as $booking)
                            <tr>
                                <td>{{ $booking->guest->full_name }}</td>
                                <td>{{ $booking->check_in->format('M d, Y') }} to {{ $booking->check_out->format('M d, Y') }}</td>
                                <td><span class="badge badge-{{ $booking->status_color }}">{{ str_replace('_', ' ', $booking->status) }}</span></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="empty-state">
                <i class="fas fa-calendar"></i>
                <div>No reservation history for this room yet.</div>
            </div>
            @endif
        @endif
    </div>
</div>
@endsection
