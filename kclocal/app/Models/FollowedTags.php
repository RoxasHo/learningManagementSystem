<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FollowedTags extends Model
{
    use HasFactory;

   

    protected $fillable = [
        'userID', 
        'tag'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    
    
}
