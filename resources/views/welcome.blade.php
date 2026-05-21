@extends('layouts.app')
@section('title', 'Welcome')
@section('content')
<section class="luxury-hero" aria-labelledby="landing-title">
    <div class="hero-copy-panel fade-up">
        <span class="eyebrow"><i class="fas fa-hotel"></i> Grand Azure Hotel</span>
        <h1 id="landing-title">Luxury stays managed with effortless precision.</h1>
        <p>
            A refined hospitality platform for guests, reception, housekeeping, billing, and hotel leadership. Reserve premium rooms, coordinate service, and keep every arrival beautifully prepared.
        </p>

        <div class="hero-actions">
            <button class="btn btn-primary btn-lg" onclick="document.getElementById('availability-panel')?.scrollIntoView({ behavior: 'smooth' })">
                <i class="fas fa-calendar-check"></i> Book Now
            </button>
            <button class="btn btn-secondary btn-lg" onclick="document.getElementById('featured-rooms')?.scrollIntoView({ behavior: 'smooth' })">
                <i class="fas fa-bed"></i> Explore Rooms
            </button>
            <button class="btn btn-ghost btn-lg" onclick="window.startVirtualTour?.()">
                <i class="fas fa-play"></i> Virtual Tour
            </button>
        </div>

        <div class="hero-metrics" aria-label="Hotel highlights">
            <div>
                <strong>120+</strong>
                <span>Luxury Rooms</span>
            </div>
            <div>
                <strong>4.9</strong>
                <span>Guest Rating</span>
            </div>
            <div>
                <strong>24/7</strong>
                <span>Concierge Desk</span>
            </div>
        </div>
    </div>

    <div class="hero-visual-panel fade-up" style="--delay: .12s;">
        <div class="hero-carousel" aria-label="Grand Azure hotel showcase">
            <div class="carousel-slide is-active">
                <img src="https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=1200&q=80" alt="Luxury hotel exterior and pool" loading="eager">
            </div>
            <div class="carousel-slide">
                <img src="https://images.unsplash.com/photo-1551882547-ff40c63fe5fa?auto=format&fit=crop&w=1200&q=80" alt="Elegant hotel lobby lounge" loading="lazy">
            </div>
            <div class="carousel-slide">
                <img src="https://images.unsplash.com/photo-1611892440504-42a792e24d32?auto=format&fit=crop&w=1200&q=80" alt="Premium hotel suite with warm lighting" loading="lazy">
            </div>
        </div>

        <article class="floating-reservation" id="availability-panel">
            <div>
                <span class="room-tag">Today</span>
                <h2>Check Availability</h2>
            </div>
            <div class="availability-grid">
                <label>
                    <span>Check In</span>
                    <input type="date" value="{{ now()->format('Y-m-d') }}">
                </label>
                <label>
                    <span>Guests</span>
                    <select>
                        <option>2 Guests</option>
                        <option>1 Guest</option>
                        <option>3 Guests</option>
                        <option>4 Guests</option>
                    </select>
                </label>
            </div>
            <button class="btn btn-primary" onclick="document.getElementById('featured-rooms')?.scrollIntoView({ behavior: 'smooth' })">
                <i class="fas fa-magnifying-glass"></i> Find Rooms
            </button>
        </article>

        <div class="suite-orbit-card">
            <i class="fas fa-crown"></i>
            <span>Award-winning hospitality</span>
        </div>
    </div>
</section>

<section class="experience-strip" aria-label="Live property snapshot">
    <article>
        <i class="fas fa-cloud-sun"></i>
        <span>Manila Bay</span>
        <strong>29 C Clear</strong>
    </article>
    <article>
        <i class="fas fa-door-open"></i>
        <span>Available Tonight</span>
        <strong>38 Rooms</strong>
    </article>
    <article>
        <i class="fas fa-martini-glass-citrus"></i>
        <span>Guest Favorite</span>
        <strong>Azure Lounge</strong>
    </article>
    <article>
        <i class="fas fa-star"></i>
        <span>Recent Review</span>
        <strong>"Impeccable service."</strong>
    </article>
</section>

<section class="section-heading" id="featured-rooms">
    <div>
        <span class="eyebrow"><i class="fas fa-key"></i> Featured Rooms</span>
        <h2>Curated suites for every arrival.</h2>
    </div>
    <div class="landing-search">
        <label for="landing-search" class="sr-only">Search rooms</label>
        <i class="fas fa-magnifying-glass"></i>
        <input id="landing-search" type="search" placeholder="Search rooms, amenities, rates..." oninput="window.filterLandingCards?.(this.value)">
    </div>
</section>

