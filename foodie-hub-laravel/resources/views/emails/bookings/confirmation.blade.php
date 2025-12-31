<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #f97316 0%, #ea580c 100%); color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
        .content { background: #fff; padding: 30px; border: 1px solid #e5e7eb; }
        .booking-details { background: #f9fafb; padding: 20px; border-radius: 8px; margin: 20px 0; }
        .detail-row { display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #e5e7eb; }
        .detail-label { font-weight: bold; color: #6b7280; }
        .detail-value { color: #111827; }
        .food-section { margin-top: 20px; padding: 20px; background: #fef3c7; border-radius: 8px; border-left: 4px solid #f97316; }
        .food-item { padding: 10px 0; border-bottom: 1px dashed #e5e7eb; }
        .footer { background: #1f2937; color: #9ca3af; text-align: center; padding: 20px; border-radius: 0 0 10px 10px; }
        .badge { display: inline-block; padding: 5px 15px; border-radius: 20px; font-size: 12px; font-weight: bold; }
        .badge-pending { background: #fef3c7; color: #92400e; }
        .badge-confirmed { background: #d1fae5; color: #065f46; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 style="margin: 0; font-size: 28px;">🍽️ Food Court</h1>
            <p style="margin: 10px 0 0 0; font-size: 16px;">Table Reservation Confirmation</p>
        </div>
        
        <div class="content">
            <h2 style="color: #f97316; margin-top: 0;">Hello {{ $booking->name }}! 👋</h2>
            
            @if($booking->status === 'confirmed')
                <p style="font-size: 16px; color: #059669;">
                    ✅ Your table booking has been <strong>CONFIRMED</strong>! We're excited to serve you.
                </p>
            @else
                <p style="font-size: 16px;">
                    Thank you for booking a table with us! Your reservation is currently 
                    <span class="badge badge-pending">PENDING</span> and will be confirmed shortly.
                </p>
            @endif

            <div class="booking-details">
                <h3 style="margin-top: 0; color: #1f2937;">📋 Booking Details</h3>
                
                <div class="detail-row">
                    <span class="detail-label">Booking Date:</span>
                    <span class="detail-value">{{ $booking->booking_date->format('l, d F Y') }}</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Booking Time:</span>
                    <span class="detail-value">{{ date('g:i A', strtotime($booking->booking_time)) }}</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Number of Guests:</span>
                    <span class="detail-value">{{ $booking->guests }} People</span>
                </div>
                
                @if($booking->table_number)
                <div class="detail-row">
                    <span class="detail-label">Table Number:</span>
                    <span class="detail-value" style="color: #f97316; font-weight: bold;">Table #{{ $booking->table_number }}</span>
                </div>
                @endif
                
                <div class="detail-row" style="border-bottom: none;">
                    <span class="detail-label">Contact:</span>
                    <span class="detail-value">{{ $booking->phone }}</span>
                </div>
            </div>

            @if($booking->table_type || $booking->seating_preference || $booking->window_side || $booking->occasion)
            <div style="margin: 20px 0; padding: 15px; background: #eff6ff; border-radius: 8px;">
                <h4 style="margin: 0 0 10px 0; color: #1e40af;">🎯 Your Preferences</h4>
                <ul style="margin: 0; padding-left: 20px;">
                    @if($booking->table_type)
                        <li>Table Type: <strong>{{ ucfirst($booking->table_type) }}</strong></li>
                    @endif
                    @if($booking->seating_preference)
                        <li>Seating: <strong>{{ strtoupper($booking->seating_preference) }}</strong></li>
                    @endif
                    @if($booking->window_side)
                        <li>Window Side: <strong>Yes</strong></li>
                    @endif
                    @if($booking->occasion)
                        <li>Occasion: <strong>{{ ucfirst($booking->occasion) }}</strong> 🎉</li>
                    @endif
                </ul>
            </div>
            @endif

            @if($booking->special_requests)
            <div style="margin: 20px 0; padding: 15px; background: #fef3c7; border-radius: 8px;">
                <h4 style="margin: 0 0 10px 0; color: #92400e;">📝 Special Requests</h4>
                <p style="margin: 0;">{{ $booking->special_requests }}</p>
            </div>
            @endif

            @if($booking->foodOrders && $booking->foodOrders->count() > 0)
            <div class="food-section">
                <h3 style="margin: 0 0 15px 0; color: #92400e;">🍕 Pre-Ordered Food (DINE-IN)</h3>
                <p style="margin: 0 0 15px 0; font-size: 14px;">
                    Your food will be freshly prepared and served at your table when you arrive!
                </p>
                
                @foreach($booking->foodOrders as $order)
                <div class="food-item">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <strong style="color: #1f2937; font-size: 15px;">{{ $order->food->name }}</strong>
                            <span style="color: #6b7280; font-size: 14px;"> × {{ $order->quantity }}</span>
                            @if($order->cooking_note)
                                <br><span style="color: #92400e; font-size: 13px;">Note: {{ $order->cooking_note }}</span>
                            @endif
                        </div>
                        <div style="color: #059669; font-weight: bold;">
                            ₹{{ $order->food->price * $order->quantity }}
                        </div>
                    </div>
                </div>
                @endforeach
                
                <div style="margin-top: 15px; padding-top: 15px; border-top: 2px solid #f97316; text-align: right;">
                    <strong style="font-size: 18px; color: #1f2937;">
                        Total: ₹{{ $booking->foodOrders->sum(function($order) { return $order->food->price * $order->quantity; }) }}
                    </strong>
                </div>
            </div>
            @endif

            <div style="margin: 30px 0; padding: 20px; background: #f0fdf4; border-left: 4px solid #10b981; border-radius: 4px;">
                <h4 style="margin: 0 0 10px 0; color: #065f46;">💚 Thank You for Choosing Food Court!</h4>
                <p style="margin: 0; color: #047857; font-size: 14px;">
                    We look forward to serving you delicious food and providing an excellent dining experience.
                    If you have any questions, feel free to contact us.
                </p>
            </div>

            <div style="text-align: center; margin: 20px 0;">
                <p style="color: #6b7280;">Need to make changes?</p>
                <a href="{{ route('my.bookings') }}" style="display: inline-block; padding: 12px 30px; background: #f97316; color: white; text-decoration: none; border-radius: 6px; font-weight: bold;">
                    View My Bookings
                </a>
            </div>
        </div>
        
        <div class="footer">
            <p style="margin: 0; font-size: 14px;">Food Court Restaurant</p>
            <p style="margin: 5px 0; font-size: 12px;">Serving delicious meals with love 🧡</p>
            <p style="margin: 10px 0 0 0; font-size: 11px;">
                © {{ date('Y') }} Food Court. All rights reserved.
            </p>
        </div>
    </div>
</body>
</html>
