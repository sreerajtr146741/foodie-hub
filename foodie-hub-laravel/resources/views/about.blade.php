@extends('layouts.app')

@section('content')
<!-- Hero Section -->
<div class="relative bg-gradient-to-r from-orange-500 to-orange-600 text-white py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-5xl font-bold mb-4">About Food Court</h1>
        <p class="text-xl text-orange-100">Serving Delicious Food with Love Since 2020</p>
    </div>
</div>

<!-- Our Story -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
        <div>
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Our Story</h2>
            <p class="text-gray-600 mb-4 leading-relaxed">
                Food Court began with a simple vision: to bring people together through the joy of delicious, authentic cuisine. What started as a small family-owned restaurant has blossomed into a beloved dining destination for food lovers across the city.
            </p>
            <p class="text-gray-600 mb-4 leading-relaxed">
                Our journey is rooted in passion, tradition, and innovation. We believe that every meal should be an experience—crafted with care, served with warmth, and enjoyed in great company.
            </p>
            <p class="text-gray-600 leading-relaxed">
                Today, we're proud to serve thousands of happy customers, offering a diverse menu that celebrates both traditional flavors and contemporary culinary creativity.
            </p>
        </div>
        <div class="rounded-lg overflow-hidden shadow-2xl">
            <img src="https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=600&h=400&fit=crop" alt="Restaurant" class="w-full h-96 object-cover">
        </div>
    </div>
</div>

<!-- Mission & Vision -->
<div class="bg-gray-50 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="bg-white p-8 rounded-lg shadow-lg">
                <div class="text-orange-600 mb-4">
                    <svg class="h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-3">Our Mission</h3>
                <p class="text-gray-600 leading-relaxed">
                    To deliver exceptional dining experiences by combining fresh ingredients, authentic recipes, and heartfelt hospitality. We strive to make every visit memorable and every dish a celebration of flavor.
                </p>
            </div>
            
            <div class="bg-white p-8 rounded-lg shadow-lg">
                <div class="text-orange-600 mb-4">
                    <svg class="h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-3">Our Vision</h3>
                <p class="text-gray-600 leading-relaxed">
                    To become the most loved restaurant in the region, known for our commitment to quality, innovation, and community. We envision a future where Food Court is synonymous with excellence in dining.
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Why Choose Us -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <h2 class="text-3xl font-bold text-center text-gray-900 mb-12">Why Choose Food Court?</h2>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
        <!-- Quality -->
        <div class="text-center">
            <div class="bg-orange-100 rounded-full p-6 inline-block mb-4">
                <svg class="h-12 w-12 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                </svg>
            </div>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">Premium Quality</h3>
            <p class="text-gray-600">Only the freshest ingredients and finest recipes make it to your plate.</p>
        </div>
        
        <!-- Hygiene -->
        <div class="text-center">
            <div class="bg-orange-100 rounded-full p-6 inline-block mb-4">
                <svg class="h-12 w-12 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                </svg>
            </div>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">100% Hygienic</h3>
            <p class="text-gray-600">Maintaining the highest standards of cleanliness and food safety.</p>
        </div>
        
        <!-- Taste -->
        <div class="text-center">
            <div class="bg-orange-100 rounded-full p-6 inline-block mb-4">
                <svg class="h-12 w-12 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5" />
                </svg>
            </div>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">Amazing Taste</h3>
            <p class="text-gray-600">Flavors that delight and dishes that keep you coming back.</p>
        </div>
        
        <!-- Service -->
        <div class="text-center">
            <div class="bg-orange-100 rounded-full p-6 inline-block mb-4">
                <svg class="h-12 w-12 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">Fast Service</h3>
            <p class="text-gray-600">Quick, efficient service without compromising on quality.</p>
        </div>
    </div>
</div>

<!-- Call to Action -->
<div class="bg-gradient-to-r from-orange-500 to-orange-600 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl font-bold text-white mb-4">Ready to Experience Food Court?</h2>
        <p class="text-xl text-orange-100 mb-8">Join thousands of satisfied customers who trust us for their dining needs.</p>
        <div class="flex justify-center gap-4">
            <a href="{{ route('menu') }}" class="bg-white text-orange-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition">
                View Menu
            </a>
            <a href="{{ route('booking.create') }}" class="bg-orange-700 text-white px-8 py-3 rounded-lg font-semibold hover:bg-orange-800 transition">
                Book a Table
            </a>
        </div>
    </div>
</div>
@endsection
