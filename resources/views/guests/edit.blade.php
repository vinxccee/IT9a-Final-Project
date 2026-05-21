@extends('layouts.app')
@section('title', 'Edit Guest')
@section('content')

<div class="page-header">
    <div>
        <span class="eyebrow"><i class="fas fa-user-pen"></i> Update Profile</span>
        <h1>Edit Guest</h1>
        <p>{{ $guest->full_name }}</p>
    </div>
    <a href="{{ route('guests.show', $guest) }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back</a>
</div>

<div class="card" style="max-width:900px;">
    <form method="POST" action="{{ route('guests.update', $guest) }}">
        @csrf @method('PUT')

        <div class="card-header">
            <div>
                <div class="card-title">Editable Guest Details</div>
                @unless(auth()->user()->isAdmin())
                    <div class="muted">Front desk can update contact details, preferences, ID details, address, and notes. Status and loyalty controls are admin-only.</div>
                @endunless
            </div>
            <span class="badge badge-{{ $guest->status_color }}">{{ $guest->status_label }}</span>
        </div>

        <div class="form-grid">
            <div class="form-group">
                <label class="form-label">First Name</label>
                <input type="text" name="first_name" class="form-control" value="{{ old('first_name', $guest->first_name) }}" required>
                @error('first_name')<div class="form-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Last Name</label>
                <input type="text" name="last_name" class="form-control" value="{{ old('last_name', $guest->last_name) }}" required>
                @error('last_name')<div class="form-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Email Address</label>
                <input type="email" name="email" class="form-control" value="{{ old('email', $guest->email) }}" required>
                @error('email')<div class="form-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Contact Number</label>
                <input type="text" name="phone" class="form-control" value="{{ old('phone', $guest->phone) }}" required>
                @error('phone')<div class="form-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">ID Type</label>
                <select name="id_type" class="form-control">
                    <option value="">Select ID type</option>
                    @foreach(["Passport","Driver's License","SSS ID","PhilHealth ID","Voter's ID","National ID"] as $id)
                        <option value="{{ $id }}" @selected(old('id_type', $guest->id_type) === $id)>{{ $id }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">ID Number</label>
                <input type="text" name="id_number" class="form-control" value="{{ old('id_number', $guest->id_number) }}">
            </div>
            <div class="form-group">
                <label class="form-label">Preferred Room Type</label>
                <input type="text" name="preferred_room_type" class="form-control" value="{{ old('preferred_room_type', $guest->preferred_room_type) }}">
            </div>
            @if(auth()->user()->isAdmin())
                <div class="form-group">
                    <label class="form-label">Guest Status</label>
                    <select name="status" class="form-control">
                        @foreach(['regular' => 'Regular', 'vip' => 'VIP', 'blacklisted' => 'Blacklisted'] as $value => $label)
                            <option value="{{ $value }}" @selected(old('status', $guest->status) === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Loyalty Points</label>
                    <input type="number" min="0" name="loyalty_points" class="form-control" value="{{ old('loyalty_points', $guest->loyalty_points) }}">
                </div>
            @endif
        </div>

        <div class="form-group">
            <label class="form-label">Address</label>
            <textarea name="address" class="form-control">{{ old('address', $guest->address) }}</textarea>
        </div>

        <div class="form-group">
            <label class="form-label">Guest Notes</label>
            <textarea name="notes" class="form-control">{{ old('notes', $guest->notes) }}</textarea>
        </div>

        <div style="display:flex;gap:10px;flex-wrap:wrap;">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update Guest</button>
            <a href="{{ route('guests.show', $guest) }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

@endsection
