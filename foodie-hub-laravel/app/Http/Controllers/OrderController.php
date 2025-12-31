<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\OrderPlaced;

class OrderController extends Controller
{
    public function checkout() {
        try {
            $cart = Cart::with('items.food')->where('user_id', Auth::id())->first();
            
            if(!$cart || $cart->items->isEmpty()) {
                return redirect()->route('cart')->with('error', 'Cart is empty');
            }

            $total = $cart->items->sum(function($item){
                return $item->food->price * $item->quantity;
            });

            return view('checkout', compact('cart', 'total'));
        } catch (\Exception $e) {
            Log::error('Checkout Error: ' . $e->getMessage());
            return redirect()->route('cart')->with('error', 'Unable to proceed to checkout.');
        }
    }

    public function placeOrder(Request $request) {
        try {
            $request->validate([
                'address' => 'required'
            ]);

            $cart = Cart::with('items.food')->where('user_id', Auth::id())->first();
            
            if(!$cart || $cart->items->isEmpty()) {
                return redirect()->route('cart')->with('error', 'Cart is empty');
            }

            $total = $cart->items->sum(function($item){
                return $item->food->price * $item->quantity;
            });

            // Create Order
            $order = Order::create([
                'user_id' => Auth::id(),
                'status' => 'placed',
                'total_price' => $total,
                'shipping_address' => $request->address
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
                Mail::to(Auth::user()->email)->send(new OrderPlaced($order));
            } catch (\Exception $e) {
                Log::error('Order Confirmation Email Error: ' . $e->getMessage());
                // Don't fail the order if email fails
            }

            return redirect()->route('orders.show', $order)->with('success', 'Order placed successfully!');
        } catch (\Exception $e) {
            Log::error('Place Order Error: ' . $e->getMessage());
            return back()->with('error', 'Unable to place order. Please try again.');
        }
    }

    public function myOrders() {
        try {
            $orders = Order::where('user_id', Auth::id())->with('items.food')->latest()->get();
            return view('my-orders', compact('orders'));
        } catch (\Exception $e) {
            Log::error('My Orders Error: ' . $e->getMessage());
            return back()->with('error', 'Unable to load orders.');
        }
    }

    public function show(Order $order) {
        try {
            if ($order->user_id !== Auth::id()) {
                abort(403);
            }
            $order->load('items.food');
            return view('orders.show', compact('order'));
        } catch (\Exception $e) {
            Log::error('Order Details Error: ' . $e->getMessage());
            return redirect()->route('my-orders')->with('error', 'Unable to load order details.');
        }
    }

    public function cancel(Order $order) {
        try {
            if ($order->user_id !== Auth::id()) {
                abort(403);
            }

            if ($order->status !== 'placed') {
                return back()->with('error', 'Only placed orders can be cancelled.');
            }

            $order->update(['status' => 'cancelled']);
            return back()->with('success', 'Order cancelled successfully.');
        } catch (\Exception $e) {
            Log::error('Cancel Order Error: ' . $e->getMessage());
            return back()->with('error', 'Unable to cancel order.');
        }
    }

    public function downloadInvoice(Order $order) {
        try {
            if ($order->user_id !== Auth::id()) {
                abort(403);
            }

            if ($order->status !== 'delivered') {
                return back()->with('error', 'Invoice is available only after delivery.');
            }

            $order->load('items.food', 'user');
            return view('orders.invoice', compact('order'));
        } catch (\Exception $e) {
            Log::error('Download Invoice Error: ' . $e->getMessage());
            return back()->with('error', 'Unable to generate invoice.');
        }
    }
}
