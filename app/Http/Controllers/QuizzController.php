<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Quizz;
use App\Models\Question;
use App\Models\Progress;
use App\Models\Course;
use App\Models\Chapter;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\CourseController;
class QuizzController extends Controller{
    public function quizzIndex($id){

        if($this->lockPage($id)==false){
            return redirect()->back()->with('failed','This page is locked');
        }

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
    public static function createQuizz($chapter_id){
        $quizz = Quizz::create([
            'chapter_id'=>$chapter_id,
            'content'=>'created to prevent empty'
        ]);
        Question::create([
            'quizz_id'=>$quizz->id,
            'question_number'=>'1',
            'statement'=>'Sample Statement',
            'type'=>'Question',
        ]);
        Question::create([
            'quizz_id'=>$quizz->id,
            'question_number'=>'1',
            'statement'=>'Sample Option',
            'type'=>'Option',
        ]);
        Question::create([
            'quizz_id'=>$quizz->id,
            'question_number'=>'1',
            'statement'=>'Answer',
            'type'=>'Answer',
        ]);

    }
    public function quizzValidate(Request $request){
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
        $cnt_correct=0;$total=0;
        foreach($results as $item){
            if($item === 'Correct') $cnt_correct+=1;
            $total+=1;
        }
        $message ='';
        $res = $cnt_correct/$total *100;
        if($cnt_correct*10>=$total*8){
            $message = 'your score is '.$res. ' %,above 80%,you passed this chapter!';
            $progress=Progress::where('student_id', $student_id)->where('chapter_id', $chapter_id)->first();
            if($progress->status == 'Uncomplete') {
                $progress->status='Complete';
                $student=Student::find($student_id);
                $student->points+=10;
                $student->save();
            }
            $progress->save();
        }
        else $message = 'your score is'. $res.' %,below 80%,try again!';
        return redirect()->back()->with('quizResult', $message);
    }
    
    public function lockPage($id){
        $userId = Auth::id(); // Get the ID of the currently authenticated user
        $lockKey = 'page_lock_' . $id; // Unique lock key for each page using the page's unique ID

        // Attempt to acquire the lock
        $lock = Cache::lock($lockKey, 1800); // Lock for 30 minutes (1800 seconds)

        // Check if the page is locked by the current user
        if (Cache::has('page_lock_user_' . $id)) {
            $lockedUser = Cache::get('page_lock_user_' . $id);
            if ($lockedUser == $userId) {
                // If the lock is held by the current user, allow access
                return true;
            } else {
                // If the lock is held by another user, deny access
                return false;
            }
        }

        // If the lock is available, acquire it and store the user ID
        if ($lock->get()) {
            Cache::put('page_lock_user_' . $id, $userId, 1800); // Store the user ID with the lock for 30 minutes
            return true;
        } else {
            return false;
        }
    }
    public function releaseLock($id)
    {
        $userId = Auth::id();
        $lockedUser = Cache::get('page_lock_user_' . $id);
        $quizz = Quizz::find($id);
        if(!$quizz) return redirect()->back()->with('failed','not found');
        $chapter_id = $quizz->chapter_id;
        $chapter = Chapter::find($chapter_id);
        if(!$chapter) return redirect()->back()->with('failed','not found');
        $course_id = $chapter->course_id;
        


        // Only allow the user who holds the lock to release it
        if ($lockedUser == $userId) {
            Cache::forget('page_lock_' . $id); // Release the lock
            Cache::forget(key: 'page_lock_user_' . $id); // Remove the user ID from the cache
            return redirect()->route('editCourse',['course_id'=>$course_id,'chapter_id'=>$chapter_id,'selectedType'=>'Material']);
        } else {
            return redirect()->back()->with('failed','no permission');
        }
        
       
    }
}
