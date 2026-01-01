@extends('layouts.email')

@section('title', 'Order Received')
@section('subtitle', 'Your food is on its way!')

@section('content')
    <div class="info-box">
        <p><strong>Hi {{ $order->user->name }},</strong></p>
        <p>Thank you for your order! We have received your order <strong>#{{ $order->id }}</strong> and it is currently <span style="background-color: #fef3c7; color: #b45309; padding: 2px 6px; border-radius: 4px; font-weight: bold;">{{ ucfirst($order->status) }}</span>.</p>
    </div>

    <h3>🛒 Order Summary</h3>
    <table style="width: 100%; border-collapse: collapse; margin-top: 10px;">
        <thead style="background-color: #f9fafb;">
            <tr>
                <th style="padding: 10px; text-align: left; border-bottom: 2px solid #eee;">Item</th>
                <th style="padding: 10px; text-align: right; border-bottom: 2px solid #eee;">Price</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
            <tr>
                <td style="padding: 10px; border-bottom: 1px solid #eee;">
                    {{ $item->food->name }} <br>
                    <span style="font-size: 12px; color: #6b7280;">Quantity: {{ $item->quantity }}</span>
                </td>
                <td style="padding: 10px; border-bottom: 1px solid #eee; text-align: right;">
                    ${{ number_format($item->price, 2) }}
                </td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td style="padding: 15px 10px; font-weight: bold; text-align: right;">Total Amount</td>
                <td style="padding: 15px 10px; font-weight: bold; text-align: right; color: #ea580c; font-size: 18px;">
                    ${{ number_format($order->total_amount, 2) }}
                </td>
            </tr>
        </tfoot>
    </table>

    <div style="margin-top: 30px; text-align: center;">
        <p>We will notify you once your food is ready!</p>
        <a href="{{ route('my-orders') }}" class="btn">Track Order</a>
    </div>
@endsection
