<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\GeminiController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\StationeryController;

Route::group(['middleware' => ['auth']], function () {  
    Route::get('/', function () {  
        return view('dashboard');  
    })->name('dashboard');  
    
    Route::prefix('stationery')->group(function () {
        Route::get('', [StationeryController::class, 'index']) ->name('stationeries');
        Route::get('/create', [StationeryController::class, 'create']) ->name('stationery.create');
        Route::post('/store', [StationeryController::class, 'store']) ->name('stationery.store');
    });
    
    Route::prefix('request')->group(function () {
        Route::get('', [RequestController::class, 'index']) ->name('requests');
        Route::get('/create', [RequestController::class, 'create']) ->name('request.create');
        Route::post('/store', [RequestController::class, 'store']) ->name('request.store');
    });

    Route::get('/get-name', [RequestController::class, 'getName'])->name('getName');
    Route::get('/get-stationery', [RequestController::class, 'getStationery'])->name('getStationery');
});

Route::get('/login', [LoginController::class, 'index']) ->name('login')->middleware('guest');
Route::post('/login', [LoginController::class, 'login']) ->name('login.in');
Route::post('/logout', [LoginController::class, 'logout']) ->name('logout');
Route::get('/register', [RegisterController::class, 'index']) ->name('register')->middleware('guest');
Route::post('/register', [RegisterController::class, 'store']) ->name('register.store');
Route::post('/generate-text', [GeminiController::class, 'generateText']);