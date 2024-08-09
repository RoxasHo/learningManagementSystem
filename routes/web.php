<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ForgetPasswordController;

use Illuminate\Support\Facades\Auth;

Route::get('/register-options', function () {
    return view('auth.register_options');
})->name('register.options');


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
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');


Route::get('/forget-password', [ForgetPasswordController::class, 'forgetPassword'])->name('forget.password');
Route::post('/forget-password', [ForgetPasswordController::class, 'forgetPasswordPost'])->name('forget.password.post');
Route::get('/reset-password/{token}', [ForgetPasswordController::class, 'resetPassword'])->name('reset.password');
Route::post('/reset-password', [ForgetPasswordController::class, 'resetPasswordPost'])->name('reset.password.post');

// Profile routes with authentication middleware
Route::middleware('auth')->group(function () {
// routes/web.php
Route::get('/profile/student/{email}', [ProfileController::class, 'showStudentProfile'])->name('profile.student');
Route::post('/profile/update-student-picture', [ProfileController::class, 'updateStudentPicture'])->name('profile.updateStudentPicture');
Route::get('/profile/student/{email}/edit', [ProfileController::class, 'editStudent'])->name('profile.editStudent');
Route::put('/profile/student/{email}', [ProfileController::class, 'updateStudent'])->name('profile.updateStudent');


Route::get('/profile/teacher/{email}', [ProfileController::class, 'showTeacherProfile'])->name('profile.teacher');
Route::post('/profile/teacher/update-picture', [ProfileController::class, 'updateTeacherPicture'])->name('profile.updateTeacherPicture');
Route::post('/profile/teacher/update-certification', [ProfileController::class, 'updateTeacherCertification'])->name('profile.updateTeacherCertification');
Route::post('/profile/teacher/update-identity-proof', [ProfileController::class, 'updateTeacherIdentityProof'])->name('profile.updateTeacherIdentityProof');
Route::get('/profile/teacher/edit/{email}', [ProfileController::class, 'editTeacher'])->name('profile.editTeacher');
Route::post('/profile/teacher/update/{email}', [ProfileController::class, 'updateTeacher'])->name('profile.updateTeacher');

Route::get('/collect-point', [LoginController::class, 'collectPoint'])->name('profile.collectPoint');


Route::get('/profile/moderator/{email}', [ProfileController::class, 'showModeratorProfile'])->name('profile.moderator');
Route::post('/profile/update-moderator-picture', [ProfileController::class, 'updateModeratorPicture'])->name('profile.updateModeratorPicture');});
Route::get('profile/moderator/edit/{email}', [ProfileController::class, 'editModerator'])->name('profile.editModerator');
Route::post('profile/moderator/update/{email}', [ProfileController::class, 'updateModerator'])->name('profile.updateModerator');



