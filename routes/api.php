<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LocalController;
use App\Http\Controllers\Api\ContratoController;
use App\Http\Controllers\Api\PagoController;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['auth:sanctum', 'tenant'])->group(function () {
    Route::apiResource('locales', LocalController::class);
    Route::apiResource('contratos', ContratoController::class)->except(['destroy']);
    Route::apiResource('pagos', PagoController::class)->only(['index', 'store']);
});
