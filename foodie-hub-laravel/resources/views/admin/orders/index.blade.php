@extends('layouts.admin')

@section('content')
<div class="bg-white p-6 rounded shadow">
    <h2 class="text-xl font-semibold mb-4">Customer Orders</h2>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b bg-gray-50">
                    <th class="p-3">Order ID</th>
                    <th class="p-3">Customer</th>
                    <th class="p-3">Items</th>
                    <th class="p-3">Total</th>
                    <th class="p-3">Address</th>
                    <th class="p-3">Status</th>
                    <th class="p-3">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                <tr class="border-b hover:bg-gray-50">
                    <td class="p-3">#{{ $order->id }}</td>
                    <td class="p-3">
                        {{ $order->user->name }}<br>
                        <span class="text-xs text-gray-500">{{ $order->user->email }}</span>
                    </td>
                    <td class="p-3">
                        <ul class="text-sm list-disc pl-4">
                            @foreach($order->items as $item)
                                <li>{{ $item->food->name }} x {{ $item->quantity }}</li>
                            @endforeach
                        </ul>
                    </td>
                    <td class="p-3 font-bold">${{ $order->total_amount }}</td>
                    <td class="p-3 text-sm max-w-xs">{{ $order->shipping_address }}</td>
                    <td class="p-3">
                        <span class="px-2 py-1 rounded text-xs font-bold 
                            {{ $order->status == 'placed' ? 'bg-blue-100 text-blue-800' : '' }}
                            {{ $order->status == 'preparing' ? 'bg-yellow-100 text-yellow-800' : '' }}
                            {{ $order->status == 'ready' ? 'bg-purple-100 text-purple-800' : '' }}
                            {{ $order->status == 'delivered' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $order->status == 'cancelled' ? 'bg-red-100 text-red-800' : '' }}
                        ">
                            {{ ucfirst($order->status) }}
                        </span>
                    </td>
                    <td class="p-3">
                        <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST" class="flex gap-2">
                            @csrf @method('PATCH')
                            <select name="status" class="border rounded text-sm p-1">
                                <option value="placed" {{ $order->status == 'placed' ? 'selected' : '' }}>Placed</option>
                                <option value="preparing" {{ $order->status == 'preparing' ? 'selected' : '' }}>Preparing</option>
                                <option value="ready" {{ $order->status == 'ready' ? 'selected' : '' }}>Ready</option>
                                <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                            <button type="submit" class="bg-gray-800 text-white text-xs px-2 py-1 rounded">Update</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
