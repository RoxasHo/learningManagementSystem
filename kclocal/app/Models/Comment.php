<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $primaryKey = 'comment_id'; 
    protected $fillable = [
        'comment_id',
        'content',
        'post_id',      
        'parent_comment_id', 
        'userID',
        'is_visible',
    ];

    public function post()
    {
        return $this->belongsTo(Post::class, 'post_id', 'post_id');
    }

    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_comment_id');
    }

    // Relationship to the replies of this comment
    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_comment_id');
    }

    public function votes()
    {
        return $this->hasMany(Vote::class, 'comment_id', 'comment_id');
    }
    
    public function likes()
    {
        return $this->hasMany(Vote::class, 'comment_id')->where('type', 'like');
    }
    
    public function dislikes()
    {
        return $this->hasMany(Vote::class, 'comment_id')->where('type', 'dislike');
    }
    
    public function userHasLiked($user)
    {
        return $this->votes()->where('userID', $user->id)->where('type', 'like')->exists();
    }
    
    public function userHasDisliked($user)
    {
        return $this->votes()->where('userID', $user->id)->where('type', 'dislike')->exists();
    }

public function user()
{
    return $this->belongsTo(User::class, 'userID');
}

}

