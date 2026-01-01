@extends('layouts.email')

@section('title', 'Your Invoice')
@section('subtitle', 'Thank you for dining with us!')

@section('content')
    <div class="info-box">
        <p><strong>Hi {{ $booking->name }},</strong></p>
        <p>We hope you enjoyed your meal. Here is the summary of your invoice. A PDF copy is attached for your records.</p>
    </div>

    <table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
        <tr>
            <td style="padding: 10px; border-bottom: 1px solid #ddd;"><strong>Invoice Number:</strong></td>
            <td style="padding: 10px; border-bottom: 1px solid #ddd; text-align: right;">INV-{{ $booking->id }}</td>
        </tr>
        <tr>
            <td style="padding: 10px; border-bottom: 1px solid #ddd;"><strong>Date:</strong></td>
            <td style="padding: 10px; border-bottom: 1px solid #ddd; text-align: right;">{{ now()->format('d M Y') }}</td>
        </tr>
        <tr>
            <td style="padding: 10px; border-bottom: 1px solid #ddd;"><strong>Total Amount:</strong></td>
            <td style="padding: 10px; border-bottom: 1px solid #ddd; text-align: right; color: #ea580c; font-weight: bold;">
                ₹{{ number_format($booking->foodOrders->sum(function($order) { return $order->food->price * $order->quantity; }), 2) }}
            </td>
        </tr>
    </table>

    <div style="text-align: center; margin-top: 30px;">
        <p>Thank you for your business!</p>
    </div>
@endsection
