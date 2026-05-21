<?php

namespace App\Http\Controllers;

use App\Models\Guest;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class GuestController extends Controller {

    public function index(Request $request) {
        $search = $request->query('search');
        $status = $request->query('status');

        $guests = Guest::query()
            ->withCount('bookings')
            ->with(['bookings' => fn ($query) => $query->with('room.roomType')->latest()->limit(1)])
            ->when(auth()->user()->isAdmin() && $request->boolean('archived'), fn ($query) => $query->onlyTrashed())
            ->when(in_array($status, ['regular', 'vip', 'blacklisted'], true), fn ($query) => $query->where('status', $status))
            ->search($search)
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $returningGuest = filled($search)
            ? Guest::withTrashed()->withCount('bookings')->search($search)->orderByDesc('bookings_count')->first()
            : null;

        return view('guests.index', compact('guests', 'search', 'status', 'returningGuest'));
    }

    public function create() {
        return view('guests.create');
    }

    public function store(Request $request) {
        $data = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name'  => 'required|string|max:100',
            'email'      => 'required|email',
            'phone'      => 'required|string|max:20',
            'address'    => 'nullable|string',
            'id_type'    => 'nullable|string',
            'id_number'  => 'nullable|string',
            'preferred_room_type' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
            'status' => ['nullable', Rule::in(['regular', 'vip', 'blacklisted'])],
            'loyalty_points' => 'nullable|integer|min:0',
        ]);

        $duplicate = Guest::withTrashed()
            ->where('email', $data['email'])
            ->orWhere('phone', $data['phone'])
            ->when($data['id_number'] ?? null, fn ($query) => $query->orWhere('id_number', $data['id_number']))
            ->first();

        if ($duplicate) {
            return redirect()
                ->route('guests.show', $duplicate)
                ->with('error', 'Returning guest detected. Existing profile loaded instead of creating a duplicate record.');
        }

        if (! auth()->user()->isAdmin()) {
            unset($data['status'], $data['loyalty_points']);
        }

        $guest = Guest::create($data);

        return redirect()->route('guests.show', $guest)->with('success', 'Guest profile created successfully.');
    }

    public function show(Guest $guest) {
        $guest->load([
            'bookings' => fn ($query) => $query->with(['room.roomType', 'invoice.payments'])->latest(),
            'invoices.payments',
        ]);

        $payments = Payment::with(['invoice.booking.room.roomType'])
            ->whereHas('invoice', fn ($query) => $query->where('guest_id', $guest->id))
            ->latest('payment_date')
            ->get();

        $paidTotal = (float) $guest->invoices->sum('paid_amount');
        $roomTypeCounts = $guest->bookings
            ->groupBy(fn ($booking) => $booking->room?->roomType?->name ?? 'Unassigned')
            ->map->count()
            ->sortDesc();

        $latestReservation = $guest->bookings->first();
        $lastStay = $guest->bookings
            ->whereIn('status', ['checked_out', 'checked_in'])
            ->sortByDesc('check_out')
            ->first();

        $stats = [
            'total_stays' => $guest->bookings->where('status', 'checked_out')->count(),
            'total_spent' => $paidTotal,
            'most_booked_room_type' => $guest->preferred_room_type ?: ($roomTypeCounts->keys()->first() ?? 'No stays yet'),
            'last_stay_date' => $lastStay?->check_out?->format('M d, Y') ?? 'No completed stay',
        ];

        $timeline = collect()
            ->merge($guest->bookings->map(fn ($booking) => [
                'date' => $booking->updated_at,
                'icon' => 'fa-calendar-check',
                'label' => 'Reservation #' . $booking->id . ' ' . str_replace('_', ' ', $booking->status),
                'detail' => optional($booking->room)->room_number
                    ? 'Room ' . $booking->room->room_number . ' from ' . $booking->check_in->format('M d, Y') . ' to ' . $booking->check_out->format('M d, Y')
                    : 'Reservation dates: ' . $booking->check_in->format('M d, Y') . ' to ' . $booking->check_out->format('M d, Y'),
            ]))
            ->merge($payments->map(fn ($payment) => [
                'date' => $payment->payment_date,
                'icon' => 'fa-receipt',
                'label' => 'Payment ' . ($payment->transaction_id ?: 'PAY-' . str_pad((string) $payment->id, 4, '0', STR_PAD_LEFT)),
                'detail' => 'Paid PHP ' . number_format((float) $payment->amount, 2) . ' for Reservation #' . optional($payment->invoice?->booking)->id,
            ]))
            ->sortByDesc('date')
            ->take(8)
            ->values();

        return view('guests.show', compact('guest', 'payments', 'stats', 'latestReservation', 'timeline'));
    }

    public function edit(Guest $guest) {
        return view('guests.edit', compact('guest'));
    }

    public function update(Request $request, Guest $guest) {
        $data = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name'  => 'required|string|max:100',
            'email'      => 'required|email|unique:guests,email,' . $guest->id,
            'phone'      => 'required|string|max:20',
            'address'    => 'nullable|string',
            'id_type'    => 'nullable|string',
            'id_number'  => 'nullable|string',
            'preferred_room_type' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
            'status' => ['nullable', Rule::in(['regular', 'vip', 'blacklisted'])],
            'loyalty_points' => 'nullable|integer|min:0',
        ]);

        if (! auth()->user()->isAdmin()) {
            unset($data['status'], $data['loyalty_points']);
        }

        $guest->update($data);
        return redirect()->route('guests.show', $guest)->with('success', 'Guest updated successfully.');
    }

    public function destroy(Guest $guest) {
        abort_unless(auth()->user()->hasRole(['receptionist', 'admin']), 403);

        $guest->delete();
        return redirect()->route('guests.index')->with('success', 'Guest archived successfully.');
    }

    public function restore(int $guest) {
        abort_unless(auth()->user()->isAdmin(), 403);

        $guest = Guest::onlyTrashed()->findOrFail($guest);
        $guest->restore();

        return redirect()->route('guests.show', $guest)->with('success', 'Guest profile restored successfully.');
    }
}
