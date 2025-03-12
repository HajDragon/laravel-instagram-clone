<!-- filepath: c:\Users\Arshia\Documents\school\output\PDFs\Summa\laravel\instagram\resources\views\Posts.blade.php -->
<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h2 class="text-2xl font-bold mb-4">My Posts</h2>
                    
                    <div class="grid grid-cols-3 gap-4">
                        @foreach($posts as $post)
                            <div class="post-item">
                                <a href="{{ route('posts.show', $post->id) }}" class="post-link" data-post-id="{{ $post->id }}">
                                    <img src="{{ Storage::url($post->image_path) }}" alt="Post {{ $post->id }}" 
                                         class="w-full h-64 object-cover rounded">
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal container for post display -->
    <div id="post-modal" class="fixed inset-0 bg-black bg-opacity-75 hidden flex items-center justify-center z-50">
        <div class="bg-white max-w-4xl w-full rounded-lg overflow-hidden">
            <div id="modal-content" class="p-4">
                <!-- Post content will be loaded here -->
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get all post links
        const postLinks = document.querySelectorAll('.post-link');
        const modal = document.getElementById('post-modal');
        const modalContent = document.getElementById('modal-content');

        // Add click event to each post link
        postLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                
                const postId = this.getAttribute('data-post-id');
                
                // Fetch post content via AJAX
                fetch(`/posts/${postId}`)
                    .then(response => response.text())
                    .then(data => {
                        modalContent.innerHTML = data;
                        modal.classList.remove('hidden');
                    });
            });
        });

        // Close modal when clicking outside content area
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                modal.classList.add('hidden');
            }
        });
    });
    </script>
</x-app-layout>