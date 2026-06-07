<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\AuthorityController;
use App\Http\Controllers\Master\ConditionController;
use App\Http\Controllers\Master\InstrumentController;
use App\Http\Controllers\Master\InstrumentStockController;
use App\Http\Controllers\Master\MenuController;
use App\Http\Controllers\Master\TitleMenuController;
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
        Route::post('register',             'register');
        Route::post('logout',               'logout');
        Route::get('me',                    'me');
        Route::put('update',                'update');
        Route::put('profile',               'updateProfile');
        Route::put('change-password',       'changePassword');
        Route::get('sessions',              'sessions');
        Route::delete('sessions/{id}',      'revokeSession');
        Route::delete('sessions',           'revokeAllSessions');
    });

    Route::prefix('master')->group(function () {
        Route::apiResource('authorities',      AuthorityController::class);
        Route::apiResource('title-menus',      TitleMenuController::class);
        Route::apiResource('menus',            MenuController::class);
        Route::apiResource('users',            UserController::class);
        Route::apiResource('conditions',       ConditionController::class);
        Route::get('instruments/stats', [InstrumentController::class, 'stats']);
        Route::apiResource('instruments',      InstrumentController::class);

        // QR Code instrumen (F3 PRD): scan untuk lookup & generate label QR
        Route::post('instrument-stocks/scan',            [InstrumentStockController::class, 'scan']);
        Route::get('instrument-stocks/{instrument_stock}/qr', [InstrumentStockController::class, 'qr']);
        Route::apiResource('instrument-stocks', InstrumentStockController::class);
        Route::apiResource('rooms',            RoomController::class);
    });
});
