<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;

// Display registration options
Route::view('/register', 'auth.register_options');

// Routes for registration pages
Route::get('/register/student', function () {
    return view('auth.registerStudent');
})->name('register.student.page');

Route::get('/register/teacher', function () {
    return view('auth.registerTeacher');
})->name('register.teacher.page');

Route::get('/register/moderator', function () {
    return view('auth.registerModerator');
})->name('register.moderator.page');

// Define named routes for form submissions
Route::post('/register/student', [RegisterController::class, 'registerStudent'])->name('student.register');
Route::post('/register/teacher', [RegisterController::class, 'registerTeacher'])->name('teacher.register');
Route::post('/register/moderator', [RegisterController::class, 'registerModerator'])->name('moderator.register');

// Test upload route
Route::post('test-upload', [TestController::class, 'uploadTest'])->name('test.upload');

// Login routes
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

// Profile routes with authentication middleware
Route::middleware('auth')->group(function () {
    Route::get('/profile/student/{email}', [ProfileController::class, 'showStudentProfile'])->name('profile.student');
    Route::post('/profile/update-photo', [ProfileController::class, 'updateProfilePhoto'])->name('profile.updatePhoto');

    Route::get('/profile/teacher/{email}', [ProfileController::class, 'showTeacherProfile'])->name('profile.teacher');
    Route::get('/profile/moderator/{email}', [ProfileController::class, 'showModeratorProfile'])->name('profile.moderator');
});
