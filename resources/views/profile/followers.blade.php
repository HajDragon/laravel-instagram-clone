<!-- filepath: c:\Users\Arshia\Documents\school\output\PDFs\Summa\laravel\instagram\resources\views\profile\followers.blade.php -->
<!-- 
    Summary:
    This view displays a list of followers for a user profile. It includes:
      - A back button to return to the profile.
      - A list of followers with each follower's profile image, name, and title.
      - A follow/unfollow button for each follower, if applicable.
      - Pagination for the followers list.
-->

@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="flex items-center mb-6">
            <!-- Back Button -->
            <a href="{{ route('profile.show', $profile) }}" class="mr-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" viewBox="0 0 20 20" fill="currentColor">
                    <!-- SVG path for back arrow -->
                    <path fill-rule="evenodd" d="M9.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L7.414 9H15a1 1 0 110 2H7.414l2.293 2.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                </svg>
            </a>
            <!-- Page Title -->
            <h1 class="text-2xl font-bold text-white">{{ $profile->name }}'s Followers</h1>
        </div>

        <div class="bg-gray-800 rounded-lg shadow-lg overflow-hidden">
            @if($followers->count() > 0)
                <!-- List of followers -->
                <ul class="divide-y divide-gray-700">
                    @foreach($followers as $followerRelation)
                        @if(isset($followerRelation->follower))
                            <li class="p-4 flex items-center justify-between">
                                <div class="flex items-center">
                                    <!-- Follower's profile image with fallback -->
                                    <img 
                                        src="{{ $followerRelation->follower->profile_image ? asset('storage/' . $followerRelation->follower->profile_image) : 'https://avatarfiles.alphacoders.com/364/thumb-1920-364866.png' }}" 
                                        alt="{{ $followerRelation->follower->name }}" 
                                        class="w-12 h-12 rounded-full object-cover mr-4"
                                    >
                                    <div>
                                        <!-- Link to follower's profile -->
                                        <a href="{{ route('profile.show', $followerRelation->follower) }}" class="font-medium text-white hover:underline">
                                            {{ $followerRelation->follower->name }}
                                        </a>
                                        <!-- Follower title -->
                                        <p class="text-sm text-gray-400">{{ $followerRelation->follower->getTitle() }}</p>
                                    </div>
                                </div>
                                
                                <!-- Follow/Unfollow button visible if the authenticated user is not the follower -->
                                @if(Auth::check() && Auth::id() !== $followerRelation->follower->id)
                                    <button 
                                        class="follow-button px-3 py-1 rounded-md {{ Auth::user()->follows($followerRelation->follower) ? 'bg-gray-600 text-gray-300' : 'bg-blue-500 text-white' }}"
                                        data-user-id="{{ $followerRelation->follower->id }}"
                                        data-following="{{ Auth::user()->follows($followerRelation->follower) ? '1' : '0' }}"
                                    >
                                        {{ Auth::user()->follows($followerRelation->follower) ? 'Following' : 'Follow' }}
                                    </button>
                                @endif
                            </li>
                        @endif
                    @endforeach
                </ul>
                
                <!-- Pagination links -->
                <div class="p-4">
                    {{ $followers->links() }}
                </div>
            @else
                <!-- Message when there are no followers -->
                <div class="p-8 text-center text-gray-400">
                    {{ $profile->name }} has no followers yet.
                </div>
            @endif
        </div>
    </div>
</div>

<!-- JavaScript to handle follow/unfollow button click -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const followButtons = document.querySelectorAll('.follow-button');
    
    followButtons.forEach(button => {
        button.addEventListener('click', function() {
            const userId = this.dataset.userId;
            const isFollowing = this.dataset.following === '1';
            
            // Send follow/unfollow request to the server
            fetch(`/follow/${userId}`, {
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
                    // Update button state after success response
                    this.dataset.following = data.following ? '1' : '0';
                    this.textContent = data.following ? 'Following' : 'Follow';
                    
                    if (data.following) {
                        this.classList.remove('bg-blue-500', 'text-white');
                        this.classList.add('bg-gray-600', 'text-gray-300');
                    } else {
                        this.classList.remove('bg-gray-600', 'text-gray-300');
                        this.classList.add('bg-blue-500', 'text-white');
                    }
                }
            });
        });
    });
});
</script>
@endsection