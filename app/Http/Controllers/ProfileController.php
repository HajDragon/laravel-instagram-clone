<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use App\Models\Post;
use App\Models\Follower;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use illuminate\Support\Collection;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        // Handle bio update through the bioTitle relationship
        if ($user->bioTitle) {
            // Update existing bioTitle
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'bio' => 'required|string', // Make sure bio is required
            ]);

            $user->bioTitle->update($validated);
        } else {
            // Create new bioTitle if it doesn't exist
            $bioTitle = \App\Models\fakeBioTitle::create([
                'bio' => $request->bio ?? '',
                'title' => $request->title ?? 'My Profile' // Default title if none provided
            ]);
            $user->bio_title_id = $bioTitle->id;
        }

        $user->save();

        return back()->with('status', 'Profile updated successfully');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    /**
     * Display the specified user's profile.
     */
    public function show(User $profile)
    {
        // Eager load relationships
        $profile->load(['bioTitle', 'followers', 'followings']);

        // Initialize posts as an empty collection (never null)
        $posts = collect([]);

        try {
            // Get the user's posts
            $posts = $profile->posts()->latest()->get();
        } catch (\Exception $e) {
            \Log::error('Error loading posts: ' . $e->getMessage());
            // Keep the empty collection if error occurs
        }

        $isFollowing = false;

        if (Auth::check()) {
            $isFollowing = Auth::user()->follows($profile);
        }

        return view('dashboard', [
            'profile' => $profile,
            'isFollowing' => $isFollowing,
            'posts' => $posts, // This will always be defined as at least an empty collection
        ]);
    }

    public function follow(Request $request, User $profile)
    {
        // Validate request data
        $validated = $request->validate([
            'follow' => 'required|boolean',
        ]);

        $userId = Auth::id();

        // Optional: Prevent self-following
        if ($userId === $profile->id) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot follow yourself'
            ], 400);
        }

        $follow = $validated['follow'];

        if ($follow) {
            // Create a follower record if one does not exist already
            $existingFollow = Follower::where('user_id', $userId)
                ->where('following_id', $profile->id)
                ->exists();

            if (!$existingFollow) {
                Follower::create([
                    'user_id' => $userId,
                    'following_id' => $profile->id,
                ]);
            }
        } else {
            // Remove the follower relationship
            Follower::where('user_id', $userId)
                ->where('following_id', $profile->id)
                ->delete();
        }

        $isFollowing = Follower::where('user_id', $userId)
            ->where('following_id', $profile->id)
            ->exists();

        return response()->json(['success' => true, 'following' => $isFollowing]);
    }

    /**
     * Display the current user's dashboard.
     */
    public function index(Request $request)
    {
        // Use the authenticated user's profile
        $profile = Auth::user();

        // Since we're viewing our own profile, we're not following ourselves
        $isFollowing = false;

        return view('dashboard', [
            'profile' => $profile,
            'isFollowing' => $isFollowing,
        ]);
    }

    /**
     * Display the user's followers.
     */
    public function followers(User $profile)
    {
        $followers = $profile->followers()->with('follower')->paginate(15);

        return view('profile.followers', [
            'profile' => $profile,
            'followers' => $followers
        ]);
    }

    /**
     * Display the users that this profile follows.
     */
    public function following(User $profile) // Match this method name with the route
    {
        $followings = $profile->followings()->with('following')->paginate(15);

        return view('profile.following', [
            'profile' => $profile,
            'followings' => $followings
        ]);
    }

    /**
     * Display the users that this profile follows.
     */
    public function followings(User $profile)
    {
        $followings = $profile->followings()->with('following')->paginate(15);

        return view('profile.followings', [
            'profile' => $profile,
            'followings' => $followings
        ]);
    }

    public function updateImage(Request $request)
    {
        $request->validate([
            'profile_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = Auth::user();

        // Delete old image if it exists
        if ($user->profile_image && Storage::disk('public')->exists($user->profile_image)) {
            Storage::disk('public')->delete($user->profile_image);
        }

        // Store the new image
        $path = $request->file('profile_image')->store('profile-photos', 'public');
        $user->profile_image = $path;
        $user->save();

        return back()->with('status', 'Profile image updated successfully');
    }

    /**
     * Display a post.
     */
    public function showPost($id)
    {
        $post = Post::with('user')->findOrFail($id);

        return view('posts.posts', compact('post'));
    }

    /**
     * Show the form for creating a new post.
     */
    public function createPost()
    {
        return view('posts.create');
    }

    /**
     * Store a newly created post in storage.
     */
    public function storePost(Request $request)
    {
        $validated = $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'caption' => 'nullable|string|max:2200',
            'location' => 'nullable|string|max:255',
        ]);

        $imagePath = $request->file('image')->store('posts', 'public');

        Post::create([
            'user_id' => Auth::id(),
            'image_path' => $imagePath,
            'caption' => $validated['caption'] ?? null,
            'location' => $validated['location'] ?? null,
        ]);

        return redirect()->route('profile.show', ['profile' => Auth::id()])
            ->with('status', 'Post created successfully!');
    }
    public function photos()
    {
        return view('photos', [
        ]);
    }
}
