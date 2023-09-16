<?php

use App\Http\Controllers\AuthorizationController;
use App\Http\Controllers\BusRouteStationController;
use App\Http\Controllers\CardTariffController;
use App\Http\Controllers\CompaniesController;
use App\Http\Controllers\CompanyServicesController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ServiceDiscountController;
use App\Http\Controllers\TestingController;
use App\Http\Controllers\UsersController;
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

/**
 * User routes
 */
Route::prefix('user')->middleware('auth:sanctum')->group(function () {
    Route::get('', [UsersController::class, 'me'])->name('users.me');

    Route::middleware('roles:admin,partner,user,employee')->group(function () {
        Route::patch('{id}', [UsersController::class, 'update'])->name('users.update');
    });
});

/**
 * Routes for companies
 */
Route::prefix('companies')->group(function () {

    Route::get('', [CompaniesController::class, 'index'])->name('companies.list');
    Route::get('{id}', [CompaniesController::class, 'single'])->name('companies.single');

    Route::middleware(['auth:sanctum','roles:admin'])->group(function () {
        Route::post('', [CompaniesController::class, 'store'])->name('companies.create');
        Route::patch('{id}', [CompaniesController::class, 'update'])->name('companies.create');
    });
});

/**
 * Company routes
 */
Route::prefix('company')->group(function () {

    /**
     * Routes for company services
     */
    Route::prefix('services')->group(function () {
        Route::get('', [CompanyServicesController::class, 'index'])->name('company.services.list');
        Route::get('{id}', [CompanyServicesController::class, 'single'])->name('company.services.single');

        Route::middleware(['auth:sanctum', 'roles:partner,admin'])->group(function () {
            Route::post('', [CompanyServicesController::class, 'store'])->name('company.services.create');
            Route::patch('{id}', [CompanyServicesController::class, 'update'])->name('company.services.update');
            Route::delete('{id}', [CompanyServicesController::class, 'delete'])->name('company.services.delete');
        });
    });
});

Route::prefix('service_discount')->name('service_discount')->group(function() {
    Route::get('', [ServiceDiscountController::class, 'index'])->name('all');
    Route::get('{id}', [ServiceDiscountController::class, 'only'])->name('only');

    Route::middleware(['auth:sanctum'])->group(function () {
        Route::post('', [ServiceDiscountController::class, 'store'])->name('create');
        Route::patch('{id}', [ServiceDiscountController::class, 'update'])->name('update');
        Route::delete('{id}', [ServiceDiscountController::class, 'delete'])->name('delete');
    });
});
