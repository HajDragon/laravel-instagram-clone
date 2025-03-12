@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 flex flex-col items-center"> 
    <div class="flex flex-wrap items-center justify-center">
        <div class="w-[150px] flex-shrink-0">
            <img src="https://avatarfiles.alphacoders.com/364/thumb-1920-364866.png"
                 class="rounded-full object-cover hover:scale-105 transition-transform duration-300 h-[150px] w-[150px]"  
                 alt="Profile Picture">
        </div>
        <div class="flex-1 pl-5 pt-4">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <div class="flex items-center gap-4">
                        <h1 class="text-3xl font-bold {{ $isFollowing ? 'text-gray-500' : 'text-white' }}">{{ $profile->name }}</h1>
                        <x-blue-tick></x-blue-tick>
                        <button
                            class="follow-button flex items-center justify-center {{ $isFollowing ? 'bg-gray-300 hover:bg-gray-400 text-gray-500' : 'bg-blue-500 hover:bg-blue-700 text-white' }} font-bold py-1.5 px-6 rounded-md w-24"
                            data-following="{{ $isFollowing ? '1' : '0' }}">
                            {{ $isFollowing ? 'Following' : 'Follow' }}
                        </button>
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                const followButton = document.querySelector('.follow-button');
                                
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
                            });
                        </script>
                        <button class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-1.5 px-6 rounded-md w-24 flex items-center justify-center">
                            Message
                        </button>
                    </div>
                    <div class="flex space-x-5 mt-4 {{ $isFollowing ? 'text-gray-500' : 'text-white' }}">
                        <div>
                            <a href="{{ route('profile.followers', ['profile' => $profile->id]) }}" class="hover:underline">
                                <strong class="font-medium">{{ $profile->followers()->count() }}</strong> followers
                            </a>
                        </div>
                        <div>
                            <strong class="font-medium">153</strong> posts
                        </div>
                        <div>
                            <a href="{{ route('profile.followings', ['profile' => $profile->id]) }}" class="hover:underline">
                                <strong class="font-medium">{{ $profile->followings()->count() }}</strong> following
                            </a>
                        </div>
                    </div>
                    <div class="pt-4 font-bold {{ $isFollowing ? 'text-gray-500' : 'text-white' }}">{{ $profile->getTitle() }}</div>
                    <div class="mt-2 {{ $isFollowing ? 'text-gray-500' : 'text-white' }}">{!! nl2br(e($profile->getBio())) !!}</div>
                    <div class="mt-2">
                        <a href="#" class="text-gray-400 hover:text-blue-800">https://www.cyberpunk.net/buy</a>
                    </div>
                    <div class="mt-2">
                        <a href="{{ route('dashboard', ['profile' => $profile->id]) }}">Dashboard</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Posts Grid -->
    <div class="grid grid-cols-3 gap-4 pt-5 max-w-4xl mx-auto justify-items-center">
        <div class="aspect-square overflow-hidden rounded-lg">
            <img src="{{ asset('storage/posts/1.jpg') }}" 
                 class="w-full h-full object-cover hover:scale-105 transition-transform duration-300" 
                 alt="Image">
        </div>
        <div class="aspect-square overflow-hidden rounded-lg">
            <img src="{{ asset('storage/posts/2.jpg') }}" 
                 class="w-full h-full object-cover hover:scale-105 transition-transform duration-300" 
                 alt="Image">
        </div>
        <div class="aspect-square overflow-hidden rounded-lg">
            <img src="{{ asset('storage/posts/3.jpg') }}" 
                 class="w-full h-full object-cover hover:scale-105 transition-transform duration-300" 
                 alt="Image">
        </div>
        <div class="aspect-square overflow-hidden rounded-lg">
            <img src="{{ asset('storage/posts/4.jpg') }}" 
                 class="w-full h-full object-cover hover:scale-105 transition-transform duration-300" 
                 alt="Image">
        </div>
        <div class="aspect-square overflow-hidden rounded-lg">
            <img src="{{ asset('storage/posts/5.jpg') }}" 
                 class="w-full h-full object-cover hover:scale-105 transition-transform duration-300" 
                 alt="Image">
        </div>
        <div class="aspect-square overflow-hidden rounded-lg">
            <img src="{{ asset('storage/posts/6.jpg') }}" 
                 class="w-full h-full object-cover hover:scale-105 transition-transform duration-300" 
                 alt="Image">
        </div>
    </div>
</div>
@endsection