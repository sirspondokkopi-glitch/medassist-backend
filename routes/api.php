<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\AuthorityController;
use App\Http\Controllers\Master\ConditionController;
use App\Http\Controllers\Master\InstrumentController;
use App\Http\Controllers\Master\InstrumentSetController;
use App\Http\Controllers\Master\InstrumentStockController;
use App\Http\Controllers\Master\MenuController;
use App\Http\Controllers\Master\RoomController;
use App\Http\Controllers\Master\TitleMenuController;
use App\Http\Controllers\Master\UserController;
use App\Http\Controllers\Transaction\OrderController;
use App\Http\Controllers\Transaction\SterilizationController;
use Illuminate\Support\Facades\Route;

// Publik
Route::prefix('auth')->controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
});

// Butuh token
Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('auth')->controller(AuthController::class)->group(function () {
        Route::post('register', 'register');
        Route::post('logout', 'logout');
        Route::get('me', 'me');
        Route::put('update', 'update');
        Route::put('profile', 'updateProfile');
        Route::put('change-password', 'changePassword');
        Route::get('sessions', 'sessions');
        Route::delete('sessions/{id}', 'revokeSession');
        Route::delete('sessions', 'revokeAllSessions');
    });

    Route::prefix('master')->group(function () {
        Route::apiResource('authorities', AuthorityController::class);
        Route::apiResource('title-menus', TitleMenuController::class);
        Route::apiResource('menus', MenuController::class);
        Route::apiResource('users', UserController::class);
        Route::apiResource('conditions', ConditionController::class);
        Route::get('instruments/stats', [InstrumentController::class, 'stats']);
        Route::apiResource('instruments', InstrumentController::class);

        // QR Code instrumen (F3 PRD): scan untuk lookup & generate label QR
        Route::post('instrument-stocks/scan', [InstrumentStockController::class, 'scan']);
        Route::get('instrument-stocks/{instrument_stock}/qr', [InstrumentStockController::class, 'qr']);
        // Riwayat pergerakan/perubahan status unit
        Route::get('instrument-stocks/{instrument_stock}/logs', [InstrumentStockController::class, 'logs']);
        Route::apiResource('instrument-stocks', InstrumentStockController::class);
        Route::apiResource('rooms', RoomController::class);

        // Set/Tray instrumen CSSD: kumpulan unit yang dikelola sebagai satu paket
        Route::apiResource('instrument-sets', InstrumentSetController::class);

        // Peminjaman instrumen (F5 PRD): order header + item unit
        Route::apiResource('orders', OrderController::class);

        // Sterilisasi CSSD: batch/siklus sterilisasi + unit di dalamnya
        Route::get('sterilizations/expiring', [SterilizationController::class, 'expiring']);
        Route::apiResource('sterilizations', SterilizationController::class);
    });
});
