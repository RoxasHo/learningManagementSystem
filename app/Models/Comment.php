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

public function getLikeCountAttribute()
{
    return $this->votes()->where('type', 'like')->count();
}

public function getDislikeCountAttribute()
{
    return $this->votes()->where('type', 'dislike')->count();
}

}

