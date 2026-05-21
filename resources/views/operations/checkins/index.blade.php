@extends('layouts.app')
@section('title', 'Check-In / Check-Out')
@section('content')
<div class="page-header">
    <div>
        <h1>Check-In / Check-Out</h1>
        <p>Manage arrivals, departures, and the room-status transition for front office operations.</p>
    </div>
</div>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Guest</th>
                    <th>Room</th>
                    <th>Stay</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($arrivals as $booking)
                    <tr>
                        <td>{{ $booking->guest->full_name }}</td>
                        <td>Room {{ $booking->room->room_number }}</td>
                        <td>{{ $booking->check_in->format('M d') }} to {{ $booking->check_out->format('M d') }}</td>
                        <td><span class="badge badge-{{ $booking->status_color }}">{{ str_replace('_', ' ', $booking->status) }}</span></td>
                        <td style="display:flex; gap:8px; flex-wrap:wrap;">
                            @if($booking->status === 'confirmed')
                                <form method="POST" action="{{ route('operations.bookings.checkin', $booking) }}">
                                    @csrf
                                    <button class="btn btn-primary btn-sm">Check In</button>
                                </form>
                            @endif
                            @if($booking->status === 'checked_in')
                                <form method="POST" action="{{ route('operations.bookings.checkout', $booking) }}">
                                    @csrf
                                    <button class="btn btn-secondary btn-sm">Check Out</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">
                            <div class="empty-state">
                                <i class="fas fa-key"></i>
                                <div>No active arrival or departure queue right now.</div>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
