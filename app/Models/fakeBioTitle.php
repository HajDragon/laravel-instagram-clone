<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class fakeBioTitle extends Model
{
    use HasFactory;
    
    protected $table = 'fake_bio_titles';

    protected $fillable = [
        'title',
        'bio',
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'bio_title_id');
    }
}

