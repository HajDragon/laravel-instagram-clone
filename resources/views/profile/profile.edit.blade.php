<!-- filepath: resources/views/profile/edit.blade.php -->
@extends('layouts.app')

@section('content')
<!--
  Profile Edit Page
  This template allows users to edit their profile information and upload profile pictures.
  Key features:
  - Back navigation to profile
  - Form with validation for profile information
  - Separate form for profile picture upload
  - Status messages for successful updates
  - Light/dark mode support
-->
<div class="container mx-auto px-4 py-8">
    <div class="max-w-3xl mx-auto">
        <!-- Back navigation -->
        <a href="{{ route('profile.show', ['profile' => Auth::user()->id]) }}" class="text-blue-500 hover:underline @if(request()->routeIs('profile.show')) active @endif">
            &larr; Back to profile
        </a>
        <h2 class="text-2xl font-bold mb-6 text-gray-800 dark:text-white">Edit Profile</h2>
        
        <!-- Status message for successful updates -->
        @if (session('status'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('status') }}
            </div>
        @endif

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-6">
            <h3 class="text-xl font-semibold mb-4 text-gray-800 dark:text-white">Profile Information</h3>
            
            <form method="POST" action="{{ route('profile.update') }}">
                @csrf
                @method('PATCH')

                <div class="mb-4">
                    <label for="name" class="block text-gray-700 dark:text-gray-300 mb-2">Name</label>
                    <input type="text" id="name" name="name" value="{{ old('name', Auth::user()->name) }}"
                           class="w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded py-2 px-3 text-gray-800 dark:text-white">
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="mb-4">
                    <label for="email" class="block text-gray-700 dark:text-gray-300 mb-2">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email', Auth::user()->email) }}"
                           class="w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded py-2 px-3 text-gray-800 dark:text-white">
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="mb-4">
                    <label for="title" class="block text-gray-700 dark:text-gray-300 mb-2">Title/Headline</label>
                    <input type="text" id="title" name="title" value="{{ old('title', Auth::user()->getTitle()) }}"
                           class="w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded py-2 px-3 text-gray-800 dark:text-white">
                    @error('title')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="bio" class="block text-gray-700 dark:text-gray-300 mb-2">Bio</label>
                    <textarea id="bio" name="bio"
                              class="w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded py-2 px-3 text-gray-800 dark:text-white">{{ old('bio', Auth::user()->bio) }}</textarea>
                    @error('bio')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Save Changes
                </button>
            </form>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-6">
            <h3 class="text-xl font-semibold mb-4 text-gray-800 dark:text-white">Profile Picture</h3>
            
            <div class="flex items-center mb-4">
                <div class="mr-4">
                    <img src="{{ Auth::user()->profile_image ? asset('storage/' . Auth::user()->profile_image) : 'https://avatarfiles.alphacoders.com/364/thumb-1920-364866.png' }}"
                         class="rounded-full w-24 h-24 object-cover border-2 border-gray-200 dark:border-gray-600" alt="Current Profile Picture">
                </div>
                <div>
                    <p class="text-gray-700 dark:text-gray-300">Current profile picture</p>
                </div>
            </div>
            
            <form method="POST" action="{{ route('profile.image.update') }}" enctype="multipart/form-data">
                @csrf
                
                <div class="mb-4">
                    <label for="profile_image" class="block text-gray-700 dark:text-gray-300 mb-2">Upload New Picture</label>
                    <input type="file" id="profile_image" name="profile_image"
                           class="w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded py-2 px-3 text-gray-800 dark:text-white">
                    @error('profile_image')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Upload New Picture
                </button>
            </form>
        </div>
    </div>
</div>
@endsection