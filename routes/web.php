<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\ChapterController;
use App\Http\Controllers\QuizzController;
use App\Http\Controllers\QuestionController;
Route::get('/', function () {
    return view('home-page');
});



//Course related path
Route::get('teacher-course/{id}', [CourseController::class, 'teacherIndex']); 
Route::get('student-course/{id}', [CourseController::class, 'studentIndex']); 
Route::get('view-course/{id}', [CourseController::class, 'viewCourse']); 
Route::post('add-course', [CourseController::class, 'addCourse'])->name('add-course'); 
Route::get('edit-course/{course_id}/{chapter_id}/{selectedType}', [CourseController::class, 'courseIndex']); 
Route::get('course-teacher-team/{id}',[CourseController::class,'courseTeamIndex']);
Route::get('change-rhs-content/{id}/{type}',[CourseController::class,'changeContent'] );
Route::post('enroll-course',[CourseController::class,'enrollCourse'])->name('enroll-course');
Route::get('course-study/{student_id}/{course_id}/{chapter_id}/{selectedType}',[CourseController::class, 'courseStudy'])->name('course-study');;


//Chapter related path
Route::get('chapter/{id}',[ChapterController::class,'chapterIndex']);
Route::post('add-chapter',[ChapterController::class,'addChapter']);
Route::delete('delete-chapter/{id}',[ChapterController::class,'deleteChapter']);
Route::put('edit-chapter',[ChapterController::class,'editChapter']);




//Material related path
Route::post('update-material',[MaterialController::class, 'updateMaterial']);
Route::get('edit-material/{id}',[MaterialController::class, 'editMaterial'])->name('edit-material');
Route::get('get-material-by-chapter/{id}',[MaterialController::class, 'getMaterialWithChapter']);
Route::post('save-material',[MaterialController::class,'saveMaterial'])->name('save-material');


//Quizz related path
Route::get('edit-quizz/{id}',[QuizzController::class,'quizzIndex']);
/*
Route::post('add-option-form',[QuizzController::class,'quizzIndex']);
Route::post('add-answer-form',[QuizzController::class,'quizzIndex']);
*/

//Question related path
Route::post('add-question',[QuestionController::class,'createQuestion'])->name('add-question');
Route::post('update-question',[QuestionController::class,'updateQuestion'])->name('update-question');
Route::post('add-option',[QuestionController::class,'addOption'])->name('add-option');
Route::post('add-answer', [QuestionController::class, 'addAnswer'])->name('add-answer');
Route::delete('delete-question',[QuestionController::class, 'deleteQuestion']);
// Routes for handling quiz-related AJAX requests
/*
Route::post('/add-question', [QuizzController::class, 'addQuestion'])->name('add-question');
*/
/*
Route::post('/add-option', [QuizzController::class, 'addOption'])->name('add-option');
Route::post('/add-answer', [QuizzController::class, 'addAnswer'])->name('add-answer');
Route::post('/update-item', [QuizzController::class, 'updateItem'])->name('update-item');
Route::post('/delete-item', [QuizzController::class, 'deleteItem'])->name('delete-item');
*/