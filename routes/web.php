<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClickController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/click', [ClickController::class, 'index']);
Route::post('/click/update', [ClickController::class, 'update']);
Route::post('/click/add', [ClickController::class, 'store']);
Route::post('/click/reset', [ClickController::class, 'reset']);
Route::post('/click/questionnaire', [ClickController::class, 'processQuestionnaire']);
Route::post('/click/increment', [ClickController::class, 'incrementCounter']);
Route::post('/containers/add', [ClickController::class, 'addContainer']);