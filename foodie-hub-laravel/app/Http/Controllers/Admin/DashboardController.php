<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User;
use App\Models\Food;
use App\Models\Booking;
use App\Models\Review;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index() {
        try {
            // Basic Stats
            $totalOrders = Order::count();
            $totalRevenue = Order::where('status', 'delivered')->sum('total_price');
            $totalCustomers = User::where('role', 'customer')->count();
            $todayOrders = Order::whereDate('created_at', today())->count();
            $totalBookings = Booking::count();
            $pendingBookings = Booking::where('status', 'pending')->count();
            $totalProducts = Food::count();
            $activeProducts = Food::where('is_available', true)->count();
            
            // Revenue Stats
            $todayRevenue = Order::where('status', 'delivered')
                ->whereDate('created_at', today())
                ->sum('total_price');
            $monthRevenue = Order::where('status', 'delivered')
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('total_price');
            $yearRevenue = Order::where('status', 'delivered')
                ->whereYear('created_at', now()->year)
                ->sum('total_price');
            
            // User Stats
            $newUsersToday = User::whereDate('created_at', today())->count();
            $newUsersThisMonth = User::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count();
            $verifiedUsers = User::where('is_verified', true)->count();
            
            // Product Stats
            $outOfStock = Food::where('is_available', false)->count();
            $topProducts = DB::table('order_items')
                ->join('food', 'order_items.food_id', '=', 'food.id')
                ->select('food.name', DB::raw('SUM(order_items.quantity) as total_sold'))
                ->groupBy('food.id', 'food.name')
                ->orderBy('total_sold', 'desc')
                ->take(5)
                ->get();
            
            // Order Status Distribution
            $ordersByStatus = Order::select('status', DB::raw('count(*) as count'))
                ->groupBy('status')
                ->get()
                ->pluck('count', 'status');
            
            // Revenue Chart Data (Last 7 Days)
            $revenueChart = Order::where('status', 'delivered')
                ->where('created_at', '>=', now()->subDays(7))
                ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total_price) as revenue'))
                ->groupBy('date')
                ->orderBy('date')
                ->get();
            
            // Orders Chart Data (Last 7 Days)
            $ordersChart = Order::where('created_at', '>=', now()->subDays(7))
                ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'))
                ->groupBy('date')
                ->orderBy('date')
                ->get();
            
            // Recent Activity
            $recentOrders = Order::with('user')->latest()->take(5)->get();
            $recentUsers = User::where('role', 'customer')->latest()->take(5)->get();
            $recentReviews = Review::with('user', 'food')->latest()->take(5)->get();

            return view('admin.dashboard', compact(
                'totalOrders', 'totalRevenue', 'totalCustomers', 'todayOrders',
                'totalBookings', 'pendingBookings', 'totalProducts', 'activeProducts',
                'todayRevenue', 'monthRevenue', 'yearRevenue',
                'newUsersToday', 'newUsersThisMonth', 'verifiedUsers',
                'outOfStock', 'topProducts', 'ordersByStatus',
                'revenueChart', 'ordersChart',
                'recentOrders', 'recentUsers', 'recentReviews'
            ));
        } catch (\Exception $e) {
            Log::error('Admin Dashboard Error: ' . $e->getMessage());
            return back()->with('error', 'Unable to load dashboard stats.');
        }
    }
}
