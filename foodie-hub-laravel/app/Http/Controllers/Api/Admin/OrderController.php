<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Models\Order;
use App\Mail\OrderStatusUpdated;

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
            return response()->json(['success' => false, 'message' => 'Failed to fetch orders'], 500);
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
            return response()->json(['success' => false, 'message' => 'Order not found'], 404);
        }
    }

    /**
     * Update order status
     */
    public function updateStatus(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'status' => 'required',
            ]);
            $order = Order::with('user')->findOrFail($id);
            $order->status = $validated['status'];
            $order->save();

            // Send Email
            try {
                Mail::to($order->user->email)->send(new OrderStatusUpdated($order));
            } catch (\Exception $e) {
                Log::error('API Order Status Email Error: ' . $e->getMessage());
            }

            return response()->json(['success' => true, 'message' => 'Order status updated', 'data' => $order]);
        } catch (\Exception $e) {
            Log::error('API Admin Order Update Status Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to update order'], 500);
        }
    }

    public function addItem(Request $request, $id)
    {
        try {
            $order = Order::findOrFail($id);
            $request->validate([
                'food_id' => 'required|exists:food,id',
                'quantity' => 'required|integer|min:1'
            ]);

            $food = \App\Models\Food::findOrFail($request->food_id);

            $existingItem = $order->items()->where('food_id', $request->food_id)->first();

            if ($existingItem) {
                $existingItem->increment('quantity', $request->quantity);
            } else {
                $order->items()->create([
                    'food_id' => $request->food_id,
                    'quantity' => $request->quantity,
                    'price' => $food->price
                ]);
            }

            $this->updateTotalPrice($order);

            return response()->json(['success' => true, 'message' => 'Item added to order', 'data' => $order->load('items.food')]);
        } catch (\Exception $e) {
            Log::error('API Admin Add Order Item Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Unable to add item'], 500);
        }
    }

    public function removeItem(Request $request, $id, $itemId)
    {
        try {
            $order = Order::findOrFail($id);
            $item = $order->items()->findOrFail($itemId);
            $item->delete();
            $this->updateTotalPrice($order);
            
            return response()->json(['success' => true, 'message' => 'Item removed from order']);
        } catch (\Exception $e) {
            Log::error('API Admin Remove Order Item Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Unable to remove item'], 500);
        }
    }

    private function updateTotalPrice(Order $order) {
        $total = $order->items->sum(function($item) {
            return $item->price * $item->quantity;
        });
        $order->update(['total_price' => $total]);
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
            return response()->json(['success' => false, 'message' => 'Failed to delete order'], 500);
        }
    }
}
