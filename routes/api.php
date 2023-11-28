<?php

use App\Http\Controllers\Api\v1\AuthController;
use App\Http\Controllers\Api\v1\Tours\ListToursController;
use App\Http\Controllers\Api\v1\Travels\ListTravelsController;
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

Route::post('/login', AuthController::class);


Route::prefix('public')
    ->group(function () {
        Route::get('/travels', ListTravelsController::class);
        Route::get('/travels/{travel}/tours', [ListToursController::class, 'byTravelSlug']);
    });
