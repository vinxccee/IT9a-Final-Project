@extends('layouts.app')
@section('title', 'Guest History')
@section('content')

<div class="page-header">
    <div>
        <span class="eyebrow"><i class="fas fa-id-card"></i> Guest Profile</span>
        <h1>{{ $guest->full_name }}</h1>
        <p>Complete guest history, reservations, payments, notes, and loyalty profile.</p>
    </div>
    <div style="display:flex;gap:8px;flex-wrap:wrap;">
        @if(!$guest->trashed())
            <a href="{{ route('guests.edit', $guest) }}" class="btn btn-warning"><i class="fas fa-pen"></i> Edit</a>
        @endif
        <button type="button" class="btn btn-secondary" onclick="window.print()"><i class="fas fa-print"></i> Print</button>
        @if(auth()->user()->isAdmin())
            @if($guest->trashed())
                <form method="POST" action="{{ route('guests.restore', $guest->id) }}">
                    @csrf @method('PATCH')
                    <button class="btn btn-secondary"><i class="fas fa-trash-arrow-up"></i> Restore</button>
                </form>
            @else
                <form method="POST" action="{{ route('guests.destroy', $guest) }}" onsubmit="return confirm('Archive this guest profile?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-danger"><i class="fas fa-box-archive"></i> Archive</button>
                </form>
            @endif
        @endif
        <a href="{{ route('guests.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back</a>
    </div>
</div>

@if($guest->status === 'blacklisted')
    <div class="alert alert-danger">
        <i class="fas fa-triangle-exclamation"></i>
        Blacklist warning: review this guest's notes and manager instructions before creating a reservation.
    </div>
@endif

@if($latestReservation)
    <div class="alert alert-success">
        <i class="fas fa-bolt"></i>
        Latest reservation loaded instantly:
        Room {{ $latestReservation->room?->room_number ?? 'TBA' }},
        {{ $latestReservation->check_in->format('M d, Y') }} to {{ $latestReservation->check_out->format('M d, Y') }}.
    </div>
@endif

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon"><i class="fas fa-suitcase-rolling"></i></div>
        <div><div class="stat-label">Total Stays</div><div class="stat-value">{{ $stats['total_stays'] }}</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon"><i class="fas fa-wallet"></i></div>
        <div><div class="stat-label">Total Spent</div><div class="stat-value">PHP {{ number_format($stats['total_spent'], 2) }}</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon"><i class="fas fa-bed"></i></div>
        <div><div class="stat-label">Preferred Room</div><div class="stat-value" style="font-size:1.35rem;">{{ $stats['most_booked_room_type'] }}</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon"><i class="fas fa-clock"></i></div>
        <div><div class="stat-label">Last Stay</div><div class="stat-value" style="font-size:1.35rem;">{{ $stats['last_stay_date'] }}</div></div>
    </div>
</div>

<div class="grid-2" style="align-items:start;">
    <div class="card">
        <div class="card-header">
            <div class="card-title">Profile Card</div>
            <span class="badge badge-{{ $guest->status_color }}">{{ $guest->status_label }}</span>
        </div>
        <div style="display:grid;gap:12px;">
            <div><strong>Guest ID</strong><div class="muted">G-{{ str_pad($guest->id, 4, '0', STR_PAD_LEFT) }}</div></div>
            <div><strong>Email</strong><div class="muted">{{ $guest->email }}</div></div>
            <div><strong>Contact Number</strong><div class="muted">{{ $guest->phone }}</div></div>
            <div><strong>Address</strong><div class="muted">{{ $guest->address ?: 'Not provided' }}</div></div>
            <div><strong>ID Record</strong><div class="muted">{{ $guest->id_type ?: 'ID type not set' }} {{ $guest->id_number ? '· '.$guest->id_number : '' }}</div></div>
            <div><strong>Loyalty Points</strong><div class="muted">{{ number_format($guest->loyalty_points) }} points</div></div>
            <div><strong>Guest QR Code</strong><div class="muted">Profile code: GUEST-{{ str_pad($guest->id, 6, '0', STR_PAD_LEFT) }}</div></div>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><div class="card-title">Guest Notes</div></div>
        <p style="white-space:pre-line;line-height:1.7;">{{ $guest->notes ?: 'No guest notes yet. Add service preferences, blacklist instructions, VIP amenities, allergies, or recurring requests from the edit screen.' }}</p>
        <div style="margin-top:16px;">
            <span class="badge badge-info"><i class="fas fa-wand-magic-sparkles"></i> Recommendation</span>
            <p class="muted">Use {{ $guest->preferred_room_type ?: 'the most frequently booked room type' }} when auto-filling returning guest reservations.</p>
        </div>
    </div>
