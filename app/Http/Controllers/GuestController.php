<?php

namespace App\Http\Controllers;

use App\Models\Guest;
use Illuminate\Http\Request;

class GuestController extends Controller {

    public function index() {
        $guests = Guest::withCount('bookings')->latest()->paginate(10);
        return view('guests.index', compact('guests'));
    }

    public function create() {
        return view('guests.create');
    }

    public function store(Request $request) {
        $data = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name'  => 'required|string|max:100',
            'email'      => 'required|email|unique:guests,email',
            'phone'      => 'required|string|max:20',
            'address'    => 'nullable|string',
            'id_type'    => 'nullable|string',
            'id_number'  => 'nullable|string',
        ]);

        Guest::create($data);
        return redirect()->route('guests.index')->with('success', 'Guest added successfully.');
    }

    public function show(Guest $guest) {
        $guest->load('bookings.room');
        return view('guests.show', compact('guest'));
    }

    public function edit(Guest $guest) {
        return view('guests.edit', compact('guest'));
    }

    public function update(Request $request, Guest $guest) {
        $data = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name'  => 'required|string|max:100',
            'email'      => 'required|email|unique:guests,email,' . $guest->id,
            'phone'      => 'required|string|max:20',
            'address'    => 'nullable|string',
            'id_type'    => 'nullable|string',
            'id_number'  => 'nullable|string',
        ]);

        $guest->update($data);
        return redirect()->route('guests.index')->with('success', 'Guest updated successfully.');
    }

    public function destroy(Guest $guest) {
        $guest->delete();
        return redirect()->route('guests.index')->with('success', 'Guest deleted successfully.');
    }
}