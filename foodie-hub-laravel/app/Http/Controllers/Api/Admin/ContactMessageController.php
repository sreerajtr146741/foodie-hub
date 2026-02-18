<?php

namespace App\Http\Controllers\Api\Admin;

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
            return response()->json(['success' => true, 'data' => $messages]);
        } catch (\Exception $e) {
            Log::error('API Admin Contact Messages Index Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Unable to load contact messages.'], 500);
        }
    }

    public function markAsRead($id)
    {
        try {
            $message = ContactMessage::findOrFail($id);
            $message->update(['is_read' => true]);
            
            return response()->json(['success' => true, 'message' => 'Message marked as read', 'data' => $message]);
        } catch (\Exception $e) {
            Log::error('API Mark Message Read Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Unable to mark message as read.'], 500);
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
                Log::error('API Contact Reply Email Error: ' . $e->getMessage());
                // Mark as read anyway? Usually yes if it's saved.
                $message->update(['is_read' => true]);
                return response()->json([
                    'success' => true, 
                    'message' => 'Reply saved but email could not be sent. Please check server mail settings.',
                    'warning' => true
                ]);
            }
            
            // Mark as read
            $message->update(['is_read' => true]);
            
            return response()->json(['success' => true, 'message' => 'Reply sent successfully to ' . $message->email]);
        } catch (\Exception $e) {
            Log::error('API Reply Message Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Unable to send reply.'], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $message = ContactMessage::findOrFail($id);
            $message->delete();
            
            return response()->json(['success' => true, 'message' => 'Message deleted successfully']);
        } catch (\Exception $e) {
            Log::error('API Delete Message Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Unable to delete message.'], 500);
        }
    }
}
