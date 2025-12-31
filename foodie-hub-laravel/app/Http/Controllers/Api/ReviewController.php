<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Review;
use App\Models\Food;

class ReviewController extends Controller
{
    /**
     * List reviews for a specific food item.
     * GET /foods/{foodId}/reviews
     */
    public function index($foodId)
    {
        try {
            $reviews = Review::with('user')
                ->where('food_id', $foodId)
                ->orderBy('created_at', 'desc')
                ->get();
            return response()->json(['success' => true, 'data' => $reviews]);
        } catch (\Exception $e) {
            Log::error('API Review Index Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to fetch reviews', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Store a new review (authenticated users only).
     * POST /reviews
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'food_id' => 'required|exists:food,id',
                'rating'  => 'required|integer|min:1|max:5',
                'comment' => 'nullable|string|max:1000',
            ]);

            $review = Review::create([
                'food_id' => $validated['food_id'],
                'user_id' => $request->user()->id,
                'rating'  => $validated['rating'],
                'comment' => $validated['comment'] ?? null,
            ]);

            return response()->json(['success' => true, 'message' => 'Review submitted', 'data' => $review], 201);
        } catch (\Exception $e) {
            Log::error('API Review Store Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to submit review', 'error' => $e->getMessage()], 500);
        }
    }
}
