<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\FoodController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Api\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Api\Admin\FoodController as AdminFoodController;
use App\Http\Controllers\Api\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Api\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\Api\Admin\TableController as AdminTableController;
use App\Http\Controllers\Api\Admin\ContactMessageController as AdminContactMessageController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public Routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Contact & Info (Public)
Route::post('/contact', [ContactController::class, 'submit']);
Route::get('/contact/info', [ContactController::class, 'info']);

// Food Routes (Public)
Route::get('/foods', [FoodController::class, 'index']);
Route::get('/foods/{id}', [FoodController::class, 'show']);
Route::get('/categories', [FoodController::class, 'categories']);
Route::get('/foods/featured', [FoodController::class, 'featured']);

// Reviews (Public Read)
Route::get('/foods/{foodId}/reviews', [ReviewController::class, 'index']);

// Protected User Routes
Route::middleware('auth:sanctum')->group(function () {
    // Auth
    Route::get('/user', [AuthController::class, 'user']);
    Route::put('/user/profile', [AuthController::class, 'updateProfile']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // Cart
    Route::get('/cart', [CartController::class, 'index']);
    Route::post('/cart/add', [CartController::class, 'add']);
    Route::put('/cart/{itemId}', [CartController::class, 'update']);
    Route::delete('/cart/{itemId}', [CartController::class, 'remove']);
    Route::delete('/cart', [CartController::class, 'clear']);

    // Orders
    Route::get('/orders', [OrderController::class, 'index']);
    Route::get('/orders/{id}', [OrderController::class, 'show']);
    Route::post('/orders', [OrderController::class, 'store']);
    Route::post('/orders/{id}/cancel', [OrderController::class, 'cancel']);

    // Bookings
    Route::get('/bookings', [BookingController::class, 'index']);
    Route::post('/bookings', [BookingController::class, 'store']);
    Route::post('/bookings/{id}/cancel', [BookingController::class, 'cancel']);

    // Reviews
    Route::post('/reviews', [ReviewController::class, 'store']);
});

// Admin API Routes
Route::middleware(['auth:sanctum', 'admin'])->prefix('admin')->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index']);
    
    // Categories
    Route::get('/categories', [AdminCategoryController::class, 'index']);
    Route::post('/categories', [AdminCategoryController::class, 'store']);
    Route::get('/categories/{category}', [AdminCategoryController::class, 'show']);
    Route::put('/categories/{category}', [AdminCategoryController::class, 'update']);
    Route::delete('/categories/{category}', [AdminCategoryController::class, 'destroy']);

    // Foods
    Route::get('/foods', [AdminFoodController::class, 'index']);
    Route::post('/foods', [AdminFoodController::class, 'store']);
    Route::get('/foods/{food}', [AdminFoodController::class, 'show']);
    Route::put('/foods/{food}', [AdminFoodController::class, 'update']);
    Route::delete('/foods/{food}', [AdminFoodController::class, 'destroy']);

    // Orders
    Route::get('/orders', [AdminOrderController::class, 'index']);
    Route::get('/orders/{id}', [AdminOrderController::class, 'show']);
    Route::patch('/orders/{order}', [AdminOrderController::class, 'updateStatus']);
    Route::post('/orders/{order}/items', [AdminOrderController::class, 'addItem']);
    Route::delete('/orders/{order}/items/{item}', [AdminOrderController::class, 'removeItem']);
    Route::delete('/orders/{order}', [AdminOrderController::class, 'destroy']);

    // Bookings
    Route::get('/bookings', [AdminBookingController::class, 'index']);
    Route::get('/bookings/{id}', [AdminBookingController::class, 'show']);
    Route::patch('/bookings/{id}', [AdminBookingController::class, 'updateStatus']);
    Route::delete('/bookings/{id}', [AdminBookingController::class, 'destroy']);

    // Tables
    Route::get('/tables', [AdminTableController::class, 'index']);
    Route::post('/tables', [AdminTableController::class, 'store']);
    Route::post('/tables/settings', [AdminTableController::class, 'updateSettings']);
    Route::patch('/tables/{id}/status', [AdminTableController::class, 'updateStatus']);
    Route::delete('/tables/{id}', [AdminTableController::class, 'destroy']);

    // Contact Messages
    Route::get('/contact-messages', [AdminContactMessageController::class, 'index']);
    Route::post('/contact-messages/{id}/mark-read', [AdminContactMessageController::class, 'markAsRead']);
    Route::post('/contact-messages/{id}/reply', [AdminContactMessageController::class, 'reply']);
    Route::delete('/contact-messages/{id}', [AdminContactMessageController::class, 'destroy']);
});

