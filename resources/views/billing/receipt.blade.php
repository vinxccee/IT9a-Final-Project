<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Receipt - Grand Azure Hotel</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Times New Roman', serif;
            line-height: 1.4;
            color: #333;
            background: #f3f0ea;
            padding: 24px;
        }
        .receipt-container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            box-shadow: 0 12px 32px rgba(0,0,0,0.12);
        }
        .receipt-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 30px;
            border-bottom: 2px solid #333;
        }
        .hotel-logo {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .logo-icon {
            width: 90px;
            height: 90px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, rgba(196, 138, 58, 0.08), rgba(47, 124, 120, 0.06));
            border-radius: 16px;
            border: 1.5px solid rgba(196, 138, 58, 0.2);
        }
        .logo-icon img {
            max-width: 95%;
            max-height: 95%;
            object-fit: contain;
        }
        .hotel-name h1 {
            font-family: 'Cormorant Garamond', serif;
            font-size: 28px;
            color: #c48a3a;
            margin: 0;
        }
        .hotel-name p {
            font-size: 12px;
            color: #666;
            margin: 2px 0;
        }
        .receipt-title {
            text-align: right;
        }
        .receipt-title h2 {
            font-family: 'Cormorant Garamond', serif;
            font-size: 20px;
            color: #333;
            margin: 0;
        }
        .receipt-title p {
            font-size: 12px;
            margin: 5px 0;
        }
        .receipt-body {
            padding: 30px;
        }
        .section-title {
            font-family: 'Cormorant Garamond', serif;
            font-size: 16px;
            color: #c48a3a;
            margin: 20px 0 10px 0;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }
        .info-table {
            width: 100%;
            margin-bottom: 20px;
        }
        .info-table td {
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }
        .info-table td:first-child {
            width: 40%;
            font-weight: bold;
        }
        .total-row {
            border-top: 2px solid #333 !important;
            font-weight: bold;
        }
        .receipt-footer {
            margin-top: 30px;
            text-align: center;
            border-top: 2px solid #333;
            padding-top: 20px;
        }
        .thank-you p {
            margin: 5px 0;
            font-size: 14px;
        }
        .receipt-notice {
            margin-top: 15px;
            font-size: 11px;
            color: #666;
        }
        @media print {
            body { padding: 0; background: white; }
            .receipt-container { box-shadow: none; }
        }
    </style>
</head>
<body>
    <div class="receipt-container">
        <div class="receipt-header">
            <div class="hotel-logo">
                <div class="logo-icon">
                    <img src="{{ asset('images/logo.png') }}" alt="Grand Azure Hotel Logo">
                </div>
                <div class="hotel-name">
                    <h1>Grand Azure Hotel</h1>
                    <p>123 Luxury Avenue, Paradise City</p>
                    <p>Phone: (555) 123-4567 | Email: reservations@grandazure.com</p>
                </div>
            </div>
            <div class="receipt-title">
                <h2>ELECTRONIC RECEIPT</h2>
                <p>Receipt #: RCP-{{ str_pad($payment->id, 6, '0', STR_PAD_LEFT) }}</p>
                <p>Date: {{ $payment->payment_date->format('F d, Y') }}</p>
                <p>Time: {{ $payment->payment_date->format('H:i:s') }}</p>
            </div>
        </div>

        <div class="receipt-body">
            <h3 class="section-title">Customer Information</h3>
            <table class="info-table">
                <tr>
                    <td>Name:</td>
                    <td>{{ $payment->invoice->booking->guest?->full_name ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td>Email:</td>
                    <td>{{ $payment->invoice->booking->guest?->email ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td>Phone:</td>
                    <td>{{ $payment->invoice->booking->guest?->phone ?? 'N/A' }}</td>
                </tr>
            </table>

            <h3 class="section-title">Payment Details</h3>
            <table class="info-table">
                <tr>
                    <td>Invoice Number:</td>
                    <td>#{{ $payment->invoice->display_number }}</td>
                </tr>
                <tr>
                    <td>Room:</td>
                    <td>{{ $payment->invoice->booking->room->room_number }} - {{ $payment->invoice->booking->room->roomType->name }}</td>
                </tr>
                <tr>
                    <td>Check-in:</td>
                    <td>{{ $payment->invoice->booking->check_in->format('M d, Y') }}</td>
                </tr>
                <tr>
                    <td>Check-out:</td>
                    <td>{{ $payment->invoice->booking->check_out->format('M d, Y') }}</td>
                </tr>
                <tr>
                    <td>Payment Method:</td>
                    <td>{{ str($payment->payment_method)->replace('_', ' ')->title() }}</td>
                </tr>
                @if($payment->transaction_id)
                <tr>
                    <td>Transaction ID:</td>
                    <td>{{ $payment->transaction_id }}</td>
                </tr>
                @endif
                <tr class="total-row">
                    <td>Amount Paid:</td>
                    <td>P{{ number_format($payment->amount, 2) }}</td>
                </tr>
            </table>

            <h3 class="section-title">Invoice Summary</h3>
            <table class="info-table">
                <tr>
                    <td>Total Invoice Amount:</td>
                    <td>P{{ number_format($payment->invoice->total_amount, 2) }}</td>
                </tr>
                <tr>
                    <td>Previously Paid:</td>
                    <td>P{{ number_format($payment->invoice->paid_amount - $payment->amount, 2) }}</td>
                </tr>
                <tr>
                    <td>Payment Amount:</td>
                    <td>P{{ number_format($payment->amount, 2) }}</td>
                </tr>
                <tr class="total-row">
                    <td>Outstanding Balance:</td>
                    <td>P{{ number_format($payment->invoice->balance, 2) }}</td>
                </tr>
            </table>

            @if($payment->notes)
            <h3 class="section-title">Notes</h3>
            <p>{{ $payment->notes }}</p>
            @endif
        </div>

        <div class="receipt-footer">
            <div class="thank-you">
                <p><strong>Thank you for choosing Grand Azure Hotel!</strong></p>
                <p>We appreciate your business and hope you enjoyed your stay.</p>
            </div>
            <div class="receipt-notice">
                <p><em>This is an electronically generated receipt.</em></p>
                <p><em>For any questions, please contact our billing department.</em></p>
            </div>
        </div>
    </div>

    <script>
        // Auto-print when loaded for printing
        if (window.location.search.includes('print')) {
            window.print();
        }
    </script>
</body>
</html>
