<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ForgetPasswordController;
use App\Http\Controllers\SuperuserController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\ChapterController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\QuestionnaireController;
use App\Http\Controllers\QuizzController;
use App\Http\Controllers\CreateController;
use App\Http\Controllers\VoteController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\CourseTeamController;
use App\Mail\UserRejectionEmail;
use App\Mail\UserStatusUpdated;



Route::get('/courses', [CourseController::class, 'showCourseList'])->name('courses.index');

Route::get('/courses/{id}', [CourseController::class, 'show'])->name('courses.show');
// Route for enrolling in a course
Route::post('/courses/enroll/{courseId}', [CourseController::class, 'enroll'])->name('courses.enroll');

// Dashboard Route
Route::get('/superuser/dashboard', [SuperuserController::class, 'dashboard'])->name('superuser.dashboard');

// Students Routes
Route::get('/superuser/students', [SuperuserController::class, 'showStudents'])->name('superuser.students');
Route::get('/superuser/students/rejected', [SuperuserController::class, 'showRejectedStudents'])->name('superuser.students.rejected');
Route::get('/superuser/students/active', [SuperuserController::class, 'showActiveStudents'])->name('superuser.students.active');
Route::get('/superuser/students/info/{id}', [SuperuserController::class, 'getStudentInfo'])->name('superuser.students.info');

// Teachers Routes
Route::get('/superuser/teachers/pending', [SuperuserController::class, 'showPendingTeachers'])->name('superuser.teachers.pending');
Route::get('/superuser/teachers/rejected', [SuperuserController::class, 'showRejectedTeachers'])->name('superuser.teachers.rejected');
Route::get('/superuser/teachers/active', [SuperuserController::class, 'showActiveTeachers'])->name('superuser.teachers.active');
Route::get('/superuser/teachers/info/{id}', [SuperuserController::class, 'getTeacherInfo']);

// Moderators Routes
Route::get('/superuser/moderators/pending', [SuperuserController::class, 'showPendingModerators'])->name('superuser.moderators.pending');
Route::get('/superuser/moderators/rejected', [SuperuserController::class, 'showRejectedModerators'])->name('superuser.moderators.rejected');
Route::get('/superuser/moderators/active', [SuperuserController::class, 'showActiveModerators'])->name('superuser.moderators.active');
Route::get('/superuser/moderators/info/{id}', [SuperuserController::class, 'getModeratorInfo']);

