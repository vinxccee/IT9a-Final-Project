@extends('layouts.app')
@section('title', 'Rooms')
@section('content')
<div class="page-header">
    <div>
        <h1>{{ auth()->user()->isGuest() ? 'Browse Rooms' : 'Room Management' }}</h1>
        <p>{{ auth()->user()->isGuest() ? 'Preview available rooms, photos, amenities, and rates before making a reservation.' : 'Availability, type assignment, and maintenance readiness in one place.' }}</p>
    </div>
    @if(auth()->user()->hasRole(['admin', 'receptionist']))
        <a href="{{ route('rooms.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Add Room</a>
    @endif
</div>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Room</th>
                    <th>Photo</th>
                    <th>Type</th>
                    <th>Rate</th>
                    <th>Capacity</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rooms as $room)
                    <tr>
                        <td><strong>{{ $room->room_number }}</strong></td>
                        <td>
                            @if($room->image_url)
                                <img src="{{ $room->image_url }}" alt="Room {{ $room->room_number }} photo" class="table-room-photo">
                            @else
                                <div class="table-room-photo table-room-placeholder"><i class="fas fa-bed"></i></div>
                            @endif
                        </td>
                        <td>{{ $room->typeLabel }}</td>
                        <td>P{{ number_format($room->price_per_night, 2) }}</td>
                        <td>{{ $room->capacity }} guest(s)</td>
                        <td><span class="badge badge-{{ $room->status_color }}">{{ str_replace('_', ' ', $room->status) }}</span></td>
                        <td style="display:flex; gap:8px;">
                            <a href="{{ route('rooms.show', $room) }}" class="btn btn-secondary btn-sm">{{ auth()->user()->isGuest() ? 'Details' : 'View' }}</a>
                            @if(auth()->user()->hasRole(['admin', 'receptionist']))
                                <a href="{{ route('rooms.edit', $room) }}" class="btn btn-secondary btn-sm">Edit</a>
                            @elseif(auth()->user()->isGuest())
                                <a href="{{ route('bookings.create', ['room_id' => $room->id]) }}" class="btn btn-primary btn-sm">Reserve</a>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7">
                            <div class="empty-state">
                                <i class="fas fa-door-closed"></i>
                                <div>No rooms have been added yet.</div>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="pagination">{{ $rooms->links() }}</div>
</div>
@endsection
