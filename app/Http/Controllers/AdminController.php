<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserRole;
use Illuminate\Http\Request;

class AdminController extends Controller {

    public function users() {
        $users = User::with('userRole')->latest()->paginate(10);
        $roles = UserRole::orderBy('name')->get();

        return view('admin.users', compact('users', 'roles'));
    }

    public function updateRole(Request $request, User $user) {
        $request->validate([
            'user_role_id' => 'required|exists:user_roles,id',
        ]);

        $user->update(['user_role_id' => $request->integer('user_role_id')]);

        return redirect()->route('admin.users')->with('success', 'User role updated.');
    }

    public function toggleActive(User $user) {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot deactivate your own account.');
        }

        $user->update(['is_active' => ! $user->is_active]);

        return redirect()->route('admin.users')->with(
            'success',
            $user->is_active ? 'User account activated.' : 'User account deactivated.'
        );
    }

    public function destroyUser(User $user) {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete yourself.');
        }
        $user->delete();
        return redirect()->route('admin.users')->with('success', 'User deleted.');
    }
}
