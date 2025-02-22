<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Teacher extends Model
{
    use HasFactory;
    protected $primaryKey = 'id'; 
    public $incrementing = true; // Indicates that this is an auto-incrementing field
    protected $keyType = 'int'; // Ensure it's set to int if using auto-increment
    
    protected $fillable = [
        'userID',
        'name',
        'certification',
        'identityProof',
        'teacherPicture',
        'yearsOfExperience',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'userID');
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'teacher_courses', 'teacher_id', 'course_id');
    }

public function getCertificationAttribute($value)
{
    return Storage::url($value);
}

public function getIdentityProofAttribute($value)
{
    return Storage::url($value);
}

public function getTeacherPictureAttribute($value)
{
    return Storage::url($value);
}

}
