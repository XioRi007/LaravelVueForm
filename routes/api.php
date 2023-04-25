<?php

use App\Http\Controllers\MemberController;
use Illuminate\Http\Request;
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
Route::get('/', [MemberController::class, 'index'])->name('index');
Route::get('/count', [MemberController::class, 'count'])->name('count');
Route::get('/{id}', [MemberController::class, 'show'])->name('show');
Route::post('/', [MemberController::class, 'register'])->name('register');
Route::put('/{id}', [MemberController::class, 'update'])->name('update');

