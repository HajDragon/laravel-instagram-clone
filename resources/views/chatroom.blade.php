@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
        <!-- Header section -->
        <div class="bg-gray-100 dark:bg-gray-700 p-4 flex items-center justify-between border-b border-gray-200 dark:border-gray-600">
            <h1 class="text-xl font-semibold text-gray-800 dark:text-white">My Messages</h1>
            <div>
                <span class="text-gray-600 dark:text-gray-300">
                    Welcome, {{ Auth::user()->name }}
                </span>
            </div>
        </div>

        <!-- Chat interface container -->
        <div class="flex flex-col md:flex-row h-[600px]">
            <!-- User list sidebar -->
            <div class="w-full md:w-1/3 border-r border-gray-200 dark:border-gray-600 overflow-y-auto">
                
                <div class="p-3 bg-gray-50 dark:bg-gray-750 border-b border-gray-200 dark:border-gray-600">
                    <div class="relative">
                        <input type="text" 
                               id="user-search" 
                               placeholder="Search users..." 
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded bg-gradient-to-r from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-800 text-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <span class="absolute right-3 top-2 text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </span>
                    </div>
                </div>
                <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($users as $user)
                        @if($user->id !== Auth::id())
                            <li class="user-list-item" data-username="{{ strtolower($user->name) }}">
                                <a href="{{ route('chat.show', $user->id) }}" 
                                   class="flex items-center px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-150 
                                          @if(isset($selectedUser) && $selectedUser->id === $user->id) bg-blue-50 dark:bg-blue-900 @endif">
                                    <div class="flex-shrink-0 relative">
                                        <img src="{{ $user->profile_image ? asset('storage/' . $user->profile_image) : 'https://avatarfiles.alphacoders.com/364/thumb-1920-364866.png' }}" 
                                             class="h-10 w-10 rounded-full object-cover"
                                             alt="{{ $user->name }}'s profile picture">
                                        @if($user->unread_count > 0)
                                            <span class="absolute -top-1 -right-1 bg-red-500 text-white rounded-full p-1 text-xs">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V4a2 2 0 10-4 0v1.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                                </svg>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $user->name }}
                                        </p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            @if($user->isOnline())
                                                <span class="inline-block h-2 w-2 rounded-full bg-green-400 mr-1"></span> Online
                                            @else 
                                                <span class="inline-block h-2 w-2 rounded-full bg-gray-400 mr-1"></span> Offline
                                            @endif
                                        </p>
                                    </div>
                                </a>
                            </li>
                        @endif
                    @endforeach
                </ul>
            </div>
            
            <!-- Chat area -->
            <div class="w-full md:w-2/3 flex flex-col">
                @if(isset($selectedUser))
                    <!-- Chat header -->
                    <div class="p-4 bg-gradient-to-r from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-800 border-b border-gray-200 dark:border-gray-600 flex items-center justify-between backdrop-filter backdrop-blur-sm bg-opacity-90 dark:bg-opacity-90">
                        <div class="flex items-center">
                            <a href="{{ route('profile.show', ['profile' => $selectedUser->id]) }}" class="font-bold text-gray-900 dark:text-white hover:underline">
                                {{ $selectedUser->name }}
                            </a>
                        </div>
                    </div>
                    
                    <!-- Chat messages area -->
                    <div id="chat-messages" class="flex-1 p-4 overflow-y-auto bg-white dark:bg-gray-800">
                        @forelse($messages ?? [] as $message)
                            <div class="mb-4 flex {{ $message->sender_id === Auth::id() ? 'justify-end' : 'justify-start' }}">
                                <div class="{{ $message->sender_id === Auth::id() 
                                                ? 'bg-blue-500 text-white rounded-tl-lg rounded-tr-lg rounded-bl-lg' 
                                                : 'bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-white rounded-tl-lg rounded-tr-lg rounded-br-lg' }} 
                                            p-3 max-w-xs lg:max-w-md">
                                    <p class="text-sm">{{ $message->content }}</p>
                                    <p class="text-xs text-right {{ $message->sender_id === Auth::id() ? 'text-blue-200' : 'text-gray-500 dark:text-gray-400' }} mt-1">
                                        {{ $message->created_at->format('g:i A') }}
                                    </p>
                                </div>
                            </div>
                        @empty
                            <div class="flex items-center justify-center h-full">
                                <p class="text-gray-500 dark:text-gray-400 text-center">
                                    No messages yet. <br>
                                    Start the conversation by sending a message!
                                </p>
                            </div>
                        @endforelse
                    </div>
                    
                    <!-- Chat input area - FORM APPROACH -->
                    <form id="chat-form" class="border-t border-gray-200 dark:border-gray-600 p-4 bg-white dark:bg-gray-800">
                        @csrf {{-- This is for non-JS fallback, not used by this AJAX script --}}
                        <input type="hidden" name="receiver_id" id="receiver_id" value="{{ $selectedUser->id }}">
                        <div class="flex items-center">
                            <input type="text"
                                   id="message-input"
                                   name="message"
                                   class="flex-1 border border-gray-300 dark:border-gray-600 rounded-l-lg py-2 px-4 focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-700 text-gray-800 dark:text-white" {{-- Added some basic styling back --}}
                                   placeholder="Type your message..."
                                   required>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-r-lg"> {{-- Added some basic styling back --}}
                                Send
                            </button>
                        </div>
                    </form>
                @else
                    <!-- No chat selected state -->
                    <div class="flex items-center justify-center h-full bg-gray-50 dark:bg-gray-800">
                        <div class="text-center p-6">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03 8 9 8s9 3.582 9 8z" />
                            </svg>
                            <p class="mt-4 text-lg font-medium text-gray-700 dark:text-gray-300">No Chat Selected</p>
                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Select a user from the list to start chatting</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('[DEBUG] DOMContentLoaded: Script started.');

    const chatMessagesContainer = document.getElementById('chat-messages');
    const selectedUserId = document.getElementById('receiver_id')?.value; // Get selected user ID for fetching

    // Add this variable to track the most recent message ID
    let lastMessageId = 0;

    function scrollToBottom() {
        if (chatMessagesContainer) {
            chatMessagesContainer.scrollTop = chatMessagesContainer.scrollHeight;
        }
    }

    function appendMessageToChat(message, currentUserId) {
        if (!chatMessagesContainer) return;

        // Remove "No messages yet" placeholder if it exists
        const noMessagesEl = chatMessagesContainer.querySelector('.no-messages-placeholder');
        if (noMessagesEl) {
            noMessagesEl.remove();
        }

        const messageDiv = document.createElement('div');
        const isSender = message.sender_id == currentUserId; // Use == for loose comparison if one is string
        messageDiv.className = `mb-4 flex ${isSender ? 'justify-end' : 'justify-start'}`;

        const bubbleDiv = document.createElement('div');
        bubbleDiv.className = `${isSender ? 'bg-blue-500 text-white rounded-tl-lg rounded-tr-lg rounded-bl-lg' : 'bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-white rounded-tl-lg rounded-tr-lg rounded-br-lg'} p-3 max-w-xs lg:max-w-md`;

        const contentP = document.createElement('p');
        contentP.className = 'text-sm';
        contentP.textContent = message.content;

        const timeP = document.createElement('p');
        timeP.className = `text-xs text-right ${isSender ? 'text-blue-200' : 'text-gray-500 dark:text-gray-400'} mt-1`;
        // Format time - assuming message.created_at is a string like "12:37 PM"
        // If it's a full timestamp, you'll need to parse and format it.
        // For simplicity, if your backend already formats it, use it directly.
        // Otherwise, new Date(message.created_at_raw).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })
        timeP.textContent = message.created_at; // Assuming backend sends it pre-formatted

        bubbleDiv.appendChild(contentP);
        bubbleDiv.appendChild(timeP);
        messageDiv.appendChild(bubbleDiv);
        chatMessagesContainer.appendChild(messageDiv);
    }

    function fetchMessages(shouldScrollToBottom = true) {
        if (!selectedUserId || !chatMessagesContainer) {
            console.log('[DEBUG] fetchMessages: No selected user or chat container.');
            return;
        }
        console.log('[DEBUG] fetchMessages: Fetching for user', selectedUserId);

        fetch(`/chat/${selectedUserId}/messages`)
            .then(response => {
                if (!response.ok) throw new Error('Network response was not ok');
                return response.json();
            })
            .then(data => {
                console.log('[DEBUG] fetchMessages: Received messages data:', data);
                chatMessagesContainer.innerHTML = '';
                
                if (data.messages && data.messages.length > 0) {
                    const currentUserId = {{ Auth::id() }};
                    data.messages.forEach(message => {
                        appendMessageToChat(message, currentUserId);
                    });
                    // Update last message ID
                    lastMessageId = Math.max(...data.messages.map(msg => msg.id));
                } else {
                    chatMessagesContainer.innerHTML = '<p class="text-center p-4 text-gray-500 dark:text-gray-400 no-messages-placeholder">No messages yet</p>';
                }
                
                if (shouldScrollToBottom) {
                    scrollToBottom();
                }
            })
            .catch(error => {
                console.error('[DEBUG] Error fetching messages:', error);
                chatMessagesContainer.innerHTML = `<p class="text-red-500 text-center">Error loading messages.</p>`;
            });
    }

    // Poll for new messages every 5 seconds
    const messagePollingInterval = setInterval(() => {
        if (!selectedUserId) return;
        
        console.log('[DEBUG] Polling: Checking for messages after ID', lastMessageId);
        
        // Use a different endpoint that only returns new messages
        fetch(`/chat/${selectedUserId}/messages/new?after=${lastMessageId}`)
            .then(response => response.json())
            .then(data => {
                console.log('[DEBUG] Polling: Received', data.messages?.length || 0, 'new messages');
                
                if (data.messages && data.messages.length > 0) {
                    const currentUserId = {{ Auth::id() }};
                    
                    // Check for potential duplicates
                    data.messages.forEach(message => {
                        console.log('[DEBUG] New message ID:', message.id, 'Content:', message.content);
                        appendMessageToChat(message, currentUserId);
                        lastMessageId = Math.max(lastMessageId, message.id);
                    });
                    
                    // Only scroll if new messages arrived
                    scrollToBottom();
                }
            })
            .catch(error => console.error('[DEBUG] Error polling messages:', error));
    }, 5000);

    // Clean up interval when page unloads
    window.addEventListener('beforeunload', () => {
        clearInterval(messagePollingInterval);
    });

    // Initial fetch of messages if a user is selected
    if (selectedUserId) {
        fetchMessages();
    }
    scrollToBottom(); // Scroll on initial load too

    // UNCOMMENT other JS functionalities if they were working
    /*
    const closeBtn = document.getElementById('close-chat');
    if (closeBtn) { // ... }
    const userSearch = document.getElementById('user-search');
    if (userSearch) { // ... }
    */

    const chatForm = document.getElementById('chat-form');
    if (chatForm) {
        console.log('[DEBUG] Form: Element with id="chat-form" FOUND.');
        chatForm.addEventListener('submit', function(event) {
            console.log('[DEBUG] Form Submit: Event listener triggered.');
            event.preventDefault();
            console.log('[DEBUG] Form Submit: event.preventDefault() CALLED.');

            const receiverIdInput = document.getElementById('receiver_id');
            const messageInput = document.getElementById('message-input');
            const csrfMetaTag = document.querySelector('meta[name="csrf-token"]');

            if (!receiverIdInput || !messageInput || !csrfMetaTag) { /* ... error handling ... */ return; }

            const receiverId = receiverIdInput.value;
            const message = messageInput.value.trim();
            const csrfToken = csrfMetaTag.getAttribute('content');

            console.log('[DEBUG] Form Submit: Data to send:', { receiverId, message, csrfTokenExists: !!csrfToken });

            if (!message || !receiverId || !csrfToken) { /* ... error handling ... */ return; }

            fetch("{{ route('chat.send') }}", {
                method: 'POST',
                headers: { 
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ receiver_id: receiverId, message: message })
            })
            .then(response => { /* ... */ return response.json(); })
            .then(data => {
                console.log('[DEBUG] Fetch: Success. Parsed JSON data:', data);
                if (data.success && data.message) {
                    messageInput.value = '';
                    // Add this line to update lastMessageId
                    lastMessageId = Math.max(lastMessageId, data.message.id);
                    
                    appendMessageToChat(data.message, {{ Auth::id() }});
                    scrollToBottom();
                }
            })
            .catch(error => { /* ... */ });
        });
    } else { /* ... */ }

    // Define showGlobalNotification if you use it
    // function showGlobalNotification(message) { /* ... */ }
    // window.showGlobalNotification = showGlobalNotification;
});
</script>
@endpush
@endsection