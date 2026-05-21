<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Http\Request;

class StaffController extends Controller {

    public function index() {
        $staff = Staff::with('user.userRole')->latest()->paginate(10);
        return view('staff.index', compact('staff'));
    }

    public function create() {
        $guestRoleId = UserRole::where('name', 'guest')->value('id');

        $users = User::with('userRole')
            ->whereDoesntHave('staff')
            ->when($guestRoleId, fn ($query) => $query->where('user_role_id', '!=', $guestRoleId))
            ->get();

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
        $staff->load('user.userRole');
        return view('staff.show', compact('staff'));
    }

    public function edit(Staff $staff) {
        $guestRoleId = UserRole::where('name', 'guest')->value('id');

        $users = User::with('userRole')
            ->when($guestRoleId, fn ($query) => $query->where('user_role_id', '!=', $guestRoleId))
            ->get();

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
