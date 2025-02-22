<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Chapter;
use App\Models\Student;
use App\Models\Question;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\ChapterRequest;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\QuizzController;
use App\Models\Material;
use App\Models\Quizz;
use App\Models\Progress;
use Illuminate\Support\Facades\Log;
use PHPUnit\Framework\Constraint\IsEmpty;

class ChapterController extends Controller
{
    public function chapterIndex($courseId){  
        
        $chapters = Chapter::where('course_id', $courseId) ->orderBy('chapter_number')->get();

       
       
        
       return view('course/edit',['chapters'=>$chapters]);
    }
    public function addChapter(ChapterRequest $request){
        

        try {
            $Ch = Chapter::where('course_id', $request->course_id)
                                ->where('chapter_number', $request->chapter_number)
                                ->get();
            
            
            if($Ch->IsEmpty()) {
            $Ch=Chapter::create([
                'chapter_name' => $request->chapter_name,
                'course_id' => $request->course_id,
                'chapter_number' => $request->chapter_number,
                'chapter_description' => $request->chapter_description, 
            ]);
            
            Material::create([
                'chapter_id'=>$Ch->id,
                'content'=>' '
            ]);
            $quizz=Quizz::create([
                'chapter_id'=>$Ch->id,
                'content'=>'empty'
            ]);
            Question::create([
                'quizz_id'=>$quizz->id,
                'question_number'=>'1',
                'statement'=>'empty',
                'type'=>'Question'
,
            ]);
             
             
            
            
            return redirect()->back();
            }
            else {return response()->json(['Chapter number and Course Id repeated','Chapter'=>$Ch],500);}
        } catch (\Exception $e) {
            // Return Json Response
            return response()->json([
                'message' => "Something went really wrong!". $e->getMessage()
            ],500);
        }
    }
    public function getMaterialId($chapter_id){
        $materialId = Material::select('id')->where('chapter_id', $chapter_id) ->get();

        
       return response()->json([
          'materialId' => $materialId
       ],200);
    } 
    public function deleteChapter($chapter_id){
        if(auth()->user()->role=='Student' ){
            return redirect('/');
        }
        try {
            $Ch =  Chapter::where('id', $chapter_id)->delete();    
            return response()->json([
                'message' => 'Chapters deleted successfully!',
                'deleted_rows' => $Ch
            ], 200);
        } catch (\Exception $e) {
            // Return Json Response
            return response()->json([
                'message' => "Something went really wrong!". $e->getMessage()
            ],500);
        }
    }
    public function editChapter(ChapterRequest $request){
        if(auth()->user()->role=='Student' ){
            return redirect('/');
        }
        $id = $request->chapter_id;
        //return response()->json(['chapter id'=>$request->chapter_id,'id'=>$request->id],200);
        //$data = $request->only(['chapter_name', 'chapter_description']); // Adjust according to the fields you need to update
        try {
            // Update the chapter where course_id and chapter_number match
            $chapter = Chapter::find($id);
            if($request->chapter_number!=\null) {
                $chapter->chapter_number=$request->chapter_number;
            }
            if($request->chapter_name!=\null) {
                $chapter->chapter_name=$request->chapter_name;
            }
            if($request->chapter_description!=\null) {
                $chapter->chapter_description=$request->chapter_description;
            }
            $chapter->save();
            if ($chapter != \null) {
                return response()->json([
                    'message' => 'Chapter updated successfully!',
                    
                ], 200);
            } else {
                return response()->json([
                    'message' => 'No chapter found with the specified course_id and chapter_number.'
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => "Something went really wrong! " . $e->getMessage()
            ], 500);
        }
    }
    public function unlockChapter(Request $request){
        if(auth()->user()->role=='Teacher' ){
            return redirect('/');
        }
        $chapter_id = $request->chapter_id;
        $student_id=$request->student_id;
        $student=Student::find($request->student_id);
        if (!$student) {
            return response()->json([
                'message' => 'Student not found.'
            ], 404);
        }
        if($student->points>=1){
            $student->points-=1;
            $student->save();
            $progress= Progress::where('chapter_id',$chapter_id)->where('student_id',$student_id)->first();
            
            if($progress){
                $progress->status = 'Uncomplete';
                $progress->save();
            }
            else{
                $student->points+=1;
                $student->save();
                return response()->json([
                    'message' => "Progress not found! student_id: " .$student_id ."chapter_id: " . $chapter_id,
                    
                
                ], 400);
                
            }
            
            
            
            return response()->json([
                'message' => "Unlocked successfuly! " 
            
            ], 200);
            
        }
        else{
            return response()->json([
                'message' => "Points is not enough! " 
            ], 500);
        }
    }
        
}
