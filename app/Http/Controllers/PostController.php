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
        // Use findOrFail to throw a 404 if the post isn't found
        $post = Post::with('user')->findOrFail($id);

        return view('posts.posts', compact('post'));
    }

    public function edit(Post $post)
    {
        // Check if the current user owns this post
        if (Auth::id() !== $post->user_id) {
            abort(403, 'Unauthorized action.');
        }

        return view('posts.edit', compact('post'));
    }

    public function update(Request $request, Post $post)
    {
        // Check if the current user owns this post
        if (Auth::id() !== $post->user_id) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'caption' => 'nullable|string|max:2200',
            'location' => 'nullable|string|max:100',
        ]);

        $post->update($validated);

        return redirect()->route('posts.show', $post)
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
