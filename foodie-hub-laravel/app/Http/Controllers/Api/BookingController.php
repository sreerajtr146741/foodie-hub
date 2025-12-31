<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Models\Booking;
use App\Models\Food;
use App\Models\BookingFoodOrder;
use App\Mail\BookingConfirmation;

class BookingController extends Controller
{
    /**
     * Get user's bookings
     */
    public function index(Request $request)
    {
        try {
            $bookings = Booking::where('user_id', $request->user()->id)
                ->with('foodOrders.food')
                ->latest()
                ->paginate($request->per_page ?? 15);

            return response()->json([
                'success' => true,
                'data' => $bookings
            ]);
        } catch (\Exception $e) {
            Log::error('API Get Bookings Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch bookings',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create new booking
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email',
                'phone' => 'required|string|max:20',
                'guests' => 'required|integer|min:1|max:20',
                'booking_date' => 'required|date|after_or_equal:today',
                'booking_time' => 'required',
                'table_type' => 'nullable|string',
                'seating_preference' => 'nullable|string',
                'window_side' => 'nullable|boolean',
                'occasion' => 'nullable|string',
                'special_requests' => 'nullable|string',
                'food_items' => 'nullable|array',
                'food_items.*.food_id' => 'required|exists:food,id',
                'food_items.*.quantity' => 'required|integer|min:1',
                'food_items.*.cooking_note' => 'nullable|string',
            ]);

            $validated['user_id'] = $request->user()->id;
            $validated['status'] = 'pending';
            $validated['window_side'] = $request->has('window_side');

            $booking = Booking::create($validated);

            // Add food pre-orders
            if ($request->has('food_items')) {
                foreach ($request->food_items as $item) {
                    BookingFoodOrder::create([
                        'booking_id' => $booking->id,
                        'food_id' => $item['food_id'],
                        'quantity' => $item['quantity'],
                        'cooking_note' => $item['cooking_note'] ?? null,
                        'food_status' => 'waiting',
                    ]);
                }
            }

            // Send email
            try {
                Mail::to($booking->email)->send(new BookingConfirmation($booking));
            } catch (\Exception $e) {
                Log::error('Booking Email Error: ' . $e->getMessage());
            }

            $booking->load('foodOrders.food');

            return response()->json([
                'success' => true,
                'message' => 'Booking created successfully',
                'data' => $booking
            ], 201);
        } catch (\Exception $e) {
            Log::error('API Create Booking Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to create booking',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cancel booking
     */
    public function cancel(Request $request, $id)
    {
        try {
            $booking = Booking::where('user_id', $request->user()->id)->findOrFail($id);

            if (!in_array($booking->status, ['pending', 'confirmed'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot cancel this booking'
                ], 400);
            }

            $booking->update(['status' => 'cancelled']);

            return response()->json([
                'success' => true,
                'message' => 'Booking cancelled successfully',
                'data' => $booking
            ]);
        } catch (\Exception $e) {
            Log::error('API Cancel Booking Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to cancel booking',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
