@extends('layouts.app')

@section('content')
<!-- Hero Section -->
<div class="relative bg-white overflow-hidden">
    <div class="max-w-7xl mx-auto">
        <div class="relative z-10 pb-8 bg-white sm:pb-16 md:pb-20 lg:max-w-2xl lg:w-full lg:pb-28 xl:pb-32">
            <main class="mt-10 mx-auto max-w-7xl px-4 sm:mt-12 sm:px-6 md:mt-16 lg:mt-20 lg:px-8 xl:mt-28">
                <div class="sm:text-center lg:text-left">
                    <h1 class="text-4xl tracking-tight font-extrabold text-gray-900 sm:text-5xl md:text-6xl">
                        <span class="block xl:inline">Delicious food delivered</span>
                        <span class="block text-orange-600 xl:inline">right to your door</span>
                    </h1>
                    <p class="mt-3 text-base text-gray-500 sm:mt-5 sm:text-lg sm:max-w-xl sm:mx-auto md:mt-5 md:text-xl lg:mx-0">
                        Experience the best cuisine in town. Fresh ingredients, masterful preparation, and fast delivery.
                    </p>
                    <div class="mt-5 sm:mt-8 sm:flex sm:justify-center lg:justify-start">
                        <div class="rounded-md shadow">
                            <a href="{{ route('menu') }}" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-orange-600 hover:bg-orange-700 md:py-4 md:text-lg md:px-10">
                                Order Now
                            </a>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <div class="lg:absolute lg:inset-y-0 lg:right-0 lg:w-1/2">
        <img class="h-56 w-full object-cover sm:h-72 md:h-96 lg:w-full lg:h-full" src="https://images.unsplash.com/photo-1504674900247-0877df9cc836?ixlib=rb-1.2.1&auto=format&fit=crop&w=1950&q=80" alt="Food background">
    </div>
</div>

<!-- Featured Section -->
<div class="py-12 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="lg:text-center mb-10">
            <h2 class="text-base text-orange-600 font-semibold tracking-wide uppercase">Discover</h2>
            <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl">
                Featured Items
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($featuredFoods as $food)
            <a href="{{ route('food.details', $food) }}" class="block bg-white rounded-lg shadow-lg overflow-hidden border border-gray-100 transition transform hover:-translate-y-1 hover:shadow-xl cursor-pointer">
                <img src="{{ $food->image ? asset('storage/' . $food->image) : 'https://placehold.co/400x300' }}" alt="{{ $food->name }}" class="w-full h-48 object-cover">
                <div class="p-4">
                    <div class="flex justify-between items-start mb-2">
                         <h3 class="text-lg font-bold text-gray-900">{{ $food->name }}</h3>
                    </div>
                    <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ $food->description }}</p>
                    <div class="flex items-center justify-between">
                        @if($food->category->discount > 0)
                            <div class="flex flex-col">
                                <div class="flex items-center gap-2">
                                     <span class="text-2xl font-bold text-orange-600">
                                        ₹{{ number_format($food->price - ($food->price * $food->category->discount / 100), 2) }}
                                    </span>
                                    <span class="text-sm text-gray-500 line-through">₹{{ $food->price }}</span>
                                </div>
                                <span class="text-xs text-green-600 font-bold self-start">{{ $food->category->discount }}% OFF</span>
                            </div>
                        @else
                            <span class="text-2xl font-bold text-orange-600">₹{{ $food->price }}</span>
                        @endif
                    </div>
                </div>
            </a>
            @endforeach
        </div>
        
        <div class="mt-12 text-center">
            <a href="{{ route('menu') }}" class="text-base font-semibold text-orange-600 hover:text-orange-500">
                View full menu <span aria-hidden="true">→</span>
            </a>
        </div>
    </div>
</div>
@endsection
