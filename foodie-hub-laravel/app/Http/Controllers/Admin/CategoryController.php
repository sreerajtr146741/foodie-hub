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
            $request->validate(['name' => 'required']);
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
}
