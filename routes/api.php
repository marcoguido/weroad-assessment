<?php

use App\Http\Controllers\Api\v1\AuthController;
use App\Http\Controllers\Api\v1\Tours\CreateTourController;
use App\Http\Controllers\Api\v1\Tours\ListToursController;
use App\Http\Controllers\Api\v1\Tours\UpdateTourController;
use App\Http\Controllers\Api\v1\Travels\CreateTravelController;
use App\Http\Controllers\Api\v1\Travels\ListTravelsController;
use App\Http\Controllers\Api\v1\Travels\UpdateTravelController;
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

Route::post('/login', AuthController::class)->name('login');

Route::middleware('auth:sanctum')
    ->prefix('admin')
    ->group(function () {
        Route::prefix('/travels')->group(function () {
            Route::name('api.admin.travels.index')
                ->get('/', [ListTravelsController::class, 'asAdministrator']);

            Route::name('api.admin.travels.create')
                ->post('/', CreateTravelController::class);

            Route::name('api.admin.travels.update')
                ->patch('/{travelId}', UpdateTravelController::class);

            Route::name('api.admin.travel-tours.index')
                ->get('/{travelId}/tours', [ListToursController::class, 'byTravelId']);
        });

        Route::prefix('/tours')->group(function () {
            Route::name('api.admin.travel-tours.create')
                ->post('/', CreateTourController::class);

            Route::name('api.admin.travel-tours.update')
                ->patch('/{tourId}', UpdateTourController::class);
        });
    });

Route::prefix('public')
    ->group(function () {
        Route::name('api.public.travels.index')
            ->get('/travels', [ListTravelsController::class, 'publiclyAvailable']);

        Route::name('api.public.travel-tours.index')
            ->get('/travels/{travel:slug}/tours', [ListToursController::class, 'byTravelSlug']);
    });
