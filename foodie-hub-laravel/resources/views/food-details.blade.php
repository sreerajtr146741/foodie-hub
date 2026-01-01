@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Product Details -->
    <div class="bg-white rounded-lg shadow overflow-hidden mb-12">
        <div class="md:flex">
            <div class="md:flex-shrink-0">
                <img class="h-64 w-full object-cover md:w-96 md:h-full" src="{{ $food->image ? asset('storage/' . $food->image) : 'https://placehold.co/600x600' }}" alt="{{ $food->name }}">
            </div>
            <div class="p-8 w-full">
                <div class="uppercase tracking-wide text-sm text-orange-500 font-semibold">{{ $food->category->name }}</div>
                <h1 class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl">{{ $food->name }}</h1>
                <!-- Average Rating -->
                <div class="flex items-center mt-2">
                    <div class="flex text-yellow-400">
                        @for($i=1; $i<=5; $i++)
                            @if($i <= round($reviews->avg('rating')))
                                <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                            @else
                                <svg class="w-5 h-5 text-gray-300 fill-current" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                            @endif
                        @endfor
                    </div>
                    <span class="text-gray-600 ml-2 text-sm">({{ $reviews->count() }} reviews)</span>
                </div>
                
                <p class="mt-4 text-gray-500 text-lg">{{ $food->description }}</p>
                
                <div class="mt-8">
                    <div class="flex items-baseline gap-4 mb-6">
                        @if($food->category->discount > 0)
                            <span class="text-4xl font-bold text-orange-600">
                                ₹{{ number_format($food->price - ($food->price * $food->category->discount / 100), 2) }}
                            </span>
                            <span class="text-xl text-gray-500 line-through">₹{{ $food->price }}</span>
                            <span class="bg-green-100 text-green-800 text-sm font-semibold px-2 py-1 rounded">{{ $food->category->discount }}% OFF</span>
                        @else
                            <span class="text-4xl font-bold text-orange-600">₹{{ $food->price }}</span>
                        @endif
                    </div>
                    
                    @auth
                        <div class="flex gap-4">
                            <!-- Add to Cart -->
                            <form action="{{ route('cart.add', $food) }}" method="POST" class="flex items-center gap-2">
                                @csrf
                                <input type="number" name="quantity" min="1" value="1" class="border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-orange-500 focus:border-orange-500 w-20">
                                <button type="submit" class="bg-orange-600 border border-transparent rounded-md shadow-sm py-3 px-6 text-base font-medium text-white hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                                    Add to Cart
                                </button>
                            </form>
                            
                            <!-- Buy Now -->
                            <form action="{{ route('cart.add', $food) }}" method="POST">
                                @csrf
                                <input type="hidden" name="quantity" value="1">
                                <input type="hidden" name="buy_now" value="1">
                                <button type="submit" class="bg-green-600 border border-transparent rounded-md shadow-sm py-3 px-8 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                    🛒 Buy Now
                                </button>
                            </form>
                        </div>
                    @else 
                        <a href="{{ route('login') }}" class="inline-block bg-orange-600 text-white px-8 py-3 rounded-md font-semibold hover:bg-orange-700">
                            Login to Order
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>

    <!-- Reviews Section -->
    <div class="bg-white rounded-lg shadow p-8 mb-12">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Customer Reviews</h2>
        
        <!-- Add Review Form -->
        @auth
            <div class="mb-8 p-6 bg-gray-50 rounded-lg">
                <h3 class="font-semibold text-lg mb-4">Write a Review</h3>
                <form action="{{ route('food.review', $food) }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Rating</label>
                        <select name="rating" class="border rounded px-3 py-2 w-full focus:outline-none focus:ring-2 focus:ring-orange-500">
                            <option value="5">⭐⭐⭐⭐⭐ (Excellent)</option>
                            <option value="4">⭐⭐⭐⭐ (Good)</option>
                            <option value="3">⭐⭐⭐ (Average)</option>
                            <option value="2">⭐⭐ (Poor)</option>
                            <option value="1">⭐ (Terrible)</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Comment</label>
                        <textarea name="comment" rows="3" class="border rounded px-3 py-2 w-full focus:outline-none focus:ring-2 focus:ring-orange-500" placeholder="Share your experience..."></textarea>
                    </div>
                    <button type="submit" class="bg-orange-600 text-white px-4 py-2 rounded hover:bg-orange-700">Submit Review</button>
                </form>
            </div>
        @endauth

        <!-- Reviews List -->
        <div class="space-y-6">
            @forelse($reviews as $review)
                <div class="border-b pb-6 last:border-b-0">
                    <div class="flex items-center justify-between mb-2">
                        <div class="font-semibold text-gray-900">{{ $review->user->name }}</div>
                        <div class="text-sm text-gray-500">{{ $review->created_at->diffForHumans() }}</div>
                    </div>
                    <div class="flex text-yellow-400 mb-2">
                        @for($i=1; $i<=5; $i++)
                            @if($i <= $review->rating)
                                <span>★</span>
                            @else
                                <span class="text-gray-300">★</span>
                            @endif
                        @endfor
                    </div>
                    <p class="text-gray-600">{{ $review->comment }}</p>
                </div>
            @empty
                <p class="text-gray-500">No reviews yet. Be the first to review!</p>
            @endforelse
        </div>
    </div>

    <!-- Similar Products -->
    @if($similarFoods->isNotEmpty())
    <div class="mb-12">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Similar Products</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($similarFoods as $item)
                <a href="{{ route('food.details', $item) }}" class="block bg-white rounded-lg shadow hover:shadow-lg transition">
                    <img src="{{ $item->image ? asset('storage/' . $item->image) : 'https://placehold.co/300x200' }}" alt="{{ $item->name }}" class="w-full h-40 object-cover rounded-t-lg">
                    <div class="p-4">
                        <h3 class="font-bold text-gray-900 mb-1">{{ $item->name }}</h3>
                        <p class="text-gray-500 text-sm mb-2 truncate">{{ $item->description }}</p>
                        <div class="flex justify-between items-center">
                            <span class="text-orange-600 font-bold">₹{{ $item->price }}</span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
    @endif

    <!-- You May Like -->
    @if($youMayLike->isNotEmpty())
    <div>
        <h2 class="text-2xl font-bold text-gray-900 mb-6">You May Also Like</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($youMayLike as $item)
                <a href="{{ route('food.details', $item) }}" class="block bg-white rounded-lg shadow hover:shadow-lg transition">
                    <img src="{{ $item->image ? asset('storage/' . $item->image) : 'https://placehold.co/300x200' }}" alt="{{ $item->name }}" class="w-full h-40 object-cover rounded-t-lg">
                    <div class="p-4">
                        <h3 class="font-bold text-gray-900 mb-1">{{ $item->name }}</h3>
                        <p class="text-gray-500 text-sm mb-2 truncate">{{ $item->description }}</p>
                        <div class="flex justify-between items-center">
                            <span class="text-orange-600 font-bold">₹{{ $item->price }}</span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection
