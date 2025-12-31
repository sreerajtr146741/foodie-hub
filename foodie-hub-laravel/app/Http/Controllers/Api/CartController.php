<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Food;

class CartController extends Controller
{
    /**
     * Get user's cart
     */
    public function index(Request $request)
    {
        try {
            $cart = Cart::with('items.food')->firstOrCreate([
                'user_id' => $request->user()->id
            ]);

            $total = $cart->items->sum(function($item) {
                return $item->food->price * $item->quantity;
            });

            return response()->json([
                'success' => true,
                'data' => [
                    'cart' => $cart,
                    'total' => $total,
                    'items_count' => $cart->items->count()
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('API Get Cart Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch cart',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Add item to cart
     */
    public function add(Request $request)
    {
        try {
            $validated = $request->validate([
                'food_id' => 'required|exists:food,id',
                'quantity' => 'required|integer|min:1'
            ]);

            $cart = Cart::firstOrCreate(['user_id' => $request->user()->id]);
            
            $cartItem = $cart->items()->where('food_id', $validated['food_id'])->first();
            
            if ($cartItem) {
                $cartItem->increment('quantity', $validated['quantity']);
            } else {
                $cart->items()->create([
                    'food_id' => $validated['food_id'],
                    'quantity' => $validated['quantity']
                ]);
            }

            $cart->load('items.food');

            return response()->json([
                'success' => true,
                'message' => 'Item added to cart',
                'data' => $cart
            ]);
        } catch (\Exception $e) {
            Log::error('API Add to Cart Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to add item to cart',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update cart item quantity
     */
    public function update(Request $request, $itemId)
    {
        try {
            $validated = $request->validate([
                'quantity' => 'required|integer|min:1'
            ]);

            $item = CartItem::findOrFail($itemId);
            
            // Verify ownership
            if ($item->cart->user_id !== $request->user()->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 403);
            }

            $item->update(['quantity' => $validated['quantity']]);

            return response()->json([
                'success' => true,
                'message' => 'Cart updated',
                'data' => $item
            ]);
        } catch (\Exception $e) {
            Log::error('API Update Cart Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update cart',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove item from cart
     */
    public function remove(Request $request, $itemId)
    {
        try {
            $item = CartItem::findOrFail($itemId);
            
            // Verify ownership
            if ($item->cart->user_id !== $request->user()->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 403);
            }

            $item->delete();

            return response()->json([
                'success' => true,
                'message' => 'Item removed from cart'
            ]);
        } catch (\Exception $e) {
            Log::error('API Remove from Cart Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to remove item',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Clear cart
     */
    public function clear(Request $request)
    {
        try {
            $cart = Cart::where('user_id', $request->user()->id)->first();
            
            if ($cart) {
                $cart->items()->delete();
            }

            return response()->json([
                'success' => true,
                'message' => 'Cart cleared'
            ]);
        } catch (\Exception $e) {
            Log::error('API Clear Cart Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to clear cart',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
