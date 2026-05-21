@extends('layouts.app')
@section('title', 'Guest History')
@section('content')

<div class="page-header">
    <div>
        <span class="eyebrow"><i class="fas fa-address-book"></i> Guest History</span>
        <h1>Guest History</h1>
        <p>Search returning guests, review stay history, and manage customer profiles.</p>
    </div>
    <a href="{{ route('guests.create') }}" class="btn btn-primary"><i class="fas fa-user-plus"></i> Add Guest</a>
</div>

<div class="card" style="margin-bottom:22px;">
    <form method="GET" action="{{ route('guests.index') }}" class="form-grid" style="align-items:end;">
        <div class="form-group" style="margin-bottom:0;">
            <label class="form-label">Search Guests</label>
            <input type="search" name="search" class="form-control" value="{{ $search }}" placeholder="Name, phone, email, reservation number, or guest ID">
        </div>
        @if(auth()->user()->isAdmin())
            <div class="form-group" style="margin-bottom:0;">
                <label class="form-label">Records</label>
                <select name="archived" class="form-control">
                    <option value="0">Active guests</option>
                    <option value="1" @selected(request()->boolean('archived'))>Archived guests</option>
                </select>
            </div>
        @endif
        <div style="display:flex;gap:10px;flex-wrap:wrap;">
            <button class="btn btn-primary"><i class="fas fa-magnifying-glass"></i> Search</button>
            <a href="{{ route('guests.index') }}" class="btn btn-secondary"><i class="fas fa-rotate-left"></i> Reset</a>
        </div>
    </form>
</div>

@if($returningGuest)
    <div class="alert {{ $returningGuest->status === 'blacklisted' ? 'alert-danger' : 'alert-success' }}">
        <i class="fas {{ $returningGuest->status === 'blacklisted' ? 'fa-triangle-exclamation' : 'fa-circle-check' }}"></i>
        Returning guest detected:
        <strong>{{ $returningGuest->full_name }}</strong>
        with {{ $returningGuest->bookings_count }} reservation{{ $returningGuest->bookings_count === 1 ? '' : 's' }} on record.
        <a href="{{ route('guests.show', $returningGuest) }}" style="font-weight:800;">Open profile</a>
    </div>
@endif

<div class="card">
    <div class="card-header">
        <div>
            <div class="card-title">Guest Records</div>
            <div class="muted">Paginated records with latest stay and duplicate-prevention search.</div>
        </div>
        <span class="badge badge-info">{{ $guests->total() }} record{{ $guests->total() === 1 ? '' : 's' }}</span>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Guest ID</th>
                    <th>Guest</th>
                    <th>Contact</th>
                    <th>Reservations</th>
                    <th>Latest Reservation</th>
                    <th>Preference</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            @forelse($guests as $guest)
                @php $latest = $guest->bookings->first(); @endphp
                <tr>
                    <td style="color:#8B949E;">G-{{ str_pad($guest->id, 4, '0', STR_PAD_LEFT) }}</td>
                    <td>
                        <strong>{{ $guest->full_name }}</strong>
                        <div class="muted">{{ $guest->email }}</div>
                    </td>
                    <td>{{ $guest->phone }}</td>
                    <td><span class="badge badge-info">{{ $guest->bookings_count }}</span></td>
                    <td>
                        @if($latest)
                            <strong>Room {{ $latest->room?->room_number ?? 'TBA' }}</strong>
                            <div class="muted">
                                {{ $latest->check_in->format('M d, Y') }}
                            </div>
                        @else
                            <span class="muted">No reservations yet</span>
                        @endif
                    </td>
                    <td>{{ $guest->preferred_room_type ?: ($latest?->room?->roomType?->name ?? 'Not set') }}</td>
                    <td>
                        <div style="display:flex;gap:6px;flex-wrap:wrap;">
                            <a href="{{ route('guests.show', $guest) }}" class="btn btn-info btn-sm"><i class="fas fa-eye"></i> View</a>
                            <a href="{{ route('guests.show', ['guest' => $guest, 'print' => 1]) }}" class="btn btn-secondary btn-sm"><i class="fas fa-print"></i> Print</a>
                            @if(auth()->user()->hasRole(['receptionist', 'admin']) && ! $guest->trashed())
                                <form method="POST" action="{{ route('guests.destroy', $guest) }}" onsubmit="return confirm('Remove this guest record from active Guest History?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-user-slash"></i> Remove</button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7"><div class="empty-state"><i class="fas fa-users-slash"></i><p>No guest records matched your search.</p></div></td></tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination">{{ $guests->links() }}</div>
</div>

<div class="card" style="margin-top:22px;">
    <div class="card-header">
        <div class="card-title">Access Rules</div>
    </div>
    <div class="grid-3">
        <div>
            <span class="badge badge-warning">Admin</span>
            <p class="muted">Full profile access, archive/restore, reports, loyalty, VIP, and blacklist management.</p>
        </div>
        <div>
            <span class="badge badge-info">Front Desk</span>
            <p class="muted">Search, view history, add guests, update contact details, and review reservations/payments.</p>
        </div>
        <div>
            <span class="badge badge-secondary">Housekeeping</span>
            <p class="muted">No guest history access. Housekeeping remains limited to assigned operations.</p>
        </div>
    </div>
</div>

@endsection

