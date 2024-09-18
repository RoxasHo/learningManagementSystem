<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\QuestionRequest;
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
    /*
    public function validateAnswer($quizz_id,$answer,$question_number){
        $question = DB::select('select answer from questions where quizz_id=? and question_number=?',[$quizz_id,$question_number]);
        $question = $question[0];    
        return $answer == $question->answer;
    }
    public function updateQuestion(QuestionRequest $request){
        $question= Question::find($request->id);
        if($question){
            $question->quizz_id= $request->quizz_id;
            $question->statement=$request->statement;
            $question->option1=$request->option1;
            $question->option2=$request->option2;
            $question->option3=$request->option3;
            $question->option4=$request->option4;
            $question->option5=$request->option5;
            $question->option6=$request->option6;
            $question->type=$request->type;
            $question->answer=$request->answer;
        }
    }
    public function deleteQuestion($quizz_id,$question_number,$question_id){
        Question::destroy($question_id);
    }
    */


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
    public function deleteQuestion($id){
        $question = Question::find($id);
        $question_number = $question->question_number;
        $deletedItem = Question::where('question_numer',$question_number)->delete();
        return redirect()->back();
    } 

}
