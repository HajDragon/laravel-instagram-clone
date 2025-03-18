@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-md mx-auto bg-gray-800 rounded-lg shadow-lg overflow-hidden">
        <div class="p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-white">Edit Post</h2>
                <a href="{{ route('profile.show', Auth::id()) }}" class="text-blue-400 hover:text-blue-300">
                    Return to Dashboard
                </a>
            </div>
            
            @if (session('success'))
                <div class="bg-green-500 text-white p-4 rounded-lg mb-6 shadow-md max-w-md mx-auto">
                    {{ session('success') }}
                </div>
            @endif
            
            <form action="{{ route('posts.update', $post) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <!-- Current Image Preview -->
                <div class="mb-6">
                    <p class="text-gray-300 font-medium mb-2">Current Image</p>
                    <div class="aspect-square max-h-64 mx-auto bg-gray-900 rounded-lg overflow-hidden">
                        <img src="{{ asset('storage/' . $post->image_path) }}" 
                             class="w-full h-full object-contain" 
                             alt="Current Post Image">
                    </div>
                </div>
                
                <!-- New Image Upload -->
                <div class="mb-6">
                    <label for="image" class="block text-gray-300 font-medium mb-2">
                        Change Image (optional)
                    </label>
                    <input 
                        type="file" 
                        name="image" 
                        id="image" 
                        class="w-full text-white bg-gray-700 border border-gray-600 rounded-md p-2.5 focus:ring-blue-500 focus:border-blue-500"
                        accept="image/*"
                        onchange="showPreview(this)"
                    >
                    <div id="preview-container" class="hidden mt-4">
                        <p class="text-gray-300 mb-2">New Image Preview:</p>
                        <div class="aspect-square max-h-64 mx-auto bg-gray-900 rounded-lg overflow-hidden">
                            <img id="preview-image" class="w-full h-full object-contain" src="#" alt="Preview">
                        </div>
                    </div>
                    @error('image')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Caption -->
                <div class="mb-6">
                    <label for="caption" class="block text-gray-300 font-medium mb-2">Caption</label>
                    <textarea 
                        name="caption" 
                        id="caption" 
                        rows="3" 
                        class="w-full text-white bg-gray-700 border border-gray-600 rounded-md p-2.5 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Write a caption..."
                    >{{ old('caption', $post->caption) }}</textarea>
                    @error('caption')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Location -->
                <div class="mb-6">
                    <label for="location" class="block text-gray-300 font-medium mb-2">Location</label>
                    <input 
                        type="text" 
                        name="location" 
                        id="location" 
                        class="w-full text-white bg-gray-700 border border-gray-600 rounded-md p-2.5 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Add location"
                        value="{{ old('location', $post->location) }}"
                    >
                    @error('location')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Buttons -->
                <div class="flex justify-between">
                    <a href="{{ route('profile.show', Auth::id()) }}" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-md">
                        Return to Dashboard
                    </a>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-md">
                        Update Post
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function showPreview(input) {
    const previewContainer = document.getElementById('preview-container');
    const previewImage = document.getElementById('preview-image');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            previewImage.src = e.target.result;
            previewContainer.classList.remove('hidden');
        }
        
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection