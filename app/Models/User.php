<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'bio_title_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Get the user's bio title relationship
     */
    public function bioTitle()
    {
        return $this->belongsTo(fakeBioTitle::class, 'bio_title_id');
    }

    /**
     * Get the user's title from the related bioTitle
     */
    public function getTitle()
    {
        return $this->bioTitle ? $this->bioTitle->title : 'No title set';
    }

    /**
     * Get the user's bio from the related bioTitle
     */
    public function getBio()
    {
        return $this->bioTitle ? $this->bioTitle->bio : 'No bio set';
    }

    public function followers()
    {
        return $this->hasMany(Follower::class, 'following_id');
    }

    public function followings()
    {
        return $this->hasMany(Follower::class, 'user_id');
    }

    public function follows(User $user)
    {
        return $this->followings()->where('following_id', $user->id)->exists();
    }

    /**
     * Get the posts for the user.
     */
    public function posts()
    {
        return $this->hasMany(Post::class);
    }
}
