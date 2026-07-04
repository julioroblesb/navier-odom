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
        $totalEquiposRegistrados = Equipo::count();
        $totalEquipos = Equipo::where('activo', true)->count();
        $totalClientes = Cliente::has('equipos')->count();
        $lecturasHoy = LecturaContador::whereDate('created_at', today())->count();
        $ultimaLectura = LecturaContador::latest()->first()?->created_at->diffForHumans();
        
        $totalAlertas = Alerta::where('resuelta', false)->count();
        $alertasToner = Alerta::where('resuelta', false)->where('tipo', 'toner_bajo')->count();
        
        // Equipos recientes
        $equiposRecientes = Equipo::with(['cliente', 'ultimaLectura'])
            ->orderByDesc('id')
            ->take(8)
            ->get();
            
        // Alertas recientes
        $alertasRecientes = Alerta::with('equipo')
            ->where('resuelta', false)
            ->latest()
            ->take(5)
            ->get();
            
        // Licencia mockeada para demostración si no hay
        $licencia = Licencia::first() ?? (object)[
            'max_equipos' => 250,
            'fecha_vencimiento' => now()->addYear(),
            'diasRestantes' => 365,
            'activa' => true
        ];

        return view('dashboard', compact(
            'totalEquiposRegistrados',
            'totalEquipos',
            'totalClientes',
            'lecturasHoy',
            'ultimaLectura',
            'totalAlertas',
            'alertasToner',
            'equiposRecientes',
            'alertasRecientes',
            'licencia'
        ));
    }
}
