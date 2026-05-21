<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\HousekeepingTask;
use App\Models\Invoice;
use App\Models\Room;
use App\Models\Guest;
use App\Models\Staff;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\ValidationException;

class BookingController extends Controller {

    public function index() {
        $bookings = Booking::with(['room.roomType', 'guest', 'user', 'invoice'])
            ->when(auth()->user()->isGuest(), fn ($query) => $query->where('user_id', auth()->id()))
            ->latest()
            ->paginate(10);

        return view('bookings.index', compact('bookings'));
    }

    public function create() {
        $user = auth()->user();

        $rooms = Room::with('roomType')
            ->where('status', 'available')
            ->orderBy('room_number')
            ->get();

        $guestProfile = $user->isGuest()
            ? $this->ensureGuestProfile($user)
            : null;

        $guests = $user->isGuest()
            ? collect([$guestProfile])
            : Guest::orderBy('first_name')->get();

        return view('bookings.create', compact('rooms', 'guests', 'guestProfile'));
    }

    public function store(Request $request) {
        $user = auth()->user();

        $data = $request->validate([
            'room_id'          => 'required|exists:rooms,id',
            'guest_id'         => 'required|exists:guests,id',
            'check_in'         => 'required|date|after_or_equal:today',
            'check_out'        => 'required|date|after:check_in',
            'num_guests'       => 'required|integer|min:1',
            'special_requests' => 'nullable|string',
            'status'           => 'required|in:pending,confirmed,checked_in,checked_out,cancelled',
        ]);

        $room = Room::with('roomType')->findOrFail($data['room_id']);
        
        // Check if room is available for the selected dates
        $availabilityError = $this->checkRoomAvailability($room, $data['check_in'], $data['check_out']);
        if ($availabilityError) {
            return back()->withInput()->withErrors(['room_id' => $availabilityError]);
        }

        if ($data['num_guests'] > $room->capacity) {
            return back()->withInput()->withErrors([
                'num_guests' => "This room can only accommodate {$room->capacity} guest(s).",
            ]);
        }

        if ($user->isGuest()) {
            $guestProfile = $this->ensureGuestProfile($user);
            $data['status'] = 'pending';
            $guest = Guest::findOrFail($data['guest_id']);

            if ($guest->id !== $guestProfile->id) {
                abort(403, 'Guests may only create their own reservations.');
            }
        }

        $nights = Carbon::parse($data['check_in'])->diffInDays($data['check_out']);
        $data['total_amount'] = $room->price_per_night * $nights;
        $data['user_id'] = $user->id;

        $booking = Booking::create($data);

        if ($data['status'] === 'checked_in') {
            $room->update(['status' => 'occupied']);
        }

        $this->syncInvoice($booking);

        $message = 'Booking created successfully.';
        if (auth()->user()->hasRole(['receptionist', 'admin'])) {
            $message .= ' Please review the billing section for invoice processing.';
        }

        return redirect()->route('bookings.index')->with('success', $message);
    }

    public function show(Booking $booking) {
        $this->ensureBookingVisibility($booking);

        $booking->load(['room.roomType', 'guest', 'user', 'invoice.payments']);
        return view('bookings.show', compact('booking'));
    }

    public function edit(Booking $booking) {
        $this->ensureBookingVisibility($booking);

        $rooms = Room::with('roomType')->get();
        $guestProfile = auth()->user()->isGuest()
            ? $this->ensureGuestProfile(auth()->user())
            : null;

        $guests = auth()->user()->isGuest()
            ? collect([$guestProfile])
            : Guest::orderBy('first_name')->get();

        return view('bookings.edit', compact('booking', 'rooms', 'guests', 'guestProfile'));
    }

