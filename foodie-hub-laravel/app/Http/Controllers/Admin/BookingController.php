<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingFoodOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\BookingConfirmation;

class BookingController extends Controller
{
    public function index()
    {
        try {
            $bookings = Booking::with('user', 'foodOrders.food')
                ->orderBy('booking_date', 'desc')
                ->orderBy('booking_time', 'desc')
                ->get();

            $availableTables = \App\Models\RestaurantTable::where('status', 'available')->get();
            $foods = \App\Models\Food::where('is_available', 1)->get();

            return view('admin.bookings.index', compact('bookings', 'availableTables', 'foods'));
        } catch (\Exception $e) {
            Log::error('Admin Bookings Index Error: ' . $e->getMessage());
            return back()->with('error', 'Unable to load bookings.');
        }
    }

    public function updateStatus(Request $request, $id)
    {
        try {
            $booking = Booking::findOrFail($id);

            // Handle table assignment
            if ($request->has('table_number')) {
                $booking->update(['table_number' => $request->table_number]);
                
                // Also update table status to booked if assigned
                $table = \App\Models\RestaurantTable::where('table_number', $request->table_number)->first();
                if ($table) {
                    $table->update(['status' => 'booked']);
                }
                
                return back()->with('success', 'Table number assigned successfully.');
            }

            // Handle booking status update
            if ($request->has('status')) {
                $booking->update(['status' => $request->status]);
                
                // Update Table Status if table_number is assigned
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

                // Send email when confirmed
                if ($request->status === 'confirmed') {
                    try {
                        Mail::to($booking->email)->send(new BookingConfirmation($booking));
                    } catch (\Exception $e) {
                        Log::error('Booking Confirm Email Error: ' . $e->getMessage());
                    }
                }

                // Send Invoice email when completed
                if ($request->status === 'completed') {
                    try {
                        Mail::to($booking->email)->send(new \App\Mail\BookingInvoice($booking));
                    } catch (\Exception $e) {
                        Log::error('Booking Invoice Email Error: ' . $e->getMessage());
                    }
                }
                
                return back()->with('success', 'Booking status updated successfully.');
            }

            // Handle food order status update
            if ($request->has('food_order_id') && $request->has('food_status')) {
                $foodOrder = BookingFoodOrder::findOrFail($request->food_order_id);
                $foodOrder->update(['food_status' => $request->food_status]);
                return back()->with('success', 'Food order status updated successfully.');
            }

            // Handle adding food item
            if ($request->has('add_food_id') && $request->has('quantity')) {
                BookingFoodOrder::create([
                    'booking_id' => $booking->id,
                    'food_id' => $request->add_food_id,
                    'quantity' => $request->quantity,
                    'food_status' => 'served', // Assuming served if added at shop
                ]);
                return back()->with('success', 'Food item added successfully.');
            }

            // Handle removing food item
            if ($request->has('remove_food_order_id')) {
                BookingFoodOrder::findOrFail($request->remove_food_order_id)->delete();
                return back()->with('success', 'Food item removed successfully.');
            }

            // Handle payment settlement
            if ($request->has('settle_payment')) {
                $total = 0;
                foreach($booking->foodOrders as $order) {
                    $total += $order->food->price * $order->quantity;
                }
                
                $booking->update([
                    'total_amount' => $total,
                    'payment_status' => 'paid',
                    'payment_method' => $request->payment_method ?? 'Cash',
                    'status' => 'completed'
                ]);

                // Update Table Status
                if ($booking->table_number) {
                    $table = \App\Models\RestaurantTable::where('table_number', $booking->table_number)->first();
                    if ($table) {
                        $table->update(['status' => 'available']);
                    }
                }

                return back()->with('success', 'Payment settled and booking completed. Total: ₹' . $total);
            }

            // Handle admin notes
            if ($request->has('admin_notes')) {
                $booking->update(['admin_notes' => $request->admin_notes]);
                return back()->with('success', 'Admin notes saved successfully.');
            }

            return back()->with('error', 'Invalid request.');
        } catch (\Exception $e) {
            Log::error('Admin Update Booking Error: ' . $e->getMessage());
            return back()->with('error', 'Unable to update booking.');
        }
    }

    public function destroy($id)
    {
        try {
            $booking = Booking::findOrFail($id);
            $booking->delete();

            return back()->with('success', 'Booking deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Admin Delete Booking Error: ' . $e->getMessage());
            return back()->with('error', 'Unable to delete booking.');
        }
    }
}
