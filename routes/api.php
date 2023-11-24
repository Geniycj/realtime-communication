<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
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
})->name('user.me');


Route::controller(UserController::class)
    ->prefix('users')
    ->middleware(['auth:sanctum'])
    ->group(function () {
        Route::get('/{user}', 'show')->name('users.show');
        Route::get('/', 'index')->name('users.index');
    });

Route::controller(ProfileController::class)
    ->prefix('profiles')
    ->middleware(['auth:sanctum'])
    ->group(function () {
        Route::get('/{profile}', 'show')->name('profile.show');
        Route::put('/{profile}', 'update')->name('profile.update.api');
    });

Route::controller(NotificationController::class)
    ->prefix('notifications')
    ->middleware(['auth:sanctum'])
    ->group(function () {
        Route::get('/{user}', 'index')->name('notifications.index');
        Route::post('/{user}', 'store')->name('notifications.store');
        Route::post('/delete/{notification}', 'destroy')->name('notifications.destroy');
    });



Route::post('login', [AuthenticatedSessionController::class, 'store'])
    ->name('login.api');

Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
    ->name('logout');
