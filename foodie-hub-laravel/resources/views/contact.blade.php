@extends('layouts.app')

@section('content')
<!-- Hero Section -->
<div class="relative bg-gradient-to-r from-orange-500 to-orange-600 text-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-5xl font-bold mb-4">Contact Us</h1>
        <p class="text-xl text-orange-100">We'd Love to Hear From You</p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
        <!-- Contact Information -->
        <div>
            <h2 class="text-3xl font-bold text-gray-900 mb-6">Get In Touch</h2>
            <p class="text-gray-600 mb-8">Have questions? We're here to help! Reach out to us through any of the following channels.</p>
            
            <!-- Contact Cards -->
            <div class="space-y-6">
                <!-- Address -->
                <div class="flex items-start space-x-4 p-4 bg-gray-50 rounded-lg">
                    <div class="flex-shrink-0">
                        <div class="bg-orange-100 rounded-full p-3">
                            <svg class="h-6 w-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900">Address</h3>
                        <p class="text-gray-600 mt-1">123 Food Street, City Center<br>New Delhi, India - 110001</p>
                    </div>
                </div>

                <!-- Phone -->
                <div class="flex items-start space-x-4 p-4 bg-gray-50 rounded-lg">
                    <div class="flex-shrink-0">
                        <div class="bg-orange-100 rounded-full p-3">
                            <svg class="h-6 w-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                        </div>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900">Phone</h3>
                        <p class="text-gray-600 mt-1">
                            <a href="tel:+919876543210" class="hover:text-orange-600">+91 98765 43210</a><br>
                            <a href="tel:+911234567890" class="hover:text-orange-600">+91 12345 67890</a>
                        </p>
                    </div>
                </div>

                <!-- Email -->
                <div class="flex items-start space-x-4 p-4 bg-gray-50 rounded-lg">
                    <div class="flex-shrink-0">
                        <div class="bg-orange-100 rounded-full p-3">
                            <svg class="h-6 w-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900">Email</h3>
                        <p class="text-gray-600 mt-1">
                            <a href="mailto:info@foodcourt.com" class="hover:text-orange-600">info@foodcourt.com</a><br>
                            <a href="mailto:support@foodcourt.com" class="hover:text-orange-600">support@foodcourt.com</a>
                        </p>
                    </div>
                </div>

                <!-- Hours -->
                <div class="flex items-start space-x-4 p-4 bg-gray-50 rounded-lg">
                    <div class="flex-shrink-0">
                        <div class="bg-orange-100 rounded-full p-3">
                            <svg class="h-6 w-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900">Opening Hours</h3>
                        <p class="text-gray-600 mt-1">
                            Mon - Fri: 10:00 AM - 10:00 PM<br>
                            Sat - Sun: 9:00 AM - 11:00 PM
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact Form -->
        <div class="bg-white p-8 rounded-lg shadow-lg">
            <h3 class="text-2xl font-bold text-gray-900 mb-6">Send Us a Message</h3>
            
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('contact.submit') }}" method="POST" class="space-y-4">
                @csrf
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Your Name *</label>
                    <input type="text" name="name" value="{{ old('name') }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email Address *</label>
                    <input type="email" name="email" value="{{ old('email') }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number *</label>
                    <input type="tel" name="phone" value="{{ old('phone') }}" placeholder="10 digits" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                    <p class="text-xs text-gray-500 mt-1">Please enter 10 digit phone number</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Your Message *</label>
                    <textarea name="message" rows="5" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">{{ old('message') }}</textarea>
                    <p class="text-xs text-gray-500 mt-1">Minimum 10 characters</p>
                </div>

                <button type="submit" class="w-full bg-orange-600 text-white py-3 rounded-lg font-semibold hover:bg-orange-700 transition">
                    Send Message
                </button>
            </form>
        </div>
    </div>

    <!-- Google Map -->
    <div class="mt-12">
        <h3 class="text-2xl font-bold text-gray-900 mb-6 text-center">Find Us On Map</h3>
        <div class="rounded-lg overflow-hidden shadow-lg">
            <iframe 
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3502.0184094609234!2d77.2090212!3d28.6139391!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x390ce2daa9eb4d0b%3A0x717971125923e5d!2sConnaught%20Place%2C%20New%20Delhi%2C%20Delhi!5e0!3m2!1sen!2sin!4v1234567890123!5m2!1sen!2sin" 
                width="100%" 
                height="450" 
                style="border:0;" 
                allowfullscreen="" 
                loading="lazy" 
                referrerpolicy="no-referrer-when-downgrade">
            </iframe>
        </div>
    </div>
</div>
@endsection
