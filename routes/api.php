<?php

use App\Http\Controllers\AuthorizationController;
use App\Http\Controllers\BusRouteStationController;
use App\Http\Controllers\CardTariffController;
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

Route::prefix('card-tariff')->name('card_tariff')->group(function() {
    Route::get('', [CardTariffController::class, 'index'])->name('all');
    Route::get('{id}', [CardTariffController::class, 'only'])->name('only');

    Route::middleware(['auth:sanctum'])->group(function () {
        Route::post('', [CardTariffController::class, 'store'])->name('create');
        Route::patch('{id}', [CardTariffController::class, 'update'])->name('update');
        Route::delete('{id}', [CardTariffController::class, 'delete'])->name('delete');
    });
});

Route::prefix('bus')->group(function () {
    Route::prefix('route')->group(function () {
        Route::prefix('stations')->group(function () {
            Route::get('', [BusRouteStationController::class, 'index']);
            Route::get('{id}', [BusRouteStationController::class, 'only']);

            Route::middleware(['auth:sanctum','roles:admin'])->group(function () {
                Route::post('', [BusRouteStationController::class, 'store']);
                Route::patch('{id}', [BusRouteStationController::class, 'update']);
                Route::delete('{id}', [BusRouteStationController::class, 'delete']);
            });
        });
    });
});

Route::middleware(['auth:sanctum','roles:admin,user'])->get('/test', function () {
    return 1;
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
