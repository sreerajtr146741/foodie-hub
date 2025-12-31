@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Back Link -->
    <div class="mb-6">
        <a href="{{ route('my-orders') }}" class="text-gray-600 hover:text-orange-600 flex items-center">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to My Orders
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <!-- Header -->
        <div class="bg-gray-50 px-8 py-6 border-b border-gray-200 flex justify-between items-center flex-wrap gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Order #{{ $order->id }}</h1>
                <p class="text-sm text-gray-500">Placed on {{ $order->created_at->format('d M Y, h:i A') }}</p>
            </div>
            <div class="flex items-center gap-4">
                @if($order->status === 'delivered')
                    <a href="{{ route('orders.invoice', $order) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Download Invoice
                    </a>
                @endif

                @if($order->status == 'placed')
                    <form action="{{ route('orders.cancel', $order) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel this order?');">
                        @csrf
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-100 text-red-700 text-sm font-medium rounded-md hover:bg-red-200">
                            Cancel Order
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <!-- Tracking Progress -->
        <div class="p-8 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900 mb-6">Order Status</h2>
            <div class="relative">
                <div class="overflow-hidden h-2 mb-4 text-xs flex rounded bg-gray-200">
                    @php
                        $width = '0%';
                        $color = 'bg-orange-500';
                        if($order->status == 'placed') $width = '25%';
                        elseif($order->status == 'processing') $width = '50%';
                        elseif($order->status == 'ready') $width = '75%';
                        elseif($order->status == 'delivered') { $width = '100%'; $color = 'bg-green-500'; }
                        elseif($order->status == 'cancelled') { $width = '100%'; $color = 'bg-red-500'; }
                    @endphp
                    <div style="width: {{ $width }}" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center {{ $color }} transition-all duration-500"></div>
                </div>
                <div class="grid grid-cols-4 text-xs sm:text-sm font-medium text-gray-600 text-center">
                    <div class="{{ in_array($order->status, ['placed', 'processing', 'ready', 'delivered']) ? 'text-orange-600 font-bold' : '' }}">Order Placed</div>
                    <div class="{{ in_array($order->status, ['processing', 'ready', 'delivered']) ? 'text-orange-600 font-bold' : '' }}">Processing</div>
                    <div class="{{ in_array($order->status, ['ready', 'delivered']) ? 'text-orange-600 font-bold' : '' }}">Ready</div>
                    <div class="{{ $order->status == 'delivered' ? 'text-green-600 font-bold' : '' }}">Delivered</div>
                </div>
                @if($order->status == 'cancelled')
                    <div class="mt-4 text-center">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                            🚫 Order Cancelled
                        </span>
                    </div>
                @endif
            </div>
        </div>

        <div class="p-8 grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Order Items -->
            <div class="md:col-span-2 space-y-6">
                <h3 class="text-xl font-bold text-gray-900 mb-4">Items Details</h3>
                @foreach($order->items as $item)
                    <div class="flex gap-4 border-b border-gray-100 pb-4 last:border-0 hover:bg-gray-50 p-2 rounded transition">
                        <img src="{{ $item->food->image ? asset('storage/' . $item->food->image) : 'https://placehold.co/100x100' }}" alt="{{ $item->food->name }}" class="w-20 h-20 object-cover rounded-lg">
                        <div class="flex-1">
                            <h4 class="font-semibold text-gray-900">{{ $item->food->name }}</h4>
                            <p class="text-sm text-gray-500">{{ $item->food->category->name ?? 'Category' }}</p>
                            <div class="flex justify-between items-center mt-2">
                                <span class="text-sm text-gray-600">Qty: {{ $item->quantity }} x ₹{{ $item->price }}</span>
                                <span class="font-bold text-gray-900">₹{{ $item->price * $item->quantity }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
                
                <div class="flex justify-between items-center pt-4 border-t border-gray-200">
                    <span class="text-lg font-bold text-gray-900">Total Amount</span>
                    <span class="text-2xl font-bold text-orange-600">₹{{ $order->total_price }}</span>
                </div>
            </div>

            <!-- Shipping Info -->
            <div class="bg-gray-50 p-6 rounded-lg h-fit">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Delivery Information</h3>
                
                <div class="mb-4">
                    <label class="block text-xs font-semibold text-gray-500 uppercase">Customer Name</label>
                    <p class="text-gray-900">{{ Auth::user()->name }}</p>
                </div>

                <div class="mb-4">
                    <label class="block text-xs font-semibold text-gray-500 uppercase">Shipping Address</label>
                    <p class="text-gray-900">{{ $order->shipping_address }}</p>
                </div>

                <div class="mb-4">
                    <label class="block text-xs font-semibold text-gray-500 uppercase">Phone</label>
                    <p class="text-gray-900">{{ Auth::user()->phone ?? 'N/A' }}</p>
                </div>
                
                <div class="pt-4 border-t border-gray-200">
                    <label class="block text-xs font-semibold text-gray-500 uppercase">Payment Method</label>
                    <p class="text-gray-900">Cash on Delivery</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
