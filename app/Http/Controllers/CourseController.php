<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use Inertia\Inertia;
use App\Http\Requests\courseAddRequest;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage; //php artisan storage:link = php artisan storage:link = http://127.0.0.1:8000/storage/1.jpg
 
class CourseController extends Controller
{
    public function teacherIndex($teacherId){  
        $course = Course::findWithTeacherId($teacherId);
        if(!$course){
         return response()->json([
            'message'=>'Product Not Found.'
         ],404);
       }
       // Return Json Response
       return response()->json([
          'course' => $course
       ],200);
    }
    public function addCourse(courseAddRequest $request){
        
        
    
        try {
          
            $path = '\tid0001\c1';
            // Create Product
            Course::create([
                'CourseName' => $request->CourseName,
                'Difficulty' => $request->Difficulty,
                'Description' => $request->Description,
                'FileLocation' => 'D:\laravel_storage'.$path,
            ]);
      
            
            Storage::disk('custom')->makeDirectory($path);
            // Return Json Response
            return response()->json([
                'message' => "Course successfully created."
            ],200);
        } catch (\Exception $e) {
            // Return Json Response
            return response()->json([
                'message' => "Something went really wrong!". $e
            ],500);
        }
    
        
        
    }
    public function courseIndex(){
        
    }
    public function updateCourse(courseUpdateReequest $request){
        return;
    }
    
    
}
