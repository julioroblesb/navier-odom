<?php

use App\Http\Controllers\Api\LecturaController;
use Illuminate\Support\Facades\Route;

Route::post('/lecturas', [LecturaController::class, 'store']);
Route::get('/status', [LecturaController::class, 'status']);
