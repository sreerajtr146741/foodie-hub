<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Food;
use App\Models\BookingFoodOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\BookingConfirmation;

class BookingController extends Controller
{
    public function create()
    {
        try {
            $foods = Food::where('is_available', 1)->get();
            return view('bookings.create', compact('foods'));
        } catch (\Exception $e) {
            Log::error('Booking Page Error: ' . $e->getMessage());
            return back()->with('error', 'Unable to load booking page.');
        }
    }

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

            $validated['user_id'] = Auth::id();
            $validated['status'] = 'pending';
            $validated['window_side'] = $request->has('window_side');

            // Create booking
            $booking = Booking::create($validated);

            // Add food pre-orders if any
            if ($request->has('food_items') && is_array($request->food_items)) {
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

            // Send confirmation email
            try {
                Mail::to($booking->email)->send(new BookingConfirmation($booking));
            } catch (\Exception $e) {
                Log::error('Booking Confirmation Email Error: ' . $e->getMessage());
                // Continue even if email fails
            }

            return redirect()->route('my.bookings')->with('success', 'Table booked successfully!');
        } catch (\Exception $e) {
            Log::error('Create Booking Error: ' . $e->getMessage());
            return back()->with('error', 'Unable to create booking. Please try again.');
        }
    }

    public function myBookings()
    {
        try {
            $bookings = Booking::where('user_id', Auth::id())
                ->orderBy('booking_date', 'desc')
                ->orderBy('booking_time', 'desc')
                ->get();

            return view('bookings.my-bookings', compact('bookings'));
        } catch (\Exception $e) {
            Log::error('My Bookings Error: ' . $e->getMessage());
            return back()->with('error', 'Unable to load bookings.');
        }
    }

    public function cancel($id)
    {
        try {
            $booking = Booking::where('id', $id)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            if ($booking->status === 'pending' || $booking->status === 'confirmed') {
                $booking->update(['status' => 'cancelled']);
                return back()->with('success', 'Booking cancelled successfully.');
            }

            return back()->with('error', 'Cannot cancel this booking.');
        } catch (\Exception $e) {
            Log::error('Cancel Booking Error: ' . $e->getMessage());
            return back()->with('error', 'Unable to cancel booking.');
        }
    }
}
