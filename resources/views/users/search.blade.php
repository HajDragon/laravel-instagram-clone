@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                @if(request('q'))
                    Search Results for "{{ request('q') }}"
                @else
                    Search Users
                @endif
            </h1>
            
            <!-- Search Form -->
            <form action="{{ route('users.search') }}" method="GET" class="flex">
                <input type="text" 
                       name="q" 
                       placeholder="Search users..." 
                       value="{{ request('q') }}"
                       class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-l-md bg-white dark:bg-gray-700 text-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-r-md">
                    Search
                </button>
            </form>
        </div>

        <!-- Search Results -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($users as $user)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-200">
                    <div class="p-4">
                        <div class="flex items-center">
                            <img src="{{ $user->profile_image ? asset('storage/' . $user->profile_image) : 'https://avatarfiles.alphacoders.com/364/thumb-1920-364866.png' }}" 
                                 class="w-12 h-12 rounded-full object-cover mr-4" 
                                 alt="{{ $user->name }}'s profile picture">
                            <div class="flex-1">
                                <a href="{{ route('profile.show', ['profile' => $user->id]) }}" class="font-bold text-gray-900 dark:text-white hover:underline">
                                    {{ $user->name }}
                                </a>
                                @if($user->is_verified)
                                    <x-blue-tick class="inline-block ml-1 h-4 w-4" />
                                @endif
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $user->followers()->count() }} followers</p>
                            </div>
                            
                            @if(Auth::check() && Auth::id() != $user->id)
                                @php
                                    
                                    $isFollowing = Auth::user()->follows($user);
                                @endphp
                                <button 
                                    class="user-follow-btn text-sm px-4 py-1 rounded-md {{ $isFollowing ? 'bg-gray-300 dark:bg-gray-600 text-gray-800 dark:text-gray-200' : 'bg-blue-500 text-white' }}"
                                    data-user-id="{{ $user->id }}"
                                    data-following="{{ $isFollowing ? '1' : '0' }}">
                                    {{ $isFollowing ? 'Following' : 'Follow' }}
                                </button>
                            @endif
                        </div>
                        
                        @if($user->getTitle())
                            <p class="mt-3 text-sm font-medium text-gray-800 dark:text-gray-200">{{ $user->getTitle() }}</p>
                        @endif
                        
                        @if($user->getBio())
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-300 line-clamp-2">
                                {{ Str::limit($user->getBio(), 100) }}
                            </p>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-span-3 py-12 text-center text-gray-500 dark:text-gray-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <p class="text-xl">No users found matching "{{ request('q') }}"</p>
                </div>
            @endforelse
        </div>
        
        <!-- Pagination Links -->
        <div class="mt-8">
            {{ $users->appends(['q' => request('q')])->links() }}
        </div>
        
        <!-- Link to view all users -->
        <div class="mt-4 text-center">
            <a href="{{ route('users.index') }}" class="text-blue-500 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300">
                View all users
            </a>
        </div>
    </div>
</div>

<!-- JavaScript for Follow/Unfollow Functionality -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const followButtons = document.querySelectorAll('.user-follow-btn');
    
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
                        this.classList.add('bg-gray-300', 'dark:bg-gray-600', 'text-gray-800', 'dark:text-gray-200');
                    } else {
                        this.classList.remove('bg-gray-300', 'dark:bg-gray-600', 'text-gray-800', 'dark:text-gray-200');
                        this.classList.add('bg-blue-500', 'text-white');
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