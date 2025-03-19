{{--
/**
 * Individual post display template.
 *
 * This template provides a detailed view of an individual post as a modal overlay,
 * including the image, caption, comments, and interactive elements like likes,
 * comments, and sharing options.
 *
 * Features:
 * - Post image display
 * - User profile information
 * - Post caption
 * - Comments section with user avatars
 * - Like, comment, share, and save buttons
 * - Close button to return to previous screen
 * - Post owner controls (edit/delete) for their own posts
 * - Responsive design (different layouts for mobile/desktop)
 *
 * Expected variables:
 * @param \App\Models\Post $post The post to display
 *
 * @requires authentication
 */
--}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $post->user->name ?? 'User' }}'s Post</title>
    <!-- Include your CSS files -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-black">
    <!-- Semi-transparent overlay with dashboard background -->
    <div class="fixed inset-0 z-0 bg-cover bg-center opacity-20" style="background-image: url('{{ asset('storage/images/dashboard-bg.jpg') }}')"></div>
    
    <!-- Instagram Post Modal -->
    <div id="instagram-post-modal" class="fixed inset-0 z-10 flex items-center justify-center overflow-y-auto">
        <!-- Close button -->
        <button id="close-post-modal" class="absolute top-4 right-4 text-white text-4xl hover:text-gray-300 z-20 focus:outline-none">&times;</button>
        
        <!-- Post container -->
        <div class="relative bg-gray-800 rounded-md max-w-3xl w-full mx-4 shadow-2xl overflow-hidden flex flex-col md:flex-row">
            <!-- Post image (left side on larger screens) -->
            <div class="md:w-2/3 bg-black flex items-center justify-center">
                <img src="{{ $post->image_url }}" 
                     class="max-w-full max-h-[80vh] object-contain" 
                     alt="Post image">
            </div>
            
            <!-- Post details (right side on larger screens) -->
            <div class="md:w-1/3 flex flex-col">
                <!-- Post header -->
                <div class="flex items-center p-4 border-b border-gray-700">
                    <img src="{{ $post->user->profile_image ? asset('storage/' . $post->user->profile_image) : 'https://avatarfiles.alphacoders.com/364/thumb-1920-364866.png' }}" 
                         class="w-8 h-8 rounded-full object-cover mr-3" 
                         alt="Profile Picture">
                    <div class="flex-1">
                        <a href="{{ route('profile.show', ['profile' => $post->user_id]) }}" class="font-bold text-white hover:underline">
                            {{ $post->user->name }}
                        </a>
                        @if($post->location)
                            <p class="text-xs text-gray-400">{{ $post->location }}</p>
                        @endif
                    </div>
                    @if(Auth::id() === $post->user_id)
                    <div class="dropdown relative">
                        <button class="text-white p-1 focus:outline-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z" />
                            </svg>
                        </button>
                        <div class="dropdown-menu hidden absolute right-0 bg-gray-900 rounded-md shadow-lg py-1 mt-1 w-40 z-20">
                            <a href="{{ route('posts.edit', $post) }}" class="block px-4 py-2 text-sm text-white hover:bg-gray-700">Edit</a>
                            <form action="{{ route('posts.destroy', $post) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-500 hover:bg-gray-700" onclick="return confirm('Are you sure you want to delete this post?')">Delete</button>
                            </form>
                        </div>
                    </div>
                    @endif
                </div>
                
                <!-- Caption and comments section -->
                <div class="flex-1 overflow-y-auto p-4" style="max-height: 50vh;">
                    <!-- Caption -->
                    @if($post->caption)
                    <div class="flex mb-4">
                        <img src="{{ $post->user->profile_image ? asset('storage/' . $post->user->profile_image) : 'https://avatarfiles.alphacoders.com/364/thumb-1920-364866.png' }}" 
                             class="w-8 h-8 rounded-full object-cover mr-3" 
                             alt="Profile Picture">
                        <div>
                            <a href="{{ route('profile.show', ['profile' => $post->user_id]) }}" class="font-bold text-white hover:underline">
                                {{ $post->user->name }}
                            </a>
                            <p class="text-white whitespace-pre-line">{{ $post->caption }}</p>
                            <p class="text-xs text-gray-400 mt-1">{{ $post->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    @endif
                    
                    <!-- Comments section title -->
                    @if(isset($post->comments) && $post->comments->count() > 0)
                        <div class="mb-3 mt-4">
                            <p class="text-gray-400 text-sm">
                                View all {{ $post->comments->count() }} comments
                            </p>
                        </div>
                    @endif

                    <!-- Comments -->
                    <div class="mt-2 space-y-3">
                        @forelse($post->comments ?? [] as $comment)
                            <div class="flex items-start mb-2">
                                <img src="{{ $comment->user->profile_image ? asset('storage/' . $comment->user->profile_image) : 'https://avatarfiles.alphacoders.com/364/thumb-1920-364866.png' }}" 
                                     class="w-7 h-7 rounded-full object-cover mr-2 flex-shrink-0" 
                                     alt="Commenter's profile picture">
                                
                                <div>
                                    <div class="flex flex-wrap">
                                        <a href="{{ route('profile.show', ['profile' => $comment->user->id]) }}" 
                                           class="font-bold text-white hover:underline mr-2">{{ $comment->user->name }}</a>
                                        <span class="text-white">{{ $comment->content }}</span>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">{{ $comment->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        @empty
                            <div class="text-gray-400 py-2">No comments yet</div>
                        @endforelse
                    </div>
                
                <!-- Post actions -->
                <div class="border-t border-gray-700 p-4">
                    <div class="flex justify-between mb-2">
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
                    
                    <!-- Likes -->
                    <div class="mb-2">
                        <span class="text-white font-bold">{{ rand(10, 1000) }} likes</span>
                    </div>
                    
                    <!-- Timestamp -->
                    <div class="text-xs text-gray-400 mb-3">
                        {{ $post->created_at->format('F j, Y') }}
                    </div>
                    
                    <!-- Comment input -->
                    <div class="flex items-center">
                        <button class="text-white mr-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </button>
                        <input type="text" placeholder="Add a comment..." 
                               class="bg-transparent border-none flex-1 text-white focus:outline-none">
                        <button class="text-blue-500 font-semibold">Post</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Toggle dropdown menu
        const dropdownButton = document.querySelector('.dropdown button');
        const dropdownMenu = document.querySelector('.dropdown-menu');
        
        if (dropdownButton) {
            dropdownButton.addEventListener('click', function() {
                dropdownMenu.classList.toggle('hidden');
            });
            
            // Close dropdown when clicking outside
            document.addEventListener('click', function(event) {
                if (!dropdownButton.contains(event.target)) {
                    dropdownMenu.classList.add('hidden');
                }
            });
        }
        
        // Close modal when clicking the close button
        document.getElementById('close-post-modal').addEventListener('click', function() {
            window.history.back();
        });
        
        // Close modal on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                window.history.back();
            }
        });
    });
    </script>
</body>
</html>