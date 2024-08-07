<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class Course extends Model{
    use HasFactory;
    
    protected $table="courses";
    protected $fillable=[
        'CourseName',
        'Description',
        'Difficulty',
        'FileLocation'
    ];
    
    public static function findWithTeacherId($teacherId){
           /*
        $courses = DB::table('courses')
           ->where('teacherId', '=', $teacherId)
           ->get();
           
           */
          $courses = Course::all();
          
         return $courses;
        
        
        
    } 
     
    
}