// Approve or Reject Users
Route::post('/superuser/update_user_status', [SuperuserController::class, 'updateUserStatus'])->name('update_user_status');
Route::post('/superuser/reject_user/{id}', [SuperuserController::class, 'rejectUser'])->name('reject_user');

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
        Route::put('profile/student/update/{email}', [ProfileController::class, 'updateStudent'])->name('profile.updateStudent');
    });

    Route::middleware('role:Teacher')->group(function () {
        Route::get('/profile/teacher/{email}', [ProfileController::class, 'showTeacherProfile'])->name('profile.teacher');
        Route::post('/profile/teacher/update-teacher-picture', [ProfileController::class, 'updateTeacherPicture'])->name('profile.updateTeacherPicture');
        Route::post('/profile/teacher/update-certification', [ProfileController::class, 'updateTeacherCertification'])->name('profile.updateTeacherCertification');
        Route::post('/profile/teacher/update-identity-proof', [ProfileController::class, 'updateTeacherIdentityProof'])->name('profile.updateTeacherIdentityProof');
        Route::get('/profile/teacher/edit/{email}', [ProfileController::class, 'editTeacher'])->name('profile.editTeacher');
        Route::post('/profile/teacher/update/{email}', [ProfileController::class, 'updateTeacher'])->name('profile.updateTeacher');
    });

    Route::middleware('role:Moderator')->group(function () {
        // Display the moderator's profile using email
        Route::get('/profile/moderator/{email}', [ProfileController::class, 'showModeratorProfile'])->name('profile.moderator');
    
        // Update moderator profile picture
        Route::post('/profile/moderator/picture/update', [ProfileController::class, 'updateModeratorPicture'])->name('profile.updateModeratorPicture');
    
        // Edit moderator profile
        Route::get('/profile/moderator/edit/{email}', [ProfileController::class, 'editModerator'])->name('profile.editModerator');
        Route::post('/profile/moderator/update/{email}', [ProfileController::class, 'updateModerator'])->name('profile.updateModerator');
    
        // Course approval/rejection routes
        Route::post('/courses/{id}/approve', [CourseController::class, 'approve'])->name('courses.approve');
        Route::post('/courses/{id}/reject', [CourseController::class, 'reject'])->name('courses.reject');
    
        // Student-related routes
        Route::get('/profile/moderator/students/{email}', [ProfileController::class, 'showStudents'])->name('profile.students');
        Route::get('/profile/moderator/students/rejected/{email}', [ProfileController::class, 'showRejectedStudents'])->name('profile.students.rejected');
        Route::get('/profile/moderator/students/active/{email}', [ProfileController::class, 'showActiveStudents'])->name('profile.students.active');
        Route::get('/profile/moderator/students/info/{id}', [ProfileController::class, 'getStudentInfo'])->name('profile.students.info');
        
        // Teacher-related routes
        Route::get('/profile/moderator/teachers/{email}', [ProfileController::class, 'showTeachers'])->name('profile.teachers');
        Route::get('/profile/moderator/teachers/rejected/{email}', [ProfileController::class, 'showRejectedTeachers'])->name('profile.teachers.rejected');
        Route::get('/profile/moderator/teachers/active/{email}', [ProfileController::class, 'showActiveTeachers'])->name('profile.teachers.active');
        Route::get('/profile/moderator/teachers/info/{id}', [ProfileController::class, 'getTeacherInfo'])->name('profile.teachers.info');
    
        // User status updates and rejection
        Route::post('/profile/update_user_status', [ProfileController::class, 'updateUserStatus'])->name('profile.update_user_status');
        Route::post('/profile/reject_user', [ProfileController::class, 'rejectUser'])->name('profile.reject_user');
    });
                
    
    Route::get('/collect-point', [LoginController::class, 'collectPoint'])->name('profile.collectPoint');
});

Route::get('/profile/students/info/{id}', [ProfileController::class, 'getStudentInfo']);
    


//Course related path
Route::get('teacher-course/{id}', [CourseController::class, 'teacherIndex']); 
Route::get('student-course/{id}', [CourseController::class, 'studentIndex']); 
Route::get('show-content/{course_id}/{chapter_id}/{selectedType}', [CourseController::class, 'showContent'])->name('showContent');
Route::post('add-course', [CourseController::class, 'addCourse'])->name('add-course'); 
Route::get('edit-course/{course_id}/{chapter_id}/{selectedType}', [CourseController::class, 'courseIndex']); 
Route::get('course-teacher-team/{id}',[CourseController::class,'courseTeamIndex']);
Route::get('change-rhs-content/{id}/{type}',[CourseController::class,'changeContent'] );
Route::post('enroll-course',[CourseController::class,'enrollCourse'])->name('enroll-course');
Route::get('course-study/{student_id}/{course_id}/{chapter_id}/{selectedType}',[CourseController::class, 'courseStudy'])->name('course-study');;
Route::post('/enrollments/rate', [CourseController::class, 'rateCourse'])->name('rate.course');


//Course team related path
Route::get('view-course-team/{id}', [CourseTeamController::class, 'viewCourseTeam']);
Route::post('add-teacher', [CourseTeamController::class, 'addTeacher']);
Route::post('remove-teacher', [CourseTeamController::class, 'removeTeacher']);


//Chapter related path
Route::get('chapter/{id}',[ChapterController::class,'chapterIndex']);
Route::post('add-chapter',[ChapterController::class,'addChapter']);
Route::delete('delete-chapter/{id}',[ChapterController::class,'deleteChapter']);
Route::put('edit-chapter',[ChapterController::class,'editChapter']);
Route::post('unlock-chapter',[ChapterController::class,'unlockChapter']);



//Material related path
Route::post('update-material',[MaterialController::class, 'updateMaterial']);
Route::get('edit-material/{id}',[MaterialController::class, 'editMaterial'])->name('edit-material');
Route::get('get-material-by-chapter/{id}',[MaterialController::class, 'getMaterialWithChapter']);
Route::post('save-material',[MaterialController::class,'saveMaterial'])->name('save-material');


