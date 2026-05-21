@extends('layouts.app')
@section('title', 'Billing')
@section('content')
<div class="page-header">
    <div>
        <h1>Billing & Payments</h1>
        <p>One invoice per reservation, with support for partial and split-style payment recording.</p>
    </div>
</div>

@if(session('new_booking_notification'))
    <div class="alert alert-info">
        <i class="fas fa-info-circle"></i>
        <strong>New Booking Alert:</strong> {{ session('new_booking_notification') }}
    </div>
@endif

@if($newBookingsCount > 0)
    <div class="alert alert-warning">
        <i class="fas fa-exclamation-triangle"></i>
        <strong>Attention Required:</strong> {{ $newBookingsCount }} new booking(s) require billing attention. Please review and process invoices.
    </div>
@endif

<!-- Tab Navigation -->
<div class="tabs">
    <button class="tab-btn active" data-tab="invoices">Invoices</button>
    <button class="tab-btn" data-tab="payments">Payments</button>
    <button class="tab-btn" data-tab="receipts">E-Receipts</button>
</div>

<!-- Invoices Tab -->
<div id="invoices-tab" class="tab-content active">
    <div class="card">
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Invoice</th>
                        <th>Guest</th>
                        <th>Reservation</th>
                        <th>Total</th>
                        <th>Paid</th>
                        <th>Balance</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($invoices as $invoice)
                        @php($guest = $invoice->guest_record)
                        <tr>
                            <td>#{{ $invoice->display_number }}</td>
                            <td>{{ $guest?->full_name ?? 'Guest record unavailable' }}</td>
                            <td>Room {{ $invoice->booking?->room?->room_number ?? 'Unassigned' }}</td>
                            <td>P{{ number_format($invoice->total_amount, 2) }}</td>
                            <td>P{{ number_format($invoice->paid_amount, 2) }}</td>
                            <td>P{{ number_format($invoice->balance, 2) }}</td>
                            <td><span class="badge badge-{{ $invoice->status === 'paid' ? 'success' : ($invoice->status === 'partially_paid' ? 'info' : 'warning') }}">{{ str_replace('_', ' ', $invoice->status) }}</span></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <div class="empty-state">
                                    <i class="fas fa-file-invoice-dollar"></i>
                                    <div>No invoices available yet.</div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="pagination">{{ $invoices->links() }}</div>
    </div>
</div>

<!-- Payments Tab -->
<div id="payments-tab" class="tab-content">
    <!-- Pending Invoices Overview -->
    <div class="card">
        <div class="card-header">
            <h3>Pending Invoices Overview</h3>
            <p>Invoices requiring payment attention</p>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Invoice</th>
                        <th>Guest</th>
                        <th>Room</th>
                        <th>Total</th>
                        <th>Paid</th>
                        <th>Balance</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pendingInvoices ?? [] as $invoice)
                        @php($guest = $invoice->booking?->guest)
                        <tr>
                            <td>#{{ $invoice->display_number }}</td>
                            <td>{{ $guest?->full_name ?? 'Guest record unavailable' }}</td>
                            <td>Room {{ $invoice->booking?->room?->room_number ?? 'Unassigned' }}</td>
                            <td>P{{ number_format($invoice->total_amount, 2) }}</td>
                            <td>P{{ number_format($invoice->paid_amount, 2) }}</td>
                            <td><strong class="text-danger">P{{ number_format($invoice->balance, 2) }}</strong></td>
                            <td><span class="badge badge-{{ $invoice->status === 'paid' ? 'success' : ($invoice->status === 'partially_paid' ? 'warning' : 'danger') }}">{{ str_replace('_', ' ', $invoice->status) }}</span></td>
                            <td>
                                <button class="btn btn-sm btn-primary" onclick="selectInvoice({{ $invoice->id }})">
                                    <i class="fas fa-credit-card"></i> Pay Now
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">
                                <div class="empty-state">
                                    <i class="fas fa-check-circle"></i>
                                    <div>All invoices are paid! No pending payments.</div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="card" style="margin-top: 30px;">
        <div class="card-header">
            <h3>Record New Payment</h3>
        </div>
        <form method="POST" action="{{ route('billing.payments.store') }}" style="padding: 20px;">
            @csrf
            <div class="form-grid">
                <div class="form-group">
                    <label for="invoice_select">Select Invoice</label>
                    <select name="invoice_id" id="invoice_select" class="form-control" required>
                        <option value="">Choose an invoice...</option>
                        @foreach($invoices->where('balance', '>', 0) as $invoice)
                            @php($guest = $invoice->guest_record)
                            <option value="{{ $invoice->id }}" {{ (request('invoice_id') == $invoice->id) ? 'selected' : '' }}>
                                #{{ $invoice->display_number }} - {{ $guest?->full_name ?? 'Guest' }} - Balance: P{{ number_format($invoice->balance, 2) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="amount">Payment Amount</label>
                    <input type="number" step="0.01" min="0.01" name="amount" id="amount" class="form-control" placeholder="0.00" required>
                </div>
                <div class="form-group">
                    <label for="payment_method">Payment Method</label>
                    <select name="payment_method" id="payment_method" class="form-control" required>
                        @foreach(['cash', 'credit_card', 'debit_card', 'bank_transfer', 'online', 'mobile_wallet', 'check', 'gift_card'] as $method)
                            <option value="{{ $method }}">{{ str($method)->replace('_', ' ')->title() }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="transaction_id">Transaction ID (Optional)</label>
                    <input type="text" name="transaction_id" id="transaction_id" class="form-control" placeholder="Reference number">
                </div>
                <div class="form-group full-width">
                    <label for="notes">Notes (Optional)</label>
                    <textarea name="notes" id="notes" class="form-control" rows="3" placeholder="Additional payment notes"></textarea>
                </div>
            </div>
            <div style="margin-top: 20px;">
                <button class="btn btn-primary" type="submit">Record Payment & Generate E-Receipt</button>
            </div>
        </form>
    </div>

    <!-- Recent Payments Table -->
    <div class="card" style="margin-top: 20px;">
        <div class="card-header">
            <h3>Recent Payments</h3>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Payment ID</th>
                        <th>Invoice</th>
                        <th>Amount</th>
                        <th>Method</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentPayments ?? [] as $payment)
                        <tr>
                            <td>#{{ $payment->id }}</td>
                            <td>#{{ $payment->invoice->display_number ?? 'N/A' }}</td>
                            <td>P{{ number_format($payment->amount, 2) }}</td>
                            <td>{{ str($payment->payment_method)->replace('_', ' ')->title() }}</td>
                            <td>{{ $payment->payment_date->format('M d, Y H:i') }}</td>
                            <td>
                                <a href="{{ route('billing.receipt.show', ['payment' => $payment, 'print' => 1]) }}" target="_blank" rel="noopener" class="btn btn-sm btn-info">
                                    <i class="fas fa-print"></i> E-Receipt
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="empty-state">
                                    <i class="fas fa-credit-card"></i>
                                    <div>No payments recorded yet.</div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- E-Receipts Tab -->
