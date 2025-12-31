<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\ContactFormSubmitted;

class ContactController extends Controller
{
    public function index()
    {
        try {
            return view('contact');
        } catch (\Exception $e) {
            Log::error('Contact Page Error: ' . $e->getMessage());
            return back()->with('error', 'Unable to load contact page.');
        }
    }

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

            return back()->with('success', 'Thank you! Your message has been sent. We will get back to you soon.');
        } catch (\Exception $e) {
            Log::error('Contact Submission Error: ' . $e->getMessage());
            return back()->with('error', 'Unable to send message. Please try again later.');
        }
    }
}
