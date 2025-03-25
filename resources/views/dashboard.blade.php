@extends('layouts.app')

@section('content')
<!--
  Dashboard View (Profile Page)
  This template displays a user's profile with their information, posts, and follow functionality.
  Key features:
  - Profile header with image, name, and stats
  - Interactive follow/unfollow button using AJAX
  - Responsive grid of user posts with clickable thumbnails
  - Conditional "Add New Post" button for profile owners
-->
<div class="container mx-auto px-4 flex flex-col items-center"> 
    <!-- Profile Header Section -->
    <div class="flex flex-wrap items-center justify-center">
        <!-- Profile Image -->
        <div class="w-[150px] flex-shrink-0">
            <img src="{{ $profile->profile_image ? asset('storage/' . $profile->profile_image) : 'https://avatarfiles.alphacoders.com/364/thumb-1920-364866.png' }}"
                 class="rounded-full object-cover hover:scale-105 transition-transform duration-300 h-[150px] w-[150px]"  
                 alt="Profile Picture">
        </div>
        <!-- User Information Section -->
        <div class="flex-1 pl-5 pt-4">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <!-- User name and action buttons -->
                    <div class="flex items-center gap-4">
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $profile->name }}</h1>
                        <x-blue-tick></x-blue-tick>
                        <!-- Follow/Unfollow Button with AJAX Implementation -->
                        <button
                            class="follow-button flex items-center justify-center {{ $isFollowing ? 'bg-gray-300 hover:bg-gray-400 text-gray-500' : 'bg-blue-500 hover:bg-blue-700 text-white' }} font-bold py-1.5 px-6 rounded-md w-24"
                            data-following="{{ $isFollowing ? '1' : '0' }}">
                            {{ $isFollowing ? 'Following' : 'Follow' }}
                        </button>
                        <script>
                            // AJAX Follow/Unfollow functionality
                            document.addEventListener('DOMContentLoaded', function() {
                                const followButton = document.querySelector('.follow-button');
                                
                                if (followButton) {
                                    followButton.addEventListener('click', function() {
                                        const isFollowing = this.dataset.following === '1';
                                        const followingId = {{ $profile->id }};
                                        
                                        fetch('/follow/' + followingId, {
                                            method: 'POST',
                                            headers: {
                                                'Content-Type': 'application/json',
                                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                            },
                                            body: JSON.stringify({
                                                follow: !isFollowing
                                            })
                                        })
                                        .then(response => response.json())
                                        .then(data => {
                                            if (data.success) {
                                                this.dataset.following = data.following ? '1' : '0';
                                                this.textContent = data.following ? 'Following' : 'Follow';
                                                
                                                if(data.following) {
                                                    this.classList.remove('bg-blue-500','hover:bg-blue-700','text-white');
                                                    this.classList.add('bg-gray-300','hover:bg-gray-400','text-gray-500');
                                                } else {
                                                    this.classList.remove('bg-gray-300','hover:bg-gray-400','text-gray-500');
                                                    this.classList.add('bg-blue-500','hover:bg-blue-700','text-white');
                                                }
                                            } else {
                                                alert('Error updating follow status.');
                                            }
                                        });
                                    });
                                }
                            });
                        </script>
                        <button class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-1.5 px-6 rounded-md w-24 flex items-center justify-center">
                            Message
                        </button>
                    </div>
                    <!-- Profile Statistics (followers, posts, following) -->
                    <div class="flex space-x-5 mt-4 text-gray-800 dark:text-white">
                        <div>
                            <a href="{{ route('profile.followers', ['profile' => $profile->id]) }}" class="hover:underline">
                                <strong class="font-medium">{{ $profile->followers()->count() }}</strong> followers
                            </a>
                        </div>
                        <div>
                            <strong class="font-medium">{{ isset($posts) ? $posts->count() : 0 }}</strong> posts
                        </div>
                        <div>
                            <a href="{{ route('profile.followings', ['profile' => $profile->id]) }}" class="hover:underline">
                                <strong class="font-medium">{{ $profile->followings()->count() }}</strong> following
                            </a>
                        </div>
                    </div>
                    <!-- User Bio Information -->
                    <div class="pt-4 font-bold text-gray-800 dark:text-white">{{ $profile->getTitle() }}</div>
                    <div class="mt-2 text-gray-700 dark:text-white">{!! nl2br(e($profile->getBio())) !!}</div>
                    <div class="mt-2">
                        <a href="#" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">https://www.cyberpunk.net/buy</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Add New Post button (only visible to profile owner) -->
    @if(Auth::check() && Auth::id() === $profile->id)
    <div class="w-full max-w-4xl flex justify-end my-4">
        <a href="{{ route('posts.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg">
            Add New Post
        </a>
    </div>
    @endif
    
    <!-- Posts Grid - Responsive layout with clickable thumbnails -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 pt-5 max-w-4xl mx-auto justify-items-center w-full">
        @if(isset($posts) && $posts->count() > 0)
            @foreach($posts as $post)
                <div class="aspect-square overflow-hidden rounded-lg cursor-pointer post-thumbnail" data-post-id="{{ $post->id }}">
                    <img src="{{ $post->image_url }}" 
                         class="w-full h-full object-cover hover:scale-105 transition-transform duration-300" 
                         alt="Post image">
                </div>
            @endforeach
        @else
            <div class="col-span-3 text-center text-gray-400 py-10 w-full">
                No posts yet.
            </div>
        @endif
    </div>
</div>

<!-- JavaScript to handle opening post details -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Get all post thumbnails
    const postThumbnails = document.querySelectorAll('.post-thumbnail');
    
    // Add click event to each thumbnail
    postThumbnails.forEach(thumbnail => {
        thumbnail.addEventListener('click', function() {
            const postId = this.getAttribute('data-post-id');
            // Navigate to the post view
            window.location.href = `/posts/${postId}`;
        });
    });
});
</script>
@endsection