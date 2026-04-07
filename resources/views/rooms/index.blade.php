@extends('layouts.app')
@section('title', 'Rooms')
@section('content')

<div class="page-header">
    <div><h1>Rooms</h1><p>Manage all hotel rooms</p></div>
    @if(auth()->user()->isAdmin() || auth()->user()->isStaff())
    <a href="{{ route('rooms.create') }}" class="btn btn-gold"><i class="fas fa-plus"></i> Add Room</a>
    @endif
</div>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr><th>#</th><th>Room No.</th><th>Type</th><th>Price/Night</th><th>Capacity</th><th>Status</th><th>Actions</th></tr>
            </thead>
            <tbody>
            @forelse($rooms as $room)
            <tr>
                <td style="color:#8B949E;">{{ $room->id }}</td>
                <td><strong>{{ $room->room_number }}</strong></td>
                <td>{{ ucfirst($room->type) }}</td>
                <td>₱{{ number_format($room->price_per_night,2) }}</td>
                <td>{{ $room->capacity }} pax</td>
                <td><span class="badge badge-{{ $room->status_color }}">{{ $room->status }}</span></td>
                <td>
                    <div style="display:flex;gap:6px;">
                        <a href="{{ route('rooms.show',$room) }}" class="btn btn-info btn-sm"><i class="fas fa-eye"></i></a>
                        @if(auth()->user()->isAdmin() || auth()->user()->isStaff())
                        <a href="{{ route('rooms.edit',$room) }}" class="btn btn-warning btn-sm"><i class="fas fa-pen"></i></a>
                        @endif
                        @if(auth()->user()->isAdmin())
                        <form method="POST" action="{{ route('rooms.destroy',$room) }}" onsubmit="return confirm('Delete this room?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                        </form>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="7"><div class="empty-state"><i class="fas fa-door-closed"></i><p>No rooms found.</p></div></td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="pagination">{{ $rooms->links() }}</div>
</div>
@endsection