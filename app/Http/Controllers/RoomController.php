<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\RoomType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RoomController extends Controller {

    public function index() {
        $rooms = Room::with('roomType')
            ->when(auth()->user()->isGuest(), fn ($query) => $query->where('status', 'available'))
            ->latest()
            ->paginate(10);

        return view('rooms.index', compact('rooms'));
    }

    public function create() {
        abort_unless(auth()->user()->hasRole(['admin', 'receptionist']), 403);

        $roomTypes = RoomType::orderBy('name')->get();

        return view('rooms.create', compact('roomTypes'));
    }

    public function store(Request $request) {
        abort_unless(auth()->user()->hasRole(['admin', 'receptionist']), 403);

        $data = $request->validate([
            'room_number'    => 'required|unique:rooms,room_number',
            'room_type_id'   => 'required|exists:room_types,id',
            'description'    => 'nullable|string',
            'status'         => 'required|in:available,occupied,under_maintenance',
            'image'          => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('rooms', 'public');
        }

        Room::create($data);
        return redirect()->route('rooms.index')->with('success', 'Room added successfully.');
    }

    public function show(Room $room) {
        if (auth()->user()->isGuest() && $room->status !== 'available') {
            abort(404);
        }

        $room->load('roomType', 'bookings.guest');
        return view('rooms.show', compact('room'));
    }

    public function edit(Room $room) {
        abort_unless(auth()->user()->hasRole(['admin', 'receptionist']), 403);

        $room->load('roomType');
        $roomTypes = RoomType::orderBy('name')->get();

        return view('rooms.edit', compact('room', 'roomTypes'));
    }

    public function update(Request $request, Room $room) {
        $user = auth()->user();

        if ($user->hasRole(['admin', 'receptionist'])) {
            $data = $request->validate([
                'room_number'    => 'required|unique:rooms,room_number,' . $room->id,
                'room_type_id'   => 'required|exists:room_types,id',
                'description'    => 'nullable|string',
                'status'         => 'required|in:available,occupied,under_maintenance',
                'image'          => 'nullable|image|max:2048',
            ]);
        } else {
            abort_unless($user->hasRole(['receptionist']), 403);

            $data = $request->validate([
                'status' => 'required|in:available,occupied,under_maintenance',
                'description' => 'nullable|string',
                'image' => 'nullable|image|max:2048',
            ]);
        }

        if ($request->hasFile('image')) {
            if ($room->image && ! str_starts_with($room->image, 'http')) {
                Storage::disk('public')->delete($room->image);
            }

            $data['image'] = $request->file('image')->store('rooms', 'public');
        }

        $room->update($data);
        return redirect()->route('rooms.index')->with('success', 'Room updated successfully.');
    }

    public function destroy(Room $room) {
        abort_unless(auth()->user()->isAdmin(), 403);

        if ($room->image && ! str_starts_with($room->image, 'http')) {
            Storage::disk('public')->delete($room->image);
        }

        $room->delete();
        return redirect()->route('rooms.index')->with('success', 'Room deleted successfully.');
    }
}
