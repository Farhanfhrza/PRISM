<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('dashboard');
});

Route::get('/login', [LoginController::class, 'index']) ->name('login')->middleware('guest');
Route::post('/login', [LoginController::class, 'login']) ->name('login.in');
Route::post('/logout', [LoginController::class, 'logout']) ->name('logout');
Route::get('/register', [RegisterController::class, 'index']) ->name('register')->middleware('guest');
Route::post('/register', [RegisterController::class, 'store']) ->name('register.store');
