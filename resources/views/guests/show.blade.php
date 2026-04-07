@extends('layouts.app')
@section('title', 'Guest Profile')
@section('content')

<div class="page-header">
    <div><h1>{{ $guest->full_name }}</h1><p>Guest Profile</p></div>
    <div style="display:flex;gap:8px;">
        @if(auth()->user()->isAdmin() || auth()->user()->isStaff())
        <a href="{{ route('guests.edit',$guest) }}" class="btn btn-warning"><i class="fas fa-pen"></i> Edit</a>
        @endif
        @if(auth()->user()->isAdmin())
        <form method="POST" action="{{ route('guests.destroy',$guest) }}" onsubmit="return confirm('Delete this guest?')">
            @csrf @method('DELETE')
            <button class="btn btn-danger"><i class="fas fa-trash"></i> Delete</button>
        </form>
        @endif
        <a href="{{ route('guests.index') }}" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Back</a>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 2fr;gap:1.5rem;">
    <div class="card">
        <div class="card-header"><div class="card-title">Personal Info</div></div>
        <dl class="detail-list">
            <dt>Full Name</dt><dd>{{ $guest->full_name }}</dd>
            <dt>Email</dt><dd>{{ $guest->email }}</dd>
            <dt>Phone</dt><dd>{{ $guest->phone }}</dd>
            <dt>Address</dt><dd>{{ $guest->address ?: '—' }}</dd>
            <dt>ID Type</dt><dd>{{ $guest->id_type ?: '—' }}</dd>
            <dt>ID Number</dt><dd>{{ $guest->id_number ?: '—' }}</dd>
        </dl>
    </div>
    <div class="card">
        <div class="card-header"><div class="card-title">Booking History</div></div>
        @if($guest->bookings->count())
        <div class="table-wrap">
            <table>
                <thead><tr><th>Room</th><th>Check In</th><th>Check Out</th><th>Total</th><th>Status</th></tr></thead>
                <tbody>
                @foreach($guest->bookings as $b)
                <tr>
                    <td>Room {{ $b->room->room_number }}</td>
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
        <div class="empty-state"><i class="fas fa-calendar-xmark"></i><p>No bookings yet.</p></div>
        @endif
    </div>
</div>
@endsection