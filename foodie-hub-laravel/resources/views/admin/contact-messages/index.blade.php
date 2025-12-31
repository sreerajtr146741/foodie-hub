@extends('layouts.admin')

@section('content')
<div class="bg-white p-6 rounded shadow" x-data="{ expandedMessage: null }">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Contact Messages</h2>
        <span class="px-3 py-1 bg-orange-100 text-orange-800 rounded-full text-sm font-semibold">
            {{ $messages->total() }} Total Messages
        </span>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if($messages->isEmpty())
        <div class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">No messages</h3>
            <p class="mt-1 text-sm text-gray-500">No customer has contacted you yet.</p>
        </div>
    @else
        <div class="space-y-4">
            @foreach($messages as $message)
                <div class="border rounded-lg {{ $message->is_read ? 'bg-white' : 'bg-orange-50 border-orange-200' }}">
                    <!-- Message Header -->
                    <div class="p-4 cursor-pointer" @click="expandedMessage = expandedMessage === {{ $message->id }} ? null : {{ $message->id }}">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <div class="flex items-center gap-2">
                                    <h3 class="font-semibold text-gray-900">{{ $message->name }}</h3>
                                    @if(!$message->is_read)
                                        <span class="px-2 py-1 bg-orange-500 text-white text-xs rounded-full">New</span>
                                    @endif
                                </div>
                                <p class="text-sm text-gray-600 mt-1">{{ $message->email }} • {{ $message->phone }}</p>
                                <p class="text-sm text-gray-500 mt-1">{{ $message->created_at->diffForHumans() }}</p>
                            </div>
                            <div class="flex items-center gap-2">
                                <svg class="h-5 w-5 text-gray-400" :class="{ 'rotate-180': expandedMessage === {{ $message->id }} }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Expanded Content -->
                    <div x-show="expandedMessage === {{ $message->id }}" x-collapse class="border-t bg-gray-50">
                        <div class="p-4">
                            <!-- Message Content -->
                            <div class="mb-4">
                                <h4 class="font-semibold text-gray-700 mb-2">Message:</h4>
                                <p class="text-gray-600 bg-white p-3 rounded border">{{ $message->message }}</p>
                            </div>

                            <!-- Reply Form -->
                            <div class="mb-4">
                                <form action="{{ route('admin.contact.messages.reply', $message->id) }}" method="POST">
                                    @csrf
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Reply to {{ $message->name }}:</label>
                                    <textarea name="reply_message" rows="4" class="w-full border border-gray-300 rounded-md p-3 focus:ring-2 focus:ring-orange-500" placeholder="Type your reply here..." required></textarea>
                                    <div class="mt-3 flex gap-2">
                                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                                            ✉️ Send Reply
                                        </button>
                                        @if(!$message->is_read)
                                            <form action="{{ route('admin.contact.messages.markRead', $message->id) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">
                                                    ✓ Mark as Read
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </form>
                            </div>

                            <!-- Actions -->
                            <div class="flex gap-2 pt-3 border-t">
                                <a href="mailto:{{ $message->email }}" class="text-blue-600 hover:text-blue-800 text-sm">
                                    📧 Email Directly
                                </a>
                                <span class="text-gray-300">|</span>
                                <a href="tel:{{ $message->phone }}" class="text-green-600 hover:text-green-800 text-sm">
                                    📞 Call
                                </a>
                                <span class="text-gray-300">|</span>
                                <form action="{{ route('admin.contact.messages.destroy', $message->id) }}" method="POST" onsubmit="return confirm('Delete this message?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 text-sm">
                                        🗑️ Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $messages->links() }}
        </div>
    @endif
</div>
@endsection
