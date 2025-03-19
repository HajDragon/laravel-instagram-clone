{{--
/**
 * Create new post form.
 *
 * This template provides a form for users to create new posts with images,
 * captions, and location information.
 *
 * Features:
 * - Image upload functionality
 * - Caption input with error handling
 * - Optional location tagging
 * - Form validation display
 * - Submit button for post creation
 *
 * Routes:
 * - Form submits to route('posts.store') via POST
 * 
 * @extends layouts.app
 */
--}}
@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-md mx-auto bg-gray-800 rounded-lg shadow-lg overflow-hidden">
        <div class="p-6">
            <h2 class="text-xl font-bold mb-6 text-white">Create New Post</h2>
            
            <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="mb-6">
                    <label for="image" class="block text-gray-300 font-medium mb-2">Upload Image</label>
                    <input 
                        type="file" 
                        name="image" 
                        id="image" 
                        class="w-full text-white bg-gray-700 border border-gray-600 rounded-md p-2.5 focus:ring-blue-500 focus:border-blue-500"
                        required
                    >
                    @error('image')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="mb-6">
                    <label for="caption" class="block text-gray-300 font-medium mb-2">Caption</label>
                    <textarea 
                        name="caption" 
                        id="caption" 
                        rows="3" 
                        class="w-full text-white bg-gray-700 border border-gray-600 rounded-md p-2.5 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Write a caption..."
                    >{{ old('caption') }}</textarea>
                    @error('caption')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="mb-6">
                    <label for="location" class="block text-gray-300 font-medium mb-2">Location</label>
                    <input 
                        type="text" 
                        name="location" 
                        id="location" 
                        class="w-full text-white bg-gray-700 border border-gray-600 rounded-md p-2.5 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Add location"
                        value="{{ old('location') }}"
                    >
                    @error('location')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="flex justify-end">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-md">
                        Share
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection