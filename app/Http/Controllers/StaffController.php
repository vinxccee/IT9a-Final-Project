<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use App\Models\User;
use Illuminate\Http\Request;

class StaffController extends Controller {

    public function index() {
        $staff = Staff::with('user')->latest()->paginate(10);
        return view('staff.index', compact('staff'));
    }

    public function create() {
        $users = User::whereDoesntHave('staff')->where('role', '!=', 'guest')->get();
        return view('staff.create', compact('users'));
    }

    public function store(Request $request) {
        $data = $request->validate([
            'user_id'    => 'required|exists:users,id',
            'position'   => 'required|string|max:100',
            'department' => 'required|string|max:100',
            'phone'      => 'required|string|max:20',
            'hired_at'   => 'required|date',
            'status'     => 'required|in:active,inactive',
        ]);

        Staff::create($data);
        return redirect()->route('staff.index')->with('success', 'Staff member added successfully.');
    }

    public function show(Staff $staff) {
        $staff->load('user');
        return view('staff.show', compact('staff'));
    }

    public function edit(Staff $staff) {
        $users = User::where('role', '!=', 'guest')->get();
        return view('staff.edit', compact('staff', 'users'));
    }

    public function update(Request $request, Staff $staff) {
        $data = $request->validate([
            'user_id'    => 'required|exists:users,id',
            'position'   => 'required|string|max:100',
            'department' => 'required|string|max:100',
            'phone'      => 'required|string|max:20',
            'hired_at'   => 'required|date',
            'status'     => 'required|in:active,inactive',
        ]);

        $staff->update($data);
        return redirect()->route('staff.index')->with('success', 'Staff member updated successfully.');
    }

    public function destroy(Staff $staff) {
        $staff->delete();
        return redirect()->route('staff.index')->with('success', 'Staff member removed successfully.');
    }
}