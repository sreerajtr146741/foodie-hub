@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-900">Dashboard</h1>
        <div class="text-sm text-gray-500">Last updated: {{ now()->format('M d, Y h:i A') }}</div>
    </div>

    <!-- Quick Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Revenue -->
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium">Total Revenue</p>
                    <p class="text-3xl font-bold mt-2">₹{{ number_format($totalRevenue, 2) }}</p>
                    <p class="text-green-100 text-xs mt-2">Today: ₹{{ number_format($todayRevenue, 2) }}</p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-full p-3">
                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"/>
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Orders -->
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium">Total Orders</p>
                    <p class="text-3xl font-bold mt-2">{{ $totalOrders }}</p>
                    <p class="text-blue-100 text-xs mt-2">Today: {{ $todayOrders }}</p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-full p-3">
                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Customers -->
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm font-medium">Total Customers</p>
                    <p class="text-3xl font-bold mt-2">{{ $totalCustomers }}</p>
                    <p class="text-purple-100 text-xs mt-2">New Today: {{ $newUsersToday }}</p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-full p-3">
                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Products -->
        <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-orange-100 text-sm font-medium">Total Products</p>
                    <p class="text-3xl font-bold mt-2">{{ $totalProducts }}</p>
                    <p class="text-orange-100 text-xs mt-2">Active: {{ $activeProducts }}</p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-full p-3">
                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Revenue & User Stats -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Revenue Breakdown -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Revenue Breakdown</h2>
            <div class="space-y-4">
                <div class="flex justify-between items-center p-4 bg-green-50 rounded-lg">
                    <div>
                        <p class="text-sm text-gray-600">Today's Revenue</p>
                        <p class="text-2xl font-bold text-green-600">₹{{ number_format($todayRevenue, 2) }}</p>
                    </div>
                    <div class="text-green-600">
                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v3.586L7.707 9.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 10.586V7z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </div>
                <div class="flex justify-between items-center p-4 bg-blue-50 rounded-lg">
                    <div>
                        <p class="text-sm text-gray-600">This Month</p>
                        <p class="text-2xl font-bold text-blue-600">₹{{ number_format($monthRevenue, 2) }}</p>
                    </div>
                    <div class="text-blue-600">
                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </div>
                <div class="flex justify-between items-center p-4 bg-purple-50 rounded-lg">
                    <div>
                        <p class="text-sm text-gray-600">This Year</p>
                        <p class="text-2xl font-bold text-purple-600">₹{{ number_format($yearRevenue, 2) }}</p>
                    </div>
                    <div class="text-purple-600">
                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V4a2 2 0 00-2-2H6zm1 2a1 1 0 000 2h6a1 1 0 100-2H7zm6 7a1 1 0 011 1v3a1 1 0 11-2 0v-3a1 1 0 011-1zm-3 3a1 1 0 100 2h.01a1 1 0 100-2H10zm-4 1a1 1 0 011-1h.01a1 1 0 110 2H7a1 1 0 01-1-1zm1-4a1 1 0 100 2h.01a1 1 0 100-2H7zm2 1a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1zm4-4a1 1 0 100 2h.01a1 1 0 100-2H13zM9 9a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1zM7 8a1 1 0 000 2h.01a1 1 0 000-2H7z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- User Stats -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">User Statistics</h2>
            <div class="space-y-4">
                <div class="flex justify-between items-center p-4 bg-gray-50 rounded-lg">
                    <div class="flex-1">
                        <p class="text-sm text-gray-600">Total Customers</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $totalCustomers }}</p>
                    </div>
                    <div class="w-24 bg-gray-200 rounded-full h-2">
                        <div class="bg-purple-600 h-2 rounded-full" style="width: 100%"></div>
                    </div>
                </div>
                <div class="flex justify-between items-center p-4 bg-gray-50 rounded-lg">
                    <div class="flex-1">
                        <p class="text-sm text-gray-600">Verified Users</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $verifiedUsers }}</p>
                    </div>
                    <div class="w-24 bg-gray-200 rounded-full h-2">
                        <div class="bg-green-600 h-2 rounded-full" style="width: {{ $totalCustomers > 0 ? ($verifiedUsers / $totalCustomers * 100) : 0 }}%"></div>
                    </div>
                </div>
                <div class="flex justify-between items-center p-4 bg-gray-50 rounded-lg">
                    <div class="flex-1">
                        <p class="text-sm text-gray-600">New This Month</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $newUsersThisMonth }}</p>
                    </div>
                    <div class="text-blue-600">
                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts & Top Products -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Top Selling Products -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Top Selling Products</h2>
            <div class="space-y-3">
                @forelse($topProducts as $index => $product)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0 w-8 h-8 bg-orange-100 text-orange-600 rounded-full flex items-center justify-center font-bold">
                                {{ $index + 1 }}
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900">{{ $product->name }}</p>
                                <p class="text-sm text-gray-500">{{ $product->total_sold }} sold</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="w-24 bg-gray-200 rounded-full h-2">
                                <div class="bg-orange-600 h-2 rounded-full" style="width: {{ ($product->total_sold / $topProducts->max('total_sold')) * 100 }}%"></div>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 text-center py-4">No sales data available</p>
                @endforelse
            </div>
        </div>

        <!-- Order Status Distribution -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Order Status Distribution</h2>
            <div class="space-y-3">
                @php
                    $statusColors = [
                        'placed' => 'blue',
                        'processing' => 'yellow',
                        'ready' => 'purple',
                        'delivered' => 'green',
                        'cancelled' => 'red'
                    ];
                @endphp
                @foreach(['placed', 'processing', 'ready', 'delivered', 'cancelled'] as $status)
                    @php
                        $count = $ordersByStatus[$status] ?? 0;
                        $percentage = $totalOrders > 0 ? ($count / $totalOrders * 100) : 0;
                        $color = $statusColors[$status];
                    @endphp
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3 flex-1">
                            <span class="w-3 h-3 bg-{{ $color }}-500 rounded-full"></span>
                            <span class="text-gray-700 capitalize">{{ $status }}</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <div class="w-32 bg-gray-200 rounded-full h-2">
                                <div class="bg-{{ $color }}-500 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                            </div>
                            <span class="text-gray-900 font-semibold w-12 text-right">{{ $count }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Recent Orders -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-bold text-gray-900">Recent Orders</h2>
            </div>
            <div class="divide-y divide-gray-200">
                @forelse($recentOrders as $order)
                    <div class="p-4 hover:bg-gray-50">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="font-semibold text-gray-900">#{{ $order->id }}</p>
                                <p class="text-sm text-gray-600">{{ $order->user->name }}</p>
                            </div>
                            <span class="px-2 py-1 text-xs font-semibold rounded-full
                                {{ $order->status == 'delivered' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $order->status == 'placed' ? 'bg-blue-100 text-blue-800' : '' }}
                                {{ $order->status == 'processing' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $order->status == 'cancelled' ? 'bg-red-100 text-red-800' : '' }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>
                        <p class="text-sm text-gray-500 mt-1">₹{{ number_format($order->total_price, 2) }}</p>
                    </div>
                @empty
                    <p class="p-4 text-gray-500 text-center">No recent orders</p>
                @endforelse
            </div>
        </div>

        <!-- Recent Customers -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-bold text-gray-900">New Customers</h2>
            </div>
            <div class="divide-y divide-gray-200">
                @forelse($recentUsers as $user)
                    <div class="p-4 hover:bg-gray-50">
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0 w-10 h-10 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center font-bold">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-gray-900 truncate">{{ $user->name }}</p>
                                <p class="text-sm text-gray-500 truncate">{{ $user->email }}</p>
                            </div>
                        </div>
                        <p class="text-xs text-gray-400 mt-2">{{ $user->created_at->diffForHumans() }}</p>
                    </div>
                @empty
                    <p class="p-4 text-gray-500 text-center">No new customers</p>
                @endforelse
            </div>
        </div>

        <!-- Recent Reviews -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-bold text-gray-900">Recent Reviews</h2>
            </div>
            <div class="divide-y divide-gray-200">
                @forelse($recentReviews as $review)
                    <div class="p-4 hover:bg-gray-50">
                        <div class="flex items-center justify-between mb-2">
                            <p class="font-semibold text-gray-900 text-sm">{{ $review->user->name }}</p>
                            <div class="flex text-yellow-400">
                                @for($i = 1; $i <= 5; $i++)
                                    <span class="text-xs">{{ $i <= $review->rating ? '★' : '☆' }}</span>
                                @endfor
                            </div>
                        </div>
                        <p class="text-sm text-gray-600">{{ $review->food->name }}</p>
                        @if($review->comment)
                            <p class="text-xs text-gray-500 mt-1 line-clamp-2">{{ $review->comment }}</p>
                        @endif
                    </div>
                @empty
                    <p class="p-4 text-gray-500 text-center">No recent reviews</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
