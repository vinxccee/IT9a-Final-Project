@extends('layouts.app')
@section('title', 'Edit Room')
@section('content')

<div class="page-header">
    <div><h1>Edit Room {{ $room->room_number }}</h1><p>Update room details</p></div>
    <a href="{{ route('rooms.index') }}" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Back</a>
</div>

<div class="card" style="max-width:700px;">
    <form method="POST" action="{{ route('rooms.update',$room) }}">
        @csrf @method('PUT')
        <div class="form-grid">
            <div class="form-group">
                <label class="form-label">Room Number</label>
                <input type="text" name="room_number" class="form-control" value="{{ old('room_number',$room->room_number) }}" required>
                @error('room_number')<div class="form-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Room Type</label>
                <select name="type" class="form-control" required>
                    @foreach(['standard','deluxe','suite','presidential'] as $t)
                    <option value="{{ $t }}" {{ (old('type',$room->type)==$t)?'selected':'' }}>{{ ucfirst($t) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Price Per Night (₱)</label>
                <input type="number" name="price_per_night" class="form-control" value="{{ old('price_per_night',$room->price_per_night) }}" step="0.01" min="0" required>
            </div>
            <div class="form-group">
                <label class="form-label">Capacity (persons)</label>
                <input type="number" name="capacity" class="form-control" value="{{ old('capacity',$room->capacity) }}" min="1" required>
            </div>
            <div class="form-group">
                <label class="form-label">Status</label>
                <select name="status" class="form-control" required>
                    @foreach(['available','occupied','maintenance'] as $s)
                    <option value="{{ $s }}" {{ (old('status',$room->status)==$s)?'selected':'' }}>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control">{{ old('description',$room->description) }}</textarea>
        </div>
        <div style="display:flex;gap:10px;margin-top:.5rem;">
            <button type="submit" class="btn btn-gold"><i class="fas fa-save"></i> Update Room</button>
            <a href="{{ route('rooms.index') }}" class="btn btn-outline">Cancel</a>
        </div>
    </form>
</div>
@endsection