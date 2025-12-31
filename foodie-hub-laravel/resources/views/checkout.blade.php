@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h1 class="text-3xl font-extrabold text-gray-900 mb-8">Checkout</h1>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
        <!-- Order Summary -->
        <div class="bg-white p-6 rounded-lg shadow h-fit">
            <h2 class="text-xl font-semibold mb-4 border-b pb-2">Order Summary</h2>
            <ul class="space-y-4 mb-4">
                @foreach($cart->items as $item)
                <li class="flex justify-between">
                    <div>
                        <span class="text-gray-800 font-medium">{{ $item->food->name }}</span>
                        <span class="text-gray-500 text-sm">x{{ $item->quantity }}</span>
                    </div>
                    <span class="font-medium">${{ $item->food->price * $item->quantity }}</span>
                </li>
                @endforeach
            </ul>
             <div class="flex justify-between border-t pt-4 text-xl font-bold">
                <span>Total</span>
                <span>${{ $total }}</span>
            </div>
        </div>

        <!-- Shipping Details -->
        <div class="bg-white p-6 rounded-lg shadow">
            <h2 class="text-xl font-semibold mb-4 border-b pb-2">Shipping Information</h2>
            <form action="{{ route('place.order') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Full Name</label>
                    <input type="text" value="{{ Auth::user()->name }}" disabled class="bg-gray-100 border rounded w-full py-2 px-3 text-gray-700 leading-tight">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Email</label>
                    <input type="email" value="{{ Auth::user()->email }}" disabled class="bg-gray-100 border rounded w-full py-2 px-3 text-gray-700 leading-tight">
                </div>
                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="address">Delivery Address</label>
                    <textarea name="address" id="address" rows="3" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Enter your full address here..."></textarea>
                </div>
                
                <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-4 rounded focus:outline-none focus:shadow-outline transition duration-150 ease-in-out">
                    Place Order (Cash on Delivery)
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
