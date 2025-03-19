<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with('user')->latest()->paginate(10);
        return view('posts.index', compact('posts'));
    }

    public function create()
    {
        return view('posts.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'caption' => 'nullable|string|max:2200',
            'location' => 'nullable|string|max:100',
        ]);

        $imagePath = $request->file('image')->store('posts', 'public');

        Auth::user()->posts()->create([
            'image_path' => $imagePath,
            'caption' => $request->caption,
            'location' => $request->location,
        ]);

        return redirect()->route('profile.show', ['profile' => Auth::id()])
            ->with('success', 'Post created successfully!');
    }

    public function show($id)
    {
        $post = Post::with(['user', 'comments.user'])->findOrFail($id);

        return view('Posts', compact('post'));
    }

    public function edit(Post $post)
    {
        // Check if the current user owns this post
        if (Auth::id() !== $post->user_id) {
            abort(403, 'Unauthorized action.');
        }

        return view('posts.edit', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        // Check if the current user owns this post
        if (Auth::id() !== $post->user_id) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'caption' => 'nullable|string|max:2200',
            'location' => 'nullable|string|max:100',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle image update if a new one is provided
        if ($request->hasFile('image')) {
            // Delete old image
            if (Storage::disk('public')->exists($post->image_path)) {
                Storage::disk('public')->delete($post->image_path);
            }

            // Store new image
            $validated['image_path'] = $request->file('image')->store('posts', 'public');
        }

        // Update the post with validated data
        $post->caption = $validated['caption'] ?? $post->caption;
        $post->location = $validated['location'] ?? $post->location;

        // Only update image_path if a new image was uploaded
        if (isset($validated['image_path'])) {
            $post->image_path = $validated['image_path'];
        }

        $post->save();

        // Redirect to the user's dashboard/profile page
        return redirect()->route('profile.show', ['profile' => Auth::id()])
            ->with('success', 'Post updated successfully!');
    }

    public function destroy(Post $post)
    {
        // Check if the current user owns this post
        if (Auth::id() !== $post->user_id) {
            abort(403, 'Unauthorized action.');
        }

        // Delete the image from storage
        if (Storage::disk('public')->exists($post->image_path)) {
            Storage::disk('public')->delete($post->image_path);
        }

        $post->delete();

        return redirect()->route('profile.show', ['profile' => Auth::id()])
            ->with('success', 'Post deleted successfully!');
    }
}
