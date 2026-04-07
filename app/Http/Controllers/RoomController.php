<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller {

    public function index() {
        $rooms = Room::latest()->paginate(10);
        return view('rooms.index', compact('rooms'));
    }

    public function create() {
        return view('rooms.create');
    }

    public function store(Request $request) {
        $data = $request->validate([
            'room_number'    => 'required|unique:rooms,room_number',
            'type'           => 'required|in:standard,deluxe,suite,presidential',
            'description'    => 'nullable|string',
            'price_per_night'=> 'required|numeric|min:0',
            'capacity'       => 'required|integer|min:1',
            'status'         => 'required|in:available,occupied,maintenance',
        ]);

        Room::create($data);
        return redirect()->route('rooms.index')->with('success', 'Room added successfully.');
    }

    public function show(Room $room) {
        $room->load('bookings.guest');
        return view('rooms.show', compact('room'));
    }

    public function edit(Room $room) {
        return view('rooms.edit', compact('room'));
    }

    public function update(Request $request, Room $room) {
        $data = $request->validate([
            'room_number'    => 'required|unique:rooms,room_number,' . $room->id,
            'type'           => 'required|in:standard,deluxe,suite,presidential',
            'description'    => 'nullable|string',
            'price_per_night'=> 'required|numeric|min:0',
            'capacity'       => 'required|integer|min:1',
            'status'         => 'required|in:available,occupied,maintenance',
        ]);

        $room->update($data);
        return redirect()->route('rooms.index')->with('success', 'Room updated successfully.');
    }

    public function destroy(Room $room) {
        $room->delete();
        return redirect()->route('rooms.index')->with('success', 'Room deleted successfully.');
    }
}