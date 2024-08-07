<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\ChapterController;
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::get('course/{id}', [CourseController::class, 'teacherIndex']); 
Route::post('add-course', [CourseController::class, 'addCourse']); 
Route::get('chapter/{id}',[ChapterController::class,'chapterIndex']);
Route::post('edit-material',[MaterialController::class, 'saveMaterial']);