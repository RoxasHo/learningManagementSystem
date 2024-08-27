<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ForgetPasswordController;
use App\Http\Controllers\SuperuserController;

// Register Routes
Route::get('/register-options', function () {
    return view('auth.register_options');
})->name('register.options');

Route::get('/register/student', function () {
    return view('auth.registerStudent');
})->name('register.student.page');
Route::post('/register/student', [RegisterController::class, 'registerStudent'])->name('student.register');
Route::post('/student/validate', [RegisterController::class, 'validateStudentField'])->name('student.validate');

Route::get('/register/teacher', function () {
    return view('auth.registerTeacher');
})->name('register.teacher.page');
Route::post('/register/teacher', [RegisterController::class, 'registerTeacher'])->name('teacher.register');
Route::post('/teacher/validate', [RegisterController::class, 'validateTeacherField'])->name('teacher.validate');

Route::get('/register/moderator', function () {
    return view('auth.registerModerator');
})->name('register.moderator.page');
Route::post('/register/moderator', [RegisterController::class, 'registerModerator'])->name('moderator.register');
Route::post('/moderator/validate', [RegisterController::class, 'validateModeratorField'])->name('moderator.validate');

// Superuser Middleware Group
Route::middleware(['auth', 'role:superuser'])->group(function () {
    Route::get('superuser/moderator-approval', [SuperuserController::class, 'showModeratorApprovalPage'])->name('superuser.moderatorApproval');
    Route::post('superuser/approve-moderator/{id}', [SuperuserController::class, 'approveModerator'])->name('superuser.approveModerator');
    Route::post('superuser/reject-moderator/{id}', [SuperuserController::class, 'rejectModerator'])->name('superuser.rejectModerator');
});

Route::get('approve-moderator/{token}', [SuperuserController::class, 'approveModeratorByToken'])->name('superuser.approveModeratorByToken');
Route::get('reject-moderator/{token}', [SuperuserController::class, 'rejectModeratorByToken'])->name('superuser.rejectModeratorByToken');

// Login Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Password Reset Routes
Route::get('/forget-password', [ForgetPasswordController::class, 'forgetPassword'])->name('forget.password');
Route::post('/forget-password', [ForgetPasswordController::class, 'forgetPasswordPost'])->name('forget.password.post');
Route::get('/reset-password/{token}', [ForgetPasswordController::class, 'resetPassword'])->name('reset.password');
Route::post('/reset-password', [ForgetPasswordController::class, 'resetPasswordPost'])->name('reset.password.post');

// Profile Routes
Route::middleware('auth')->group(function () {
    Route::middleware('role:Student')->group(function () {
        Route::get('/profile/student/{email}', [ProfileController::class, 'showStudentProfile'])->name('profile.student');
        Route::post('/profile/update-student-picture', [ProfileController::class, 'updateStudentPicture'])->name('profile.updateStudentPicture');
        Route::get('/profile/student/{email}/edit', [ProfileController::class, 'editStudent'])->name('profile.editStudent');
        Route::put('/profile/student/{email}', [ProfileController::class, 'updateStudent'])->name('profile.updateStudent');
    });

    Route::middleware('role:Teacher')->group(function () {
        Route::get('/profile/teacher/{email}', [ProfileController::class, 'showTeacherProfile'])->name('profile.teacher');
        Route::post('/profile/teacher/update-picture', [ProfileController::class, 'updateTeacherPicture'])->name('profile.updateTeacherPicture');
        Route::post('/profile/teacher/update-certification', [ProfileController::class, 'updateTeacherCertification'])->name('profile.updateTeacherCertification');
        Route::post('/profile/teacher/update-identity-proof', [ProfileController::class, 'updateTeacherIdentityProof'])->name('profile.updateTeacherIdentityProof');
        Route::get('/profile/teacher/edit/{email}', [ProfileController::class, 'editTeacher'])->name('profile.editTeacher');
        Route::post('/profile/teacher/update/{email}', [ProfileController::class, 'updateTeacher'])->name('profile.updateTeacher');
    });

    Route::middleware('role:Moderator')->group(function () {
        Route::get('/profile/moderator/{email}', [ProfileController::class, 'showModeratorProfile'])->name('profile.moderator');
        Route::post('/profile/update-moderator-picture', [ProfileController::class, 'updateModeratorPicture'])->name('profile.updateModeratorPicture');
        Route::get('profile/moderator/edit/{email}', [ProfileController::class, 'editModerator'])->name('profile.editModerator');
        Route::post('profile/moderator/update/{email}', [ProfileController::class, 'updateModerator'])->name('profile.updateModerator');
    });

    Route::get('/collect-point', [LoginController::class, 'collectPoint'])->name('profile.collectPoint');
});
