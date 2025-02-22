<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name', 'image_url'];

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_category');
    }
}
