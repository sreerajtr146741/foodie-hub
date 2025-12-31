<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Models\ContactMessage;
use App\Mail\ContactFormSubmitted;

class ContactController extends Controller
{
    /**
     * Submit contact form
     */
    public function submit(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email',
                'phone' => 'required|string|regex:/^[0-9]{10}$/',
                'message' => 'required|string|min:10',
            ]);

            // Save to database
            $contact = ContactMessage::create($validated);

            // Send email to admin
            try {
                Mail::to(config('mail.from.address'))->send(new ContactFormSubmitted($contact));
            } catch (\Exception $e) {
                Log::error('Contact Form Email Error: ' . $e->getMessage());
            }

            return response()->json([
                'success' => true,
                'message' => 'Thank you! Your message has been sent. We will get back to you soon.',
                'data' => $contact
            ], 201);
        } catch (\Exception $e) {
            Log::error('API Contact Submission Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to send message',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get contact information
     */
    public function info()
    {
        try {
            return response()->json([
                'success' => true,
                'data' => [
                    'restaurant_name' => 'Food Court',
                    'address' => '123 Food Street, City Center, New Delhi, India 110001',
                    'phone' => '+91 98765 43210',
                    'email' => 'info@foodcourt.com',
                    'working_hours' => [
                        'monday_friday' => '10:00 AM - 10:00 PM',
                        'saturday_sunday' => '9:00 AM - 11:00 PM'
                    ],
                    'social_media' => [
                        'facebook' => 'https://facebook.com/foodcourt',
                        'instagram' => 'https://instagram.com/foodcourt',
                        'twitter' => 'https://twitter.com/foodcourt'
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('API Get Contact Info Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch contact information',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
