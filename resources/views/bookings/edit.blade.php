@extends('layouts.app')
@section('title', 'Edit Reservation')
@section('content')
<div class="page-header">
    <div>
        <h1>Edit Reservation #{{ $booking->id }}</h1>
        <p>Adjust stay details without breaking room availability rules.</p>
    </div>
    <a href="{{ route('bookings.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back</a>
</div>

@include('bookings.partials.room-photo-gallery', ['rooms' => $rooms, 'selectedRoomId' => old('room_id', $booking->room_id)])

<div class="card" style="max-width:980px;">
    <form method="POST" action="{{ route('bookings.update', $booking) }}">
        @csrf
        @method('PUT')
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
                        @foreach($guests as $guest)
                            <option value="{{ $guest->id }}" {{ old('guest_id', $booking->guest_id) == $guest->id ? 'selected' : '' }}>{{ $guest->full_name }}</option>
                        @endforeach
                    </select>
                @endif
                @error('guest_id') <div class="form-error">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label class="form-label">Room</label>
                <select name="room_id" id="room_id" class="form-control" required>
                    @foreach($rooms as $room)
                        <option value="{{ $room->id }}" {{ old('room_id', $booking->room_id) == $room->id ? 'selected' : '' }}>
                            Room {{ $room->room_number }} | {{ ucfirst($room->type) }} | P{{ number_format($room->price_per_night, 2) }}/night
                        </option>
                    @endforeach
                </select>
                @error('room_id') <div class="form-error">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label class="form-label">Check-In Date</label>
                <input type="date" name="check_in" class="form-control" value="{{ old('check_in', $booking->check_in->format('Y-m-d')) }}" required>
                @error('check_in') <div class="form-error">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label class="form-label">Check-Out Date</label>
                <input type="date" name="check_out" class="form-control" value="{{ old('check_out', $booking->check_out->format('Y-m-d')) }}" required>
                @error('check_out') <div class="form-error">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label class="form-label">Guests</label>
                <input type="number" name="num_guests" class="form-control" value="{{ old('num_guests', $booking->num_guests) }}" min="1" required>
                @error('num_guests') <div class="form-error">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label class="form-label">Status</label>
                <select name="status" class="form-control" required {{ auth()->user()->isGuest() ? 'disabled' : '' }}>
                    @foreach(['pending', 'confirmed', 'checked_in', 'checked_out', 'cancelled'] as $status)
                        <option value="{{ $status }}" {{ old('status', $booking->status) === $status ? 'selected' : '' }}>
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
            <textarea name="special_requests" class="form-control">{{ old('special_requests', $booking->special_requests) }}</textarea>
            @error('special_requests') <div class="form-error">{{ $message }}</div> @enderror
        </div>

        <div style="display:flex; gap:10px;">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update Reservation</button>
            <a href="{{ route('bookings.show', $booking) }}" class="btn btn-secondary">Cancel</a>
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
