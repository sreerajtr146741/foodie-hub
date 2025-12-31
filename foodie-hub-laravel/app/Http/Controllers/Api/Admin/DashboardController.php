<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\User;
use App\Models\Food;
use App\Models\Booking;
use App\Models\Review;

class DashboardController extends Controller
{
    /**
     * Get dashboard statistics
     */
    public function index()
    {
        try {
            // Basic Stats
            $stats = [
                'total_orders' => Order::count(),
                'total_revenue' => Order::where('status', 'delivered')->sum('total_price'),
                'total_customers' => User::where('role', 'customer')->count(),
                'today_orders' => Order::whereDate('created_at', today())->count(),
                'total_bookings' => Booking::count(),
                'pending_bookings' => Booking::where('status', 'pending')->count(),
                'total_products' => Food::count(),
                'active_products' => Food::where('is_available', true)->count(),
            ];

            // Revenue Stats
            $revenue = [
                'today' => Order::where('status', 'delivered')->whereDate('created_at', today())->sum('total_price'),
                'this_month' => Order::where('status', 'delivered')->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->sum('total_price'),
                'this_year' => Order::where('status', 'delivered')->whereYear('created_at', now()->year)->sum('total_price'),
            ];

            // User Stats
            $users = [
                'new_today' => User::whereDate('created_at', today())->count(),
                'new_this_month' => User::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count(),
                'verified' => User::where('is_verified', true)->count(),
            ];

            // Order Status Distribution
            $ordersByStatus = Order::select('status', DB::raw('count(*) as count'))
                ->groupBy('status')
                ->pluck('count', 'status');

            // Top Products
            $topProducts = DB::table('order_items')
                ->join('food', 'order_items.food_id', '=', 'food.id')
                ->select('food.id', 'food.name', DB::raw('SUM(order_items.quantity) as total_sold'))
                ->groupBy('food.id', 'food.name')
                ->orderBy('total_sold', 'desc')
                ->take(5)
                ->get();

            // Recent Orders
            $recentOrders = Order::with('user')->latest()->take(5)->get();

            return response()->json([
                'success' => true,
                'data' => [
                    'stats' => $stats,
                    'revenue' => $revenue,
                    'users' => $users,
                    'orders_by_status' => $ordersByStatus,
                    'top_products' => $topProducts,
                    'recent_orders' => $recentOrders,
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('API Admin Dashboard Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch dashboard data',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
