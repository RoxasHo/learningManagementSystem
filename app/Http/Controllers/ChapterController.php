<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Chapter;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\ChapterRequest;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\QuizzController;
use App\Models\Material;
use App\Models\Quizz;
use App\Models\Question;
use App\Traits\Lockable;
class ChapterController extends Controller
{   
    use Lockable;
    public function chapterIndex($courseId){  
        
        //$chapters = Chapter::where('course_id', $courseId) ->orderBy('chapter_number')->get();
        $chapters = DB::select('select * from chapters where course_id = ? order by chapter_number',[$courseId]);
        //dump($chapters);
       
        
       return view('course/edit',['chapters'=>$chapters,'course_id'=>$courseId]);
    }

    public function addChapter(ChapterRequest $request){
        try {
            $Ch = Chapter::where('course_id', $request->course_id)
                                ->where('chapter_number', $request->chapter_number)
                                ->get();
            
            if($Ch->isEmpty()) {
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
                'content'=>' '
            ]);
            Question::create([
                'quizz_id'=>$quizz->id,
                'question_number'=>'1',
                'statement'=>'',
                'type'=>'Question'

            ]);
             
            
            
             return redirect()->back()->with('message', 'Course successfully created.');
            }
            else {return redirect()->back()->with('message', 'Chapter id and course number repeated.');}
        } catch (\Exception $e) {
            // Return Json Response
            return redirect()->back()->with('message', 'Course successfully created.');
        }
    }
    public function getMaterialId($chapter_id){
        $materialId = Material::select('id')->where('chapter_id', $chapter_id) ->get();

        
       return response()->json([
          'materialId' => $materialId
       ],200);
    } 
    public function deleteChapter($chapter_id){
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
    

    
    
       

        
}
