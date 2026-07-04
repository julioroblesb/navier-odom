<?php

namespace App\Http\Controllers;

use App\Models\LecturaContador;
use Illuminate\Http\Request;

class LecturaWebController extends Controller
{
    public function index(Request $request)
    {
        $query = LecturaContador::with(['equipo.cliente']);
        
        // Simple search by serial or cliente
        if ($request->filled('q')) {
            $q = $request->q;
            $query->whereHas('equipo', function($eq) use ($q) {
                $eq->where('serial', 'like', "%{$q}%")
                   ->orWhereHas('cliente', function($cl) use ($q) {
                       $cl->where('razon_social', 'like', "%{$q}%");
                   });
            });
        }

        $lecturas = $query->latest()->paginate(20);
        
        return view('lecturas.index', compact('lecturas'));
    }
}
