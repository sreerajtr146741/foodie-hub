<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Food;
use App\Models\Category;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    public function index() {
        try {
            $featuredFoods = Food::where('is_available', true)->take(6)->get();
            return view('home', compact('featuredFoods'));
        } catch (\Exception $e) {
            Log::error('Home Page Error: ' . $e->getMessage());
            return redirect()->route('menu')->with('error', 'Unable to load featured items.');
        }
    }

    public function menu(Request $request) {
        try {
            $query = Food::where('is_available', true);
            
            // Search filter
            if ($request->has('search') && $request->search != '') {
                $query->where(function($q) use ($request) {
                    $q->where('name', 'LIKE', '%' . $request->search . '%')
                      ->orWhere('description', 'LIKE', '%' . $request->search . '%');
                });
            }
            
            // Category filter
            if ($request->has('category') && $request->category != '') {
                $query->where('category_id', $request->category);
            }

            $foods = $query->get();
            $categories = Category::all();
            
            return view('menu', compact('foods', 'categories'));
        } catch (\Exception $e) {
            Log::error('Menu Page Error: ' . $e->getMessage());
            return back()->with('error', 'Unable to load menu.');
        }
    }

    public function show(Food $food) {
        try {
            // Similar products (same category, diff id)
            $similarFoods = Food::where('category_id', $food->category_id)
                                ->where('id', '!=', $food->id)
                                ->take(4)
                                ->get();

            // You may like (random other products)
            $youMayLike = Food::where('id', '!=', $food->id)
                              ->inRandomOrder()
                              ->take(4)
                              ->get();

            $reviews = Review::where('food_id', $food->id)->with('user')->latest()->get();
            
            return view('food-details', compact('food', 'similarFoods', 'youMayLike', 'reviews'));
        } catch (\Exception $e) {
            Log::error('Food Details Error: ' . $e->getMessage());
            return redirect()->route('menu')->with('error', 'Unable to load product details.');
        }
    }

    public function storeReview(Request $request, Food $food) {
        try {
            $request->validate([
                'rating' => 'required|integer|min:1|max:5',
                'comment' => 'nullable|string'
            ]);

            Review::create([
                'user_id' => Auth::id(),
                'food_id' => $food->id,
                'rating' => $request->rating,
                'comment' => $request->comment
            ]);

            return back()->with('success', 'Review added successfully!');
        } catch (\Exception $e) {
            Log::error('Review Submission Error: ' . $e->getMessage());
            return back()->with('error', 'Unable to submit review. Please try again.');
        }
    }
}
