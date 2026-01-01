@extends('layouts.email')

@section('title', 'Booking Confirmation')
@section('subtitle', 'Your table is ready!')

@section('content')
    <h2 style="text-align: center;">Hello {{ $booking->name }}! 👋</h2>
    
    @if($booking->status === 'confirmed')
        <div class="info-box" style="border-left: 4px solid #10b981; background-color: #ecfdf5;">
            <p style="color: #047857; margin: 0;">✅ Your table booking has been <strong>CONFIRMED</strong>! We look forward to seeing you.</p>
        </div>
    @else
        <div class="info-box" style="border-left: 4px solid #f59e0b; background-color: #fffbeb;">
            <p style="color: #b45309; margin: 0;">⏳ Your reservation is currently <strong>PENDING</strong> and will be confirmed shortly.</p>
        </div>
    @endif

    <h3>📅 Booking Details</h3>
    <table style="width: 100%; border-collapse: collapse;">
        <tr>
            <td style="padding: 8px 0; border-bottom: 1px solid #eee;"><strong>Date & Time:</strong></td>
            <td style="padding: 8px 0; border-bottom: 1px solid #eee; text-align: right;">{{ $booking->booking_date->format('d M Y') }} at {{ date('g:i A', strtotime($booking->booking_time)) }}</td>
        </tr>
        <tr>
            <td style="padding: 8px 0; border-bottom: 1px solid #eee;"><strong>Guests:</strong></td>
            <td style="padding: 8px 0; border-bottom: 1px solid #eee; text-align: right;">{{ $booking->guests }} People</td>
        </tr>
         @if($booking->table_number)
        <tr>
            <td style="padding: 8px 0; border-bottom: 1px solid #eee;"><strong>Table Number:</strong></td>
            <td style="padding: 8px 0; border-bottom: 1px solid #eee; text-align: right; color: #ea580c; font-weight: bold;">#{{ $booking->table_number }}</td>
        </tr>
        @endif
    </table>

    @if($booking->foodOrders && $booking->foodOrders->count() > 0)
        <div style="margin-top: 30px;">
            <h3>🍕 Pre-Ordered Items</h3>
            <table style="width: 100%; border-collapse: collapse;">
                @foreach($booking->foodOrders as $order)
                <tr>
                    <td style="padding: 8px 0; border-bottom: 1px dashed #eee;">{{ $order->food->name }} <span style="font-size: 12px; color: #666;">x{{ $order->quantity }}</span></td>
                    <td style="padding: 8px 0; border-bottom: 1px dashed #eee; text-align: right;">₹{{ number_format($order->food->price * $order->quantity, 2) }}</td>
                </tr>
                @endforeach
                <tr>
                    <td style="padding: 10px 0; font-weight: bold;">Total Pre-order Cost</td>
                    <td style="padding: 10px 0; text-align: right; font-weight: bold; color: #ea580c;">₹{{ number_format($booking->foodOrders->sum(function($o){ return $o->food->price * $o->quantity; }), 2) }}</td>
                </tr>
            </table>
        </div>
    @endif

    <div style="text-align: center; margin-top: 30px;">
        <a href="{{ route('my.bookings') }}" class="btn">View My Bookings</a>
    </div>
@endsection
