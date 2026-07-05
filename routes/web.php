<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\EquipoController;
use App\Http\Controllers\LecturaWebController;
use App\Http\Controllers\AlertaController;
use App\Http\Controllers\AgenteController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TenantController;

// Auth Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Rutas protegidas (Requieren autenticación)
Route::middleware(['auth', 'check.tenant.status'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('clientes', ClienteController::class);
    Route::resource('sucursales', \App\Http\Controllers\SucursalController::class)->except(['index', 'create', 'show', 'edit']);
    Route::resource('equipos', EquipoController::class);

    Route::get('/lecturas', [LecturaWebController::class, 'index'])->name('lecturas.index');
    Route::get('/alertas', [AlertaController::class, 'index'])->name('alertas.index');
    Route::patch('/alertas/{alerta}/resolve', [AlertaController::class, 'resolve'])->name('alertas.resolve');
    Route::get('/agentes', [AgenteController::class, 'index'])->name('agentes.index');

    // Panel exclusivo de super admin
    Route::middleware(['super_admin'])->group(function () {
        Route::resource('tenants', TenantController::class)->except(['show', 'destroy']);
    });
});
