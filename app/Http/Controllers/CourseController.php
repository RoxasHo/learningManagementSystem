<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Chapter;
use App\Models\CourseEnrollment;
use App\Models\Progress;
use Inertia\Inertia;
use App\Http\Requests\courseAddRequest;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage; //php artisan storage:link = php artisan storage:link = http://127.0.0.1:8000/storage/1.jpg
use Illuminate\Support\Facades\DB;
class CourseController extends Controller
{
    public function teacherIndex($teacherId){ 
        
        
        $course = DB::table('courses as C')
    ->join('teacher_courses as TC', 'C.id', '=', 'TC.course_id')
    ->select('C.id','C.CourseName', 'C.Difficulty', 'C.Description', 'C.status','TC.role')
    ->where('TC.teacher_id', $teacherId)
    ->get();
        if(!$course){
         return response()->json([
            'message'=>'Course Not Found.'
         ],404);
       }
       // Return Json Response
       /*
       return response()->json([
          'course' => $course
       ],200);
       */
       return view('course/index',['id'=>$teacherId,'course'=>$course]);
    }
    public function studentIndex($studentId){  
        //$course = Course::findWithTeacherId($studentId);

        $course = DB::table('courses as C')->join ('chapters as Ch','Ch.course_id','=','C.id')
                    ->join('progresses as P','P.chapter_id','=','Ch.id')
                    ->select('C.course_name','C.course_detail','')
                    ->where('P.student_id',$studentId)
                    ->get();

        if(!$course){
         return response()->json([
            'message'=>'Course Not Found.'
         ],404);
       }
       // Return Json Response
       /*
       return response()->json([
          'course' => $course
       ],200);
       */
       return view('course/index',['id'=>$studentId,'course'=>$course]);
    }
    public function viewCourse($course_id){
        $course = DB::table('courses as C')
                    ->select('C.id','C.CourseName','C.Difficulty','C.Description')
                    ->where('C.id',$course_id)->get();
        $course=$course[0];
        
        $rating = DB::table('course_enrollment')
        ->where('course_id', $course_id)
        ->avg('rating');

        $teachers = DB::table('teachers as T')
                    ->join('teacher_courses as TC','TC.teacher_id','=','T.teacherID')
                    ->select('T.name')
                    ->where('TC.course_id',$course_id)
                    ->get();
        $teachers=$teachers[0];

        
        return view('course/view',['course'=>$course,'rating'=>$rating,'teachers'=>$teachers,'student_id'=>'1']);
    }
    public function courseTeamIndex($course_id){
        
        $team = DB::table('teacher_courses as TC')
        ->join('courses as C', 'C.id', '=', 'TC.course_id')
        ->join('teachers as T','T.teacherId','=','TC.teacher_id')
        ->select('T.name','TC.role')
        ->where('TC.course_id', $course_id)
        ->get();
        
        //dump($team);
        return view('course-team',['team'=>$team]);
        
    }
    
    public function addCourse(courseAddRequest $request){
        //dump($request);
        try {   
            $ch = Course::create([
                'CourseName' => $request->CourseName,
                'Difficulty' => $request->Difficulty,
                'Description' => $request->Description,
                'status'=>'pending',
                
            ]);
            $data = [
                'teacher_id' => 1,  // Replace with the actual teacher_id
                'course_id' => $ch->id,   // Replace with the actual course_id
                'role' => 'leader' // Replace with the actual status
            ];
        
            // Insert the data into the teacher_courses table
            DB::table('teacher_courses')->insert($data);
            
            
            // Return Json Response
            return redirect()->back()->with('message', 'Course successfully created.');
        } catch (\Exception $e) {
            // Return Json Response
            return redirect()->back()->with('message', $e);
        }
    }
    public function courseIndex($course_id,$chapter_id,$selectedType){



        $chapters = DB::select('select * from chapters where course_id=? order by chapter_number ',[$course_id]);
        $materials = DB::select('select M.id as material_id,M.chapter_id,M.content  from materials M,chapters C where M.chapter_id=C.id and C.course_id=?',[$course_id]);
        $quizzs  =   DB::select('select Q.id as quizz_id,Q.content,Q.chapter_id,Qs.question_number,Qs.statement,Qs.type from quizz Q,chapters C ,questions Qs where Q.chapter_id=C.id and Qs.quizz_id=Q.id and C.course_id=?',[$course_id]);
        dump($quizzs);
        // Assuming $quizzes is the array fetched from the database
        $groupedQuizzes = [];
        $quizz_id = $quizzs[0]->quizz_id;
        // Group quizzes by question_number
        foreach ($quizzs as $quiz) {
            $questionNumber = $quiz->question_number;
            if (!isset($groupedQuizzes[$questionNumber])) {
                $groupedQuizzes[$questionNumber] = [
                    'questions' => [],
                    'options' => [],
                    'answers' => []
                ];
            }

            if ($quiz->type === 'Question') {
                $groupedQuizzes[$questionNumber]['questions'][] = $quiz;
            } elseif ($quiz->type === 'Option') {
                $groupedQuizzes[$questionNumber]['options'][] = $quiz;
            } elseif ($quiz->type === 'Answer') {
                $groupedQuizzes[$questionNumber]['answers'][] = $quiz;
            }
        }




        dump($groupedQuizzes);

        if($chapter_id=='null')
        $chapter_id = $chapters[0]->id;
        return view('course/edit',['chapters'=>$chapters,'materials'=>$materials,'quizzs'=>$groupedQuizzes,'course_id'=>$course_id,'chapter_id'=>$chapter_id,'selectedType'=>$selectedType,'content_id'=>'','quizz_id'=>$quizz_id]);
        //return view('course/edit',['chapter'=>$chapters,'course_id'=>$course_id]);
    }
    
