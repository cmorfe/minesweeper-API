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

/**
 * Auth
 */
Route::post('register', [App\Http\Controllers\AuthController::class, 'register']);
Route::post('login', [App\Http\Controllers\AuthController::class, 'login']);
Route::post('logout', [App\Http\Controllers\AuthController::class, 'logout']);

/**
 * Boards
 */
Route::resource('boards', App\Http\Controllers\API\BoardAPIController::class)
    ->only('index', 'store', 'show', 'update');

/**
 * Squares
 */
Route::prefix('boards/{board}/squares/{square}')->group(function () {
    Route::post('open', [App\Http\Controllers\API\SquareAPIController::class, 'open']);
    Route::post('mark', [App\Http\Controllers\API\SquareAPIController::class, 'mark']);
});
