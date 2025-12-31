@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h1 class="text-3xl font-extrabold text-gray-900 mb-8">Shopping Cart</h1>

    @if(!$cart || $cart->items->isEmpty())
        <div class="text-center py-12 bg-white rounded-lg shadow">
            <p class="text-gray-500 text-lg mb-4">Your cart is empty.</p>
            <a href="{{ route('menu') }}" class="text-orange-600 hover:text-orange-500 font-medium">Go to Menu</a>
        </div>
    @else
        <div class="bg-white shadow overflow-hidden sm:rounded-md">
            <ul class="divide-y divide-gray-200">
                @foreach($cart->items as $item)
                <li class="px-6 py-4 flex items-center justify-between">
                    <div class="flex items-center">
                        <img class="h-16 w-16 object-cover rounded mr-4" src="{{ $item->food->image ? asset('storage/' . $item->food->image) : 'https://placehold.co/100' }}" alt="{{ $item->food->name }}">
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $item->food->name }}</p>
                            <p class="text-sm text-gray-500">${{ $item->food->price }}</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                         <form action="{{ route('cart.update', $item) }}" method="POST" id="form-{{ $item->id }}">
                            @csrf @method('PATCH')
                            <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" 
                                   class="w-16 border rounded p-1 text-center text-sm"
                                   onchange="document.getElementById('form-{{ $item->id }}').submit()">
                        </form>
                        <span class="font-bold text-gray-900">₹{{ $item->food->price * $item->quantity }}</span>
                        <form action="{{ route('cart.remove', $item) }}" method="POST">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800" title="Remove from cart">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </form>
                    </div>
                </li>
                @endforeach
            </ul>
            <div class="bg-gray-50 px-6 py-4 flex justify-between items-center">
                <div class="text-lg font-bold text-gray-900">Total: ${{ $cart->items->sum(fn($i) => $i->food->price * $i->quantity) }}</div>
                <a href="{{ route('checkout') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                    Proceed to Checkout
                </a>
            </div>
        </div>
    @endif
</div>
@endsection
