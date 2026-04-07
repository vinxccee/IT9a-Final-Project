<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Room;
use App\Models\Guest;
use App\Models\Staff;
use App\Models\User;

class DashboardController extends Controller {
    public function index() {
        $stats = [
            'total_rooms'      => Room::count(),
            'available_rooms'  => Room::where('status', 'available')->count(),
            'total_bookings'   => Booking::count(),
            'active_bookings'  => Booking::whereIn('status', ['confirmed', 'checked_in'])->count(),
            'total_guests'     => Guest::count(),
            'total_staff'      => Staff::count(),
            'revenue'          => Booking::whereIn('status', ['confirmed', 'checked_in', 'checked_out'])->sum('total_amount'),
            'pending_bookings' => Booking::where('status', 'pending')->count(),
        ];

        $recent_bookings = Booking::with(['room', 'guest'])
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact('stats', 'recent_bookings'));
    }
}