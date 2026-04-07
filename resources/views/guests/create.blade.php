@extends('layouts.app')
@section('title', 'Add Guest')
@section('content')

<div class="page-header">
    <div><h1>Add Guest</h1><p>Register a new hotel guest</p></div>
    <a href="{{ route('guests.index') }}" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Back</a>
</div>

<div class="card" style="max-width:700px;">
    <form method="POST" action="{{ route('guests.store') }}">
        @csrf
        <div class="form-grid">
            <div class="form-group">
                <label class="form-label">First Name</label>
                <input type="text" name="first_name" class="form-control" value="{{ old('first_name') }}" required>
                @error('first_name')<div class="form-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Last Name</label>
                <input type="text" name="last_name" class="form-control" value="{{ old('last_name') }}" required>
                @error('last_name')<div class="form-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Email Address</label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                @error('email')<div class="form-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Phone Number</label>
                <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" required>
            </div>
            <div class="form-group">
                <label class="form-label">ID Type</label>
                <select name="id_type" class="form-control">
                    <option value="">Select ID type</option>
                    @foreach(["Passport","Driver's License","SSS ID","PhilHealth ID","Voter's ID","National ID"] as $id)
                    <option value="{{ $id }}" {{ old('id_type')==$id?'selected':'' }}>{{ $id }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">ID Number</label>
                <input type="text" name="id_number" class="form-control" value="{{ old('id_number') }}">
            </div>
        </div>
        <div class="form-group">
            <label class="form-label">Address</label>
            <textarea name="address" class="form-control" placeholder="Full address...">{{ old('address') }}</textarea>
        </div>
        <div style="display:flex;gap:10px;">
            <button type="submit" class="btn btn-gold"><i class="fas fa-save"></i> Save Guest</button>
            <a href="{{ route('guests.index') }}" class="btn btn-outline">Cancel</a>
        </div>
    </form>
</div>
@endsection