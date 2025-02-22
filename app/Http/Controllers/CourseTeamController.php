<?php

namespace App\Http\Controllers;
use App\Models\TeacherCourse;
use App\Models\Teacher;
use App\Models\Course;
use App\Models\User;
use Brick\Math\BigInteger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use response;
class CourseTeamController extends Controller
{
    public function viewCourseTeam($course_id) {
        // Redirect if the user is a Student
        if (auth()->user()->role == 'Student') {
            return redirect('/');
        }
    
        $user_id = auth()->id();
        $course_name=Course::find($course_id);
        $course_name=$course_name->course_name;
        $teacher = DB::table('teachers')->where('userID', $user_id)->first(); // Use first() here
        if(auth()->user()->role=='Moderator'){
            $team = DB::table('teacher_courses')
                ->join('teachers', 'teachers.id', '=', 'teacher_courses.teacher_id')
                ->where('teacher_courses.course_id', $course_id)
                ->get();
        
               
            return view('courses.team', [
                'team' => $team,
                'course_id' => $course_id,
                'course_name'=>$course_name,
                'role' => 'Moderator'
            ]);
        }
        else{
            if (!$teacher) {
                // Handle the case where the teacher is not found, e.g., return an error or redirect
                return redirect('/')->withErrors('Teacher not found.');
            }
        
            $teacher_id = $teacher->id;
        
            $role = DB::table('teacher_courses')
                ->where('teacher_id', $teacher_id)
                ->where('course_id', $course_id)
                ->select('role')
                ->first(); // Use first() to get a single record
        
            // Handle case where the role is not found
            if (!$role) {
                return redirect('/')->withErrors('Role not found for this course.');
            }
        
            $team = DB::table('teacher_courses')
                ->join('teachers', 'teachers.id', '=', 'teacher_courses.teacher_id')
                ->where('teacher_courses.course_id', $course_id)
                ->get();
        
            $role=$role->role;
            
            return view('courses.team', [
                'team' => $team,
                'course_id' => $course_id,
                'course_name'=>$course_name,
                'role' => $role,
            ]);
        }
        
    }
    
    public function addTeacher(Request $request){
        
        if(auth()->user()->role =='Student'){
            return redirect('/');
        }
        $course_id = $request->course_id;
        $teacher_address = $request->new_teacher_email;
       ;
        
        $teacher=User::where('email',$teacher_address)->first();
        $new_teacher= Teacher::where('userID',$teacher->id)->first();
        //dump($teacher);
        //dump($new_teacher);
        if(!$new_teacher || $teacher->role!='Teacher'){
           dump($new_teacher);
            //return;
            return redirect()->back()->with('failed','the teacher address not found!!!');
        }
        $tc = TeacherCourse::where('teacher_id',$new_teacher->id)->where('course_id',$course_id)->first();
        if($tc){
            return redirect()->back()->with('failed','already exist');
        }
        $new_teacher_id=$new_teacher->id;
        $data=[
            'course_id'=>$course_id,
            'teacher_id'=> $new_teacher_id,
            'role'=>'member'
        ];
        DB::table('teacher_courses')->insert($data);
        return redirect()->back();
    }
    public function removeTeacher(Request $request){
        // Authorization check
        $course_id=$request->course_id;
        $teacher_id=$request->teacher_id;
        dump($request);
        if(auth()->user()->role == 'Student'){
            return redirect()->back()->with('failed','Not a teacher');
        }
    
        // Find the TeacherCourse record
        $tc = TeacherCourse::where('teacher_id', (int)$teacher_id)
            ->where('course_id', (int)$course_id)
            ->first();
    
        // Check if record exists
        if ($tc == null) {
            return redirect()->back()->with('failed','Record not found');// 404 for not found
        }
    
        // Prevent removing a leader
        if ($tc->role == 'leader') {
            return redirect()->back()->with('failed','No removing leader');// 404 for not found
        }
    
        // Delete the TeacherCourse record
        $tc->delete();
    
     return redirect()->back()->with('success','Deleted!!!');// 404 for not found
    }
    

}