    public function update(Request $request, Booking $booking) {
        $user = auth()->user();
        $this->ensureBookingVisibility($booking);

        $data = $request->validate([
            'room_id'          => 'required|exists:rooms,id',
            'guest_id'         => 'required|exists:guests,id',
            'check_in'         => 'required|date',
            'check_out'        => 'required|date|after:check_in',
            'num_guests'       => 'required|integer|min:1',
            'special_requests' => 'nullable|string',
            'status'           => 'required|in:pending,confirmed,checked_in,checked_out,cancelled',
        ]);

        $room = Room::with('roomType')->findOrFail($data['room_id']);
        
        // Check if room is available for the selected dates (excluding current booking)
        $availabilityError = $this->checkRoomAvailability($room, $data['check_in'], $data['check_out'], $booking->id);
        if ($availabilityError) {
            return back()->withInput()->withErrors(['room_id' => $availabilityError]);
        }

        if ($data['num_guests'] > $room->capacity) {
            return back()->withInput()->withErrors([
                'num_guests' => "This room can only accommodate {$room->capacity} guest(s).",
            ]);
        }

        if ($user->isGuest()) {
            $guestProfile = $this->ensureGuestProfile($user);

            if ((int) $data['guest_id'] !== (int) $guestProfile->id) {
                abort(403, 'Guests may only update their own reservations.');
            }

            if ($booking->status === 'checked_in') {
                abort(403, 'Checked-in reservations can only be changed by an admin.');
            }

            $data['status'] = 'pending';
        }

        $nights = Carbon::parse($data['check_in'])->diffInDays($data['check_out']);
        $data['total_amount'] = $room->price_per_night * $nights;

        if ($data['status'] === 'checked_in') {
            $room->update(['status' => 'occupied']);
        } elseif (in_array($data['status'], ['checked_out', 'cancelled'])) {
            $room->update(['status' => 'available']);
        }

        $booking->update($data);
        $this->syncInvoice($booking->fresh(['room']));

        return redirect()->route('bookings.index')->with('success', 'Booking updated successfully.');
    }

    public function destroy(Booking $booking) {
        $this->ensureBookingVisibility($booking);

        if ($booking->status === 'checked_in' && ! auth()->user()->isAdmin()) {
            return back()->with('error', 'Checked-in reservations can only be removed by an admin.');
        }

        $booking->delete();
        return redirect()->route('bookings.index')->with('success', 'Booking deleted successfully.');
    }

    public function approve(Booking $booking)
    {
        abort_unless(auth()->user()->hasRole(['receptionist', 'admin']), 403);

        $booking->loadMissing(['room.roomType', 'guest', 'invoice']);

        if ($booking->status !== 'pending') {
            return back()->with('error', 'Only pending guest reservations can be approved by the front desk.');
        }

        if ($booking->room->status === 'under_maintenance') {
            return back()->with('error', 'This room is under maintenance and cannot be assigned to a confirmed reservation.');
        }

        $booking->update(['status' => 'confirmed']);
        $this->syncInvoice($booking->fresh(['room', 'invoice', 'guest']));

        $assignedStaff = $this->createArrivalPreparationTask($booking);

        $message = 'Reservation approved and invoice prepared for billing review.';

        if ($assignedStaff) {
            $message .= ' Housekeeping preparation was assigned to ' . $assignedStaff->user->name . '.';
        } else {
            $message .= ' No housekeeping staff profile was available for auto-assignment yet.';
        }

        return redirect()->route('bookings.index')->with('success', $message);
    }

    private function ensureBookingVisibility(Booking $booking): void
    {
        if (auth()->user()->isGuest() && $booking->user_id !== auth()->id()) {
            abort(403, 'You do not have access to this reservation.');
        }
    }

    private function checkRoomAvailability(Room $room, string $checkIn, string $checkOut, ?int $ignoreBookingId = null): ?string
    {
        if ($room->status === 'under_maintenance') {
            return 'Rooms under maintenance cannot be reserved.';
        }

        $overlapExists = Booking::where('room_id', $room->id)
            ->when($ignoreBookingId, fn ($query) => $query->where('id', '!=', $ignoreBookingId))
            ->active()
            ->where(function ($query) use ($checkIn, $checkOut) {
                $query->whereBetween('check_in', [$checkIn, $checkOut])
                    ->orWhereBetween('check_out', [$checkIn, $checkOut])
                    ->orWhere(function ($innerQuery) use ($checkIn, $checkOut) {
                        $innerQuery->where('check_in', '<=', $checkIn)
                            ->where('check_out', '>=', $checkOut);
                    });
            })
            ->exists();

        if ($overlapExists) {
            return 'This room already has an active reservation for the selected dates.';
        }

        return null;
    }

