<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Question;
use Illuminate\Support\Facades\DB;
class QuestionController extends Controller
{
    public function createQuestion(Request $request){
        Question::create([
            'statement'=>$request->statement,
            'question_number'=>$request->question_number,
            'quizz_id'=>$request->quizz_id,
            'type'=>$request->type
        ]);
        
        return redirect()->back();
    }
    
    public function updateQuestion(Request $request){
        //dump($request);
        $question = Question::find($request->question_id);
        if($question){
            $question->statement=$request->statement; 
            $question->save();    
            return response()->json([
                'status' => 'success',
                'message' => 'Question updated successfully!',
            ]);
        }
        else{
            return response()->json([
                'status' => 'failed',
                'message' => 'Question updated failed!',
            ]);
        }
        
    }
    
    public function addOption(Request $request){
        Question::create([
            'quizz_id'=>$request->quizz_id,
            'type'=>'Option',
            'question_number'=>$request->question_number,
            'statement'=>$request->statement
        ]);
        return redirect()->back();
    }

    public function addAnswer(Request $request){
        Question::create([
            'quizz_id'=>$request->quizz_id,
            'type'=>'Answer',
            'question_number'=>$request->question_number,
            'statement'=>$request->statement
        ]);
        return redirect()->back();
    }


    public function deleteQuestion($id) {
        
            // Split the ID by '-' and get the last part (the number)
            $id = explode('-', $id);  
            $id = end($id);  
    
            // Find the question by ID
            $question = Question::find($id);
    
            if (!$question) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Question not found!',
                ], 404);
            }
            $quizz_id = $question->quizz_id;
            
            // Get the question number and delete the question(s) with that number
            $question_number = $question->question_number;
            $cnt = Question::where('quizz_id',$quizz_id)->where('type','Question')->count();
            if($cnt==1){
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Cannot remove last question!',
                ], 400);
            }
            $deletedItem = Question::where('question_number', $question_number)->delete();
    
            // Return a success message if the question was deleted
            return response()->json([
                'status' => 'success',
                'message' => 'Question deleted successfully!',
            ], 200);
            
        
    }
    public function deleteOption($id) {
        
        // Split the ID by '-' and get the last part (the number)
        $id = explode('-', $id);  
        $id = end($id);  

        // Find the question by ID
        $question = Question::find($id);
        
        if (!$question) {
            return response()->json([
                'status' => 'error',
                'message' => 'Question not found!',
            ], 404);
        }

        $question->delete();
        

        // Return a success message if the question was deleted
        return response()->json([
            'status' => 'success',
            'message' => 'Question deleted successfully!',
        ], 200);
        
    
}
    
}
