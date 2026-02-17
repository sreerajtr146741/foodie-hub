@extends('layouts.admin')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-3xl font-bold text-gray-900">Table Bookings Management</h2>
            <p class="text-gray-600 mt-1">Manage all restaurant table reservations and food pre-orders</p>
        </div>
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
            <p class="mt-2 text-gray-500">Bookings will appear here when customers make reservations.</p>
        </div>
    @else
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date & Time</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Guests</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Table</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Food Orders</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($bookings as $booking)
                        <tr class="hover:bg-gray-50" x-data="{ expanded: false }">
                            <td class="px-6 py-4">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $booking->name }}</p>
                                    <p class="text-sm text-gray-500">{{ $booking->phone }}</p>
                                    <p class="text-xs text-gray-400">{{ $booking->email }}</p>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm text-gray-900">{{ $booking->booking_date->format('d M Y') }}</p>
                                <p class="text-sm text-gray-500">{{ date('g:i A', strtotime($booking->booking_time)) }}</p>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                {{ $booking->guests }}
                            </td>
                            <td class="px-6 py-4">
                                <form method="POST" action="{{ route('admin.bookings.updateStatus', $booking->id) }}" class="flex items-center gap-2">
                                    @csrf
                                    @method('PATCH')
                                    <select name="table_number" onchange="this.form.submit()" class="w-24 px-2 py-1 border rounded text-xs">
                                        <option value="">Select Table</option>
                                        @foreach($availableTables as $table)
                                            <option value="{{ $table->table_number }}" {{ $booking->table_number == $table->table_number ? 'selected' : '' }}>
                                                {{ $table->table_number }} ({{ $table->type }})
                                            </option>
                                        @endforeach
                                        @if($booking->table_number && !$availableTables->contains('table_number', $booking->table_number))
                                            <option value="{{ $booking->table_number }}" selected>{{ $booking->table_number }}</option>
                                        @endif
                                    </select>
                                </form>
                            </td>
                            <td class="px-6 py-4">
                                @if($booking->foodOrders->count() > 0)
                                    <button @click="expanded = !expanded" class="text-sm text-orange-600 hover:text-orange-800 flex items-center">
                                        <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                        {{ $booking->foodOrders->count() }} items
                                    </button>
                                @else
                                    <span class="text-sm text-gray-400">No pre-order</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <form method="POST" action="{{ route('admin.bookings.updateStatus', $booking->id) }}">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status" onchange="this.form.submit()" class="text-sm rounded px-2 py-1 border
                                        @if($booking->status === 'pending') bg-yellow-100 text-yellow-800 border-yellow-300
                                        @elseif($booking->status === 'confirmed') bg-green-100 text-green-800 border-green-300
                                        @elseif($booking->status === 'cancelled') bg-red-100 text-red-800 border-red-300
                                        @else bg-blue-100 text-blue-800 border-blue-300 @endif">
                                        <option value="pending" @if($booking->status === 'pending') selected @endif>Pending</option>
                                        <option value="confirmed" @if($booking->status === 'confirmed') selected @endif>Confirmed</option>
                                        <option value="cancelled" @if($booking->status === 'cancelled') selected @endif>Cancelled</option>
                                        <option value="completed" @if($booking->status === 'completed') selected @endif>Completed</option>
                                    </select>
                                </form>
                            </td>
                            <td class="px-6 py-4">
                                <form method="POST" action="{{ route('admin.bookings.destroy', $booking->id) }}" onsubmit="return confirm('Delete this booking?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 text-sm">Delete</button>
                                </form>
                            </td>
                        </tr>
                        
                        <!-- Expanded Row for Food Orders -->
                        @if($booking->foodOrders->count() > 0)
                            <tr x-show="expanded" x-collapse class="bg-orange-50">
                                <td colspan="7" class="px-6 py-4">
                                    <div class="max-w-4xl">
                                        <h4 class="text-sm font-semibold text-gray-900 mb-3">Food Pre-Orders (DINE-IN)</h4>
                                        <div class="space-y-2">
                                            @foreach($booking->foodOrders as $order)
                                                <div class="flex items-center justify-between bg-white p-3 rounded border">
                                                    <div class="flex-1">
                                                        <p class="text-sm font-medium text-gray-900">{{ $order->food->name }} × {{ $order->quantity }}</p>
                                                        <p class="text-xs text-gray-500">Price: ₹{{ $order->food->price }}</p>
                                                        @if($order->cooking_note)
                                                            <p class="text-xs text-orange-600 mt-1">📝 {{ $order->cooking_note }}</p>
                                                        @endif
                                                    </div>
                                                    <form method="POST" action="{{ route('admin.bookings.updateStatus', $booking->id) }}">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="food_order_id" value="{{ $order->id }}">
                                                        <select name="food_status" onchange="this.form.submit()" class="text-xs rounded px-2 py-1 border
                                                            @if($order->food_status === 'waiting') bg-yellow-100 text-yellow-800
                                                            @elseif($order->food_status === 'preparing') bg-blue-100 text-blue-800
                                                            @else bg-green-100 text-green-800 @endif">
                                                            <option value="waiting" @if($order->food_status === 'waiting') selected @endif>Waiting</option>
                                                            <option value="preparing" @if($order->food_status === 'preparing') selected @endif>Preparing</option>
                                                            <option value="served" @if($order->food_status === 'served') selected @endif>Served</option>
                                                        </select>
                                                    </form>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endif

                        <!-- Preferences & Notes -->
                        <tr x-show="expanded" x-collapse class="bg-gray-50">
                            <td colspan="7" class="px-6 py-4">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <h5 class="text-xs font-semibold text-gray-700 mb-2">Preferences:</h5>
                                        @if($booking->table_type || $booking->seating_preference || $booking->window_side)
                                            <div class="flex flex-wrap gap-1">
                                                @if($booking->table_type)
                                                    <span class="px-2 py-1 text-xs bg-white rounded">{{ ucfirst($booking->table_type) }}</span>
                                                @endif
                                                @if($booking->seating_preference)
                                                    <span class="px-2 py-1 text-xs bg-white rounded">{{ strtoupper($booking->seating_preference) }}</span>
                                                @endif
                                                @if($booking->window_side)
                                                    <span class="px-2 py-1 text-xs bg-white rounded">Window</span>
                                                @endif
                                            </div>
                                        @else
                                            <p class="text-xs text-gray-500">No preferences</p>
                                        @endif
                                        
                                        @if($booking->occasion)
                                            <p class="text-xs text-gray-600 mt-2"><strong>Occasion:</strong> {{ ucfirst($booking->occasion) }}</p>
                                        @endif
                                        
                                        @if($booking->special_requests)
                                            <p class="text-xs text-gray-600 mt-2"><strong>Special Requests:</strong> {{ $booking->special_requests }}</p>
                                        @endif
                                    </div>
                                    <div>
                                        <h5 class="text-xs font-semibold text-gray-700 mb-2">Admin Notes:</h5>
                                        <form method="POST" action="{{ route('admin.bookings.updateStatus', $booking->id) }}">
                                            @csrf
                                            @method('PATCH')
                                            <textarea name="admin_notes" rows="3" class="w-full text-xs border rounded p-2" placeholder="Add notes...">{{ $booking->admin_notes }}</textarea>
                                            <button type="submit" class="mt-1 text-xs bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700">Save Notes</button>
                                        </form>
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
