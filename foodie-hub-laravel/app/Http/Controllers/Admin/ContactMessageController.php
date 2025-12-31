<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\ContactReply;

class ContactMessageController extends Controller
{
    public function index()
    {
        try {
            $messages = ContactMessage::latest()->paginate(20);
            return view('admin.contact-messages.index', compact('messages'));
        } catch (\Exception $e) {
            Log::error('Admin Contact Messages Index Error: ' . $e->getMessage());
            return back()->with('error', 'Unable to load contact messages.');
        }
    }

    public function markAsRead($id)
    {
        try {
            $message = ContactMessage::findOrFail($id);
            $message->update(['is_read' => true]);
            
            return back()->with('success', 'Message marked as read');
        } catch (\Exception $e) {
            Log::error('Mark Message Read Error: ' . $e->getMessage());
            return back()->with('error', 'Unable to mark message as read.');
        }
    }

    public function reply(Request $request, $id)
    {
        try {
            $request->validate([
                'reply_message' => 'required|string|min:10',
            ]);

            $message = ContactMessage::findOrFail($id);
            
            // Send reply email
            try {
                Mail::to($message->email)->send(new ContactReply($message, $request->reply_message));
            } catch (\Exception $e) {
                Log::error('Contact Reply Email Error: ' . $e->getMessage());
                // Continue marking as read even if email fails? Maybe better to warn user.
                return back()->with('warning', 'Reply saved but email could not be sent. Please check logs.');
            }
            
            // Mark as read
            $message->update(['is_read' => true]);
            
            return back()->with('success', 'Reply sent successfully to ' . $message->email);
        } catch (\Exception $e) {
            Log::error('Reply Message Error: ' . $e->getMessage());
            return back()->with('error', 'Unable to send reply.');
        }
    }

    public function destroy($id)
    {
        try {
            $message = ContactMessage::findOrFail($id);
            $message->delete();
            
            return back()->with('success', 'Message deleted successfully');
        } catch (\Exception $e) {
            Log::error('Delete Message Error: ' . $e->getMessage());
            return back()->with('error', 'Unable to delete message.');
        }
    }
}
