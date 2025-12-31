<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Food;
use App\Models\Category;

class FoodController extends Controller
{
    /**
     * List all foods
     */
    public function index()
    {
        try {
            $foods = Food::with('category')->get();
            return response()->json(['success' => true, 'data' => $foods]);
        } catch (\Exception $e) {
            Log::error('API Admin Food Index Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to fetch foods', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Store a new food item
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'price' => 'required|numeric',
                'category_id' => 'required|exists:categories,id',
                'is_available' => 'required|boolean',
                'image' => 'nullable|image|max:2048',
            ]);

            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('food_images', 'public');
                $validated['image'] = $path;
            }

            $food = Food::create($validated);
            return response()->json(['success' => true, 'message' => 'Food created', 'data' => $food], 201);
        } catch (\Exception $e) {
            Log::error('API Admin Food Store Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to create food', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Show a food item
     */
    public function show($id)
    {
        try {
            $food = Food::with('category')->findOrFail($id);
            return response()->json(['success' => true, 'data' => $food]);
        } catch (\Exception $e) {
            Log::error('API Admin Food Show Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Food not found', 'error' => $e->getMessage()], 404);
        }
    }

    /**
     * Update a food item
     */
    public function update(Request $request, $id)
    {
        try {
            $food = Food::findOrFail($id);
            $validated = $request->validate([
                'name' => 'sometimes|string|max:255',
                'description' => 'sometimes|nullable|string',
                'price' => 'sometimes|numeric',
                'category_id' => 'sometimes|exists:categories,id',
                'is_available' => 'sometimes|boolean',
                'image' => 'sometimes|nullable|image|max:2048',
            ]);

            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('food_images', 'public');
                $validated['image'] = $path;
            }

            $food->update($validated);
            return response()->json(['success' => true, 'message' => 'Food updated', 'data' => $food]);
        } catch (\Exception $e) {
            Log::error('API Admin Food Update Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to update food', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Delete a food item
     */
    public function destroy($id)
    {
        try {
            $food = Food::findOrFail($id);
            $food->delete();
            return response()->json(['success' => true, 'message' => 'Food deleted']);
        } catch (\Exception $e) {
            Log::error('API Admin Food Delete Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to delete food', 'error' => $e->getMessage()], 500);
        }
    }
}
