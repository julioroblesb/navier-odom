<?php

namespace App\Http\Controllers;

use App\Models\Equipo;
use App\Models\LecturaContador;
use Illuminate\Http\Request;

class LecturaWebController extends Controller
{
    public function index(Request $request)
    {
        // Get all equipos with their latest lectura
        $query = \App\Models\Equipo::with(['cliente', 'ultimaLectura'])
            ->whereHas('ultimaLectura');
        
        // Simple search
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function($eq) use ($q) {
                $eq->where('serial', 'like', "%{$q}%")
                   ->orWhere('modelo', 'like', "%{$q}%")
                   ->orWhereHas('cliente', function($cl) use ($q) {
                       $cl->where('razon_social', 'like', "%{$q}%");
                   });
            });
        }

        $equipos = $query->get();
        
        return view('lecturas.index', compact('equipos'));
    }
}
