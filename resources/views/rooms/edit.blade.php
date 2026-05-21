@extends('layouts.app')
@section('title', 'Edit Room')
@section('content')
<div class="page-header">
    <div>
        <h1>Edit Room {{ $room->room_number }}</h1>
        <p>Update room setup and operational status.</p>
    </div>
    <a href="{{ route('rooms.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back</a>
</div>

<div class="card" style="max-width:900px;">
    <form method="POST" action="{{ route('rooms.update', $room) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-grid">
            <div class="form-group">
                <label class="form-label">Room Number</label>
                <input type="text" name="room_number" class="form-control" value="{{ old('room_number', $room->room_number) }}" {{ auth()->user()->hasRole(['receptionist']) ? 'readonly' : '' }} required>
                @error('room_number') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            @if(auth()->user()->hasRole(['admin', 'receptionist']))
                <div class="form-group">
                    <label class="form-label">Room Type</label>
                    <select name="room_type_id" class="form-control" required>
                        @foreach($roomTypes as $roomType)
                            <option value="{{ $roomType->id }}" {{ old('room_type_id', $room->room_type_id) == $roomType->id ? 'selected' : '' }}>
                                {{ ucfirst($roomType->name) }} · P{{ number_format($roomType->base_price, 2) }} · {{ $roomType->capacity }} pax
                            </option>
                        @endforeach
                    </select>
                    @error('room_type_id') <div class="form-error">{{ $message }}</div> @enderror
                </div>
            @endif

            <div class="form-group">
                <label class="form-label">Status</label>
                <select name="status" class="form-control" required>
                    @foreach(['available', 'occupied', 'under_maintenance'] as $status)
                        <option value="{{ $status }}" {{ old('status', $room->status) === $status ? 'selected' : '' }}>
                            {{ str($status)->replace('_', ' ')->title() }}
                        </option>
                    @endforeach
                </select>
                @error('status') <div class="form-error">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control">{{ old('description', $room->description) }}</textarea>
            @error('description') <div class="form-error">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Room Photo</label>
            @if($room->image_url)
                <img src="{{ $room->image_url }}" alt="Room {{ $room->room_number }}" class="room-form-preview">
            @endif
            <input type="file" name="image" class="form-control" accept="image/*">
            <div class="muted" style="margin-top:8px;font-size:0.9rem;">Upload a new photo to replace the current guest-facing image.</div>
            @error('image') <div class="form-error">{{ $message }}</div> @enderror
        </div>

        <div style="display:flex; gap:10px;">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update Room</button>
            <a href="{{ route('rooms.show', $room) }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
