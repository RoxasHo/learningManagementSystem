<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chapter extends Model
{
    use HasFactory;
    protected $table="chapters";
    protected $fillable=[
        'chapter_name',
        'chapter_number',
        'chapter_description',
        'course_id'
    ];
    public static function getChapterWithCourse($courseId){
           $chapters = Chapter::where("course_id", $courseId)->get();
           return $chapters;
        
    }
   
}
