@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h1 class="text-3xl font-extrabold text-gray-900 mb-8">My Orders</h1>

    @if($orders->isEmpty())
        <div class="text-center py-12 bg-white rounded-lg shadow">
            <p class="text-gray-500 text-lg mb-4">You haven't placed any orders yet.</p>
            <a href="{{ route('menu') }}" class="text-orange-600 hover:text-orange-500 font-medium">Start Ordering</a>
        </div>
    @else
        <div class="space-y-6">
            @foreach($orders as $order)
            <div class="bg-white shadow overflow-hidden sm:rounded-lg border border-gray-200 transition hover:shadow-md">
                <div class="px-4 py-5 sm:px-6 bg-gray-50 flex justify-between items-center">
                    <div>
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            <a href="{{ route('orders.show', $order) }}" class="hover:text-orange-600">Order #{{ $order->id }}</a>
                        </h3>
                        <p class="mt-1 max-w-2xl text-sm text-gray-500">Placed on {{ $order->created_at->format('M d, Y') }}</p>
                    </div>
                    <div class="flex items-center gap-4">
                        <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide
                                {{ $order->status == 'placed' ? 'bg-blue-100 text-blue-800' : '' }}
                                {{ $order->status == 'processing' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $order->status == 'ready' ? 'bg-purple-100 text-purple-800' : '' }}
                                {{ $order->status == 'delivered' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $order->status == 'cancelled' ? 'bg-red-100 text-red-800' : '' }}
                            ">
                            {{ ucfirst($order->status) }}
                        </span>
                        <a href="{{ route('orders.show', $order) }}" class="text-sm font-medium text-orange-600 hover:text-orange-900">
                            View Details &rarr;
                        </a>
                    </div>
                </div>
                <div class="border-t border-gray-200 cursor-pointer" onclick="window.location='{{ route('orders.show', $order) }}'">
                    <ul class="divide-y divide-gray-200">
                        @foreach($order->items as $item)
                        <li class="px-4 py-4 sm:px-6 flex justify-between">
                            <div class="flex items-center">
                                <span class="font-medium text-gray-900">{{ $item->food->name }}</span>
                                <span class="ml-2 text-gray-500 text-sm">x {{ $item->quantity }}</span>
                            </div>
                            <span class="text-gray-900">₹{{ $item->price * $item->quantity }}</span>
                        </li>
                        @endforeach
                        <li class="px-4 py-4 sm:px-6 flex justify-between bg-gray-50">
                            <span class="font-bold text-gray-900">Total Amount</span>
                            <span class="font-bold text-orange-600">₹{{ $order->total_price }}</span>
                        </li>
                    </ul>
                </div>
            </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
