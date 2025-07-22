<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\PetController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\GeminiController;
use App\Http\Controllers\API\GalleryController;
use App\Http\Controllers\API\RequestController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\EmployeeController;
use App\Http\Controllers\API\StationeryController;
use App\Http\Controllers\API\InsertStockController;
use App\Http\Controllers\StationeryController as ControllersStationeryController;

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


Route::middleware('auth:sanctum')->group(function () {
    //get Profile
    Route::apiResource('users', UserController::class);
    Route::post('logout', [UserController::class, 'logout']);
    Route::post('request', [RequestController::class, 'store']);
    Route::get('employees/by-division', [EmployeeController::class, 'getEmployeesByDivision']);
    Route::get('stationery/by-division', [StationeryController::class, 'getStationeryByDivision']);
    
    Route::get('/stationery', [StationeryController::class, 'index']);

    Route::get('/requests', [RequestController::class, 'index']);
    Route::get('/requests/{id}', [RequestController::class, 'show']);
    Route::delete('/requests/{id}', [RequestController::class, 'destroy']);
    Route::post('/requests/{id}/approve', [RequestController::class, 'approve']);
    Route::post('/requests/{id}/reject', [RequestController::class, 'reject']);
    // Route::apiResource('gallery', GalleryController::class);

    Route::get('/insert-stock', [InsertStockController::class, 'index']);
    Route::post('/insert-stock', [InsertStockController::class, 'store']);
    Route::delete('/insert-stock/{id}', [InsertStockController::class, 'destroy']);

    // Route tambahan untuk barcode scanner

});

Route::post('users', [UserController::class, 'store']);
Route::post('users/login', [UserController::class, 'login']);
Route::post('/generate-text', [GeminiController::class, 'generateText']);
Route::get('/gemini-models', [GeminiController::class, 'listAvailableModels']);
