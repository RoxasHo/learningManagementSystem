<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Progress extends Model
{
    use HasFactory;
    protected $table="progresses";
    protected $fillable=[
        'chapter_id',
        'student_id',
        'status'
    ];

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id'); // Adjust 'course_id' if your foreign key has a different name
    }

    // Define the relationship with the Student model if necessary
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id'); // Adjust 'student_id' if your foreign key has a different name
    }
}
