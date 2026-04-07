@extends('layouts.app')
@section('title', 'User Management')
@section('content')

<div class="page-header">
    <div><h1>User Management</h1><p>Admin Panel — Manage system users and roles</p></div>
</div>

<div style="background:rgba(201,168,76,.08);border:1px solid rgba(201,168,76,.2);border-radius:10px;padding:12px 16px;margin-bottom:1.5rem;font-size:.875rem;display:flex;align-items:center;gap:8px;">
    <i class="fas fa-shield-halved" style="color:#C9A84C;"></i>
    <span><strong style="color:#C9A84C;">Admin Only:</strong> This panel is restricted to administrators. You can manage roles and delete user accounts here.</span>
</div>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr><th>#</th><th>Name</th><th>Email</th><th>Role</th><th>Registered</th><th>Change Role</th><th>Actions</th></tr>
            </thead>
            <tbody>
            @foreach($users as $u)
            <tr>
                <td style="color:#8B949E;">{{ $u->id }}</td>
                <td>
                    <div style="display:flex;align-items:center;gap:8px;">
                        <div style="width:30px;height:30px;background:linear-gradient(135deg,#C9A84C,#E8C97A);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:700;color:#0D1117;">
                            {{ strtoupper(substr($u->name,0,2)) }}
                        </div>
                        {{ $u->name }}
                        @if($u->id === auth()->id())
                        <span class="badge badge-warning" style="font-size:.65rem;">You</span>
                        @endif
                    </div>
                </td>
                <td>{{ $u->email }}</td>
                <td>
                    <span class="role-{{ $u->role }}">
                        @if($u->role === 'admin') <i class="fas fa-shield-halved"></i> @endif
                        {{ ucfirst($u->role) }}
                    </span>
                </td>
                <td>{{ $u->created_at->format('M d, Y') }}</td>
                <td>
                    @if($u->id !== auth()->id())
                    <form method="POST" action="{{ route('admin.users.role',$u) }}" style="display:flex;gap:6px;align-items:center;">
                        @csrf @method('PATCH')
                        <select name="role" class="form-control" style="width:auto;padding:5px 10px;font-size:.8rem;">
                            @foreach(['admin','staff','guest'] as $r)
                            <option value="{{ $r }}" {{ $u->role===$r?'selected':'' }}>{{ ucfirst($r) }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-gold btn-sm">Set</button>
                    </form>
                    @else
                    <span style="color:#8B949E;font-size:.8rem;">Cannot change own role</span>
                    @endif
                </td>
                <td>
                    @if($u->id !== auth()->id())
                    <form method="POST" action="{{ route('admin.users.destroy',$u) }}" onsubmit="return confirm('Delete user {{ $u->name }}?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
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