    public function enrollCourse(Request $request){
        
        
        $student_id = $request->student_id;
        $course_id = $request->course_id;
        $ce = CourseEnrollment::where('course_id',$course_id)->where('student_id',$student_id)->get();
        if($ce->isEmpty()){

        
        CourseEnrollment::create([
            'student_id'=>$student_id,
            'course_id'=>$course_id,
            'rating'=>0,
            'comment'=>'' 

        ]);
        $chapters = DB::select('select id,chapter_number from chapters where course_id=?',[$course_id]);
       
        foreach($chapters as $ch){
            
            if($ch->chapter_number<=3){
                Progress::create([
                    'chapter_id'=>$ch->id,
                    'student_id'=>$student_id,
                    'status'=>'Uncomplete'
    
                ]);
            }
            else{
                Progress::create([
                    'chapter_id'=>$ch->id,
                    'student_id'=>$student_id,
                    'status'=>'Locked'
    
                ]);
            }
            
        }
    }
    
     
    return redirect()->route('course-study',['student_id'=>$student_id,'course_id'=>$course_id,'chapter_id'=>'null','selectedType'=>'Material']);
    }
    public function courseStudy($student_id,$course_id,$chapter_id,$selectedType){
        $chapters = DB::select('select * from chapters Ch,progresses P where Ch.id=P.chapter_id and course_id=? order by chapter_number',[$course_id]);

        $materials = DB::select('select M.id as material_id,M.chapter_id,M.content  from materials M,chapters C where M.chapter_id=C.id and C.course_id=?',[$course_id]);
        $quizzs  =   DB::select('select * from quizz Q,chapters C ,questions Qs where Q.chapter_id=C.id and Qs.quizz_id=Q.id and C.course_id=?',[$course_id]);
        $prog = DB::select('select * from progresses P where P.student_id=? and P.chapter_id=?',[$student_id,$chapter_id]);
        $groupedQuizzes = [];
        $quizz_id = $quizzs[0]->quizz_id;
        // Group quizzes by question_number
        foreach ($quizzs as $quiz) {
            $questionNumber = $quiz->question_number;
            if (!isset($groupedQuizzes[$questionNumber])) {
                $groupedQuizzes[$questionNumber] = [
                    'questions' => [],
                    'options' => [],
                    'answers' => []
                ];
            }

            if ($quiz->type === 'Question') {
                $groupedQuizzes[$questionNumber]['questions'][] = $quiz;
            } elseif ($quiz->type === 'Option') {
                $groupedQuizzes[$questionNumber]['options'][] = $quiz;
            } elseif ($quiz->type === 'Answer') {
                $groupedQuizzes[$questionNumber]['answers'][] = $quiz;
            }
        }



        
        
        if($chapter_id=='null')
        $chapter_id = $chapters[0]->id;
        return view('course/student',['chapters'=>$chapters,'materials'=>$materials,'quizzs'=>$groupedQuizzes,'course_id'=>$course_id,'chapter_id'=>$chapter_id,'student_id'=>$student_id,'selectedType'=>$selectedType,'content_id'=>'','prog'=>$prog,'quizz_id'=>$quizz_id]);
        //return view('course/edit',['chapter'=>$chapters,'course_id'=>$course_id]);
    }
    public function changeContent($id,$type){
        

        return Redirect()->back();
    }
    
    
    
}
