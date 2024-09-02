<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CreateController;
use App\Http\Controllers\VoteController;
use App\Http\Controllers\ReportController;

Route::get('/', function () {
    return view('/layouts/index');
});

Route::post('/upload', [CreateController::class, 'upload'])->name('ckeditor.upload');

Route::get('/create', [CreateController::class, 'displayCreate'])->name('create.page');

Route::post('/create', [CreateController::class, 'create']);

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


