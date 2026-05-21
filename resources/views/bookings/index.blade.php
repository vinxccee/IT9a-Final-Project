@extends('layouts.app')
@section('title', 'Reservations')
@section('content')
<div class="page-header">
    <div>
        <h1>Reservations</h1>
        <p>Overlap-protected bookings with front desk approval, billing readiness, and room-preparation workflow.</p>
    </div>
    <a href="{{ route('bookings.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> New Reservation</a>
</div>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Guest</th>
                    <th>Room</th>
                    <th>Photo</th>
                    <th>Stay</th>
                    <th>Nights</th>
                    <th>Total</th>
                    <th>Status</th>
                    @if(auth()->user()->hasRole(['receptionist', 'admin']))
                        <th>Front Desk</th>
                    @endif
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bookings as $booking)
                    <tr>
                        <td>{{ $booking->guest->full_name }}</td>
                        <td>Room {{ $booking->room->room_number }} | {{ strtoupper($booking->room->type ?? 'n/a') }}</td>
                        <td>
                            @if($booking->room->image_url)
                                <img src="{{ $booking->room->image_url }}" alt="Room {{ $booking->room->room_number }} photo" class="table-room-photo">
                            @else
                                <div class="table-room-photo table-room-placeholder"><i class="fas fa-bed"></i></div>
                            @endif
                        </td>
                        <td>{{ $booking->check_in->format('M d, Y') }} to {{ $booking->check_out->format('M d, Y') }}</td>
                        <td>{{ $booking->nights }}</td>
                        <td>P{{ number_format($booking->total_amount, 2) }}</td>
                        <td><span class="badge badge-{{ $booking->status_color }}">{{ str_replace('_', ' ', $booking->status) }}</span></td>
                        @if(auth()->user()->hasRole(['receptionist', 'admin']))
                            <td>
                                @if($booking->status === 'pending')
                                    <form method="POST" action="{{ route('bookings.approve', $booking) }}">
                                        @csrf
                                        <button type="submit" class="btn btn-primary btn-sm">Approve</button>
                                    </form>
                                @elseif($booking->status === 'confirmed')
                                    <span class="badge badge-success">Accepted</span>
                                @else
                                    <span class="badge badge-secondary">{{ str($booking->status)->replace('_', ' ')->title() }}</span>
                                @endif
                            </td>
                        @endif
                        <td style="display:flex; gap:8px;">
                            <a href="{{ route('bookings.show', $booking) }}" class="btn btn-secondary btn-sm">View</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ auth()->user()->hasRole(['receptionist', 'admin']) ? 9 : 8 }}">
                            <div class="empty-state">
                                <i class="fas fa-calendar-xmark"></i>
                                <div>No reservations have been created yet.</div>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="pagination">{{ $bookings->links() }}</div>
</div>
@endsection
