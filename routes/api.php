<?php

use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
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

Route::middleware(['auth:sanctum'])->get('/me', function (Request $request) {
    return $request->user();
});


Route::controller(UserController::class)
    ->prefix('users')
    ->middleware(['auth:sanctum'])
    ->group(function () {
        Route::get('/{user}', 'show');
        Route::get('/', 'index');
    });

Route::controller(ProfileController::class)
    ->prefix('profiles')
    ->middleware(['auth:sanctum'])
    ->group(function () {
        Route::get('/{profile}', 'show');
        Route::put('/{profile}', 'update');
    });

Route::controller(NotificationController::class)
    ->prefix('notifications')
    ->middleware(['auth:sanctum'])
    ->group(function () {
        Route::get('/{user}', 'index');
        Route::post('/{user}', 'store');
        Route::delete('/{notification}', 'destroy');
    });

require __DIR__.'/auth.php';
