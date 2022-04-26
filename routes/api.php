<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
Route::post('/user/get_token',[\App\Http\Controllers\UserController::class,'get_token']);
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
//Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login']);

Route::middleware('auth:sanctum')->post('/expense/add_expense', [\App\Http\Controllers\ExpenseController::class,'add_expense']);
Route::middleware('auth:sanctum')->get('/expense/get_expenses', [\App\Http\Controllers\ExpenseController::class,'get_expenses']);
