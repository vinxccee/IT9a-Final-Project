@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')
<section class="dashboard-command">
    <div>
        <span class="eyebrow"><i class="fas fa-crown"></i> Grand Azure Control Room</span>
        <h1>Every stay, service queue, and invoice in one elegant view.</h1>
        <p>Monitor room readiness, upcoming arrivals, billing exposure, and guest-facing operations from a focused luxury hotel command center.</p>
        <div class="hero-actions">
            @if(auth()->user()->canAccessModule('reservation_booking'))
                <a href="{{ route('bookings.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> New Reservation</a>
                <a href="{{ route('bookings.index') }}" class="btn btn-secondary"><i class="fas fa-calendar-days"></i> Reservation Board</a>
            @endif
            @if(auth()->user()->canAccessModule('housekeeping_maintenance'))
                <a href="{{ route('housekeeping.index') }}" class="btn btn-ghost"><i class="fas fa-broom"></i> Housekeeping</a>
            @endif
        </div>
    </div>
    <div class="command-stack" aria-label="Operational highlights">
        <article>
            <span>Occupancy Pulse</span>
            <strong>{{ $stats['active_bookings'] }} active stays</strong>
            <div class="progress-line"><span style="width: 68%;"></span></div>
        </article>
        <article>
            <span>Rooms Ready</span>
            <strong>{{ $stats['available_rooms'] }} available now</strong>
            <div class="progress-line"><span style="width: 74%;"></span></div>
        </article>
        <article>
            <span>Service Attention</span>
            <strong>{{ $stats['pending_tasks'] }} pending tasks</strong>
            <div class="progress-line"><span style="width: 42%;"></span></div>
        </article>
    </div>
</section>

