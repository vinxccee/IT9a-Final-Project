@extends('layouts.app')
@section('title', 'Edit Staff')
@section('content')

<div class="page-header">
    <div><h1>Edit Staff Member</h1><p>{{ $staff->user->name }}</p></div>
    <a href="{{ route('staff.index') }}" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Back</a>
</div>

<div class="card" style="max-width:700px;">
    <form method="POST" action="{{ route('staff.update',$staff) }}">
        @csrf @method('PUT')
        <div class="form-grid">
            <div class="form-group">
                <label class="form-label">User Account</label>
                <select name="user_id" class="form-control" required>
                    @foreach($users as $u)
                    <option value="{{ $u->id }}" {{ (old('user_id',$staff->user_id)==$u->id)?'selected':'' }}>{{ $u->name }} ({{ $u->role }})</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Position</label>
                <input type="text" name="position" class="form-control" value="{{ old('position',$staff->position) }}" required>
            </div>
            <div class="form-group">
                <label class="form-label">Department</label>
                <input type="text" name="department" class="form-control" value="{{ old('department',$staff->department) }}" required>
            </div>
            <div class="form-group">
                <label class="form-label">Phone Number</label>
                <input type="text" name="phone" class="form-control" value="{{ old('phone',$staff->phone) }}" required>
            </div>
            <div class="form-group">
                <label class="form-label">Date Hired</label>
                <input type="date" name="hired_at" class="form-control" value="{{ old('hired_at',$staff->hired_at->format('Y-m-d')) }}" required>
            </div>
            <div class="form-group">
                <label class="form-label">Status</label>
                <select name="status" class="form-control" required>
                    <option value="active" {{ (old('status',$staff->status)=='active')?'selected':'' }}>Active</option>
                    <option value="inactive" {{ (old('status',$staff->status)=='inactive')?'selected':'' }}>Inactive</option>
                </select>
            </div>
        </div>
        <div style="display:flex;gap:10px;margin-top:.5rem;">
            <button type="submit" class="btn btn-gold"><i class="fas fa-save"></i> Update Staff</button>
            <a href="{{ route('staff.index') }}" class="btn btn-outline">Cancel</a>
        </div>
    </form>
</div>
@endsection