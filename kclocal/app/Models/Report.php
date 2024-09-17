<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;
    protected $fillable = [
        'report_type',
        'custom_content',
        'post_id',
        'comment_id',
        'userID',
    ];

    
    public function post()
    {
        return $this->belongsTo(Post::class, 'post_id');
    }

    public function comment()
    {
        return $this->belongsTo(Comment::class, 'comment_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'userID'); // Define the relationship to the User model
    }
    
}
