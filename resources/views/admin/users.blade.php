@extends('layouts.app')
@section('title', 'User Management')
@section('content')
<div class="page-header">
    <div>
        <h1>User Management</h1>
        <p>Assign operational roles and deactivate accounts that should no longer access the system.</p>
    </div>
</div>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>User</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Change Role</th>
                    <th>Controls</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr>
                        <td>
                            <strong>{{ $user->name }}</strong>
                            @if($user->id === auth()->id())
                                <span class="badge badge-warning">You</span>
                            @endif
                        </td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->roleLabel() }}</td>
                        <td>
                            <span class="badge badge-{{ $user->is_active ? 'success' : 'danger' }}">
                                {{ $user->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>
                            @if($user->id !== auth()->id())
                                <form method="POST" action="{{ route('admin.users.role', $user) }}" style="display:flex; gap:8px;">
                                    @csrf
                                    @method('PATCH')
                                    <select name="user_role_id" class="form-control" style="min-width:180px;">
                                        @foreach($roles as $role)
                                            <option value="{{ $role->id }}" {{ $user->user_role_id === $role->id ? 'selected' : '' }}>
                                                {{ str($role->name)->replace('_', ' ')->title() }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="btn btn-secondary btn-sm">Save</button>
                                </form>
                            @else
                                <span class="muted">Own role cannot be changed here.</span>
                            @endif
                        </td>
                        <td style="display:flex; gap:8px;">
                            @if($user->id !== auth()->id())
                                <form method="POST" action="{{ route('admin.users.active', $user) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-secondary btn-sm">
                                        {{ $user->is_active ? 'Deactivate' : 'Activate' }}
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Delete this user account?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="pagination">{{ $users->links() }}</div>
</div>
@endsection
