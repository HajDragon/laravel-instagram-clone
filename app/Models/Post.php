<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'image_path',
        'caption',
        'location',
    ];

    /**
     * Get the image URL for the post
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

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
