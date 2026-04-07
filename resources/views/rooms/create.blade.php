@extends('layouts.app')
@section('title', 'Add Room')
@section('content')

<div class="page-header">
    <div><h1>Add Room</h1><p>Register a new hotel room</p></div>
    <a href="{{ route('rooms.index') }}" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Back</a>
</div>

<div class="card" style="max-width:700px;">
    <form method="POST" action="{{ route('rooms.store') }}">
        @csrf
        <div class="form-grid">
            <div class="form-group">
                <label class="form-label">Room Number</label>
                <input type="text" name="room_number" class="form-control" value="{{ old('room_number') }}" required placeholder="e.g. 101">
                @error('room_number')<div class="form-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Room Type</label>
                <select name="type" class="form-control" required>
                    <option value="">Select type</option>
                    @foreach(['standard','deluxe','suite','presidential'] as $t)
                    <option value="{{ $t }}" {{ old('type')==$t?'selected':'' }}>{{ ucfirst($t) }}</option>
                    @endforeach
                </select>
                @error('type')<div class="form-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Price Per Night (₱)</label>
                <input type="number" name="price_per_night" class="form-control" value="{{ old('price_per_night') }}" step="0.01" min="0" required>
                @error('price_per_night')<div class="form-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Capacity (persons)</label>
                <input type="number" name="capacity" class="form-control" value="{{ old('capacity') }}" min="1" required>
                @error('capacity')<div class="form-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Status</label>
                <select name="status" class="form-control" required>
                    @foreach(['available','occupied','maintenance'] as $s)
                    <option value="{{ $s }}" {{ old('status')==$s?'selected':'' }}>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
                @error('status')<div class="form-error">{{ $message }}</div>@enderror
            </div>
        </div>
        <div class="form-group">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" placeholder="Brief room description...">{{ old('description') }}</textarea>
        </div>
        <div style="display:flex;gap:10px;margin-top:.5rem;">
            <button type="submit" class="btn btn-gold"><i class="fas fa-save"></i> Save Room</button>
            <a href="{{ route('rooms.index') }}" class="btn btn-outline">Cancel</a>
        </div>
    </form>
</div>
@endsection