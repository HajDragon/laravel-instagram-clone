<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log; // Add for logging
use Illuminate\Http\JsonResponse;

class ChatController extends Controller
{
    public function index()
    {
        $users = User::where('id', '!=', Auth::id())
            ->withCount([
                'receivedMessages as unread_count' => function ($query) {
                    $query->where('read', false);
                }
            ])
            ->get()
            ->sortByDesc('unread_count');

        return view('chatroom', [
            'users' => $users,
            'selectedUser' => null, // Explicitly pass null for selectedUser
            'messages' => collect(), // Explicitly pass an empty collection for messages
        ]);
    }

    public function show(User $user)
    {
        $users = User::where('id', '!=', Auth::id())->get();
        $selectedUser = $user;

        $messages = Message::where(function ($query) use ($user) {
            $query->where('sender_id', Auth::id())
                ->where('receiver_id', $user->id);
        })->orWhere(function ($query) use ($user) {
            $query->where('sender_id', $user->id)
                ->where('receiver_id', Auth::id());
        })->orderBy('created_at')->get();

        Message::where('sender_id', $user->id)
            ->where('receiver_id', Auth::id())
            ->where('read', false)
            ->update(['read' => true]);

        return view('chatroom', compact('users', 'selectedUser', 'messages'));
    }

    /**
     * Send a message to another user
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendMessage(Request $request)
    {
        Log::info('sendMessage called', $request->all()); // Log incoming request

        $validated = $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'required|string'
        ]);

        try {
            $message = new Message();
            $message->sender_id = Auth::id();
            $message->receiver_id = $validated['receiver_id'];
            $message->content = $validated['message'];
            $message->save();

            Log::info('Message saved successfully', ['message_id' => $message->id]);

            return response()->json([
                'success' => true,
                'message' => [ // Return the created message details if needed by JS
                    'id' => $message->id,
                    'sender_id' => $message->sender_id,
                    'receiver_id' => $message->receiver_id,
                    'content' => $message->content,
                    'created_at' => $message->created_at->format('g:i A') // Consistent format
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error saving message: ' . $e->getMessage(), [
                'request' => $request->all(),
                'trace' => $e->getTraceAsString() // For detailed debugging
            ]);
            return response()->json(['success' => false, 'error' => 'Could not send message.'], 500);
        }
    }

    public function getMessages(User $user)
    {
        // Get messages between current user and selected user
        $messages = Message::where(function ($query) use ($user) {
            $query->where('sender_id', Auth::id())
                ->where('receiver_id', $user->id);
        })->orWhere(function ($query) use ($user) {
            $query->where('sender_id', $user->id)
                ->where('receiver_id', Auth::id());
        })
            ->orderBy('created_at')
            ->get()
            ->map(function ($message) {
                return [
                    'id' => $message->id,
                    'sender_id' => $message->sender_id,
                    'receiver_id' => $message->receiver_id,
                    'content' => $message->content,
                    'created_at' => $message->created_at->format('g:i A')
                ];
            });

        return response()->json(['messages' => $messages]);
    }

    public function getMessagesJson(User $user): JsonResponse
    {
        $currentUserId = Auth::id();
        $messages = Message::where(function ($query) use ($currentUserId, $user) {
            $query->where('sender_id', $currentUserId)
                ->where('receiver_id', $user->id);
        })->orWhere(function ($query) use ($currentUserId, $user) {
            $query->where('sender_id', $user->id)
                ->where('receiver_id', $currentUserId);
        })->orderBy('created_at', 'asc')->get()
            ->map(function ($message) { // Format for JS
                return [
                    'id' => $message->id,
                    'sender_id' => $message->sender_id,
                    'receiver_id' => $message->receiver_id,
                    'content' => $message->content,
                    'created_at' => $message->created_at->format('g:i A'), // Or your preferred format
                    // 'created_at_raw' => $message->created_at->toIso8601String(), // If you need raw for JS date parsing
                ];
            });

        return response()->json(['messages' => $messages]);
    }

    public function getNewMessages($userId, Request $request)
    {
        $lastId = $request->query('after', 0);

        // Debug the query parameters
        \Log::info("Fetching new messages after ID: " . $lastId);

        $messages = Message::where(function ($query) use ($userId) {
            $query->where('sender_id', auth()->id())
                ->where('receiver_id', $userId)
                ->orWhere(function ($q) use ($userId) {
                    $q->where('sender_id', $userId)
                        ->where('receiver_id', auth()->id());
                });
        })
            // This is the critical part - make sure we're filtering by ID properly
            ->where('id', '>', $lastId)
            ->orderBy('created_at', 'asc')
            ->get();

        // Debug the results
        \Log::info("Found " . $messages->count() . " new messages");

        return response()->json([
            'messages' => $messages
        ]);
    }
}
