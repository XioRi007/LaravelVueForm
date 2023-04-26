<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MemberController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/admin', [AdminController::class, 'index']);
    Route::post('/logout', [AuthController::class, 'logout']);
});
Route::get('/', [MemberController::class, 'index']);
Route::get('/count', [MemberController::class, 'count']);
Route::get('/{id}', [MemberController::class, 'show']);
Route::put('/{id}', [MemberController::class, 'update']);
Route::post('/', [MemberController::class, 'register']);

