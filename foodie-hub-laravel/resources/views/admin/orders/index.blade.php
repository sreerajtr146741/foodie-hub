@extends('layouts.admin')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-3xl font-bold text-gray-900">Direct Food Entry & Order Management</h2>
            <p class="text-gray-600 mt-1">Manage customer orders, update statuses, and add food items directly</p>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if($orders->isEmpty())
        <div class="bg-white shadow rounded-lg p-12 text-center">
            <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
            </svg>
            <h3 class="mt-4 text-lg font-medium text-gray-900">No orders found</h3>
            <p class="mt-2 text-gray-500">Orders will appear here when customers place them or when you create them.</p>
        </div>
    @else
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Order Details</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Items</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($orders as $order)
                        <tr class="hover:bg-gray-50" x-data="{ expanded: false }">
                            <td class="px-6 py-4">
                                <div class="text-sm font-bold text-gray-900">#ORD-{{ $order->id }}</div>
                                <div class="text-xs text-gray-500">{{ $order->created_at->format('d M Y, h:i A') }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">{{ $order->user->name }}</div>
                                <div class="text-xs text-gray-500">{{ $order->user->email }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <button @click="expanded = !expanded" class="text-sm text-blue-600 hover:text-blue-800 flex items-center">
                                    <svg class="h-4 w-4 mr-1 transition-transform" :class="expanded ? 'rotate-90' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                    {{ $order->items->count() }} Items
                                </button>
                            </td>
                            <td class="px-6 py-4 font-bold text-orange-600">
                                ₹{{ $order->total_price }}
                            </td>
                            <td class="px-6 py-4">
                                <form method="POST" action="{{ route('admin.orders.updateStatus', $order) }}">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status" onchange="this.form.submit()" class="text-xs rounded px-2 py-1 border font-medium
                                        @if($order->status === 'placed') bg-blue-100 text-blue-800 border-blue-300
                                        @elseif($order->status === 'preparing') bg-yellow-100 text-yellow-800 border-yellow-300
                                        @elseif($order->status === 'ready') bg-purple-100 text-purple-800 border-purple-300
                                        @elseif($order->status === 'delivered') bg-green-100 text-green-800 border-green-300
                                        @else bg-red-100 text-red-800 border-red-300 @endif">
                                        <option value="placed" @if($order->status === 'placed') selected @endif>Placed</option>
                                        <option value="preparing" @if($order->status === 'preparing') selected @endif>Preparing</option>
                                        <option value="ready" @if($order->status === 'ready') selected @endif>Ready</option>
                                        <option value="delivered" @if($order->status === 'delivered') selected @endif>Delivered</option>
                                        <option value="cancelled" @if($order->status === 'cancelled') selected @endif>Cancelled</option>
                                    </select>
                                </form>
                            </td>
                            <td class="px-6 py-4">
                                <button @click="expanded = !expanded" class="text-xs bg-gray-100 text-gray-800 px-3 py-1 rounded hover:bg-gray-200">
                                    View/Edit Items
                                </button>
                            </td>
                        </tr>

                        <!-- Expanded Row for Direct Food Entry -->
                        <tr x-show="expanded" class="bg-gray-50">
                            <td colspan="6" class="px-6 py-4 border-b">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- Current Items List -->
                                    <div>
                                        <h4 class="text-sm font-semibold text-gray-900 mb-3 border-b pb-2">Order Items</h4>
                                        <div class="space-y-2">
                                            @foreach($order->items as $item)
                                                <div class="flex items-center justify-between bg-white p-3 rounded border shadow-sm">
                                                    <div>
                                                        <p class="text-sm font-medium text-gray-900">{{ $item->food->name }}</p>
                                                        <p class="text-xs text-gray-500">₹{{ $item->price }} × {{ $item->quantity }} = <span class="font-bold text-gray-700">₹{{ $item->price * $item->quantity }}</span></p>
                                                    </div>
                                                    <form action="{{ route('admin.orders.removeItem', [$order->id, $item->id]) }}" method="POST" onsubmit="return confirm('Remove this item?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-500 hover:text-red-700">
                                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                            </svg>
                                                        </button>
                                                    </form>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    <!-- Direct Entry Form -->
                                    <div>
                                        <h4 class="text-sm font-semibold text-gray-900 mb-3 border-b pb-2 text-blue-600">Quick Add Item</h4>
                                        <form action="{{ route('admin.orders.addItem', $order->id) }}" method="POST" class="bg-white p-4 rounded border shadow-sm">
                                            @csrf
                                            <div class="space-y-4">
                                                <div>
                                                    <label class="block text-xs font-medium text-gray-700 mb-1">Select Food Item</label>
                                                    <select name="food_id" required class="w-full text-sm border rounded p-2 focus:ring-blue-500 focus:border-blue-500">
                                                        <option value="">Choose a dish...</option>
                                                        @foreach($foods as $food)
                                                            <option value="{{ $food->id }}">{{ $food->name }} - ₹{{ $food->price }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="grid grid-cols-2 gap-4">
                                                    <div>
                                                        <label class="block text-xs font-medium text-gray-700 mb-1">Quantity</label>
                                                        <input type="number" name="quantity" value="1" min="1" class="w-full text-sm border rounded p-2">
                                                    </div>
                                                    <div class="flex items-end">
                                                        <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded text-sm hover:bg-blue-700 transition font-bold shadow-md">
                                                            Add to Order
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>

                                        <div class="mt-4 p-3 bg-orange-50 border border-orange-200 rounded text-orange-800 text-xs">
                                            <strong>Note:</strong> Total price will be automatically updated to <strong>₹{{ $order->total_price }}</strong> after adding/removing items.
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
