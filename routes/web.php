<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('/auth', [\App\Http\Controllers\AuthController::class, 'auth'])->name('auth');
Route::get('/send', [\App\Http\Controllers\SendInfoController::class, 'showForm'])->name('show.form');
Route::post('/send', [\App\Http\Controllers\SendInfoController::class, 'sendForm'])->name('send.form');
