<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Order;

class OrderController extends Controller
{
    /**
     * List all orders
     */
    public function index()
    {
        try {
            $orders = Order::with(['user', 'items.food'])->orderBy('created_at', 'desc')->get();
            return response()->json(['success' => true, 'data' => $orders]);
        } catch (\Exception $e) {
            Log::error('API Admin Order Index Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to fetch orders', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Show a specific order
     */
    public function show($id)
    {
        try {
            $order = Order::with(['user', 'items.food'])->findOrFail($id);
            return response()->json(['success' => true, 'data' => $order]);
        } catch (\Exception $e) {
            Log::error('API Admin Order Show Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Order not found', 'error' => $e->getMessage()], 404);
        }
    }

    /**
     * Update order status
     */
    public function updateStatus(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'status' => 'required|in:placed,processing,ready,delivered,cancelled,returned',
            ]);
            $order = Order::findOrFail($id);
            $order->status = $validated['status'];
            $order->save();
            return response()->json(['success' => true, 'message' => 'Order status updated', 'data' => $order]);
        } catch (\Exception $e) {
            Log::error('API Admin Order Update Status Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to update order', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Delete an order
     */
    public function destroy($id)
    {
        try {
            $order = Order::findOrFail($id);
            $order->delete();
            return response()->json(['success' => true, 'message' => 'Order deleted']);
        } catch (\Exception $e) {
            Log::error('API Admin Order Delete Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to delete order', 'error' => $e->getMessage()], 500);
        }
    }
}
