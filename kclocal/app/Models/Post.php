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
        'userID',
    
    ];

    public function comments()
    {
        return $this->hasMany(Comment::class, 'post_id', 'post_id');
    }

    public function votes()
{
    return $this->hasMany(Vote::class, 'post_id', 'post_id');
}

public function likes()
{
    return $this->hasMany(Vote::class, 'post_id')->where('type', 'like');
}

public function dislikes()
{
    return $this->hasMany(Vote::class, 'post_id')->where('type', 'dislike');
}

public function user()
    {
        return $this->belongsTo(User::class, 'userID');
    }

    public function userHasLiked($user)
    {
        return $this->votes()->where('userID', $user->id)->where('type', 'like')->whereNull('comment_id')->exists();
    }
    
    public function userHasDisliked($user)
    {
        return $this->votes()->where('userID', $user->id)->where('type', 'dislike')->whereNull('comment_id')->exists();
    }

}
