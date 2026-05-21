<?php

namespace App\Http\Controllers;

use App\Models\HousekeepingTask;
use App\Models\Room;
use App\Models\Staff;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class HousekeepingController extends Controller
{
    public function index()
    {
        $orderColumn = Schema::hasColumn('housekeeping_tasks', 'assigned_at')
            ? 'assigned_at'
            : 'created_at';

        $query = HousekeepingTask::with(['room', 'staff.user']);

        // Filter tasks based on user role
        if (auth()->user()->isHousekeepingStaff()) {
            // Housekeeping staff see their tasks (in_progress and completed)
            $query->whereHas('staff', function($query) {
                $query->where('user_id', auth()->id());
            })->whereIn('status', ['in_progress', 'completed']);
        }

        $tasks = $query->latest($orderColumn)->paginate(10);

        // Get pending tasks that need approval (for housekeeping staff)
        $pendingTasks = collect();
        if (auth()->user()->isHousekeepingStaff()) {
            $pendingTasks = HousekeepingTask::with(['room', 'staff.user'])
                ->where('status', 'pending')
                ->whereHas('staff', function($query) {
                    $query->where('user_id', auth()->id());
                })
                ->latest($orderColumn)
                ->get();
        }

        $rooms = Room::orderBy('room_number')->get();

        // Get staff members from Staff table and convert to consistent format
        // Only include housekeeping and maintenance staff, exclude admin users
        $staffMembers = Staff::with('user')
            ->whereIn('department', ['Housekeeping', 'Maintenance'])
            ->orWhere('position', 'like', '%Housekeeping%')
            ->whereHas('user', function ($query) {
                // Exclude admin users from the dropdown
                $query->whereDoesntHave('userRole', function ($subQuery) {
                    $subQuery->where('name', 'admin');
                });
            })
            ->get()->map(function ($staff) {
                return (object) [
                    'id' => $staff->id,
                    'user' => $staff->user,
                    'position' => $staff->position,
                    'department' => $staff->department
                ];
            });

        // Also include users with housekeeping_staff role who might not have Staff records yet
        $housekeepingStaffUsers = User::where('user_role_id', function ($query) {
            $query->select('id')
                  ->from('user_roles')
                  ->where('name', 'housekeeping_staff')
                  ->limit(1);
        })->get()->map(function ($user) {
            // Create a temporary staff-like object for housekeeping staff users
            return (object) [
                'id' => 'user_' . $user->id, // Prefix to distinguish from actual staff IDs
                'user' => $user,
                'position' => 'Housekeeping Staff',
                'department' => 'Housekeeping'
            ];
        });

        // Merge the collections as arrays to avoid getKey() issues
        $allStaffMembers = array_merge($staffMembers->toArray(), $housekeepingStaffUsers->toArray());
        $staffMembers = collect($allStaffMembers);

        return view('housekeeping.index', compact('tasks', 'rooms', 'staffMembers', 'pendingTasks'));
    }

    public function approve(Request $request, HousekeepingTask $task)
    {
        // Only housekeeping staff can approve tasks assigned to them
        if (!auth()->user()->isHousekeepingStaff()) {
            abort(403);
        }

        // Check if the task is assigned to this housekeeping staff
        // Since pending tasks are already filtered in index(), this task should belong to the current user
        $isAssignedToUser = $task->staff && $task->staff->user_id === auth()->id();

        if (!$isAssignedToUser) {
            abort(403, 'You can only approve tasks assigned to you.');
        }

        // Only pending tasks can be approved
        if ($task->status !== 'pending') {
            return back()->withErrors(['task' => 'This task is not pending approval.']);
        }

        // Approve the task (change status to in_progress)
        $task->update([
            'status' => 'in_progress',
            'assigned_at' => now(),
        ]);

        return back()->with('success', 'Task approved and started successfully!');
    }

    public function store(Request $request)
    {
        abort_unless(auth()->user()->hasRole(['admin', 'receptionist']), 403);

        $data = $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'staff_id' => 'required|string', // Changed from exists:staff,id to string to handle user_ prefix
            'task_type' => 'required|in:cleaning,maintenance,restocking',
            'description' => 'nullable|string',
        ]);

        // Handle housekeeping staff users (prefixed with 'user_')
        if (str_starts_with($data['staff_id'], 'user_')) {
            $userId = str_replace('user_', '', $data['staff_id']);
            $user = User::findOrFail($userId);

            // Check if user has housekeeping_staff role
            if (!$user->isHousekeepingStaff()) {
                return back()->withErrors(['staff_id' => 'Selected user is not a housekeeping staff member.']);
            }

            // Find or create staff record for this user
            $staff = Staff::firstOrCreate(
                ['user_id' => $userId],
                [
                    'position' => 'Housekeeping Staff',
                    'department' => 'Housekeeping',
                    'phone' => $user->phone ?? 'Not provided', // Use user's phone or default
                    'status' => 'active',
                    'hired_at' => now()
                ]
            );

            $data['staff_id'] = $staff->id;
        } else {
            // Validate that regular staff_id exists
            $request->validate([
                'staff_id' => 'exists:staff,id'
            ]);
        }

        $data['status'] = 'pending';

        if (Schema::hasColumn('housekeeping_tasks', 'assigned_at')) {
            $data['assigned_at'] = now();
        }

        HousekeepingTask::create($data);

        return back()->with('success', 'Housekeeping task assigned.');
    }

    public function update(Request $request, HousekeepingTask $task)
    {
        // Allow admin, receptionist, and housekeeping staff (but only for their own tasks)
        if (auth()->user()->isHousekeepingStaff()) {
            abort_unless($task->staff_id === auth()->user()->staff->id, 403);
        } else {
            abort_unless(auth()->user()->hasRole(['admin', 'receptionist']), 403);
        }

        $data = $request->validate([
            'status' => 'required|in:pending,in_progress,completed,cancelled',
            'notes' => 'nullable|string',
        ]);

        if ($data['status'] === 'completed') {
            $data['completed_at'] = now();
            $task->room()->update(['status' => 'available']);
        }

        if ($data['status'] === 'in_progress') {
            $task->room()->update(['status' => 'under_maintenance']);
        }

        $task->update($data);

        return back()->with('success', 'Housekeeping task updated.');
    }
}
