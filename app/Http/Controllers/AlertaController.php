<?php

namespace App\Http\Controllers;

use App\Models\Alerta;
use Illuminate\Http\Request;

class AlertaController extends Controller
{
    public function index()
    {
        $alertas = Alerta::with(['equipo.cliente'])
                        ->where('resuelta', false)
                        ->orderByDesc('nivel_severidad')
                        ->latest()
                        ->paginate(20);
                        
        $alertasResueltas = Alerta::with(['equipo.cliente'])
                        ->where('resuelta', true)
                        ->latest()
                        ->take(10)
                        ->get();
                        
        return view('alertas.index', compact('alertas', 'alertasResueltas'));
    }

    public function resolve(Alerta $alerta)
    {
        $alerta->update(['resuelta' => true]);
        return back()->with('success', 'Alerta marcada como resuelta.');
    }
}
