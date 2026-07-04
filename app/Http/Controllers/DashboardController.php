<?php

namespace App\Http\Controllers;

use App\Models\Equipo;
use App\Models\Cliente;
use App\Models\LecturaContador;
use App\Models\Alerta;
use App\Models\Licencia;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'equipos' => Equipo::where('activo', true)->count(),
            'clientes' => Cliente::count(),
            'lecturas_mes' => LecturaContador::whereYear('created_at', now()->year)
                ->whereMonth('created_at', now()->month)
                ->count(),
        ];

        $ultimasLecturas = LecturaContador::with(['equipo.cliente'])
            ->latest()
            ->take(10)
            ->get();

        $alertas = Alerta::with(['equipo.cliente'])
            ->where('resuelta', false)
            ->latest()
            ->take(10)
            ->get();

        return view('dashboard', compact('stats', 'ultimasLecturas', 'alertas'));
    }
}
