@extends('layouts.app')

@section('content')
<div class="bg-gradient-to-r from-orange-500 to-orange-600 text-white py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-center">Our Menu</h1>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Search & Filter Section -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
        <form method="GET" action="{{ route('menu') }}" class="space-y-4">
            <!-- Search Bar -->
            <div class="flex gap-4">
                <div class="flex-1">
                    <div class="relative">
                        <input 
                            type="text" 
                            name="search" 
                            value="{{ request('search') }}" 
                            placeholder="Search for food items..." 
                            class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                        >
                        <svg class="absolute left-3 top-3.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>
                <button type="submit" class="px-8 py-3 bg-orange-600 text-white rounded-lg hover:bg-orange-700 font-semibold">
                    Search
                </button>
            </div>

            <!-- Category Filter Buttons -->
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('menu') }}" 
                   class="px-4 py-2 rounded-full {{ !request('category') && !request('search') ? 'bg-orange-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} transition">
                    All
                </a>
                @foreach($categories as $cat)
                    <button 
                        type="submit" 
                        name="category" 
                        value="{{ $cat->id }}"
                        class="px-4 py-2 rounded-full {{ request('category') == $cat->id ? 'bg-orange-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} transition">
                        {{ $cat->name }}
                    </button>
                @endforeach
            </div>

            <!-- Active Filters Display -->
            @if(request('search') || request('category'))
                <div class="flex items-center gap-2 pt-2 border-t">
                    <span class="text-sm text-gray-600">Active filters:</span>
                    @if(request('search'))
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-orange-100 text-orange-800">
                            Search: "{{ request('search') }}"
                            <a href="{{ route('menu', ['category' => request('category')]) }}" class="ml-2 hover:text-orange-900">×</a>
                        </span>
                    @endif
                    @if(request('category'))
                        @php
                            $selectedCategory = $categories->find(request('category'));
                        @endphp
                        @if($selectedCategory)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-orange-100 text-orange-800">
                                Category: {{ $selectedCategory->name }}
                                <a href="{{ route('menu', ['search' => request('search')]) }}" class="ml-2 hover:text-orange-900">×</a>
                            </span>
                        @endif
                    @endif
                    <a href="{{ route('menu') }}" class="text-sm text-orange-600 hover:text-orange-800 ml-2">Clear all</a>
                </div>
            @endif
        </form>
    </div>

    <!-- Results Count -->
    <div class="mb-6">
        <p class="text-gray-600">
            Showing <span class="font-semibold text-gray-900">{{ $foods->count() }}</span> 
            {{ $foods->count() == 1 ? 'item' : 'items' }}
            @if(request('search'))
                matching "<span class="font-semibold">{{ request('search') }}</span>"
            @endif
        </p>
    </div>

    <!-- Food Items Grid -->
    @if($foods->isEmpty())
        <div class="text-center py-16">
            <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <h3 class="mt-4 text-lg font-medium text-gray-900">No items found</h3>
            <p class="mt-2 text-gray-500">
                @if(request('search'))
                    Try adjusting your search terms or <a href="{{ route('menu') }}" class="text-orange-600 hover:text-orange-700">browse all items</a>
                @else
                    No food items available in this category.
                @endif
            </p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($foods as $food)
                <a href="{{ route('food.details', $food) }}" class="block bg-white rounded-lg shadow-lg overflow-hidden border border-gray-100 transition transform hover:-translate-y-1 hover:shadow-xl cursor-pointer">
                    <img src="{{ $food->image ? asset('storage/' . $food->image) : 'https://placehold.co/400x300' }}" alt="{{ $food->name }}" class="w-full h-48 object-cover">
                    <div class="p-4">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="text-lg font-bold text-gray-900">{{ $food->name }}</h3>
                            <span class="px-2 py-1 bg-orange-100 text-orange-600 text-xs font-semibold rounded">
                                {{ $food->category->name }}
                            </span>
                        </div>
                        <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ $food->description }}</p>
                        <div class="flex justify-between items-center">
                            <span class="text-2xl font-bold text-orange-600">₹{{ $food->price }}</span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    @endif
</div>
@endsection
