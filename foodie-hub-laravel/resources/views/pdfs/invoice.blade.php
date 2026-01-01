<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Invoice #{{ $booking->id }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; color: #333; line-height: 1.4; font-size: 14px; }
        .header { border-bottom: 2px solid #ddd; margin-bottom: 30px; padding-bottom: 10px; }
        .logo { font-size: 24px; font-weight: bold; color: #ea580c; }
        .invoice-info { float: right; text-align: right; }
        .billing-to { margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 30px; }
        th { background: #f3f4f6; padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }
        td { padding: 10px; border-bottom: 1px solid #eee; }
        .totals { margin-top: 30px; float: right; width: 300px; }
        .totals-row { display: flex; justify-content: space-between; padding: 5px 0; font-weight: bold; }
        .footer { position: fixed; bottom: 0; left: 0; right: 0; text-align: center; color: #999; font-size: 12px; border-top: 1px solid #eee; padding-top: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <div class="invoice-info">
            <strong>Invoice #:</strong> INV-{{ $booking->id }}<br>
            <strong>Date:</strong> {{ now()->format('d M Y') }}
        </div>
        <div class="logo">Foodie Hub</div>
    </div>

    <div class="billing-to">
        <strong>Bill To:</strong><br>
        {{ $booking->name }}<br>
        {{ $booking->email }}<br>
        {{ $booking->phone }}
    </div>

    <div class="booking-details">
        <strong>Booking Details:</strong><br>
        Date: {{ $booking->booking_date->format('d M Y') }} at {{ date('g:i A', strtotime($booking->booking_time)) }}<br>
        Guests: {{ $booking->guests }}<br>
        Table: {{ $booking->table_number ?? 'N/A' }}
    </div>

    <table>
        <thead>
            <tr>
                <th>Description</th>
                <th style="text-align: center;">Qty</th>
                <th style="text-align: right;">Price</th>
                <th style="text-align: right;">Total</th>
            </tr>
        </thead>
        <tbody>
            @if($booking->foodOrders && $booking->foodOrders->count() > 0)
                @foreach($booking->foodOrders as $order)
                <tr>
                    <td>{{ $order->food->name }}</td>
                    <td style="text-align: center;">{{ $order->quantity }}</td>
                    <td style="text-align: right;">₹{{ number_format($order->food->price, 2) }}</td>
                    <td style="text-align: right;">₹{{ number_format($order->food->price * $order->quantity, 2) }}</td>
                </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="4" style="text-align: center;">No food items ordered for this booking.</td>
                </tr>
            @endif
        </tbody>
    </table>

    <div class="totals" style="text-align: right;">
        <p><strong>Grand Total: ₹{{ number_format($booking->foodOrders->sum(function($order) { return $order->food->price * $order->quantity; }), 2) }}</strong></p>
    </div>

    <div class="footer">
        Thank you for dining with us! | Foodie Hub Restaurant
    </div>
</body>
</html>
