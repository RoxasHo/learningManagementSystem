<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Quizzs;

class QuizzController extends Controller
{
    //
    public function quizzIndex($id){
        
        $quizz = DB::select('select Qs.id as question_id,Qs.question_number,Qs.statement,Qs.type from Questions Qs where Qs.quizz_id = ?',[$id]);
        $groupedQuizz = [];
        // Iterate over the fetched questions
        foreach ($quizz as $question) {
            // Check if 'question_number' already exists in the grouped array
            if (!array_key_exists($question->question_number, $groupedQuizz)) {
                // If not, initialize it as an empty array
                $groupedQuizz[$question->question_number] = [];
            }
            // Append the current question to the respective 'question_number' group
            $groupedQuizz[$question->question_number][] = $question;
        }
        return view('quizz/index',['groupedQuizz'=>$groupedQuizz,'quizz_id'=>$id]);
    }
    
}
