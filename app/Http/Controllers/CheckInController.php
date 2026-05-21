<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\CheckIn;
use App\Models\CheckOut;
use App\Models\HousekeepingTask;
use App\Models\Room;
use App\Models\Staff;
use Illuminate\Http\Request;

class CheckInController extends Controller
{
    public function index()
    {
        $arrivals = Booking::with(['room.roomType', 'guest', 'checkin', 'checkout'])
            ->whereIn('status', ['confirmed', 'checked_in'])
            ->orderBy('check_in')
            ->get();

        return view('operations.checkins.index', compact('arrivals'));
    }

    public function store(Request $request, Booking $booking)
    {
        abort_unless(auth()->user()->hasRole(['admin', 'receptionist']), 403);

        if ($booking->status !== 'confirmed') {
            return back()->with('error', 'Only confirmed reservations can be checked in.');
        }

        if ($booking->room->status === 'under_maintenance') {
            return back()->with('error', 'This room is under maintenance and cannot be checked in.');
        }

        $data = $request->validate([
            'notes' => 'nullable|string',
            'id_document_type' => 'nullable|string|max:100',
            'id_document_number' => 'nullable|string|max:100',
        ]);

        CheckIn::updateOrCreate(
            ['booking_id' => $booking->id],
            [
                'room_id' => $booking->room_id,
                'guest_id' => $booking->guest_id,
                'staff_id' => $this->resolveStaffProfile()->id,
                'checkin_time' => now(),
                'notes' => $data['notes'] ?? null,
                'id_document_type' => $data['id_document_type'] ?? null,
                'id_document_number' => $data['id_document_number'] ?? null,
            ]
        );

        $booking->update(['status' => 'checked_in']);
        $booking->room()->update(['status' => 'occupied']);

        return back()->with('success', 'Guest checked in successfully.');
    }

    public function checkout(Request $request, Booking $booking)
    {
        abort_unless(auth()->user()->hasRole(['admin', 'receptionist']), 403);

        if ($booking->status !== 'checked_in') {
            return back()->with('error', 'Only checked-in reservations can be checked out.');
        }

        $data = $request->validate([
            'notes' => 'nullable|string',
        ]);

        $staff = $this->resolveStaffProfile();

        CheckOut::updateOrCreate(
            ['booking_id' => $booking->id],
            [
                'room_id' => $booking->room_id,
                'guest_id' => $booking->guest_id,
                'staff_id' => $staff->id,
                'checkout_time' => now(),
                'total_amount' => $booking->invoice?->total_amount ?? $booking->total_amount,
                'notes' => $data['notes'] ?? null,
            ]
        );

        $booking->update(['status' => 'checked_out']);
        $booking->room()->update(['status' => 'under_maintenance']);

        HousekeepingTask::create([
            'room_id' => $booking->room_id,
            'staff_id' => $staff->id,
            'task_type' => 'cleaning',
            'description' => 'Post-checkout room turnover',
            'status' => 'pending',
            'assigned_at' => now(),
        ]);

        return back()->with('success', 'Guest checked out and housekeeping task created.');
    }

    private function resolveStaffProfile(): Staff
    {
        $user = auth()->user();

        return Staff::firstOrCreate(
            ['user_id' => $user->id],
            [
                'position' => $user->isAdmin() ? 'Administrator' : 'Operations Staff',
                'department' => $user->isAdmin() ? 'Administration' : 'Front Office',
                'phone' => $user->phone,
                'hired_at' => now()->toDateString(),
                'status' => 'active',
            ]
        );
    }
}
