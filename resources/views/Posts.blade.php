<!--
  Post Detail View
  This template displays a single post in a modal format with interactions.
  Key features:
  - Modal layout with close functionality
  - Post image display with header and user information
  - Action buttons (like, comment, share, bookmark)
  - Comments section with input field
  - JavaScript for modal interactions
-->
<div id="instagram-post-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-75 overflow-y-auto">
    <!-- Close button -->
    <button id="close-post-modal" class="absolute top-4 right-4 text-white text-3xl">&times;</button>
    
    <!-- Post container -->
    <div class="bg-gray-800 rounded-md max-w-lg w-full mx-4 overflow-hidden shadow-xl">
        <!-- Post header with user information -->
        <div class="flex items-center p-3 border-b border-gray-700">
            <img src="{{ $post->user->profile_image ? asset('storage/' . $post->user->profile_image) : 'https://avatarfiles.alphacoders.com/364/thumb-1920-364866.png' }}" 
                 class="w-8 h-8 rounded-full object-cover mr-3" 
                 alt="Profile Picture">
            <div class="flex-1">
                <a href="{{ route('profile.show', ['profile' => $post->user_id]) }}" class="font-bold text-white hover:underline">
                    {{ $post->user->name }}
                </a>
                <p class="text-xs text-gray-400">{{ $post->location ?? 'Instagram' }}</p>
            </div>
            <button class="text-white">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z" />
                </svg>
            </button>
        </div>
        
        <!-- Post image - main content -->
        <div class="aspect-square bg-black flex items-center justify-center">
            <img src="{{ $post->image_url }}" 
                 class="max-h-full max-w-full object-contain" 
                 alt="Post image">
        </div>
        
        <!-- Post actions (like, comment, share, save) -->
        <div class="p-3 border-t border-b border-gray-700">
            <div class="flex justify-between">
                <div class="flex space-x-4">
                    <button class="text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                    </button>
                    <button class="text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                    </button>
                    <button class="text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" />
                        </svg>
                    </button>
                </div>
                <button class="text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                    </svg>
                </button>
            </div>
            
            <!-- Likes count -->
            <div class="mt-2">
                <span class="text-white font-bold">{{ $post->likes_count ?? rand(10, 1000) }} likes</span>
            </div>
        </div>
        
        <!-- Caption and comments section -->
        <div class="p-3">
            <div class="flex mb-2">
                <a href="{{ route('profile.show', ['profile' => $post->user_id]) }}" class="font-bold text-white hover:underline mr-2">
                    {{ $post->user->name }}
                </a>
                <span class="text-white">{{ $post->caption ?? 'Enjoying life! #instagram #clone #laravel' }}</span>
            </div>
            
            <!-- View all comments link -->
            @if(isset($post->comments) && $post->comments->count() > 0)
                <a href="#" class="text-gray-400 text-sm">
                    View all {{ $post->comments->count() }} comments
                </a>
            @endif
            
            <!-- Comments display -->
            <div class="mt-1">
                @forelse($post->comments ?? [] as $comment)
                    <div class="flex">
                        <a href="{{ route('profile.show', ['profile' => $comment->user->id]) }}" 
                           class="font-bold text-white hover:underline mr-2">{{ $comment->user->name }}</a>
                        <span class="text-white">{{ $comment->content }}</span>
                    </div>
                @empty
                    <div class="text-gray-400">No comments yet</div>
                @endforelse
            </div>
            
            <!-- Post timestamp -->
            <div class="mt-2 text-xs text-gray-400">
                {{ $post->created_at ? $post->created_at->diffForHumans() : '2 HOURS AGO' }}
            </div>
            
            <!-- Comment input field -->
            <div class="mt-3 border-t border-gray-700 pt-3">
                <div class="flex">
                    <button class="text-white mr-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </button>
                    <input type="text" placeholder="Add a comment..." class="bg-transparent border-none flex-1 text-white focus:outline-none">
                    <button class="text-blue-500 font-semibold">Post</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal interaction JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Close modal when clicking the close button
    document.getElementById('close-post-modal').addEventListener('click', function() {
        closePostModal();
    });
    
    // Close modal when clicking outside the post
    document.getElementById('instagram-post-modal').addEventListener('click', function(e) {
        if (e.target === this) {
            closePostModal();
        }
    });
    
    // Escape key to close modal
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closePostModal();
        }
    });
    
    function closePostModal() {
        // Return to the previous page or dashboard
        window.history.back();
    }
});
</script>