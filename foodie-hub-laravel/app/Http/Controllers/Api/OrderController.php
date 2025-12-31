<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Models\Order;
use App\Models\Cart;
use App\Mail\OrderPlaced;

class OrderController extends Controller
{
    /**
     * Get user's orders
     */
    public function index(Request $request)
    {
        try {
            $orders = Order::where('user_id', $request->user()->id)
                ->with('items.food')
                ->latest()
                ->paginate($request->per_page ?? 15);

            return response()->json([
                'success' => true,
                'data' => $orders
            ]);
        } catch (\Exception $e) {
            Log::error('API Get Orders Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch orders',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get single order
     */
    public function show(Request $request, $id)
    {
        try {
            $order = Order::where('user_id', $request->user()->id)
                ->with('items.food')
                ->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $order
            ]);
        } catch (\Exception $e) {
            Log::error('API Get Order Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Order not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Place new order
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'shipping_address' => 'required|string'
            ]);

            $cart = Cart::with('items.food')
                ->where('user_id', $request->user()->id)
                ->first();

            if (!$cart || $cart->items->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cart is empty'
                ], 400);
            }

            $total = $cart->items->sum(function($item) {
                return $item->food->price * $item->quantity;
            });

            // Create Order
            $order = Order::create([
                'user_id' => $request->user()->id,
                'status' => 'placed',
                'total_price' => $total,
                'shipping_address' => $validated['shipping_address']
            ]);

            // Create Order Items
            foreach($cart->items as $item) {
                $order->items()->create([
                    'food_id' => $item->food_id,
                    'quantity' => $item->quantity,
                    'price' => $item->food->price
                ]);
            }

            // Clear Cart
            $cart->items()->delete();

            // Send Email
            try {
                Mail::to($request->user()->email)->send(new OrderPlaced($order));
            } catch (\Exception $e) {
                Log::error('Order Email Error: ' . $e->getMessage());
            }

            $order->load('items.food');

            return response()->json([
                'success' => true,
                'message' => 'Order placed successfully',
                'data' => $order
            ], 201);
        } catch (\Exception $e) {
            Log::error('API Place Order Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to place order',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cancel order
     */
    public function cancel(Request $request, $id)
    {
        try {
            $order = Order::where('user_id', $request->user()->id)->findOrFail($id);

            if ($order->status !== 'placed') {
                return response()->json([
                    'success' => false,
                    'message' => 'Only placed orders can be cancelled'
                ], 400);
            }

            $order->update(['status' => 'cancelled']);

            return response()->json([
                'success' => true,
                'message' => 'Order cancelled successfully',
                'data' => $order
            ]);
        } catch (\Exception $e) {
            Log::error('API Cancel Order Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to cancel order',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
