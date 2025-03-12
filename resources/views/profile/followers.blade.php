@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="flex items-center mb-6">
            <a href="{{ route('profile.show', $profile) }}" class="mr-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M9.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L7.414 9H15a1 1 0 110 2H7.414l2.293 2.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                </svg>
            </a>
            <h1 class="text-2xl font-bold text-white">{{ $profile->name }}'s Followers</h1>
        </div>

        <div class="bg-gray-800 rounded-lg shadow-lg overflow-hidden">
            @if($followers->count() > 0)
                <ul class="divide-y divide-gray-700">
                    @foreach($followers as $followerRelation)
                        @if(isset($followerRelation->follower))
                            <li class="p-4 flex items-center justify-between">
                                <div class="flex items-center">
                                    <img 
                                        src="{{ $followerRelation->follower->profile_image ? asset('storage/' . $followerRelation->follower->profile_image) : 'https://avatarfiles.alphacoders.com/364/thumb-1920-364866.png' }}" 
                                        alt="{{ $followerRelation->follower->name }}" 
                                        class="w-12 h-12 rounded-full object-cover mr-4"
                                    >
                                    <div>
                                        <a href="{{ route('profile.show', $followerRelation->follower) }}" class="font-medium text-white hover:underline">
                                            {{ $followerRelation->follower->name }}
                                        </a>
                                        <p class="text-sm text-gray-400">{{ $followerRelation->follower->getTitle() }}</p>
                                    </div>
                                </div>
                                
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
                
                <div class="p-4">
                    {{ $followers->links() }}
                </div>
            @else
                <div class="p-8 text-center text-gray-400">
                    {{ $profile->name }} has no followers yet.
                </div>
            @endif
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const followButtons = document.querySelectorAll('.follow-button');
    
    followButtons.forEach(button => {
        button.addEventListener('click', function() {
            const userId = this.dataset.userId;
            const isFollowing = this.dataset.following === '1';
            
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