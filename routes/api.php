<?php

use App\Http\Controllers\AuthorizationController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\TestingController;
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

Route::get('test', [TestingController::class, 'test'])->name('api.test');

/**
 * Auth routes
 */
Route::prefix('auth')->group(function() {

    Route::post('login', [RegisterController::class, 'login'])->name('auth.login');
    Route::post('register', [RegisterController::class, 'register'])->name('auth.register');

    Route::middleware('auth:sanctum')->group(function() {
        Route::post('tokens/create', [AuthorizationController::class, 'createPersonalToken'])->name('auth.token.create');
    });


});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
