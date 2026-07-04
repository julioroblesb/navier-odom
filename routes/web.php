<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\EquipoController;
use App\Http\Controllers\LecturaWebController;
use App\Http\Controllers\AlertaController;
use App\Http\Controllers\AgenteController;
use App\Http\Controllers\LicenciaController;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::resource('clientes', ClienteController::class);
Route::resource('equipos', EquipoController::class);

Route::get('/lecturas', [LecturaWebController::class, 'index'])->name('lecturas.index');
Route::get('/alertas', [AlertaController::class, 'index'])->name('alertas.index');
Route::patch('/alertas/{alerta}/resolve', [AlertaController::class, 'resolve'])->name('alertas.resolve');
Route::get('/agentes', [AgenteController::class, 'index'])->name('agentes.index');
Route::get('/licencia', [LicenciaController::class, 'index'])->name('licencia.index');
Route::post('/licencia', [LicenciaController::class, 'store'])->name('licencia.store');
