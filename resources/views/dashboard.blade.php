@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')

<div class="page-header">
    <div>
        <h1>Dashboard</h1>
        <p>Welcome back, {{ auth()->user()->name }} — {{ now()->format('F d, Y') }}</p>
    </div>
    @if(auth()->user()->isAdmin() || auth()->user()->isStaff())
    <a href="{{ route('bookings.create') }}" class="btn btn-gold">
        <i class="fas fa-plus"></i> New Booking
    </a>
    @endif
</div>

{{-- Stats --}}
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon">🛏️</div>
        <div class="stat-value" style="color:#C9A84C;">{{ $stats['total_rooms'] }}</div>
        <div class="stat-label">Total Rooms</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">✅</div>
        <div class="stat-value" style="color:#3FB950;">{{ $stats['available_rooms'] }}</div>
        <div class="stat-label">Available</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">📋</div>
        <div class="stat-value" style="color:#58A6FF;">{{ $stats['total_bookings'] }}</div>
        <div class="stat-label">Total Bookings</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">⚡</div>
        <div class="stat-value" style="color:#D29922;">{{ $stats['active_bookings'] }}</div>
        <div class="stat-label">Active Bookings</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">👥</div>
        <div class="stat-value">{{ $stats['total_guests'] }}</div>
        <div class="stat-label">Total Guests</div>
    </div>
    @if(auth()->user()->isAdmin())
    <div class="stat-card">
        <div class="stat-icon">💰</div>
        <div class="stat-value" style="color:#C9A84C;font-size:1.3rem;">₱{{ number_format($stats['revenue'],2) }}</div>
        <div class="stat-label">Total Revenue</div>
    </div>
    @endif
</div>

{{-- Recent Bookings --}}
<div class="card">
    <div class="card-header">
        <div class="card-title">Recent Bookings</div>
        <a href="{{ route('bookings.index') }}" class="btn btn-outline btn-sm">View All</a>
    </div>
    @if($recent_bookings->count())
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>#</th><th>Guest</th><th>Room</th>
                    <th>Check In</th><th>Check Out</th>
                    <th>Amount</th><th>Status</th>
                </tr>
            </thead>
            <tbody>
            @foreach($recent_bookings as $b)
            <tr>
                <td style="color:#8B949E;">{{ $b->id }}</td>
                <td>{{ $b->guest->full_name }}</td>
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
@endsection