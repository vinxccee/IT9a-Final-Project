@extends('layouts.app')
@section('title', 'Add Room')
@section('content')
<div class="page-header">
    <div>
        <h1>Add Room</h1>
        <p>Register a room and map it to a reusable room type.</p>
    </div>
    <a href="{{ route('rooms.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back</a>
</div>

<div class="card" style="max-width:900px;">
    <form method="POST" action="{{ route('rooms.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="form-grid">
            <div class="form-group">
                <label class="form-label">Room Number</label>
                <input type="text" name="room_number" class="form-control" value="{{ old('room_number') }}" required>
                @error('room_number') <div class="form-error">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label class="form-label">Room Type</label>
                <select name="room_type_id" class="form-control" required>
                    <option value="">Select room type</option>
                    @foreach($roomTypes as $roomType)
                        <option value="{{ $roomType->id }}" {{ old('room_type_id') == $roomType->id ? 'selected' : '' }}>
                            {{ ucfirst($roomType->name) }} · P{{ number_format($roomType->base_price, 2) }} · {{ $roomType->capacity }} pax
                        </option>
                    @endforeach
                </select>
                @error('room_type_id') <div class="form-error">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label class="form-label">Status</label>
                <select name="status" class="form-control" required>
                    @foreach(['available', 'occupied', 'under_maintenance'] as $status)
                        <option value="{{ $status }}" {{ old('status') === $status ? 'selected' : '' }}>
                            {{ str($status)->replace('_', ' ')->title() }}
                        </option>
                    @endforeach
                </select>
                @error('status') <div class="form-error">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" placeholder="Highlight the room location, view, or use case.">{{ old('description') }}</textarea>
            @error('description') <div class="form-error">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Room Photo</label>
            <input type="file" name="image" class="form-control" accept="image/*">
            <div class="muted" style="margin-top:8px;font-size:0.9rem;">Guests will see this photo while choosing and reviewing reservations.</div>
            @error('image') <div class="form-error">{{ $message }}</div> @enderror
        </div>

        <div style="display:flex; gap:10px;">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Room</button>
            <a href="{{ route('rooms.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
