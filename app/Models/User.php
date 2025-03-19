<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class User
 * 
 * Represents a user in the Instagram clone application.
 * 
 * @property int $id
 * @property string $name The user's display name
 * @property string $email The user's email address
 * @property \Carbon\Carbon|null $email_verified_at
 * @property string $password The hashed password
 * @property int|null $bio_title_id Reference to the bio and title
 * @property string|null $profile_image Path to the user's profile image
 * @property string|null $remember_token
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * 
 * @property-read \App\Models\fakeBioTitle|null $bioTitle
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Follower[] $followers
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Follower[] $followings
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Post[] $posts
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'bio_title_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Get the user's bio title relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function bioTitle()
    {
        return $this->belongsTo(fakeBioTitle::class, 'bio_title_id');
    }

    /**
     * Get the user's title from the related bioTitle.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->bioTitle ? $this->bioTitle->title : 'No title set';
    }

    /**
     * Get the user's bio from the related bioTitle.
     *
     * @return string
     */
    public function getBio()
    {
        return $this->bioTitle ? $this->bioTitle->bio : 'No bio set';
    }

    /**
     * Get users who follow this user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function followers()
    {
        return $this->hasMany(Follower::class, 'following_id');
    }

    /**
     * Get users that this user follows.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function followings()
    {
        return $this->hasMany(Follower::class, 'user_id');
    }

    /**
     * Check if this user follows another user.
     *
     * @param User $user The user to check
     * @return bool
     */
    public function follows(User $user)
    {
        return $this->followings()->where('following_id', $user->id)->exists();
    }

    /**
     * Get all posts created by this user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function posts()
    {
        return $this->hasMany(Post::class);
    }
}
