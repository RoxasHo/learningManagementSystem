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
class ChapterController extends Controller
{
    public function chapterIndex($courseId){  
        
        $chapters = Chapter::where('course_id', $courseId) ->orderBy('chapter_number')->get();

        
       return response()->json([
          'chapters' => $chapters
       ],200);
    }
    public function addChapter(ChapterRequest $request){
        try {
            $Ch = Chapter::where('course_id', $request->courseId)
                                ->where('chapter_number', $request->chapter_number)
                                ->get();
            if(!$Ch ) {return response()->json(['Chapter number and Course Id repeated','Chapter'=>$Ch],200);}
            $Ch=Chapter::create([
                'chapter_name' => $request->chapter_name,
                'course_id' => $request->course_id,
                'chapter_number' => $request->chapter_number,
                'chapter_description' => $request->chapter_description, 
            ]);
            MaterialController::createMaterial($Ch->id);
            QuizzController::createQuizz($Ch->id);
            
            
            return response()->json([
                'message' => "Chapter successfully created."
            ],200);
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
