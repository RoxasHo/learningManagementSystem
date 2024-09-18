<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Quizz;
use App\Models\Question;
use App\Models\Progress;
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
    /*
    submission = {
        'Q1'=>['id1','id2','id3'],
        'Q2'=>['id1','id2','id3'],
        ...

    
    }
    */


    public function quizzValidate(Request $request){
        // Validate the request
        /*
        $request->validate([
            'answers.*' => 'array', // Ensure each answer is an array
            'answers.*.*' => 'string', // Ensure each answer is a string
        ]);
        */
    
        $submittedAnswers = $request->input('answers'); // Get submitted answers
        $quizz_id=$request->quizz_id;
        $student_id=$request->student_id;
        $chapter_id=Quizz::where('id', $quizz_id)->first();
        if($chapter_id) $chapter_id=$chapter_id->chapter_id;
        
        $results = [];
        foreach ($submittedAnswers as $question_number => $answers) {
            // Fetch correct answers from the database for this question
            $correctAnswers = Question::where('quizz_id', $quizz_id)
                ->where('question_number', $question_number)
                ->where('type','Answer')
                ->pluck('statement')
                ->toArray();
            //dump($answers,$correctAnswers);
            // Check if submitted answers match the correct answers
            $isCorrect = !array_diff($answers, $correctAnswers) && !array_diff($correctAnswers, $answers);
            $results[$question_number] = $isCorrect ? 'Correct' : 'Incorrect';
        }
        //dump($results);
        $cnt_correct=0;$total=0;
        foreach($results as $item){
            if($item === 'Correct') $cnt_correct+=1;
            $total+=1;
        }
        $message ='';
        $res = $cnt_correct/$total *100;
        if($cnt_correct*10>=$total*8){
            
            $message = 'your score is '.$res.'%,above 80%,you passed this chapter!';
            DB::table('progresses')
        ->where('student_id', $student_id)
        ->where('chapter_id', $chapter_id)
        ->update(['status' => 'Complete']);
        }
        else $message = 'your score is'. $res .'%,below 80%,try again!';
        return redirect()->back()->with('quizResult', $message);
    }
    
}