//Quizz related path
Route::get('edit-quizz/{id}',[QuizzController::class,'quizzIndex'])->name('quizzIndex');
Route::post('submit-quizz',[QuizzController::class,'quizzValidate'])->name('submit-quizz');
Route::get('done-quizz-edit/{id}',[QuizzController::class,'releaseLock']);


//Question related path
Route::post('add-question',[QuestionController::class,'createQuestion'])->name('add-question');
Route::post('update-question',[QuestionController::class,'updateQuestion'])->name('update-question');
Route::post('add-option',[QuestionController::class,'addOption'])->name('add-option');
Route::post('add-answer', [QuestionController::class, 'addAnswer'])->name('add-answer');
Route::delete('delete-question/{id}',[QuestionController::class, 'deleteQuestion']);
Route::delete('delete-option/{id}',[QuestionController::class, 'deleteOption']);


//Forum routes
Route::post('/upload', [CreateController::class, 'upload'])->name('ckeditor.upload');

Route::get('/create', [CreateController::class, 'displayCreate'])->name('create.page');

Route::post('/create', [CreateController::class, 'create'])->middleware('auth');

Route::get('/show', [CreateController::class, 'show'])->name('show.main');

Route::get('/showtags', [CreateController::class, 'showtags'])->name('showtags');

Route::get('/showByTags', [CreateController::class, 'showByTag'])->name('posts.byTag');

Route::get('/showPost/{post_id}', [CreateController::class, 'showPost'])->name('post.show');

Route::post('/showPost/{post_id}', [CreateController::class, 'storeComment'])->name('comment.store');

Route::post('/showPost/{post_id}/{parent_comment_id}', [CreateController::class, 'replyToComment'])->name('comment.reply');

Route::post('/showPost/{post_id}/vote/{type}', [VoteController::class, 'votePost']);

Route::post('/showPost/{comment_id}/vote/{type}', [VoteController::class, 'voteComment']);

Route::post('/showPost/{post_id}/vote/{type}/remove', [VoteController::class, 'removeVotePost']);

Route::post('/showComment/{comment_id}/vote/{type}/remove', [VoteController::class, 'removeVoteComment']);

Route::post('/report', [ReportController::class, 'store'])->name('report.store');

Route::get('/showtags/search', [CreateController::class, 'searchTags'])->name('tags.search');

Route::get('/show/search', [CreateController::class, 'searchPosts'])->name('posts.search');

Route::post('/tags/follow', [CreateController::class, 'follow'])->name('tags.follow');

Route::post('/tags/unfollow', [CreateController::class, 'unfollow'])->name('tags.unfollow');

Route::get('/posts', [CreateController::class, 'index'])->name('posts.index');

Route::get('/show/following', [CreateController::class, 'index'])->name('community.index');

// For voting
Route::post('/post/like', [VoteController::class, 'like'])->name('post.like');
Route::post('/post/dislike', [VoteController::class, 'dislike'])->name('post.dislike');

// For fetching vote counts
Route::get('/votes/counts/{post_id}', [VoteController::class, 'getVoteCounts']);

Route::post('/comment/like', [VoteController::class, 'likeComment'])->name('comment.like');

Route::post('/comment/dislike', [VoteController::class, 'dislikeComment'])->name('comment.dislike');

Route::get('/my-posts', [CreateController::class, 'viewMyPosts'])->name('view.mypost');

// web.php
Route::delete('/post/{post}', [CreateController::class, 'destroy'])->name('post.destroy');

Route::delete('/comments/{id}', [CreateController::class, 'destroyComment'])->name('comment.destroy');

Route::delete('/replies/{id}', [CreateController::class, 'destroyReply'])->name('reply.destroy');



// Route::get('/questionnaire', [QuestionnaireController::class, 'showQuestionnaire'])
//     ->middleware('auth')
//     ->name('questionnaire.show');

// Route::post('/questionnaire', [QuestionnaireController::class, 'storeResponses'])
//     ->middleware('auth')
//     ->name('questionnaire.store');

Route::middleware(['auth', 'questionnaire.completed'])->group(function () {
    
});

Route::get('/',[CourseController::class,'showHomePage']);
Route::get('/questionnaire', [QuestionnaireController::class, 'showQuestionnaire'])->name('questionnaire.show');
Route::post('/questionnaire', [QuestionnaireController::class, 'storeResponses'])->name('questionnaire.store');
    





