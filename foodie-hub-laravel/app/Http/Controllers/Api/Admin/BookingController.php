<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Booking;

class BookingController extends Controller
{
    /**
     * List all bookings
     */
    public function index()
    {
        try {
            $bookings = Booking::with('foodOrders.food')->orderBy('created_at', 'desc')->get();
            return response()->json(['success' => true, 'data' => $bookings]);
        } catch (\Exception $e) {
            Log::error('API Admin Booking Index Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to fetch bookings', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Show a booking
     */
    public function show($id)
    {
        try {
            $booking = Booking::with('foodOrders.food')->findOrFail($id);
            return response()->json(['success' => true, 'data' => $booking]);
        } catch (\Exception $e) {
            Log::error('API Admin Booking Show Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Booking not found', 'error' => $e->getMessage()], 404);
        }
    }

    /**
     * Update booking status
     */
    public function updateStatus(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'status' => 'required|in:pending,confirmed,cancelled,completed',
            ]);
            $booking = Booking::findOrFail($id);
            $booking->status = $validated['status'];
            $booking->save();
            return response()->json(['success' => true, 'message' => 'Booking status updated', 'data' => $booking]);
        } catch (\Exception $e) {
            Log::error('API Admin Booking Update Status Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to update booking', 'error' => $e->getMessage()], 500);
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
            return response()->json(['success' => false, 'message' => 'Failed to delete booking', 'error' => $e->getMessage()], 500);
        }
    }
}