<div class="page-header">
    <div>
        <span class="eyebrow"><i class="fas fa-chart-column"></i> Operations Overview</span>
        <h1>Executive Dashboard</h1>
        <p>A consolidated view of rooms, reservations, revenue exposure, and service operations.</p>
    </div>

    @if(auth()->user()->canAccessModule('reservation_booking'))
        <a href="{{ route('bookings.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> New Reservation
        </a>
    @endif
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-head">
            <div class="stat-label">Total Rooms</div>
            <div class="stat-icon"><i class="fas fa-door-open"></i></div>
        </div>
        <div class="stat-value">{{ $stats['total_rooms'] }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-head">
            <div class="stat-label">Available Rooms</div>
            <div class="stat-icon"><i class="fas fa-bed"></i></div>
        </div>
        <div class="stat-value" style="color:#4e6847;">{{ $stats['available_rooms'] }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-head">
            <div class="stat-label">Maintenance Rooms</div>
            <div class="stat-icon"><i class="fas fa-screwdriver-wrench"></i></div>
        </div>
        <div class="stat-value" style="color:#8a5520;">{{ $stats['maintenance_rooms'] }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-head">
            <div class="stat-label">Active Bookings</div>
            <div class="stat-icon"><i class="fas fa-calendar-check"></i></div>
        </div>
        <div class="stat-value" style="color:#2f7c78;">{{ $stats['active_bookings'] }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-head">
            <div class="stat-label">Pending Tasks</div>
            <div class="stat-icon"><i class="fas fa-clipboard-list"></i></div>
        </div>
        <div class="stat-value">{{ $stats['pending_tasks'] }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-head">
            <div class="stat-label">Outstanding Invoices</div>
            <div class="stat-icon"><i class="fas fa-file-invoice-dollar"></i></div>
        </div>
        <div class="stat-value" style="color:#c45a46;">{{ $stats['outstanding_invoices'] }}</div>
    </div>
</div>

<div class="grid-3" style="margin-bottom:22px;">
    @foreach($modules as $module)
        @php
            $moduleIcons = [
                'Room Management' => 'fa-door-open',
                'Reservations & Booking' => 'fa-calendar-check',
                'Check-In / Check-Out' => 'fa-key',
                'Billing & Payments' => 'fa-file-invoice-dollar',
                'Housekeeping & Maintenance' => 'fa-broom',
            ];
        @endphp
        <div class="card module-card {{ $module['enabled'] ? '' : 'is-disabled' }}">
            <div>
                <div class="module-icon">
                    <i class="fas {{ $moduleIcons[$module['title']] ?? 'fa-grid-2' }}"></i>
                </div>
                <div class="card-title">{{ $module['title'] }}</div>
                <p class="muted" style="margin:10px 0 18px;">{{ $module['description'] }}</p>
            </div>
            <div>
                @if($module['enabled'])
                    <a href="{{ $module['route'] }}" class="btn btn-secondary btn-sm">Open Module</a>
                @else
                    <span class="badge badge-secondary">Restricted For This Role</span>
                @endif
            </div>
        </div>
    @endforeach
</div>

<div class="grid-2">
    @if(auth()->user()->hasRole(['receptionist', 'admin']))
        <div class="card">
            <div class="section-header">
                <h2>Front Desk Queue</h2>
                <a href="{{ route('bookings.index') }}" class="btn btn-secondary btn-sm">Open Reservations</a>
            </div>
            @if($front_desk_queue->isNotEmpty())
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>Guest</th>
                                <th>Room</th>
                                <th>Stay</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($front_desk_queue as $booking)
                                <tr>
                                    <td>{{ $booking->guest->full_name }}</td>
                                    <td>Room {{ $booking->room->room_number }} | {{ strtoupper($booking->room->type ?? 'n/a') }}</td>
                                    <td>{{ $booking->check_in->format('M d') }} to {{ $booking->check_out->format('M d') }}</td>
                                    <td>
                                        <form method="POST" action="{{ route('bookings.approve', $booking) }}">
                                            @csrf
                                            <button type="submit" class="btn btn-primary btn-sm">Approve</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="empty-state">
                    <i class="fas fa-bell-slash"></i>
                    <div>No guest reservations are waiting for front desk approval.</div>
                </div>
            @endif
        </div>
    @endif

    <div class="card">
        <div class="section-header">
            <h2>Recent Reservations</h2>
            @if(auth()->user()->canAccessModule('reservation_booking'))
                <a href="{{ route('bookings.index') }}" class="btn btn-secondary btn-sm">View All</a>
            @endif
        </div>
        @if($recent_bookings->isNotEmpty())
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Guest</th>
                            <th>Room</th>
                            <th>Stay</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recent_bookings as $booking)
                            <tr>
                                <td>{{ $booking->guest->full_name }}</td>
                                <td>Room {{ $booking->room->room_number }} | {{ strtoupper($booking->room->type ?? 'n/a') }}</td>
                                <td>{{ $booking->check_in->format('M d') }} to {{ $booking->check_out->format('M d') }}</td>
                                <td><span class="badge badge-{{ $booking->status_color }}">{{ str_replace('_', ' ', $booking->status) }}</span></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="empty-state">
                <i class="fas fa-calendar-xmark"></i>
                <div>No reservation activity yet.</div>
            </div>
        @endif
    </div>

    <div class="card">
        <div class="section-header">
            <h2>Billing Snapshot</h2>
            @if(auth()->user()->canAccessModule('billing_payments'))
                <a href="{{ route('billing.index') }}" class="btn btn-secondary btn-sm">Open Billing</a>
            @endif
        </div>
        @if($billing_snapshot->isNotEmpty())
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Invoice</th>
                            <th>Guest</th>
                            <th>Total</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($billing_snapshot as $invoice)
                            <tr>
                                <td>#{{ $invoice->display_number }}</td>
                                <td>{{ $invoice->guest_record?->full_name ?? 'Guest record unavailable' }}</td>
                                <td>PHP {{ number_format($invoice->total_amount, 2) }}</td>
                                <td><span class="badge badge-{{ $invoice->status === 'paid' ? 'success' : ($invoice->status === 'partially_paid' ? 'info' : 'warning') }}">{{ str_replace('_', ' ', $invoice->status) }}</span></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="empty-state">
                <i class="fas fa-file-invoice"></i>
                <div>Invoices will appear here as reservations are created.</div>
            </div>
        @endif
    </div>
</div>

@if(auth()->user()->canAccessModule('housekeeping_maintenance'))
    <div class="card" style="margin-top:22px;">
        <div class="section-header">
            <h2>Housekeeping Board</h2>
            <a href="{{ route('housekeeping.index') }}" class="btn btn-secondary btn-sm">Manage Tasks</a>
        </div>
        @if($housekeeping_tasks->isNotEmpty())
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Room</th>
                            <th>Task</th>
                            <th>Assigned To</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($housekeeping_tasks as $task)
                            <tr>
                                <td>{{ $task->room->room_number }}</td>
                                <td>{{ ucfirst($task->task_type) }}</td>
                                <td>{{ $task->staff->user->name ?? 'Unassigned' }}</td>
                                <td><span class="badge badge-{{ $task->status === 'completed' ? 'success' : ($task->status === 'in_progress' ? 'info' : 'warning') }}">{{ str_replace('_', ' ', $task->status) }}</span></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="empty-state">
                <i class="fas fa-broom"></i>
                <div>No housekeeping tasks are waiting right now.</div>
            </div>
        @endif
    </div>
@endif
@endsection
