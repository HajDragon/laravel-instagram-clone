@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-white">{{ $profile->name }}'s Followers</h1>
        <a href="{{ route('profile.show', ['profile' => $profile->id]) }}" class="text-blue-500 hover:underline">
            &larr; Back to profile
        </a>
    </div>
    
    <div class="bg-gray-800 rounded-lg overflow-hidden">
        @forelse ($followers as $followerRelation)
            <div class="flex items-center justify-between p-4 border-b border-gray-700">
                <div class="flex items-center">
                    <img src="{{ $followerRelation->follower->profile_image ? asset('storage/' . $followerRelation->follower->profile_image) : 'https://avatarfiles.alphacoders.com/364/thumb-1920-364866.png' }}" 
                         alt="Profile" 
                         class="w-12 h-12 rounded-full object-cover mr-4">
                    <div>
                        <a href="{{ route('profile.show', ['profile' => $followerRelation->follower->id]) }}" class="text-white font-medium hover:underline">
                            {{ $followerRelation->follower->name }}
                        </a>
                        <p class="text-gray-400 text-sm">{{ $followerRelation->follower->getTitle() }}</p>
                    </div>
                </div>
                
                @if (Auth::id() !== $followerRelation->follower->id)
                <button
                    class="follow-button {{ Auth::user()->follows($followerRelation->follower) ? 'bg-gray-300 hover:bg-gray-400 text-gray-500' : 'bg-blue-500 hover:bg-blue-700 text-white' }} font-bold py-1 px-4 rounded-md"
                    data-following="{{ Auth::user()->follows($followerRelation->follower) ? '1' : '0' }}"
                    data-user-id="{{ $followerRelation->follower->id }}">
                    {{ Auth::user()->follows($followerRelation->follower) ? 'Following' : 'Follow' }}
                </button>
                @endif
            </div>
        @empty
            <div class="text-center text-gray-400 py-8">
                No followers yet.
            </div>
        @endforelse
    </div>
    
    <div class="mt-6">
        {{ $followers->links() }}
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const followButtons = document.querySelectorAll('.follow-button');
        
        followButtons.forEach(button => {
            button.addEventListener('click', function() {
                const isFollowing = this.dataset.following === '1';
                const userId = this.dataset.userId;
                
                fetch('/follow/' + userId, {
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
        });
    });
</script>
@endsection