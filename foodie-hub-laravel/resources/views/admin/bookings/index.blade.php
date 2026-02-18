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
                                <button @click="expanded = !expanded" class="text-sm text-orange-600 hover:text-orange-800 flex items-center gap-1 group">
                                    <svg class="h-4 w-4 transition-transform duration-200" :class="expanded ? 'rotate-90' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                    @if($booking->foodOrders->count() > 0)
                                        <span class="font-medium">{{ $booking->foodOrders->count() }} items</span>
                                    @else
                                        <span class="text-gray-400 group-hover:text-orange-600">Add food</span>
                                    @endif
                                </button>
                            </td>
                            <td class="px-6 py-4">
                                <form method="POST" action="{{ route('admin.bookings.updateStatus', $booking->id) }}">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status" onchange="this.form.submit()" class="text-sm rounded-full px-3 py-1 border font-medium
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
                                    <button type="submit" class="text-red-400 hover:text-red-600 transition">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        
                        <!-- Combined Expanded Row -->
                        <tr x-show="expanded" x-collapse class="bg-gray-50">
                            <td colspan="7" class="px-6 py-8 border-t border-gray-100">
                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                                    <!-- Left: Food & Billing -->
                                    <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
                                        <div class="flex items-center justify-between mb-5 border-b pb-3">
                                            <h4 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                                                <span class="p-1.5 bg-orange-100 rounded-lg text-orange-600">
                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                                    </svg>
                                                </span>
                                                Food Items & Billing
                                            </h4>
                                            <div class="text-base font-black text-orange-600">
                                                Total: ₹{{ number_format($booking->foodOrders->sum(fn($o) => $o->food->price * $o->quantity), 2) }}
                                            </div>
                                        </div>

                                        <!-- Add Food Form -->
                                        <div class="bg-blue-50/50 p-4 rounded-xl mb-6 border border-blue-100/50">
                                            <form method="POST" action="{{ route('admin.bookings.updateStatus', $booking->id) }}" class="flex items-end gap-3">
                                                @csrf
                                                @method('PATCH')
                                                <div class="flex-1">
                                                    <label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wider">Add Item</label>
                                                    <select name="add_food_id" required class="w-full text-sm border-gray-200 rounded-lg p-2 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition outline-none">
                                                        <option value="">Select food item...</option>
                                                        @foreach($foods as $f)
                                                            <option value="{{ $f->id }}">{{ $f->name }} - ₹{{ number_format($f->price, 2) }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="w-20">
                                                    <label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wider">Qty</label>
                                                    <input type="number" name="quantity" value="1" min="1" class="w-full text-sm border-gray-200 rounded-lg p-2 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition outline-none">
                                                </div>
                                                <button type="submit" class="bg-blue-600 text-white px-5 py-2 text-sm font-bold rounded-lg hover:bg-blue-700 transition shadow-sm hover:shadow-md active:scale-95">Add</button>
                                            </form>
                                        </div>

                                        <div class="space-y-3 max-h-80 overflow-y-auto pr-2 custom-scrollbar">
                                            @forelse($booking->foodOrders as $order)
                                                <div class="flex items-center justify-between bg-white p-4 rounded-xl border border-gray-100 hover:border-orange-200 hover:shadow-sm transition-all group">
                                                    <div class="flex-1">
                                                        <div class="flex items-center gap-3">
                                                            <p class="text-sm font-bold text-gray-900">{{ $order->food->name }} <span class="text-gray-400 font-normal">×</span> {{ $order->quantity }}</p>
                                                            <span class="text-xs font-black px-2 py-0.5 bg-gray-100 rounded text-gray-700">₹{{ number_format($order->food->price * $order->quantity, 2) }}</span>
                                                        </div>
                                                        <p class="text-xs text-gray-500 mt-1">Unit Price: ₹{{ number_format($order->food->price, 2) }}</p>
                                                        @if($order->cooking_note)
                                                            <div class="mt-2 text-[10px] text-orange-600 bg-orange-50 px-2 py-1 rounded-md inline-block">
                                                                📝 {{ $order->cooking_note }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="flex items-center gap-3">
                                                        <form method="POST" action="{{ route('admin.bookings.updateStatus', $booking->id) }}">
                                                            @csrf
                                                            @method('PATCH')
                                                            <input type="hidden" name="food_order_id" value="{{ $order->id }}">
                                                            <select name="food_status" onchange="this.form.submit()" class="text-[10px] uppercase font-bold rounded-lg px-2 py-1 border transition-colors
                                                                @if($order->food_status === 'waiting') bg-yellow-50 text-yellow-700 border-yellow-200
                                                                @elseif($order->food_status === 'preparing') bg-blue-50 text-blue-700 border-blue-200
                                                                @else bg-green-50 text-green-700 border-green-200 @endif">
                                                                <option value="waiting" @if($order->food_status === 'waiting') selected @endif>Waiting</option>
                                                                <option value="preparing" @if($order->food_status === 'preparing') selected @endif>Preparing</option>
                                                                <option value="served" @if($order->food_status === 'served') selected @endif>Served</option>
                                                            </select>
                                                        </form>
                                                        <form method="POST" action="{{ route('admin.bookings.updateStatus', $booking->id) }}" onsubmit="return confirm('Remove this item?');">
                                                            @csrf
                                                            @method('PATCH')
                                                            <input type="hidden" name="remove_food_order_id" value="{{ $order->id }}">
                                                            <button type="submit" class="text-gray-300 hover:text-red-500 transition-colors">
                                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                                </svg>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            @empty
                                                <div class="flex flex-col items-center justify-center py-10 text-gray-400 bg-gray-50/50 rounded-xl border border-dashed">
                                                    <svg class="h-8 w-8 mb-2 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                                    </svg>
                                                    <p class="text-sm italic">No items ordered yet.</p>
                                                </div>
                                            @endforelse
                                        </div>

                                        <!-- Settlement -->
                                        @if($booking->status !== 'completed' && $booking->status !== 'cancelled')
                                            <div class="mt-8 pt-6 border-t flex flex-col sm:flex-row items-center justify-between gap-4">
                                                <div class="text-xs text-gray-400 font-medium italic">Finalize the order to complete booking</div>
                                                <form method="POST" action="{{ route('admin.bookings.updateStatus', $booking->id) }}" class="flex items-center gap-3">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="settle_payment" value="1">
                                                    <div class="relative">
                                                        <select name="payment_method" class="text-xs font-bold border-gray-200 rounded-lg px-3 py-2 bg-white focus:ring-2 focus:ring-green-500/20 focus:border-green-500 outline-none appearance-none pr-8">
                                                            <option value="Cash">CASH</option>
                                                            <option value="Card">CARD</option>
                                                            <option value="UPI">UPI</option>
                                                        </select>
                                                        <div class="absolute right-2 top-1/2 -translate-y-1/2 pointer-events-none text-gray-400">
                                                            <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                                        </div>
                                                    </div>
                                                    <button type="submit" class="bg-green-600 text-white px-5 py-2 text-xs font-black rounded-lg hover:bg-green-700 shadow-sm hover:shadow-green-200 transition-all flex items-center gap-2 active:scale-95">
                                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                        SETTLE & COMPLETE
                                                    </button>
                                                </form>
                                            </div>
                                        @elseif($booking->payment_status === 'paid')
                                            <div class="mt-6 pt-6 border-t flex justify-end">
                                                <div class="inline-flex items-center gap-2 px-4 py-2 bg-green-50 text-green-700 border border-green-100 rounded-xl">
                                                    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                                    <span class="text-xs font-black uppercase tracking-widest">PAID ₹{{ number_format($booking->total_amount, 2) }} via {{ $booking->payment_method }}</span>
                                                </div>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Right: Preferences & Notes -->
                                    <div class="space-y-6">
                                        <!-- Preferences card -->
                                        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
                                            <h4 class="text-sm font-bold text-gray-900 mb-4 flex items-center gap-2">
                                                <span class="p-1.5 bg-blue-100 rounded-lg text-blue-600">
                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                                                    </svg>
                                                </span>
                                                Customer Request Details
                                            </h4>
                                            
                                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-5">
                                                <div class="p-3 bg-gray-50 rounded-lg">
                                                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter mb-1">Seating Pref</p>
                                                    <p class="text-sm font-semibold text-gray-700">{{ $booking->seating_preference ? strtoupper($booking->seating_preference) : 'NOT SPECIFIED' }}</p>
                                                </div>
                                                <div class="p-3 bg-gray-50 rounded-lg">
                                                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter mb-1">Table Type</p>
                                                    <p class="text-sm font-semibold text-gray-700">{{ $booking->table_type ? ucfirst($booking->table_type) : 'STANDARD' }}</p>
                                                </div>
                                                <div class="p-3 bg-gray-50 rounded-lg">
                                                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter mb-1">Window Side</p>
                                                    <p class="text-sm font-semibold text-gray-700">{{ $booking->window_side ? 'YES' : 'NO' }}</p>
                                                </div>
                                                <div class="p-3 bg-gray-50 rounded-lg">
                                                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter mb-1">Occasion</p>
                                                    <p class="text-sm font-semibold text-gray-700">{{ $booking->occasion ? ucfirst($booking->occasion) : 'NONE' }}</p>
                                                </div>
                                            </div>

                                            @if($booking->special_requests)
                                                <div class="p-4 bg-orange-50/30 border border-orange-100 rounded-xl">
                                                    <p class="text-xs font-bold text-orange-800 mb-1 flex items-center gap-1">
                                                        <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20"><path d="M11 3a1 1 0 10-2 0v1a1 1 0 102 0V3zM5.884 6.643a1 1 0 10-1.414-1.414l.707-.707a1 1 0 001.414 1.414l-.707.707zM18 10a1 1 0 11-2 0h-1a1 1 0 112 0h1zM5.05 13.05a1 1 0 10-1.414 1.414l.707.707a1 1 0 101.414-1.414l-.707-.707zM10 18a1 1 0 100-2v-1a1 1 0 100 2v1zM14.95 13.05a1 1 0 101.414 1.414l-.707.707a1 1 0 10-1.414-1.414l.707-.707zM18 10a1 1 0 11-2 0h-1a1 1 0 112 0h1zM14.116 6.643a1 1 0 111.414-1.414l-.707-.707a1 1 0 11-1.414 1.414l.707.707z"/></svg>
                                                        Special Requests:
                                                    </p>
                                                    <p class="text-sm text-gray-600 italic leading-relaxed">"{{ $booking->special_requests }}"</p>
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Notes card -->
                                        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
                                            <h4 class="text-sm font-bold text-gray-900 mb-4 flex items-center gap-2">
                                                <span class="p-1.5 bg-purple-100 rounded-lg text-purple-600">
                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                </span>
                                                Internal Admin Notes
                                            </h4>
                                            <form method="POST" action="{{ route('admin.bookings.updateStatus', $booking->id) }}">
                                                @csrf
                                                @method('PATCH')
                                                <textarea name="admin_notes" rows="4" class="w-full text-sm border-gray-200 rounded-xl p-4 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 outline-none transition-all placeholder:text-gray-300" placeholder="Write private notes about this customer or booking here...">{{ $booking->admin_notes }}</textarea>
                                                <div class="flex justify-end mt-3">
                                                    <button type="submit" class="bg-gray-900 text-white px-6 py-2 rounded-lg hover:bg-black transition font-bold text-xs uppercase tracking-widest active:scale-95 shadow-sm">Save Notes</button>
                                                </div>
                                            </form>
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
