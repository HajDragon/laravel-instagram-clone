<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Follower
 * 
 * Represents a follow relationship between users in the Instagram clone.
 * 
 * @property int $id
 * @property int $user_id The ID of the user who is following
 * @property int $following_id The ID of the user being followed
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * 
 * @property-read \App\Models\User $follower The user who is following
 * @property-read \App\Models\User $following The user being followed
 */
class Follower extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'following_id',
    ];

    /**
     * Get the user who is following.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function follower()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the user being followed.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function following()
    {
        return $this->belongsTo(User::class, 'following_id');
    }
}