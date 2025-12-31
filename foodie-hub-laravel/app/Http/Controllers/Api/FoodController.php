<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Food;
use App\Models\Category;
use App\Models\Review;

class FoodController extends Controller
{
    /**
     * Get all food items
     */
    public function index(Request $request)
    {
        try {
            $query = Food::with('category')->where('is_available', true);

            // Search
            if ($request->has('search')) {
                $query->where(function($q) use ($request) {
                    $q->where('name', 'LIKE', '%' . $request->search . '%')
                      ->orWhere('description', 'LIKE', '%' . $request->search . '%');
                });
            }

            // Category filter
            if ($request->has('category_id')) {
                $query->where('category_id', $request->category_id);
            }

            // Price range
            if ($request->has('min_price')) {
                $query->where('price', '>=', $request->min_price);
            }
            if ($request->has('max_price')) {
                $query->where('price', '<=', $request->max_price);
            }

            $foods = $query->paginate($request->per_page ?? 15);

            return response()->json([
                'success' => true,
                'data' => $foods
            ]);
        } catch (\Exception $e) {
            Log::error('API Get Foods Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch food items',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get single food item
     */
    public function show($id)
    {
        try {
            $food = Food::with(['category', 'reviews.user'])->findOrFail($id);

            // Similar products
            $similarFoods = Food::where('category_id', $food->category_id)
                ->where('id', '!=', $food->id)
                ->take(4)
                ->get();

            return response()->json([
                'success' => true,
                'data' => [
                    'food' => $food,
                    'similar_foods' => $similarFoods,
                    'average_rating' => $food->reviews->avg('rating'),
                    'total_reviews' => $food->reviews->count(),
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('API Get Food Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Food item not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Get all categories
     */
    public function categories()
    {
        try {
            $categories = Category::withCount('foods')->get();

            return response()->json([
                'success' => true,
                'data' => $categories
            ]);
        } catch (\Exception $e) {
            Log::error('API Get Categories Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch categories',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get featured/popular foods
     */
    public function featured()
    {
        try {
            $featured = Food::where('is_available', true)
                ->withCount('reviews')
                ->orderBy('reviews_count', 'desc')
                ->take(6)
                ->get();

            return response()->json([
                'success' => true,
                'data' => $featured
            ]);
        } catch (\Exception $e) {
            Log::error('API Get Featured Foods Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch featured items',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
