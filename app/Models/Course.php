<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Teacher;
class Course extends Model
{
    
    protected $table="courses";
    protected $fillable = [
        'course_name', 
        'course_description', 
        'category_id',
        'difficulty', 
        'rating', 
        'teacher_name',
        'clicks',
    ];

    public $timestamps = true;

    public function enrollments() {
        return $this->hasMany(Enrollment::class);
    }

    public function userCourses() {
        return $this->hasMany(UserCourse::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'course_category');
    }
    
    public function teachers()
    {
        return $this->belongsToMany(Teacher::class, 'teacher_courses', 'course_id', 'teacher_id');
    }
    

    public function progresses()
    {
        return $this->hasMany(Progress::class, 'course_id'); // Adjust 'course_id' if necessary
    }

    public function chapters()
    {
        return $this->hasMany(Chapter::class, 'course_id', 'id');
    }
}


