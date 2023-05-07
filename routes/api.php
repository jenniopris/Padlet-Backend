<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PadletController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EntryController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\CommentController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('padlets', [PadletController::class, 'index']);
Route::get('padlets/{id}', [PadletController::class, 'findByPadletID']);
Route::get('padlets/findByUserId/{user_id}', [PadletController::class, 'findByUserID']);
Route::post('padlets', [PadletController::class, 'save']);
Route::put('padlets/{id}', [PadletController::class, 'update']);
Route::delete('padlets/{id}', [PadletController::class, 'delete']);

Route::get('users', [UserController::class, 'index']);
Route::get('users/{id}', [UserController::class, 'findByUserID']);
Route::get('users/search/{searchTerm}', [UserController::class, 'findBySearchTerm']);
Route::post('users', [UserController::class, 'save']);
Route::put('users/{id}', [UserController::class, 'update']);
Route::delete('users/{id}', [UserController::class, 'delete']);

Route::get('entries', [EntryController::class, 'index']);
Route::get('entries/{id}', [EntryController::class, 'findByID']);
Route::get('entries/search/{searchTerm}', [EntryController::class, 'findBySearchTerm']);
Route::get('entries/padlet/{padlet_id}', [EntryController::class, 'getEntriesByPadletID']);
Route::post('entries', [EntryController::class, 'save']);
Route::put('entries/{id}', [EntryController::class, 'update']);
Route::delete('entries/{id}', [EntryController::class, 'delete']);

Route::get('ratings', [RatingController::class, 'index']);
Route::get('ratings/{id}', [RatingController::class, 'findByID']);
Route::get('ratings/entry/{entry_id}', [RatingController::class, 'getRatingsByEntryID']);
Route::post('ratings', [RatingController::class, 'save']);
Route::put('ratings/{id}', [RatingController::class, 'update']);
Route::delete('ratings/{id}', [RatingController::class, 'delete']);

Route::get('comments', [CommentController::class, 'index']);
Route::get('comments/{id}', [CommentController::class, 'findByID']);
Route::get('comments/entry/{entry_id}', [CommentController::class, 'getCommentByEntryID']);
Route::post('comments', [CommentController::class, 'save']);
Route::put('comments/{id}', [CommentController::class, 'update']);
Route::delete('comments/{id}', [CommentController::class, 'delete']);
