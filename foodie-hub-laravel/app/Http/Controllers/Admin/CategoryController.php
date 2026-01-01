<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{
    public function index() {
        try {
            $categories = Category::all();
            return view('admin.categories.index', compact('categories'));
        } catch (\Exception $e) {
            Log::error('Admin Categories Index Error: ' . $e->getMessage());
            return back()->with('error', 'Unable to load categories.');
        }
    }

    public function store(Request $request) {
        try {
            $request->validate([
                'name' => 'required|unique:categories,name',
                'discount' => 'nullable|integer|min:0|max:100'
            ]);
            Category::create($request->all());
            return back()->with('success', 'Category created successfully');
        } catch (\Exception $e) {
            Log::error('Admin Create Category Error: ' . $e->getMessage());
            return back()->with('error', 'Unable to create category.');
        }
    }

    public function destroy(Category $category) {
        try {
            $category->delete();
            return back()->with('success', 'Category deleted successfully');
        } catch (\Exception $e) {
            Log::error('Admin Delete Category Error: ' . $e->getMessage());
            return back()->with('error', 'Unable to delete category.');
        }
    }

    public function edit(Category $category) {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category) {
        try {
            $request->validate([
                'name' => 'required|unique:categories,name,' . $category->id,
                'discount' => 'nullable|integer|min:0|max:100'
            ]);
            $category->update($request->all());
            return redirect()->route('admin.categories.index')->with('success', 'Category updated successfully');
        } catch (\Exception $e) {
            Log::error('Admin Update Category Error: ' . $e->getMessage());
            return back()->with('error', 'Unable to update category.');
        }
    }
}
