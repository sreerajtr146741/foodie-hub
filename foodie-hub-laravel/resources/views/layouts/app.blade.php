<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'FoodieHub') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; }
    </style>
</head>
<body class="bg-gray-50">
    <nav class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <a href="{{ route('home') }}" class="flex-shrink-0 flex items-center text-xl font-bold text-orange-600">
                        Food Court
                    </a>
                </div>
                <div class="flex space-x-4 items-center">
                    <a href="{{ route('home') }}" class="text-gray-700 hover:text-orange-600 px-3 py-2 rounded-md text-sm font-medium">Home</a>
                    <a href="{{ route('menu') }}" class="text-gray-700 hover:text-orange-600 px-3 py-2 rounded-md text-sm font-medium">Menu</a>
                    <a href="{{ route('booking.create') }}" class="text-gray-700 hover:text-orange-600 px-3 py-2 rounded-md text-sm font-medium">Book Table</a>
                    <a href="{{ route('about') }}" class="text-gray-700 hover:text-orange-600 px-3 py-2 rounded-md text-sm font-medium">About Us</a>
                    <a href="{{ route('contact') }}" class="text-gray-700 hover:text-orange-600 px-3 py-2 rounded-md text-sm font-medium">Contact</a>
                    @auth
                        <a href="{{ route('cart') }}" class="text-gray-700 hover:text-orange-600 px-3 py-2 rounded-md text-sm font-medium">Cart</a>
                        <a href="{{ route('my-orders') }}" class="text-gray-700 hover:text-orange-600 px-3 py-2 rounded-md text-sm font-medium">My Orders</a>
                        <a href="{{ route('my.bookings') }}" class="text-gray-700 hover:text-orange-600 px-3 py-2 rounded-md text-sm font-medium">My Bookings</a>
                        @if(Auth::user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="text-orange-600 font-bold px-3 py-2 rounded-md text-sm">Admin Panel</a>
                        @endif
                        
                        <!-- Profile Dropdown -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="focus:outline-none">
                                @if(auth()->user()->avatar)
                                    <img class="h-10 w-10 rounded-full object-cover ring-2 ring-orange-500 hover:ring-orange-600 transition" src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="Avatar">
                                @else
                                    <div class="h-10 w-10 rounded-full bg-orange-500 hover:bg-orange-600 flex items-center justify-center text-white font-bold ring-2 ring-orange-200 transition cursor-pointer">
                                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                    </div>
                                @endif
                            </button>

                            <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-orange-50">
                                    <svg class="inline h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    Edit Profile
                                </a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                        <svg class="inline h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                        </svg>
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-700 hover:text-orange-600 px-3 py-2 rounded-md text-sm font-medium">Login</a>
                        <a href="{{ route('register') }}" class="bg-orange-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-orange-700">Register</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <main class="py-6 min-h-screen">
        @if(session('success'))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-4">
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    {{ session('success') }}
                </div>
            </div>
        @endif
        @if(session('error'))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-4">
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                    {{ session('error') }}
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    <footer class="bg-gray-800 text-white mt-12 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p>&copy; {{ date('Y') }} FoodieHub. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