<div id="receipts-tab" class="tab-content">
    <div class="card">
        <div class="card-header">
            <h3>E-Receipts</h3>
            <p>View and print electronic receipts for all payments</p>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Receipt #</th>
                        <th>Invoice</th>
                        <th>Guest</th>
                        <th>Amount</th>
                        <th>Payment Method</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($allPayments ?? [] as $payment)
                        <tr>
                            <td>RCP-{{ str_pad($payment->id, 6, '0', STR_PAD_LEFT) }}</td>
                            <td>#{{ $payment->invoice->display_number ?? 'N/A' }}</td>
                            <td>{{ $payment->invoice->guest_record?->full_name ?? 'N/A' }}</td>
                            <td>P{{ number_format($payment->amount, 2) }}</td>
                            <td>{{ str($payment->payment_method)->replace('_', ' ')->title() }}</td>
                            <td>{{ $payment->payment_date->format('M d, Y H:i') }}</td>
                            <td>
                                <a href="{{ route('billing.receipt.show', $payment) }}" target="_blank" rel="noopener" class="btn btn-sm btn-primary">
                                    <i class="fas fa-eye"></i> View
                                </a>
                                <a href="{{ route('billing.receipt.show', ['payment' => $payment, 'print' => 1]) }}" target="_blank" rel="noopener" class="btn btn-sm btn-secondary">
                                    <i class="fas fa-print"></i> Print
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <div class="empty-state">
                                    <i class="fas fa-receipt"></i>
                                    <div>No receipts available yet.</div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function showTab(tabName, clickedButton = null) {
    // Hide all tab contents
    document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.remove('active');
    });

    // Remove active class from all tab buttons
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('active');
    });

    // Show selected tab content
    const tabContent = document.getElementById(tabName + '-tab');
    if (tabContent) {
        tabContent.classList.add('active');
    }

    // Add active class to clicked button
    if (clickedButton) {
        clickedButton.classList.add('active');
    } else {
        // Fallback: find button by text content
        document.querySelectorAll('.tab-btn').forEach(btn => {
            if (btn.textContent.toLowerCase().includes(tabName.replace('s', ''))) {
                btn.classList.add('active');
            }
        });
    }
}

// Initialize tabs and handle URL parameters
document.addEventListener('DOMContentLoaded', function() {
    // Update tab buttons to use the new showTab function
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.onclick = function() {
            const tabName = this.getAttribute('data-tab');
            showTab(tabName, this);
        };
    });

    // Auto-select tab and invoice based on URL parameters
    const urlParams = new URLSearchParams(window.location.search);
    const tab = urlParams.get('tab');
    const invoiceId = urlParams.get('invoice_id');

    if (tab) {
        showTab(tab + 's'); // Add 's' because tabs are named 'payments', 'receipts'
    }

    if (invoiceId) {
        const invoiceSelect = document.getElementById('invoice_select');
        if (invoiceSelect) {
            invoiceSelect.value = invoiceId;
        }
        if (!tab) {
            showTab('payments'); // If invoice_id is specified but no tab, show payments
        }
    }
});

function selectInvoice(invoiceId) {
    const invoiceSelect = document.getElementById('invoice_select');
    if (invoiceSelect) {
        invoiceSelect.value = invoiceId;
    }
    showTab('payments');
    // Scroll to the payment form
    const paymentForm = document.querySelector('#payments-tab .card:nth-child(2)');
    if (paymentForm) {
        paymentForm.scrollIntoView({ behavior: 'smooth' });
    }
}
</script>
@endsection
