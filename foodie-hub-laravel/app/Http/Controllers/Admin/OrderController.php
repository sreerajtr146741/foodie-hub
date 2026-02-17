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
            $foods = \App\Models\Food::where('is_available', 1)->get();
            return view('admin.orders.index', compact('orders', 'foods'));
        } catch (\Exception $e) {
            Log::error('Admin Order Index Error: ' . $e->getMessage());
            return back()->with('error', 'Unable to load orders.');
        }
    }

    public function addItem(Request $request, Order $order) {
        try {
            $request->validate([
                'food_id' => 'required|exists:food,id',
                'quantity' => 'required|integer|min:1'
            ]);

            $food = \App\Models\Food::findOrFail($request->food_id);

            // Check if item already exists in order
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

            return back()->with('success', 'Item added to order successfully.');
        } catch (\Exception $e) {
            Log::error('Admin Add Order Item Error: ' . $e->getMessage());
            return back()->with('error', 'Unable to add item.');
        }
    }

    public function removeItem(Request $request, Order $order, \App\Models\OrderItem $item) {
        try {
            $item->delete();
            $this->updateTotalPrice($order);
            return back()->with('success', 'Item removed from order successfully.');
        } catch (\Exception $e) {
            Log::error('Admin Remove Order Item Error: ' . $e->getMessage());
            return back()->with('error', 'Unable to remove item.');
        }
    }

    private function updateTotalPrice(Order $order) {
        $total = $order->items->sum(function($item) {
            return $item->price * $item->quantity;
        });
        $order->update(['total_price' => $total]);
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
