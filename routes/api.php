<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AgentController;

/*
|--------------------------------------------------------------------------
| API Routes - Andespc
|--------------------------------------------------------------------------
| Rutas para la comunicación de agentes cliente (Andes-Agent) y servicios externos.
*/

Route::prefix('v1/agent')->group(function () {
    Route::get('/ping', [AgentController::class, 'ping']);
    Route::post('/sync', [AgentController::class, 'sync']);
});
