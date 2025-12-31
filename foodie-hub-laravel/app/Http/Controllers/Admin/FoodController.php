<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Food;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class FoodController extends Controller
{
    public function index() {
        try {
            $foods = Food::with('category')->get();
            $categories = Category::all();
            return view('admin.foods.index', compact('foods', 'categories'));
        } catch (\Exception $e) {
            Log::error('Admin Food Index Error: ' . $e->getMessage());
            return back()->with('error', 'Unable to load food items.');
        }
    }

    public function store(Request $request) {
        try {
            $validated = $request->validate([
                'name' => 'required',
                'price' => 'required|numeric',
                'category_id' => 'required|exists:categories,id',
                'image' => 'nullable|image',
                'description' => 'nullable'
            ]);

            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('foods', 'public');
                $validated['image'] = $path;
            }

            Food::create($validated);
            return back()->with('success', 'Food item added');
        } catch (\Exception $e) {
            Log::error('Admin Create Food Error: ' . $e->getMessage());
            return back()->with('error', 'Unable to add food item.');
        }
    }

    public function update(Request $request, Food $food) {
        try {
             $validated = $request->validate([
                'name' => 'required',
                'price' => 'required|numeric',
                'category_id' => 'required|exists:categories,id',
                'description' => 'nullable',
                'is_available' => 'boolean'
            ]);
            
            if ($request->hasFile('image')) {
                 if($food->image){
                    Storage::disk('public')->delete($food->image);
                 }
                $path = $request->file('image')->store('foods', 'public');
                $validated['image'] = $path;
            }

            $food->update($validated);
            return back()->with('success', 'Food updated');
        } catch (\Exception $e) {
            Log::error('Admin Update Food Error: ' . $e->getMessage());
            return back()->with('error', 'Unable to update food item.');
        }
    }

    public function destroy(Food $food) {
        try {
            if($food->image){
                Storage::disk('public')->delete($food->image);
            }
            $food->delete();
            return back()->with('success', 'Food deleted');
        } catch (\Exception $e) {
            Log::error('Admin Delete Food Error: ' . $e->getMessage());
            return back()->with('error', 'Unable to delete food item.');
        }
    }
}
