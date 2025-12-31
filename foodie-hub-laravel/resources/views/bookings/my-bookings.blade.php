@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <div class="mb-6">
        <h2 class="text-3xl font-bold text-gray-900">My Table Bookings</h2>
        <p class="text-gray-600 mt-1">View and manage your restaurant reservations</p>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if($bookings->isEmpty())
        <div class="bg-white shadow rounded-lg p-12 text-center">
            <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            <h3 class="mt-4 text-lg font-medium text-gray-900">No bookings yet</h3>
            <p class="mt-2 text-gray-500">Book a table to see your reservations here.</p>
            <a href="{{ route('booking.create') }}" class="mt-4 inline-block px-6 py-2 bg-orange-600 text-white rounded-md hover:bg-orange-700">
                Book a Table
            </a>
        </div>
    @else
        <div class="space-y-4">
            @foreach($bookings as $booking)
                <div class="bg-white shadow rounded-lg overflow-hidden">
                    <div class="p-6">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <div class="flex items-center">
                                    <h3 class="text-lg font-semibold text-gray-900">{{ $booking->name }}</h3>
                                    @if($booking->status === 'pending')
                                        <span class="ml-3 px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                                    @elseif($booking->status === 'confirmed')
                                        <span class="ml-3 px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Confirmed</span>
                                    @elseif($booking->status === 'cancelled')
                                        <span class="ml-3 px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Cancelled</span>
                                    @else
                                        <span class="ml-3 px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">Completed</span>
                                    @endif
                                </div>

                                <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div>
                                        <p class="text-sm text-gray-500">Date & Time</p>
                                        <p class="text-sm font-medium text-gray-900">
                                            {{ $booking->booking_date->format('d M Y') }} at {{ date('g:i A', strtotime($booking->booking_time)) }}
                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500">Guests</p>
                                        <p class="text-sm font-medium text-gray-900">{{ $booking->guests }} People</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500">Phone</p>
                                        <p class="text-sm font-medium text-gray-900">{{ $booking->phone }}</p>
                                    </div>
                                </div>

                                @if($booking->table_number)
                                    <div class="mt-3">
                                        <p class="text-sm text-gray-500">Table Number</p>
                                        <p class="text-sm font-semibold text-orange-600">Table #{{ $booking->table_number }}</p>
                                    </div>
                                @endif

                                @if($booking->table_type || $booking->seating_preference || $booking->window_side)
                                    <div class="mt-3">
                                        <p class="text-sm text-gray-500">Preferences</p>
                                        <div class="flex flex-wrap gap-2 mt-1">
                                            @if($booking->table_type)
                                                <span class="px-2 py-1 text-xs bg-gray-100 text-gray-700 rounded">{{ ucfirst($booking->table_type) }}</span>
                                            @endif
                                            @if($booking->seating_preference)
                                                <span class="px-2 py-1 text-xs bg-gray-100 text-gray-700 rounded">{{ strtoupper($booking->seating_preference) }}</span>
                                            @endif
                                            @if($booking->window_side)
                                                <span class="px-2 py-1 text-xs bg-gray-100 text-gray-700 rounded">Window Side</span>
                                            @endif
                                        </div>
                                    </div>
                                @endif

                                @if($booking->occasion)
                                    <div class="mt-3">
                                        <p class="text-sm text-gray-500">Occasion</p>
                                        <p class="text-sm font-medium text-gray-900">{{ ucfirst($booking->occasion) }}</p>
                                    </div>
                                @endif

                                @if($booking->special_requests)
                                    <div class="mt-3">
                                        <p class="text-sm text-gray-500">Special Requests</p>
                                        <p class="text-sm text-gray-700">{{ $booking->special_requests }}</p>
                                    </div>
                                @endif

                                <!-- Food Pre-Orders -->
                                @if($booking->foodOrders->count() > 0)
                                    <div class="mt-4 border-t pt-4">
                                        <h4 class="text-sm font-semibold text-gray-900 mb-2 flex items-center">
                                            <svg class="h-4 w-4 mr-2 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                            </svg>
                                            Pre-Ordered Food (DINE-IN)
                                        </h4>
                                        <div class="space-y-2">
                                            @foreach($booking->foodOrders as $order)
                                                <div class="flex justify-between items-center bg-gray-50 p-3 rounded">
                                                    <div class="flex-1">
                                                        <p class="text-sm font-medium text-gray-900">{{ $order->food->name }} × {{ $order->quantity }}</p>
                                                        @if($order->cooking_note)
                                                            <p class="text-xs text-gray-500 mt-1">Note: {{ $order->cooking_note }}</p>
                                                        @endif
                                                    </div>
                                                    <span class="px-2 py-1 text-xs font-semibold rounded 
                                                        @if($order->food_status === 'waiting') bg-yellow-100 text-yellow-800
                                                        @elseif($order->food_status === 'preparing') bg-blue-100 text-blue-800
                                                        @else bg-green-100 text-green-800 @endif">
                                                        {{ ucfirst($order->food_status) }}
                                                    </span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <!-- Action Button -->
                            @if($booking->status === 'pending' || $booking->status === 'confirmed')
                                <div class="ml-4">
                                    <form method="POST" action="{{ route('booking.cancel', $booking->id) }}" onsubmit="return confirm('Are you sure you want to cancel this booking?');">
                                        @csrf
                                        <button type="submit" class="px-4 py-2 bg-red-600 text-white text-sm rounded-md hover:bg-red-700">
                                            Cancel Booking
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