<section class="room-showcase-grid">
    <article class="room-showcase-card landing-search-card" data-search="deluxe ocean view king suite balcony minibar breakfast">
        <img src="https://images.unsplash.com/photo-1590490360182-c33d57733427?auto=format&fit=crop&w=900&q=80" alt="Sea Breeze King Suite" loading="lazy">
        <div class="room-showcase-body">
            <div class="room-meta-row">
                <span class="room-tag">Deluxe Ocean View</span>
                <span class="room-status available">Available</span>
            </div>
            <h3>Sea Breeze King Suite</h3>
            <p>Private balcony, king bed, minibar, sunrise views, and breakfast service.</p>
            <div class="amenity-chips">
                <span><i class="fas fa-user-group"></i> 2 Guests</span>
                <span><i class="fas fa-water"></i> Ocean View</span>
                <span><i class="fas fa-wifi"></i> Wi-Fi</span>
            </div>
            <div class="room-showcase-footer">
                <strong>PHP 7,200 / night</strong>
                <button class="btn btn-primary btn-sm" onclick="window.openBookingModal('Sea Breeze King Suite', 7200)">View Details</button>
            </div>
        </div>
    </article>

    <article class="room-showcase-card landing-search-card" data-search="executive suite premium lounge seating work desk spa bathroom">
        <img src="https://images.unsplash.com/photo-1591088398332-8a7791972843?auto=format&fit=crop&w=900&q=80" alt="Azure Executive Retreat" loading="lazy">
        <div class="room-showcase-body">
            <div class="room-meta-row">
                <span class="room-tag">Executive Suite</span>
                <span class="room-status available">Available</span>
            </div>
            <h3>Azure Executive Retreat</h3>
            <p>Lounge seating, work desk, spa-inspired bathroom, and priority concierge.</p>
            <div class="amenity-chips">
                <span><i class="fas fa-user-group"></i> 3 Guests</span>
                <span><i class="fas fa-briefcase"></i> Work Desk</span>
                <span><i class="fas fa-spa"></i> Spa Bath</span>
            </div>
            <div class="room-showcase-footer">
                <strong>PHP 9,800 / night</strong>
                <button class="btn btn-primary btn-sm" onclick="window.openBookingModal('Azure Executive Retreat', 9800)">View Details</button>
            </div>
        </div>
    </article>

    <article class="room-showcase-card landing-search-card" data-search="premium twin room city view family amenities fitness shuttle">
        <img src="https://images.unsplash.com/photo-1566665797739-1674de7a421a?auto=format&fit=crop&w=900&q=80" alt="Azure City Escape twin room" loading="lazy">
        <div class="room-showcase-body">
            <div class="room-meta-row">
                <span class="room-tag">Premier Twin</span>
                <span class="room-status available">Available</span>
            </div>
            <h3>Azure City Escape</h3>
            <p>Flexible bedding, skyline outlook, fitness access, and 24-hour room service.</p>
            <div class="amenity-chips">
                <span><i class="fas fa-user-group"></i> 4 Guests</span>
                <span><i class="fas fa-city"></i> City View</span>
                <span><i class="fas fa-dumbbell"></i> Fitness</span>
            </div>
            <div class="room-showcase-footer">
                <strong>PHP 5,400 / night</strong>
                <button class="btn btn-primary btn-sm" onclick="window.openBookingModal('Azure City Escape', 5400)">View Details</button>
            </div>
        </div>
    </article>
</section>

<section class="section-heading">
    <div>
        <span class="eyebrow"><i class="fas fa-layer-group"></i> Management Suite</span>
        <h2>Built for real hotel operations.</h2>
    </div>
</section>

<section class="management-grid">
    @foreach([
        ['fa-calendar-check', 'Reservation Management', 'Control booking status, dates, guest records, and room inventory.'],
        ['fa-bell-concierge', 'Front Desk Operations', 'Approve arrivals, coordinate check-ins, and resolve guest requests.'],
        ['fa-address-book', 'Guest History Tracking', 'Keep profiles, stay preferences, and repeat-guest details close.'],
        ['fa-door-open', 'Smart Room Assignment', 'Match guest needs with room type, capacity, and availability.'],
        ['fa-broom', 'Housekeeping Monitoring', 'Track cleaning, maintenance, approvals, and readiness by room.'],
        ['fa-file-invoice-dollar', 'Payment Integration', 'Monitor invoices, balances, receipts, and settlement status.'],
        ['fa-chart-line', 'Analytics Dashboard', 'View occupancy, active bookings, revenue exposure, and service queues.'],
        ['fa-globe', 'Online Booking', 'Let guests explore room categories and begin reservations quickly.'],
    ] as [$icon, $title, $copy])
        <article class="management-card">
            <i class="fas {{ $icon }}"></i>
            <h3>{{ $title }}</h3>
            <p>{{ $copy }}</p>
        </article>
    @endforeach
</section>

<div id="bookingModal" class="modal-overlay" style="display:none;">
    <div class="modal-content">
        <button class="modal-close" onclick="window.closeBookingModal()" aria-label="Close booking dialog">&times;</button>
        <h2>Ready to experience<br><span id="modalRoomName" style="color: var(--gold-deep);"></span>?</h2>
        <p id="modalRoomPrice" class="modal-price"></p>
        <div class="modal-actions">
            <a href="{{ route('login') }}" class="btn btn-primary"><i class="fas fa-sign-in-alt"></i> Sign In to Book</a>
            <a href="{{ route('register') }}" class="btn btn-secondary"><i class="fas fa-user-plus"></i> Create Account</a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    window.filterLandingCards = function (value) {
        const query = (value || '').trim().toLowerCase();
        document.querySelectorAll('.landing-search-card').forEach((card) => {
            const haystack = card.dataset.search || '';
            card.style.display = !query || haystack.includes(query) ? '' : 'none';
        });
    };

    window.openBookingModal = function(roomName, price) {
        document.getElementById('modalRoomName').textContent = roomName;
        document.getElementById('modalRoomPrice').textContent = `PHP ${price.toLocaleString()} per night`;
        document.getElementById('bookingModal').style.display = 'flex';
    };

    window.closeBookingModal = function() {
        document.getElementById('bookingModal').style.display = 'none';
    };

    window.startVirtualTour = function() {
        document.querySelector('.hero-visual-panel')?.classList.add('tour-pulse');
        setTimeout(() => document.querySelector('.hero-visual-panel')?.classList.remove('tour-pulse'), 1400);
    };

    document.addEventListener('click', function(event) {
        const modal = document.getElementById('bookingModal');
        if (event.target === modal) {
            window.closeBookingModal();
        }
    });
</script>
@endpush
