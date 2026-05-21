<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\HousekeepingTask;
use App\Models\Invoice;
use App\Models\Room;
use App\Models\Guest;
use App\Models\Staff;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller {
    public function index() {
        $user = auth()->user();

        $stats = [
            'total_rooms'      => Room::count(),
            'available_rooms'  => Room::where('status', 'available')->count(),
            'maintenance_rooms'=> Room::where('status', 'under_maintenance')->count(),
            'total_bookings'   => Booking::count(),
            'active_bookings'  => Booking::whereIn('status', ['confirmed', 'checked_in'])->count(),
            'total_guests'     => Guest::count(),
            'total_staff'      => Staff::count(),
            'revenue'          => Invoice::sum('paid_amount'),
            'pending_bookings' => Booking::where('status', 'pending')->count(),
            'pending_tasks'    => HousekeepingTask::whereIn('status', ['pending', 'in_progress'])->count(),
            'outstanding_invoices' => Invoice::whereIn('status', ['unpaid', 'partially_paid', 'overdue'])->count(),
        ];

        $recentBookingsQuery = Booking::with(['room.roomType', 'guest', 'invoice'])
            ->latest()
            ->take(6);

        if ($user->isGuest()) {
            $recentBookingsQuery->where('user_id', $user->id);
        }

        $recent_bookings = $recentBookingsQuery->get();

        $front_desk_queue = collect();

        if ($user->hasRole(['receptionist', 'admin'])) {
            $front_desk_queue = Booking::with(['room.roomType', 'guest', 'user'])
                ->where('status', 'pending')
                ->latest()
                ->take(5)
                ->get();
        }

        $billing_snapshot = Invoice::with(['booking.room', 'guest'])
            ->latest()
            ->take(5)
            ->get();

        $housekeepingOrderColumn = Schema::hasColumn('housekeeping_tasks', 'assigned_at')
            ? 'assigned_at'
            : 'created_at';

        $housekeepingQuery = HousekeepingTask::with(['room', 'staff.user']);

        // Filter tasks based on user role
        if ($user->isHousekeepingStaff()) {
            $housekeepingQuery->where('staff_id', $user->staff->id ?? null);
        }

        $housekeeping_tasks = $housekeepingQuery->latest($housekeepingOrderColumn)->take(5)->get();

        $modules = [
            [
                'title' => 'Room Management',
                'description' => 'Track availability, room types, pricing, and maintenance readiness.',
                'route' => route('rooms.index'),
                'enabled' => $user->canAccessModule('room_management'),
            ],
            [
                'title' => 'Reservations & Booking',
                'description' => 'Handle guest reservations, changes, and cancellations with overlap checks.',
                'route' => route('bookings.index'),
                'enabled' => $user->canAccessModule('reservation_booking'),
            ],
            [
                'title' => 'Check-In / Check-Out',
                'description' => 'Move confirmed arrivals into occupancy and trigger post-stay room turnover.',
                'route' => route('operations.checkins.index'),
                'enabled' => $user->canAccessModule('checkin_checkout'),
            ],
            [
                'title' => 'Billing & Payments',
                'description' => 'Generate invoices, monitor balances, and record split or partial payments.',
                'route' => route('billing.index'),
                'enabled' => $user->canAccessModule('billing_payments'),
            ],
            [
                'title' => 'Housekeeping & Maintenance',
                'description' => 'Assign room tasks and keep room readiness visible to operations.',
                'route' => route('housekeeping.index'),
                'enabled' => $user->canAccessModule('housekeeping_maintenance'),
            ],
        ];

        $notifications = collect();

        // Pending bookings for receptionists
        if ($user->hasRole(['receptionist', 'admin']) && $stats['pending_bookings'] > 0) {
            $notifications->push([
                'type' => 'warning',
                'icon' => 'fa-clock',
                'title' => 'Pending Bookings',
                'message' => "You have {$stats['pending_bookings']} booking(s) awaiting confirmation.",
                'action' => [
                    'url' => route('bookings.index'),
                    'text' => 'Review',
                    'class' => 'btn-warning'
                ]
            ]);
        }

        // Overdue invoices for billing staff
        if ($user->hasRole(['receptionist', 'admin']) && $stats['outstanding_invoices'] > 0) {
            $notifications->push([
                'type' => 'danger',
                'icon' => 'fa-exclamation-triangle',
                'title' => 'Outstanding Invoices',
                'message' => "There are {$stats['outstanding_invoices']} invoice(s) that need attention.",
                'action' => [
                    'url' => route('billing.index'),
                    'text' => 'View',
                    'class' => 'btn-danger'
                ]
            ]);
        }

        // Pending housekeeping tasks
        if ($user->hasRole(['receptionist', 'admin']) && $stats['pending_tasks'] > 0) {
            $notifications->push([
                'type' => 'info',
                'icon' => 'fa-broom',
                'title' => 'Housekeeping Tasks',
                'message' => "You have {$stats['pending_tasks']} task(s) to complete.",
                'action' => [
                    'url' => route('housekeeping.index'),
                    'text' => 'View Tasks',
                    'class' => 'btn-info'
                ]
            ]);
        }

        return view('dashboard', compact('stats', 'recent_bookings', 'billing_snapshot', 'housekeeping_tasks', 'modules', 'front_desk_queue', 'notifications'));
    }
}
