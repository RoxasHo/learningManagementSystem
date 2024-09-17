<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    use HasFactory;
    protected $primaryKey = 'vote_id';

    protected $fillable = ['post_id', 'comment_id', 'type', 'userID'];

    public function post()
    {
        return $this->belongsTo(Post::class, 'post_id');
    }

    public function comment()
    {
        return $this->belongsTo(Comment::class, 'comment_id');
    }

    public function votes()
{
    return $this->hasMany(Vote::class, 'post_id');
}

public function scopeForPost($query)
{
    return $query->whereNull('comment_id');
}

public function scopeForComment($query)
{
    return $query->whereNotNull('comment_id');
}
}

