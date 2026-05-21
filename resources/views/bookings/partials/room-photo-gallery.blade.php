@if($rooms->isNotEmpty())
    <section class="room-choice-gallery" aria-label="Available room photos">
        <div class="section-header">
            <div>
                <h2>Room Photos</h2>
                <p class="muted" style="margin:6px 0 0;">Preview the room before confirming the reservation details.</p>
            </div>
        </div>

        <div class="room-choice-grid">
            @foreach($rooms as $room)
                @php
                    $isSelected = (int) old('room_id', $selectedRoomId ?? 0) === (int) $room->id;
                @endphp
                <article class="room-choice-card {{ $isSelected ? 'is-selected' : '' }}" data-room-card="{{ $room->id }}">
                    @if($room->image_url)
                        <img src="{{ $room->image_url }}" alt="Room {{ $room->room_number }} photo" class="room-choice-image">
                    @else
                        <div class="room-choice-image room-choice-placeholder">
                            <i class="fas fa-bed"></i>
                        </div>
                    @endif

                    <div class="room-choice-body">
                        <div class="room-meta-row">
                            <span class="room-tag">Room {{ $room->room_number }}</span>
                            <span class="room-status {{ $room->status === 'available' ? 'available' : 'booked' }}">{{ str($room->status)->replace('_', ' ')->title() }}</span>
                        </div>
                        <h3>{{ $room->typeLabel }}</h3>
                        <p class="room-info">{{ $room->description ?: 'Comfortable stay with essential hotel amenities.' }}</p>
                        <div class="room-choice-facts">
                            <span><i class="fas fa-user-group"></i> {{ $room->capacity }} guest(s)</span>
                            <span><i class="fas fa-tag"></i> P{{ number_format($room->price_per_night, 2) }}/night</span>
                        </div>
                        <div style="display:flex;gap:8px;flex-wrap:wrap;">
                            <a href="{{ route('rooms.show', $room) }}" class="btn btn-secondary btn-sm">
                                <i class="fas fa-circle-info"></i> Details
                            </a>
                            <button type="button" class="btn btn-secondary btn-sm room-select-btn" data-room-id="{{ $room->id }}">
                                <i class="fas fa-check"></i> Choose Room
                            </button>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    </section>
@endif
