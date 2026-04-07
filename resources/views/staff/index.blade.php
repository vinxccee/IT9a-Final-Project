@extends('layouts.app')
@section('title', 'Staff')
@section('content')

<div class="page-header">
    <div><h1>Staff Directory</h1><p>Hotel personnel management</p></div>
    @if(auth()->user()->isAdmin())
    <a href="{{ route('staff.create') }}" class="btn btn-gold"><i class="fas fa-plus"></i> Add Staff</a>
    @endif
</div>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr><th>#</th><th>Name</th><th>Position</th><th>Department</th><th>Phone</th><th>Hired</th><th>Status</th><th>Actions</th></tr>
            </thead>
            <tbody>
            @forelse($staff as $s)
            <tr>
                <td style="color:#8B949E;">{{ $s->id }}</td>
                <td><strong>{{ $s->user->name }}</strong><br><span style="font-size:.75rem;color:#8B949E;">{{ $s->user->email }}</span></td>
                <td>{{ $s->position }}</td>
                <td>{{ $s->department }}</td>
                <td>{{ $s->phone }}</td>
                <td>{{ $s->hired_at->format('M d, Y') }}</td>
                <td><span class="badge {{ $s->status=='active' ? 'badge-success' : 'badge-secondary' }}">{{ $s->status }}</span></td>
                <td>
                    @if(auth()->user()->isAdmin())
                    <div style="display:flex;gap:5px;">
                        <a href="{{ route('staff.edit',$s) }}" class="btn btn-warning btn-sm"><i class="fas fa-pen"></i></a>
                        <form method="POST" action="{{ route('staff.destroy',$s) }}" onsubmit="return confirm('Remove this staff member?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                        </form>
                    </div>
                    @endif
                </td>
            </tr>
            @empty
            <tr><td colspan="8"><div class="empty-state"><i class="fas fa-id-badge"></i><p>No staff members found.</p></div></td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="pagination">{{ $staff->links() }}</div>
</div>
@endsection