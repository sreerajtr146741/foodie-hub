<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AboutController extends Controller
{
    /**
     * Return static about page data as JSON.
     */
    public function index()
    {
        try {
            return response()->json([
                'success' => true,
                'data' => [
                    'title' => 'About Food Court',
                    'description' => 'Food Court is a modern restaurant management platform offering a seamless ordering experience for customers and powerful admin tools for restaurant owners.',
                    'mission' => 'Deliver fresh, delicious food with quick, reliable service.',
                    'contact' => [
                        'email' => 'info@foodcourt.com',
                        'phone' => '+91 98765 43210',
                        'address' => '123 Food Street, New Delhi, India',
                    ],
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('API About Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to load about information',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