</div>

<div class="card" style="margin-top:22px;">
    <div class="card-header">
        <div>
            <div class="card-title">Reservation History</div>
            <div class="muted">Previous and current reservations for this guest.</div>
        </div>
        <span class="badge badge-info">{{ $guest->bookings->count() }} reservation{{ $guest->bookings->count() === 1 ? '' : 's' }}</span>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Reservation ID</th>
                    <th>Room</th>
                    <th>Room Type</th>
                    <th>Check-In</th>
                    <th>Check-Out</th>
                    <th>Guests</th>
                    <th>Total Paid</th>
                    <th>Payment</th>
                    <th>Booked</th>
                </tr>
            </thead>
            <tbody>
            @forelse($guest->bookings as $booking)
                @php
                    $invoice = $booking->invoice;
                    $paid = (float) optional($invoice)->paid_amount;
                    $paymentStatus = optional($invoice)->status ?? 'unpaid';
                @endphp
                <tr>
                    <td>#{{ $booking->id }}</td>
                    <td>{{ $booking->room?->room_number ?? 'TBA' }}</td>
                    <td>{{ $booking->room?->roomType?->name ?? 'Unassigned' }}</td>
                    <td>{{ $booking->check_in->format('M d, Y') }}</td>
                    <td>{{ $booking->check_out->format('M d, Y') }}</td>
                    <td>{{ $booking->num_guests }}</td>
                    <td>PHP {{ number_format($paid, 2) }}</td>
                    <td><span class="badge badge-{{ $paymentStatus === 'paid' ? 'success' : ($paymentStatus === 'unpaid' ? 'danger' : 'warning') }}">{{ str_replace('_', ' ', $paymentStatus) }}</span></td>
                    <td>{{ $booking->created_at->format('M d, Y') }}</td>
                </tr>
            @empty
                <tr><td colspan="9"><div class="empty-state"><i class="fas fa-calendar-xmark"></i><p>No reservation history yet.</p></div></td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="card" style="margin-top:22px;">
    <div class="card-header">
        <div>
            <div class="card-title">Payment History</div>
            <div class="muted">Receipt numbers, balances, and payment methods linked to reservations.</div>
        </div>
        <span class="badge badge-info">{{ $payments->count() }} payment{{ $payments->count() === 1 ? '' : 's' }}</span>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Payment ID</th>
                    <th>Reservation ID</th>
                    <th>Method</th>
                    <th>Amount Paid</th>
                    <th>Remaining Balance</th>
                    <th>Payment Date</th>
                    <th>OR / Receipt No.</th>
                </tr>
            </thead>
            <tbody>
            @forelse($payments as $payment)
                <tr>
                    <td>PAY-{{ str_pad($payment->id, 4, '0', STR_PAD_LEFT) }}</td>
                    <td>#{{ $payment->invoice?->booking?->id ?? 'N/A' }}</td>
                    <td>{{ ucwords(str_replace('_', ' ', $payment->payment_method)) }}</td>
                    <td>PHP {{ number_format((float) $payment->amount, 2) }}</td>
                    <td>PHP {{ number_format((float) $payment->invoice?->balance, 2) }}</td>
                    <td>{{ $payment->payment_date->format('M d, Y h:i A') }}</td>
                    <td>{{ $payment->transaction_id ?: 'OR-' . str_pad($payment->id, 6, '0', STR_PAD_LEFT) }}</td>
                </tr>
            @empty
                <tr><td colspan="7"><div class="empty-state"><i class="fas fa-receipt"></i><p>No payment history yet.</p></div></td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="card" style="margin-top:22px;">
    <div class="card-header"><div class="card-title">Guest Activity Timeline</div></div>
    <div style="display:grid;gap:14px;">
        @forelse($timeline as $item)
            <div style="display:flex;gap:12px;align-items:flex-start;padding:12px;border:1px solid var(--line);border-radius:16px;background:rgba(255,255,255,0.62);">
                <div class="stat-icon" style="width:38px;height:38px;"><i class="fas {{ $item['icon'] }}"></i></div>
                <div>
                    <strong>{{ $item['label'] }}</strong>
                    <div class="muted">{{ $item['detail'] }}</div>
                    <div class="muted" style="font-size:.82rem;">{{ $item['date']?->format('M d, Y h:i A') }}</div>
                </div>
            </div>
        @empty
            <div class="empty-state"><i class="fas fa-timeline"></i><p>No activity recorded yet.</p></div>
        @endforelse
    </div>
</div>

@endsection

@if(request()->boolean('print'))
    @push('scripts')
        <script>
            window.addEventListener('load', () => window.print());
        </script>
    @endpush
@endif
