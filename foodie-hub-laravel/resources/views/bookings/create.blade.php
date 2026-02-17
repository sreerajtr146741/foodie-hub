@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-orange-500 to-orange-600 px-6 py-8">
            <h2 class="text-3xl font-bold text-white">Book a Table</h2>
            <p class="text-orange-100 mt-1">Reserve your spot at Food Court & Pre-order your meal</p>
        </div>

        <!-- Form -->
        <form action="{{ route('booking.store') }}" method="POST" class="p-6 space-y-8" x-data="bookingForm()">
            @csrf

            <div class="flex items-center justify-between bg-blue-50 border border-blue-200 p-4 rounded-lg mb-4">
                <div class="flex items-center">
                    <svg class="h-5 w-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="text-sm text-blue-800">Note: We reserve {{ \App\Models\RestaurantSetting::getValue('online_tables_count', 5) }} tables for online bookings per time slot.</span>
                </div>
                <a href="{{ route('admin.tables.index') }}" class="text-xs text-blue-600 hover:underline">Admin: Setup Tables</a>
            </div>

            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Customer Information -->
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <svg class="h-5 w-5 mr-2 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    Customer Information
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Full Name *</label>
                        <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-orange-500 focus:border-orange-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Mobile Number *</label>
                        <input type="tel" name="phone" value="{{ old('phone', auth()->user()->phone) }}" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-orange-500 focus:border-orange-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Email Address *</label>
                        <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-orange-500 focus:border-orange-500">
                    </div>
                </div>
            </div>

            <!-- Booking Information -->
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <svg class="h-5 w-5 mr-2 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    Booking Details
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Date *</label>
                        <input type="date" name="booking_date" value="{{ old('booking_date') }}" min="{{ date('Y-m-d') }}" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-orange-500 focus:border-orange-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Time *</label>
                        <input type="time" name="booking_time" value="{{ old('booking_time') }}" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-orange-500 focus:border-orange-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Number of Guests *</label>
                        <input type="number" name="guests" value="{{ old('guests', 2) }}" min="1" max="20" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-orange-500 focus:border-orange-500">
                    </div>
                </div>
            </div>

            <!-- Table Preference -->
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <svg class="h-5 w-5 mr-2 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Table Preference (Optional)
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Table Type</label>
                        <select name="table_type" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-orange-500 focus:border-orange-500">
                            <option value="">No preference</option>
                            <option value="indoor">Indoor</option>
                            <option value="outdoor">Outdoor</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Seating</label>
                        <select name="seating_preference" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-orange-500 focus:border-orange-500">
                            <option value="">No preference</option>
                            <option value="ac">AC</option>
                            <option value="non-ac">Non-AC</option>
                        </select>
                    </div>
                    <div>
                        <label class="flex items-center mt-7">
                            <input type="checkbox" name="window_side" value="1" class="h-4 w-4 text-orange-600 focus:ring-orange-500 border-gray-300 rounded">
                            <span class="ml-2 text-sm text-gray-700">Window Side (if available)</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Occasion & Special Requests -->
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <svg class="h-5 w-5 mr-2 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7" />
                    </svg>
                    Special Details (Optional)
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Occasion</label>
                        <select name="occasion" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-orange-500 focus:border-orange-500">
                            <option value="">Select occasion</option>
                            <option value="birthday">Birthday</option>
                            <option value="anniversary">Anniversary</option>
                            <option value="business">Business Meeting</option>
                            <option value="casual">Casual Dining</option>
                            <option value="date">Romantic Date</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Special Requests</label>
                        <input type="text" name="special_requests" value="{{ old('special_requests') }}" placeholder="Wheelchair access, High chair, Decoration..." class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-orange-500 focus:border-orange-500">
                    </div>
                </div>
            </div>

            <!-- Food Pre-Order Section -->
            <div class="border-t-4 border-orange-500 pt-6">
                <div class="bg-orange-50 border border-orange-200 rounded-lg p-4 mb-4">
                    <div class="flex items-start">
                        <svg class="h-6 w-6 text-orange-600 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div>
                            <h4 class="text-sm font-semibold text-orange-900">DINE-IN FOOD PRE-ORDER (Optional)</h4>
                            <p class="text-sm text-orange-700 mt-1">Pre-order your food and it will be ready when you arrive. This is NOT takeaway - food will be served at your table.</p>
                        </div>
                    </div>
                </div>

                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <svg class="h-5 w-5 mr-2 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    Select Food Items (Optional)
                </h3>

                <div class="space-y-3 mb-4">
                    <template x-for="(item, index) in foodItems" :key="index">
                        <div class="border border-gray-300 rounded-lg p-4 bg-gray-50">
                            <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                                <div class="md:col-span-5">
                                    <label class="block text-sm font-medium text-gray-700">Food Item</label>
                                    <select x-model="item.food_id" :name="'food_items[' + index + '][food_id]'" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-orange-500 focus:border-orange-500">
                                        <option value="">Select food</option>
                                        @foreach($foods as $food)
                                            <option value="{{ $food->id }}">{{ $food->name }} - ₹{{ $food->price }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700">Quantity</label>
                                    <input type="number" x-model="item.quantity" :name="'food_items[' + index + '][quantity]'" min="1" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-orange-500 focus:border-orange-500">
                                </div>
                                <div class="md:col-span-4">
                                    <label class="block text-sm font-medium text-gray-700">Cooking Note</label>
                                    <input type="text" x-model="item.cooking_note" :name="'food_items[' + index + '][cooking_note]'" placeholder="Less spicy, No onion..." class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-orange-500 focus:border-orange-500">
                                </div>
                                <div class="md:col-span-1 flex items-end">
                                    <button type="button" @click="removeFoodItem(index)" class="w-full px-3 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                                        <svg class="h-5 w-5 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                <button type="button" @click="addFoodItem()" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 flex items-center">
                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Add Food Item
                </button>
            </div>

            <!-- Submit Buttons -->
            <div class="flex justify-between pt-6 border-t">
                <a href="{{ route('home') }}" class="px-6 py-3 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                    Cancel
                </a>
                <button type="submit" class="px-8 py-3 bg-orange-600 text-white rounded-md hover:bg-orange-700 font-semibold">
                    Reserve Table
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function bookingForm() {
    return {
        foodItems: [],
        addFoodItem() {
            this.foodItems.push({
                food_id: '',
                quantity: 1,
                cooking_note: ''
            });
        },
        removeFoodItem(index) {
            this.foodItems.splice(index, 1);
        }
    }
}
</script>
@endsection
