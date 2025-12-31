<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Category;

class CategoryController extends Controller
{
    /**
     * List all categories
     */
    public function index()
    {
        try {
            $categories = Category::all();
            return response()->json(['success' => true, 'data' => $categories]);
        } catch (\Exception $e) {
            Log::error('API Admin Category Index Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to fetch categories', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Store a new category
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
            ]);
            $category = Category::create($validated);
            return response()->json(['success' => true, 'message' => 'Category created', 'data' => $category], 201);
        } catch (\Exception $e) {
            Log::error('API Admin Category Store Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to create category', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Show a category
     */
    public function show($id)
    {
        try {
            $category = Category::findOrFail($id);
            return response()->json(['success' => true, 'data' => $category]);
        } catch (\Exception $e) {
            Log::error('API Admin Category Show Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Category not found', 'error' => $e->getMessage()], 404);
        }
    }

    /**
     * Update a category
     */
    public function update(Request $request, $id)
    {
        try {
            $category = Category::findOrFail($id);
            $validated = $request->validate([
                'name' => 'sometimes|string|max:255',
                'description' => 'sometimes|nullable|string',
            ]);
            $category->update($validated);
            return response()->json(['success' => true, 'message' => 'Category updated', 'data' => $category]);
        } catch (\Exception $e) {
            Log::error('API Admin Category Update Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to update category', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Delete a category
     */
    public function destroy($id)
    {
        try {
            $category = Category::findOrFail($id);
            $category->delete();
            return response()->json(['success' => true, 'message' => 'Category deleted']);
        } catch (\Exception $e) {
            Log::error('API Admin Category Delete Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to delete category', 'error' => $e->getMessage()], 500);
        }
    }
}
