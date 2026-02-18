<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingFoodOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\BookingConfirmation;

class BookingController extends Controller
{
    /**
     * List all bookings
     */
    public function index()
    {
        try {
            $bookings = Booking::with(['user', 'foodOrders.food'])
                ->orderBy('booking_date', 'desc')
                ->orderBy('booking_time', 'desc')
                ->get();
            return response()->json(['success' => true, 'data' => $bookings]);
        } catch (\Exception $e) {
            Log::error('API Admin Booking Index Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to fetch bookings'], 500);
        }
    }

    /**
     * Show a booking
     */
    public function show($id)
    {
        try {
            $booking = Booking::with(['user', 'foodOrders.food'])->findOrFail($id);
            return response()->json(['success' => true, 'data' => $booking]);
        } catch (\Exception $e) {
            Log::error('API Admin Booking Show Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Booking not found'], 404);
        }
    }

    /**
     * Update booking status and details
     */
    public function updateStatus(Request $request, $id)
    {
        try {
            $booking = Booking::findOrFail($id);

            // Handle table assignment
            if ($request->has('table_number')) {
                $booking->update(['table_number' => $request->table_number]);
                $table = \App\Models\RestaurantTable::where('table_number', $request->table_number)->first();
                if ($table) {
                    $table->update(['status' => 'booked']);
                }
                return response()->json(['success' => true, 'message' => 'Table assigned', 'data' => $booking]);
            }

            // Handle booking status update
            if ($request->has('status')) {
                $booking->update(['status' => $request->status]);
                
                if ($booking->table_number) {
                    $table = \App\Models\RestaurantTable::where('table_number', $booking->table_number)->first();
                    if ($table) {
                        if ($request->status === 'confirmed') {
                            $table->update(['status' => 'booked']);
                        } elseif ($request->status === 'completed' || $request->status === 'cancelled') {
                            $table->update(['status' => 'available']);
                        }
                    }
                }

                if ($request->status === 'confirmed') {
                    try {
                        Mail::to($booking->email)->send(new BookingConfirmation($booking));
                    } catch (\Exception $e) {
                        Log::error('API Booking Confirm Email Error: ' . $e->getMessage());
                    }
                }

                if ($request->status === 'completed') {
                    try {
                        Mail::to($booking->email)->send(new \App\Mail\BookingInvoice($booking));
                    } catch (\Exception $e) {
                        Log::error('API Booking Invoice Email Error: ' . $e->getMessage());
                    }
                }
                
                return response()->json(['success' => true, 'message' => 'Booking status updated', 'data' => $booking]);
            }

            // Handle food order status update
            if ($request->has('food_order_id') && $request->has('food_status')) {
                $foodOrder = BookingFoodOrder::findOrFail($request->food_order_id);
                $foodOrder->update(['food_status' => $request->food_status]);
                return response()->json(['success' => true, 'message' => 'Food status updated']);
            }

            // Handle adding food item
            if ($request->has('add_food_id') && $request->has('quantity')) {
                $item = BookingFoodOrder::create([
                    'booking_id' => $booking->id,
                    'food_id' => $request->add_food_id,
                    'quantity' => $request->quantity,
                    'food_status' => 'served',
                ]);
                return response()->json(['success' => true, 'message' => 'Food item added', 'data' => $item]);
            }

            // Handle removing food item
            if ($request->has('remove_food_order_id')) {
                BookingFoodOrder::findOrFail($request->remove_food_order_id)->delete();
                return response()->json(['success' => true, 'message' => 'Food item removed']);
            }

            // Handle payment settlement
            if ($request->has('settle_payment')) {
                $total = 0;
                $booking->load('foodOrders.food');
                foreach($booking->foodOrders as $order) {
                    $total += $order->food->price * $order->quantity;
                }
                
                $booking->update([
                    'total_amount' => $total,
                    'payment_status' => 'paid',
                    'payment_method' => $request->payment_method ?? 'Cash',
                    'status' => 'completed'
                ]);

                if ($booking->table_number) {
                    $table = \App\Models\RestaurantTable::where('table_number', $booking->table_number)->first();
                    if ($table) {
                        $table->update(['status' => 'available']);
                    }
                }

                return response()->json(['success' => true, 'message' => 'Payment settled', 'total' => $total]);
            }

            // Handle admin notes
            if ($request->has('admin_notes')) {
                $booking->update(['admin_notes' => $request->admin_notes]);
                return response()->json(['success' => true, 'message' => 'Notes saved']);
            }

            return response()->json(['success' => false, 'message' => 'Invalid request'], 400);
        } catch (\Exception $e) {
            Log::error('API Admin Update Booking Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to update booking'], 500);
        }
    }

    /**
     * Delete a booking
     */
    public function destroy($id)
    {
        try {
            $booking = Booking::findOrFail($id);
            $booking->delete();
            return response()->json(['success' => true, 'message' => 'Booking deleted']);
        } catch (\Exception $e) {
            Log::error('API Admin Booking Delete Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to delete booking'], 500);
        }
    }
}
