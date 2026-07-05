<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tenant;
use App\Models\Cliente;
use App\Models\Equipo;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Global stats for Super Admin
        $stats = [
            'tenants' => Tenant::count(),
            'tenants_activos' => Tenant::where('estado', 'activo')->count(),
            'clientes_global' => Cliente::withoutGlobalScope('tenant')->count(),
            'equipos_global' => Equipo::withoutGlobalScope('tenant')->count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
