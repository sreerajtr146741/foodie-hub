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

            return view('admin.bookings.index', compact('bookings'));
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
                return back()->with('success', 'Table number assigned successfully.');
            }

            // Handle booking status update
            if ($request->has('status')) {
                $booking->update(['status' => $request->status]);
                
                // Send email when confirmed
                if ($request->status === 'confirmed') {
                    try {
                        Mail::to($booking->email)->send(new BookingConfirmation($booking));
                    } catch (\Exception $e) {
                        Log::error('Booking Confirm Email Error: ' . $e->getMessage());
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
