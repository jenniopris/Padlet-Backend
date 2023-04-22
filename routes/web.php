<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

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
    $padlets = DB::table('padlets')->get();
    $users = DB::table('users')->get();
    $entries = DB::table('entries')->get();
    $comments = DB::table('comments')->get();
    $ratings = DB::table('ratings')->get();

    return view('welcome', compact('padlets', 'users', 'entries', 'comments', 'ratings'));
});
