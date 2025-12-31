<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\OrderStatusUpdated;

class OrderController extends Controller
{
    public function index() {
        try {
            $orders = Order::with('user', 'items.food')->latest()->get();
            return view('admin.orders.index', compact('orders'));
        } catch (\Exception $e) {
            Log::error('Admin Order Index Error: ' . $e->getMessage());
            return back()->with('error', 'Unable to load orders.');
        }
    }

    public function updateStatus(Request $request, Order $order) {
        try {
            $request->validate(['status' => 'required']);
            $order->update(['status' => $request->status]);

            // Send Email
            try {
                Mail::to($order->user->email)->send(new OrderStatusUpdated($order));
            } catch (\Exception $e) {
                Log::error('Order Status Email Error: ' . $e->getMessage());
                // Continue even if email fails
            }

            return back()->with('success', 'Order status updated');
        } catch (\Exception $e) {
            Log::error('Admin Update Order Status Error: ' . $e->getMessage());
            return back()->with('error', 'Unable to update order status.');
        }
    }
}
