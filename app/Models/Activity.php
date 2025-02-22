<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $fillable = ['user_id', 'course_id', 'action'];

    // Relationship to the Course model
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    // Relationship to the User model (assuming you have a User model)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
