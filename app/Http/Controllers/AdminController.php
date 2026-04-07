<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller {

    public function users() {
        $users = User::latest()->paginate(10);
        return view('admin.users', compact('users'));
    }

    public function updateRole(Request $request, User $user) {
        $request->validate([
            'role' => 'required|in:admin,staff,guest'
        ]);

        $user->update(['role' => $request->role]);
        return redirect()->route('admin.users')->with('success', 'User role updated.');
    }

    public function destroyUser(User $user) {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete yourself.');
        }
        $user->delete();
        return redirect()->route('admin.users')->with('success', 'User deleted.');
    }
}