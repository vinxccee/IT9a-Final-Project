@extends('layouts.app')
@section('title', 'New Reservation')
@section('content')
<div class="page-header">
    <div>
        <h1>Create Reservation</h1>
        <p>Guests can request a stay while staff can confirm operational details.</p>
    </div>
    <a href="{{ route('bookings.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back</a>
</div>

@include('bookings.partials.room-photo-gallery', ['rooms' => $rooms, 'selectedRoomId' => old('room_id', request('room_id'))])

<div class="card" style="max-width:980px;">
    <form method="POST" action="{{ route('bookings.store') }}">
        @csrf
        <div class="form-grid">
            <div class="form-group">
                <label class="form-label">Guest</label>
                @if(auth()->user()->isGuest() && isset($guestProfile))
                    <input type="hidden" name="guest_id" value="{{ $guestProfile->id }}">
                    <div class="form-control" style="display:flex;align-items:center;justify-content:space-between;gap:12px;">
                        <span>{{ $guestProfile->full_name }}</span>
                        <span class="muted">{{ $guestProfile->email }}</span>
                    </div>
                @else
                    <select name="guest_id" class="form-control" required>
                        <option value="">Select guest</option>
                        @foreach($guests as $guest)
                            <option value="{{ $guest->id }}" {{ old('guest_id') == $guest->id ? 'selected' : '' }}>{{ $guest->full_name }}</option>
                        @endforeach
                    </select>
                @endif
                @error('guest_id') <div class="form-error">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label class="form-label">Room</label>
                <select name="room_id" id="room_id" class="form-control" required>
                    <option value="">Select room</option>
                    @foreach($rooms as $room)
                        <option value="{{ $room->id }}" {{ old('room_id', request('room_id')) == $room->id ? 'selected' : '' }}>
                            Room {{ $room->room_number }} | {{ ucfirst($room->type) }} | P{{ number_format($room->price_per_night, 2) }}/night
                        </option>
                    @endforeach
                </select>
                @error('room_id') <div class="form-error">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label class="form-label">Check-In Date</label>
                <input type="date" name="check_in" class="form-control" value="{{ old('check_in') }}" required>
                @error('check_in') <div class="form-error">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label class="form-label">Check-Out Date</label>
                <input type="date" name="check_out" class="form-control" value="{{ old('check_out') }}" required>
                @error('check_out') <div class="form-error">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label class="form-label">Guests</label>
                <input type="number" name="num_guests" class="form-control" value="{{ old('num_guests', 1) }}" min="1" required>
                @error('num_guests') <div class="form-error">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label class="form-label">Status</label>
                <select name="status" class="form-control" required {{ auth()->user()->isGuest() ? 'disabled' : '' }}>
                    @foreach(['pending', 'confirmed', 'checked_in', 'checked_out', 'cancelled'] as $status)
                        <option value="{{ $status }}" {{ old('status', auth()->user()->isGuest() ? 'pending' : 'pending') === $status ? 'selected' : '' }}>
                            {{ str($status)->replace('_', ' ')->title() }}
                        </option>
                    @endforeach
                </select>
                @if(auth()->user()->isGuest())
                    <input type="hidden" name="status" value="pending">
                @endif
                @error('status') <div class="form-error">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Special Requests</label>
            <textarea name="special_requests" class="form-control" placeholder="Add arrival notes, bed preference, or guest requests.">{{ old('special_requests') }}</textarea>
            @error('special_requests') <div class="form-error">{{ $message }}</div> @enderror
        </div>

        <div class="alert">
            One invoice is created per reservation automatically, and rooms under maintenance cannot be booked.
        </div>

        <div style="display:flex; gap:10px;">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Reservation</button>
            <a href="{{ route('bookings.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    document.querySelectorAll('.room-select-btn').forEach((button) => {
        button.addEventListener('click', () => {
            const roomId = button.dataset.roomId;
            const roomSelect = document.getElementById('room_id');

            if (!roomSelect) {
                return;
            }

            roomSelect.value = roomId;
            roomSelect.dispatchEvent(new Event('change'));
            roomSelect.scrollIntoView({ behavior: 'smooth', block: 'center' });
        });
    });

    document.getElementById('room_id')?.addEventListener('change', (event) => {
        document.querySelectorAll('[data-room-card]').forEach((card) => {
            card.classList.toggle('is-selected', card.dataset.roomCard === event.target.value);
        });
    });
</script>
@endpush
