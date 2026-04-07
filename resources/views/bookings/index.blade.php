@extends('layouts.app')
@section('title', 'Bookings')
@section('content')

<div class="page-header">
    <div><h1>Bookings</h1><p>All room reservations</p></div>
    @if(auth()->user()->isAdmin() || auth()->user()->isStaff())
    <a href="{{ route('bookings.create') }}" class="btn btn-gold"><i class="fas fa-plus"></i> New Booking</a>
    @endif
</div>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr><th>#</th><th>Guest</th><th>Room</th><th>Check In</th><th>Check Out</th><th>Nights</th><th>Total</th><th>Status</th><th>Actions</th></tr>
            </thead>
            <tbody>
            @forelse($bookings as $b)
            <tr>
                <td style="color:#8B949E;">{{ $b->id }}</td>
                <td>{{ $b->guest->full_name }}</td>
                <td>Room {{ $b->room->room_number }} <span style="color:#8B949E;font-size:.78rem;">{{ ucfirst($b->room->type) }}</span></td>
                <td>{{ $b->check_in->format('M d, Y') }}</td>
                <td>{{ $b->check_out->format('M d, Y') }}</td>
                <td>{{ $b->nights }}</td>
                <td style="color:#C9A84C;font-weight:600;">₱{{ number_format($b->total_amount,2) }}</td>
                <td><span class="badge badge-{{ $b->status_color }}">{{ $b->status }}</span></td>
                <td>
                    <div style="display:flex;gap:5px;">
                        <a href="{{ route('bookings.show',$b) }}" class="btn btn-info btn-sm"><i class="fas fa-eye"></i></a>
                        @if(auth()->user()->isAdmin() || auth()->user()->isStaff())
                        <a href="{{ route('bookings.edit',$b) }}" class="btn btn-warning btn-sm"><i class="fas fa-pen"></i></a>
                        @endif
                        @if(auth()->user()->isAdmin())
                        <form method="POST" action="{{ route('bookings.destroy',$b) }}" onsubmit="return confirm('Delete this booking?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                        </form>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="9"><div class="empty-state"><i class="fas fa-calendar-xmark"></i><p>No bookings yet.</p></div></td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="pagination">{{ $bookings->links() }}</div>
</div>
@endsection