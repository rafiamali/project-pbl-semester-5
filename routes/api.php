<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AnggaranTahunanController;
use App\Http\Controllers\Api\TorController;
use App\Http\Controllers\Api\LpjController;
use App\Http\Controllers\Api\LampiranController;

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected routes
Route::middleware('auth:api')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/profile', [AuthController::class, 'getProfile']);

    // Anggaran Tahunan routes
    Route::apiResource('anggaran-tahunan', AnggaranTahunanController::class);

    // TOR routes
    Route::apiResource('tor', TorController::class);
    Route::post('tor/{id}/ajukan', [TorController::class, 'ajukan']);
    Route::post('tor/{id}/setujui', [TorController::class, 'setujui']);
    Route::post('tor/{id}/tolak', [TorController::class, 'tolak']);
    Route::post('tor/{id}/revisi', [TorController::class, 'revisi']);

    // LPJ routes
    Route::apiResource('lpj', LpjController::class);
    Route::post('lpj/{id}/ajukan', [LpjController::class, 'ajukan']);
    Route::post('lpj/{id}/setujui', [LpjController::class, 'setujui']);
    Route::post('lpj/{id}/tolak', [LpjController::class, 'tolak']);

    // Lampiran routes
    Route::post('lampiran/upload', [LampiranController::class, 'upload']);
    Route::get('lampiran/{id}/download', [LampiranController::class, 'download']);
    Route::delete('lampiran/{id}', [LampiranController::class, 'destroy']);
});
