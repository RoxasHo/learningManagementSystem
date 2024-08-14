<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\ChapterController;
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

//Course related path
Route::get('course/{id}', [CourseController::class, 'teacherIndex']); 
Route::post('add-course', [CourseController::class, 'addCourse']); 

//Chapter related path
Route::get('chapter/{id}',[ChapterController::class,'chapterIndex']);
Route::post('add-chapter',[ChapterController::class,'addChapter']);
Route::delete('delete-chapter/{id}',[ChapterController::class,'deleteChapter']);
Route::put('edit-chapter',[ChapterController::class,'editChapter']);

//Material related path
Route::post('edit-material',[MaterialController::class, 'saveMaterial']);
Route::get('get-material/{id}',[MaterialController::class, 'getMaterial']);
Route::get('get-material-by-chapter/{id}',[MaterialController::class, 'getMaterialWithChapter']);