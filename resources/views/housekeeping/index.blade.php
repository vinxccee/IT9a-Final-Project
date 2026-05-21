@extends('layouts.app')
@section('title', 'Housekeeping')
@section('content')
<div class="page-header">
    <div>
        <h1>Housekeeping & Maintenance</h1>
        <p>Assign room tasks and update readiness across cleaning, maintenance, and restocking.</p>
    </div>
</div>

@if(auth()->user()->isHousekeepingStaff() && $pendingTasks->count() > 0)
    <div class="card" style="margin-bottom:22px; border-left: 4px solid #ffc107;">
        <div class="card-title">
            <i class="fas fa-bell"></i> Pending Requests
            <span class="badge badge-warning">{{ $pendingTasks->count() }}</span>
        </div>
        <div style="margin-top:16px;">
            @foreach($pendingTasks as $task)
                <div class="notification-item" style="border: 1px solid #e9ecef; border-radius: 8px; padding: 16px; margin-bottom: 12px; background: #fff3cd;">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h5 style="margin: 0; color: #856404;">Room {{ $task->room->room_number }} - {{ ucfirst($task->task_type) }}</h5>
                            <p style="margin: 4px 0; color: #856404;">{{ $task->description ?? 'No description provided' }}</p>
                            <small style="color: #6c757d;">Requested {{ $task->created_at->diffForHumans() }}</small>
                        </div>
                        <div>
                            <form method="POST" action="{{ route('housekeeping.approve', $task) }}" style="display: inline;">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-success btn-sm">
                                    <i class="fas fa-check"></i> Accept Task
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif

@if(auth()->user()->isAdmin())
    <div class="card" style="margin-bottom:22px;">
        <div class="card-title">Assign Task</div>
        <form method="POST" action="{{ route('housekeeping.store') }}" style="margin-top:16px;">
            @csrf
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Room</label>
                    <select name="room_id" class="form-control" required>
                        @foreach($rooms as $room)
                            <option value="{{ $room->id }}">Room {{ $room->room_number }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Staff</label>
                    <select name="staff_id" class="form-control" required>
                        @foreach($staffMembers as $staffMember)
                            <option value="{{ $staffMember->id }}">{{ $staffMember->user->name ?? 'Staff' }} ({{ $staffMember->position ?? 'Unknown' }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Task Type</label>
                    <select name="task_type" class="form-control" required>
                        @foreach(['cleaning', 'maintenance', 'restocking'] as $taskType)
                            <option value="{{ $taskType }}">{{ ucfirst($taskType) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control"></textarea>
            </div>
            <button class="btn btn-primary" type="submit">Assign Task</button>
        </form>
    </div>
@elseif(auth()->user()->isReceptionist())
    <div class="card" style="margin-bottom:22px;">
        <div class="card-title">Create Housekeeping Request</div>
        <form method="POST" action="{{ route('housekeeping.store') }}" style="margin-top:16px;">
            @csrf
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Room</label>
                    <select name="room_id" class="form-control" required>
                        @foreach($rooms as $room)
                            <option value="{{ $room->id }}">Room {{ $room->room_number }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Staff</label>
                    <select name="staff_id" class="form-control" required>
                        @foreach($staffMembers as $staffMember)
                            <option value="{{ $staffMember->id }}">{{ $staffMember->user->name ?? 'Staff' }} ({{ $staffMember->position ?? 'Unknown' }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Task Type</label>
                    <select name="task_type" class="form-control" required>
                        @foreach(['cleaning', 'maintenance', 'restocking'] as $taskType)
                            <option value="{{ $taskType }}">{{ ucfirst($taskType) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" placeholder="e.g., Room cleaning after checkout, aircon repair needed, towel replacement"></textarea>
            </div>
            <button class="btn btn-primary" type="submit">Create Request</button>
        </form>
    </div>
@endif

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Room</th>
                    <th>Task</th>
                    <th>Assigned To</th>
                    <th>Assigned</th>
                    <th>Status</th>
                    @if(!auth()->user()->isHousekeepingStaff())
                    <th>Update</th>
                    @else
                    <th>Action</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @forelse($tasks as $task)
                    <tr>
                        <td>{{ $task->room->room_number }}</td>
                        <td>{{ ucfirst($task->task_type) }}<br><span class="muted">{{ $task->description }}</span></td>
                        <td>{{ $task->staff->user->name ?? 'Unknown' }}</td>
                        <td>{{ $task->assigned_at?->format('M d, Y H:i') }}</td>
                        <td><span class="badge badge-{{ $task->status === 'completed' ? 'success' : ($task->status === 'in_progress' ? 'info' : 'warning') }}">{{ str_replace('_', ' ', $task->status) }}</span></td>
                        <td>
                            @if(auth()->user()->isHousekeepingStaff())
                                <form method="POST" action="{{ route('housekeeping.update', $task) }}" style="display:grid; gap:8px; min-width:200px;">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status" class="form-control">
                                        @foreach(['pending', 'in_progress', 'completed'] as $status)
                                            <option value="{{ $status }}" {{ $task->status === $status ? 'selected' : '' }}>{{ str($status)->replace('_', ' ')->title() }}</option>
                                        @endforeach
                                    </select>
                                    <input type="text" name="notes" class="form-control" placeholder="Add notes..." value="{{ old('notes') }}">
                                    <button type="submit" class="btn btn-secondary btn-sm">Update</button>
                                </form>
                            @else
                                <form method="POST" action="{{ route('housekeeping.update', $task) }}" style="display:grid; gap:8px; min-width:220px;">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status" class="form-control">
                                        @foreach(['pending', 'in_progress', 'completed', 'cancelled'] as $status)
                                            <option value="{{ $status }}" {{ $task->status === $status ? 'selected' : '' }}>{{ str($status)->replace('_', ' ')->title() }}</option>
                                        @endforeach
                                    </select>
                                    <input type="text" name="notes" class="form-control" placeholder="Update notes">
                                    <button type="submit" class="btn btn-secondary btn-sm">Save</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">
                            <div class="empty-state">
                                <i class="fas fa-broom"></i>
                                <div>No housekeeping tasks are currently assigned.</div>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="pagination">{{ $tasks->links() }}</div>
</div>
@endsection
