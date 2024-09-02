<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $primaryKey = 'post_id'; 
    protected $fillable = [
        
        'title',
        'content',
        'tag',
    
    ];

    public function comments()
    {
        return $this->hasMany(Comment::class, 'post_id', 'post_id');
    }

    public function votes()
{
    return $this->hasMany(Vote::class, 'post_id', 'post_id');
}

public function getLikeCountAttribute()
{
    return $this->votes()->where('type', 'like')->count();
}

public function getDislikeCountAttribute()
{
    return $this->votes()->where('type', 'dislike')->count();
}
}
