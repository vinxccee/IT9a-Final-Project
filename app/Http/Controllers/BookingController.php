<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Room;
use App\Models\Guest;
use Illuminate\Http\Request;

class BookingController extends Controller {

    public function index() {
        $bookings = Booking::with(['room', 'guest', 'user'])->latest()->paginate(10);
        return view('bookings.index', compact('bookings'));
    }

    public function create() {
        $rooms  = Room::where('status', 'available')->get();
        $guests = Guest::orderBy('first_name')->get();
        return view('bookings.create', compact('rooms', 'guests'));
    }

    public function store(Request $request) {
        $data = $request->validate([
            'room_id'          => 'required|exists:rooms,id',
            'guest_id'         => 'required|exists:guests,id',
            'check_in'         => 'required|date|after_or_equal:today',
            'check_out'        => 'required|date|after:check_in',
            'num_guests'       => 'required|integer|min:1',
            'special_requests' => 'nullable|string',
            'status'           => 'required|in:pending,confirmed,checked_in,checked_out,cancelled',
        ]);

        $room   = Room::findOrFail($data['room_id']);
        $nights = \Carbon\Carbon::parse($data['check_in'])->diffInDays($data['check_out']);
        $data['total_amount'] = $room->price_per_night * $nights;
        $data['user_id']      = auth()->id();

        Booking::create($data);

        if ($data['status'] === 'checked_in') {
            $room->update(['status' => 'occupied']);
        }

        return redirect()->route('bookings.index')->with('success', 'Booking created successfully.');
    }

    public function show(Booking $booking) {
        $booking->load(['room', 'guest', 'user']);
        return view('bookings.show', compact('booking'));
    }

    public function edit(Booking $booking) {
        $rooms  = Room::all();
        $guests = Guest::orderBy('first_name')->get();
        return view('bookings.edit', compact('booking', 'rooms', 'guests'));
    }

    public function update(Request $request, Booking $booking) {
        $data = $request->validate([
            'room_id'          => 'required|exists:rooms,id',
            'guest_id'         => 'required|exists:guests,id',
            'check_in'         => 'required|date',
            'check_out'        => 'required|date|after:check_in',
            'num_guests'       => 'required|integer|min:1',
            'special_requests' => 'nullable|string',
            'status'           => 'required|in:pending,confirmed,checked_in,checked_out,cancelled',
        ]);

        $room   = Room::findOrFail($data['room_id']);
        $nights = \Carbon\Carbon::parse($data['check_in'])->diffInDays($data['check_out']);
        $data['total_amount'] = $room->price_per_night * $nights;

        // Update room status based on booking status
        if ($data['status'] === 'checked_in') {
            $room->update(['status' => 'occupied']);
        } elseif (in_array($data['status'], ['checked_out', 'cancelled'])) {
            $room->update(['status' => 'available']);
        }

        $booking->update($data);
        return redirect()->route('bookings.index')->with('success', 'Booking updated successfully.');
    }

    public function destroy(Booking $booking) {
        $booking->delete();
        return redirect()->route('bookings.index')->with('success', 'Booking deleted successfully.');
    }
}