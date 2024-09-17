<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model {

    use HasFactory;

    
      protected $primaryKey = 'studentID'; // Use 'studentID' as primary key
    public $incrementing = true; // Indicates that this is an auto-incrementing field
    protected $keyType = 'int'; // Ensure it's set to int if using auto-increment
    
    protected $fillable = [
        'userID',
        'name',
        'reportComment',
        'progress',
        'interests',
        'studentPicture',
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
