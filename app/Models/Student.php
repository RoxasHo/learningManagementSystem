<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model {

    use HasFactory;

    protected $fillable = [
        'userID',
        'name',
        'reportComment',
        'customerContactNumber',
        'progress',
        'interests',
        'profile_photo_url',
    ];

    public function user() {
        return $this->belongsTo(User::class, 'userID');
    }
/*
    // Student.php
    public function recentCourses() {
        return $this->hasMany(Course::class, 'student_id'); // 根据你的数据库字段定义
    }

    public function completedCourses() {
        return $this->hasMany(Course::class, 'student_id')->where('status', 'completed');
    }

    public function eCertifications() {
        return $this->hasMany(Certification::class, 'student_id');
    }

 * 
 */
    
}
