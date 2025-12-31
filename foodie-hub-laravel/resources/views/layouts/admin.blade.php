<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - FoodieHub</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Poppins', sans-serif; }</style>
</head>
<body class="bg-gray-100">
    <div class="flex h-screen bg-gray-100">
        <!-- Sidebar -->
        <div class="w-64 bg-gray-900 text-white space-y-6 py-7 px-2 absolute inset-y-0 left-0 transform -translate-x-full md:relative md:translate-x-0 transition duration-200 ease-in-out">
            <div class="flex items-center space-x-2 px-4">
                <span class="text-2xl font-extrabold text-orange-500">Food Court Admin</span>
            </div>
            
            <nav>
                <a href="{{ route('admin.dashboard') }}" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-800 {{ request()->routeIs('admin.dashboard') ? 'bg-gray-800 text-orange-500' : '' }}">
                    Dashboard
                </a>
                <a href="{{ route('admin.categories.index') }}" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-800 {{ request()->routeIs('admin.categories.*') ? 'bg-gray-800 text-orange-500' : '' }}">
                    Categories
                </a>
                <a href="{{ route('admin.foods.index') }}" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-800 {{ request()->routeIs('admin.foods.*') ? 'bg-gray-800 text-orange-500' : '' }}">
                    Food Items
                </a>
                <a href="{{ route('admin.orders.index') }}" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-800 {{ request()->routeIs('admin.orders.*') ? 'bg-gray-800 text-orange-500' : '' }}">
                    Orders
                </a>
                <a href="{{ route('admin.bookings.index') }}" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-800 {{ request()->routeIs('admin.bookings.*') ? 'bg-gray-800 text-orange-500' : '' }}">
                    Table Bookings
                </a>
                <a href="{{ route('admin.contact.messages.index') }}" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-800 {{ request()->routeIs('admin.contact.messages.*') ? 'bg-gray-800 text-orange-500' : '' }}">
                    Contact Messages
                </a>
                <!-- Link back to site -->
                <a href="{{ route('home') }}" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-800 mt-4 text-sm text-gray-400">
                    Back to Website
                </a>
                <form method="POST" action="{{ route('logout') }}" class="mt-4">
                    @csrf
                    <button type="submit" class="w-full text-left py-2.5 px-4 rounded transition duration-200 hover:bg-gray-800 text-red-400">Logout</button>
                </form>
            </nav>
        </div>

        <!-- Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <header class="flex justify-between items-center py-4 px-6 bg-white shadow-sm">
                <div class="flex items-center">
                    <h1 class="text-2xl font-semibold text-gray-800">Admin Dashboard</h1>
                </div>
            </header>
            
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
                 @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                        {{ session('success') }}
                    </div>
                @endif
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
