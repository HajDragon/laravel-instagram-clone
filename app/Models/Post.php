<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Post
 * 
 * Represents a post in the Instagram clone application.
 * 
 * @property int $id
 * @property int $user_id The user who created the post
 * @property string $image_path Path to the image or Picsum reference
 * @property string|null $caption The post caption text
 * @property string|null $location The location tag for the post
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * 
 * @property-read string $image_url The URL to display the image
 * @property-read \App\Models\User $user
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Comment[] $comments
 */
class Post extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'image_path',
        'caption',
        'location',
    ];

    /**
     * Get the image URL for the post.
     * 
     * Handles both local storage and Picsum Photos integration.
     *
     * @return string The complete URL to the image
     */
    public function getImageUrlAttribute()
    {
        if (str_starts_with($this->image_path, 'picsum:')) {
            // Extract the Picsum ID number
            $id = substr($this->image_path, 7);
            return "https://picsum.photos/id/{$id}/800/800";
        }

        // Fallback to the traditional storage path
        return asset('storage/' . $this->image_path);
    }

    /**
     * Get the user who created this post.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the comments for this post.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
