<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\AuthorityController;
use App\Http\Controllers\Master\ConditionController;
use App\Http\Controllers\Master\InstrumentController;
use App\Http\Controllers\Master\InstrumentStockController;
use App\Http\Controllers\Master\MenuController;
use App\Http\Controllers\Master\RoomController;
use App\Http\Controllers\Master\UserController;
use Illuminate\Support\Facades\Route;

// Publik
Route::prefix('auth')->controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
});

// Butuh token
Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('auth')->controller(AuthController::class)->group(function () {
        Route::post('register',         'register');
        Route::post('logout',           'logout');
        Route::get('me',                'me');
        Route::put('update',            'update');
        Route::put('profile',           'updateProfile');
        Route::put('change-password',   'changePassword');
    });

    Route::prefix('master')->group(function () {
        Route::apiResource('authorities',      AuthorityController::class);
        Route::apiResource('menus',            MenuController::class);
        Route::apiResource('users',            UserController::class);
        Route::apiResource('conditions',       ConditionController::class);
        Route::apiResource('instruments',      InstrumentController::class);
        Route::apiResource('instrument-stocks', InstrumentStockController::class);
        Route::apiResource('rooms',            RoomController::class);
    });
});
