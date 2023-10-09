<?php

use App\Http\Controllers\Web\ClientesController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\EngenheirosController;
use App\Http\Controllers\Web\MinhaContaController;
use App\Http\Controllers\Web\ResetPasswordController;
use App\Http\Controllers\Web\UsuariosController;
use Illuminate\Support\Facades\Route;
use App\Livewire\Pages\Auth\Login;
use App\Livewire\Pages\Obras\EtapasObra;
use App\Livewire\Pages\Obras\Obras;
use App\Livewire\Pages\Reunioes\Reunioes;

Route::get('/', Login::class)->name('login')->middleware('guest');

Route::get('/solicitar-alteracao-senha', [ResetPasswordController::class, 'index'])
    ->middleware('guest')->name('password.request');
Route::post('/solicitar-alteracao-senha', [ResetPasswordController::class, 'store'])
    ->middleware('guest')->name('password.email');

Route::get('/alterar-senha/{token}', [ResetPasswordController::class, 'edit'])
    ->middleware('guest')->name('password.reset');
Route::post('/alterar-senha', [ResetPasswordController::class, 'update'])
    ->middleware('guest')->name('password.update');

Route::group([
    'prefix' => 'home',
    'as' => 'dashboard.',
    'middleware' => 'auth'
], function() {

    Route::get('/', [DashboardController::class, 'index'])->name('home');
    Route::get('/logout', [DashboardController::class, 'destroy'])->name('logout');

    Route::middleware('admin')->group(function() {
        
        Route::get('/usuarios', [UsuariosController::class, 'index'])->name('usuarios');
        Route::get('/engenheiros', [EngenheirosController::class, 'index'])->name('engenheiros');
        Route::get('/clientes', [ClientesController::class, 'index'])->name('clientes');

    });

    Route::get('/minha-conta', [MinhaContaController::class, 'index'])->name('minha-conta');
    Route::get('/obras', Obras::class)->name('obras');
    Route::get('/obras/{obra}', EtapasObra::class)->name('etapas-obra');

    Route::get('/reunioes', Reunioes::class)->name('reunioes');

    
});

Route::get('/test-pdf-relatorio', fn() => view('pdf.obras-relatorios'));
