@extends('layouts.app')
@section('title', 'Guests')
@section('content')

<div class="page-header">
    <div><h1>Guests</h1><p>All registered hotel guests</p></div>
    @if(auth()->user()->isAdmin() || auth()->user()->isStaff())
    <a href="{{ route('guests.create') }}" class="btn btn-gold"><i class="fas fa-plus"></i> Add Guest</a>
    @endif
</div>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr><th>#</th><th>Name</th><th>Email</th><th>Phone</th><th>Bookings</th><th>ID Type</th><th>Actions</th></tr>
            </thead>
            <tbody>
            @forelse($guests as $g)
            <tr>
                <td style="color:#8B949E;">{{ $g->id }}</td>
                <td><strong>{{ $g->full_name }}</strong></td>
                <td>{{ $g->email }}</td>
                <td>{{ $g->phone }}</td>
                <td><span class="badge badge-info">{{ $g->bookings_count }}</span></td>
                <td>{{ $g->id_type ?: '—' }}</td>
                <td>
                    <div style="display:flex;gap:5px;">
                        <a href="{{ route('guests.show',$g) }}" class="btn btn-info btn-sm"><i class="fas fa-eye"></i></a>
                        @if(auth()->user()->isAdmin() || auth()->user()->isStaff())
                        <a href="{{ route('guests.edit',$g) }}" class="btn btn-warning btn-sm"><i class="fas fa-pen"></i></a>
                        @endif
                        @if(auth()->user()->isAdmin())
                        <form method="POST" action="{{ route('guests.destroy',$g) }}" onsubmit="return confirm('Delete this guest?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                        </form>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="7"><div class="empty-state"><i class="fas fa-users-slash"></i><p>No guests found.</p></div></td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="pagination">{{ $guests->links() }}</div>
</div>
@endsection