    private function assertRoomCanBeBooked(Room $room, string $checkIn, string $checkOut, ?int $ignoreBookingId = null): void
    {
        if ($room->status === 'under_maintenance') {
            throw ValidationException::withMessages([
                'room_id' => 'Rooms under maintenance cannot be reserved.',
            ]);
        }

        $overlapExists = Booking::where('room_id', $room->id)
            ->when($ignoreBookingId, fn ($query) => $query->where('id', '!=', $ignoreBookingId))
            ->active()
            ->where(function ($query) use ($checkIn, $checkOut) {
                $query->whereBetween('check_in', [$checkIn, $checkOut])
                    ->orWhereBetween('check_out', [$checkIn, $checkOut])
                    ->orWhere(function ($innerQuery) use ($checkIn, $checkOut) {
                        $innerQuery->where('check_in', '<=', $checkIn)
                            ->where('check_out', '>=', $checkOut);
                    });
            })
            ->exists();

        if ($overlapExists) {
            throw ValidationException::withMessages([
                'room_id' => 'This room already has an active reservation for the selected dates.',
            ]);
        }
    }

    private function syncInvoice(Booking $booking): void
    {
        $invoice = $booking->invoice;
        $invoiceColumns = Schema::getColumnListing('invoices');
        $payload = [
            'total_amount' => $booking->total_amount,
            'paid_amount' => (float) optional($invoice)->paid_amount,
            'status' => optional($invoice)->status ?? 'unpaid',
            'due_date' => $booking->check_out,
        ];

        if (in_array('user_id', $invoiceColumns, true)) {
            $payload['user_id'] = $booking->user_id;
        }

        if (in_array('invoice_number', $invoiceColumns, true)) {
            $payload['invoice_number'] = optional($invoice)->invoice_number ?: ('INV-' . str_pad((string) $booking->id, 4, '0', STR_PAD_LEFT));
        }

        if (in_array('issued_at', $invoiceColumns, true)) {
            $payload['issued_at'] = optional($invoice)->issued_at ?? now();
        }

        if (in_array('guest_id', $invoiceColumns, true)) {
            $payload['guest_id'] = $booking->guest_id;
        }

        if (in_array('room_charge', $invoiceColumns, true)) {
            $payload['room_charge'] = $booking->total_amount;
        }

        if (in_array('service_charge', $invoiceColumns, true)) {
            $payload['service_charge'] = (float) optional($invoice)->service_charge;
            $payload['total_amount'] = $booking->total_amount + $payload['service_charge'];
        }

        if (in_array('notes', $invoiceColumns, true)) {
            $payload['notes'] = optional($invoice)->notes;
        }

        Invoice::updateOrCreate(['booking_id' => $booking->id], $payload);
    }

    private function ensureGuestProfile($user): Guest
    {
        $nameParts = preg_split('/\s+/', trim($user->name), 2);

        return Guest::firstOrCreate(
            ['email' => $user->email],
            [
                'first_name' => $nameParts[0] ?? $user->name,
                'last_name' => $nameParts[1] ?? 'Guest',
                'phone' => $user->phone,
            ]
        );
    }

    private function createArrivalPreparationTask(Booking $booking): ?Staff
    {
        $housekeepingStaff = Staff::with('user')
            ->where(function ($query) {
                $query->where('department', 'Housekeeping')
                    ->orWhere('position', 'like', '%Housekeeping%');
            })
            ->orderBy('id')
            ->first();

        if (! $housekeepingStaff) {
            return null;
        }

        $existingTask = HousekeepingTask::query()
            ->where('room_id', $booking->room_id)
            ->where('staff_id', $housekeepingStaff->id)
            ->where('task_type', 'cleaning')
            ->whereIn('status', ['pending', 'in_progress'])
            ->where('description', 'like', '%Reservation #' . $booking->id . '%')
            ->first();

        if ($existingTask) {
            return $housekeepingStaff;
        }

        $payload = [
            'room_id' => $booking->room_id,
            'staff_id' => $housekeepingStaff->id,
            'task_type' => 'cleaning',
            'description' => 'Reservation #' . $booking->id . ' approved by front desk. Prepare room for ' . $booking->guest->full_name . ' arrival on ' . $booking->check_in->format('M d, Y') . '.',
            'status' => 'pending',
            'notes' => 'Auto-generated after front desk approval.',
        ];

        if (Schema::hasColumn('housekeeping_tasks', 'assigned_at')) {
            $payload['assigned_at'] = now();
        }

        HousekeepingTask::create($payload);

        return $housekeepingStaff;
    }
}
