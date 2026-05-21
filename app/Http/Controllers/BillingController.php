<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class BillingController extends Controller
{
    public function index()
    {
        $invoiceUserColumn = Schema::hasColumn('invoices', 'user_id') ? 'user_id' : null;

        $invoices = Invoice::with(['booking.room', 'booking.guest', 'payments'])
            ->when(auth()->user()->isGuest(), function ($query) use ($invoiceUserColumn) {
                if ($invoiceUserColumn) {
                    $query->where('user_id', auth()->id());
                } else {
                    $query->whereHas('booking.guest', fn ($guestQuery) => $guestQuery->where('email', auth()->user()->email));
                }
            })
            ->latest()
            ->paginate(10);

        // Count new bookings that need billing attention (confirmed bookings without fully paid invoices)
        $newBookingsCount = 0;
        if (auth()->user()->hasRole(['admin', 'receptionist'])) {
            $newBookingsCount = \App\Models\Booking::where('status', 'confirmed')
                ->whereHas('invoice', function ($query) {
                    $query->whereIn('status', ['unpaid', 'partially_paid']);
                })
                ->count();
        }

        // Get pending invoices for the payments tab overview
        $pendingInvoices = Invoice::with(['booking.room.roomType', 'booking.guest', 'payments'])
            ->when(auth()->user()->isGuest(), function ($query) {
                $query->whereHas('booking', function ($bookingQuery) {
                    $bookingQuery->where('user_id', auth()->id())
                        ->orWhereHas('guest', fn ($guestQuery) => $guestQuery->where('email', auth()->user()->email));
                });
            })
            ->whereIn('status', ['unpaid', 'partially_paid'])
            ->whereHas('booking', function ($query) {
                $query->whereIn('status', ['confirmed', 'checked_in', 'checked_out']);
            })
            ->latest()
            ->take(10)
            ->get();

        // Get recent payments for the payments tab
        $recentPayments = \App\Models\Payment::with(['invoice.booking.guest'])
            ->when(auth()->user()->isGuest(), function ($query) {
                $query->whereHas('invoice.booking', function ($bookingQuery) {
                    $bookingQuery->where('user_id', auth()->id())
                        ->orWhereHas('guest', fn ($guestQuery) => $guestQuery->where('email', auth()->user()->email));
                });
            })
            ->latest()
            ->take(10)
            ->get();

        // Get all payments for receipts tab
        $allPayments = \App\Models\Payment::with(['invoice.booking.guest'])
            ->when(auth()->user()->isGuest(), function ($query) {
                $query->whereHas('invoice.booking', function ($bookingQuery) {
                    $bookingQuery->where('user_id', auth()->id())
                        ->orWhereHas('guest', fn ($guestQuery) => $guestQuery->where('email', auth()->user()->email));
                });
            })
            ->latest()
            ->paginate(20);

        return view('billing.index', compact('invoices', 'newBookingsCount', 'pendingInvoices', 'recentPayments', 'allPayments'));
    }

    public function storePayment(Request $request)
    {
        abort_unless(auth()->user()->hasRole(['admin', 'receptionist']), 403);

        $data = $request->validate([
            'invoice_id' => 'required|exists:invoices,id',
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|in:cash,credit_card,debit_card,bank_transfer,online,mobile_wallet,check,gift_card',
            'transaction_id' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $invoice = Invoice::findOrFail($data['invoice_id']);

        // Check if payment amount doesn't exceed balance
        if ($data['amount'] > $invoice->balance) {
            return back()->withInput()->withErrors(['amount' => 'Payment amount cannot exceed the outstanding balance.']);
        }

        $payment = Payment::create([
            'invoice_id' => $invoice->id,
            'amount' => $data['amount'],
            'payment_method' => $data['payment_method'],
            'transaction_id' => $data['transaction_id'] ?? null,
            'payment_date' => now(),
            'notes' => $data['notes'] ?? null,
        ]);

        $paidAmount = (float) $invoice->payments()->sum('amount');
        $status = $paidAmount >= (float) $invoice->total_amount
            ? 'paid'
            : ($paidAmount > 0 ? 'partially_paid' : 'unpaid');

        $invoice->update([
            'paid_amount' => $paidAmount,
            'status' => $status,
        ]);

        return redirect()->route('billing.index', ['invoice_id' => $invoice->id])
            ->with('success', 'Payment recorded successfully. E-Receipt generated.')
            ->with('receipt_id', $payment->id);
    }

    public function showReceipt(Payment $payment)
    {
        $payment->load(['invoice.guest', 'invoice.booking.room.roomType', 'invoice.booking.guest']);

        // Ensure user can only view receipts for their own payments (guests) or all payments (staff)
        if (auth()->user()->isGuest()) {
            $booking = $payment->invoice?->booking;
            $invoiceGuest = $payment->invoice?->guest;
            $bookingGuest = $booking?->guest;

            $canViewReceipt = (int) $booking?->user_id === (int) auth()->id()
                || $bookingGuest?->email === auth()->user()->email
                || $invoiceGuest?->email === auth()->user()->email;

            if (! $canViewReceipt) {
                abort(403);
            }
        }

        return view('billing.receipt', compact('payment'));
    }
}
