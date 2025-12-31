<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Food;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{
    public function index() {
        try {
            $cart = Cart::with('items.food')->firstOrCreate(['user_id' => Auth::id()]);
            return view('cart', compact('cart'));
        } catch (\Exception $e) {
            Log::error('Cart Page Error: ' . $e->getMessage());
            return back()->with('error', 'Unable to load cart.');
        }
    }

    public function add(Request $request, Food $food) {
        try {
            $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);
            
            $cartItem = $cart->items()->where('food_id', $food->id)->first();
            
            if ($cartItem) {
                $cartItem->increment('quantity', $request->quantity ?? 1);
            } else {
                $cart->items()->create([
                    'food_id' => $food->id,
                    'quantity' => $request->quantity ?? 1
                ]);
            }

            // If "Buy Now" clicked, redirect to checkout
            if ($request->has('buy_now')) {
                return redirect()->route('checkout');
            }

            return redirect()->back()->with('success', 'Item added to cart');
        } catch (\Exception $e) {
            Log::error('Add to Cart Error: ' . $e->getMessage());
            return back()->with('error', 'Unable to add item to cart.');
        }
    }

    public function update(Request $request, CartItem $item) {
        try {
            $item->update(['quantity' => $request->quantity]);
            return back()->with('success', 'Cart updated');
        } catch (\Exception $e) {
            Log::error('Update Cart Error: ' . $e->getMessage());
            return back()->with('error', 'Unable to update cart.');
        }
    }

    public function remove(CartItem $item) {
        try {
            $item->delete();
            return back()->with('success', 'Item removed');
        } catch (\Exception $e) {
            Log::error('Remove from Cart Error: ' . $e->getMessage());
            return back()->with('error', 'Unable to remove item from cart.');
        }
    }
}
