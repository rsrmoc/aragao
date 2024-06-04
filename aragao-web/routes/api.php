<?php

use App\Http\Controllers\Api\RastreamentoController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('clientApp')->post('/rastreamento/gravar', [RastreamentoController::class, 'gravarLocalizacao'])->name('rastreamento-gravar');

// NUNCA UTILIZAR ESSA ROTA EM PRODUÇÃO
Route::get('gerar-token', function() {
    $token = \App\Services\Helpers\AppService::generateToken();
    return response()->json([
        'token' => $token
    ]);
})->name('gerar-token');