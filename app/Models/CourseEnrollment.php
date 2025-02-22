<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseEnrollment extends Model
{
    use HasFactory;
    protected $table="course_enrollment";
    protected $fillable=[
        'student_id',
        'course_id',
        'rating',
        'comment'
    ];
}
