<?php

namespace App\Http\Controllers;

use App\Models\Equipo;
use Illuminate\Http\Request;

class AgenteController extends Controller
{
    public function index()
    {
        $equipos = Equipo::with('cliente')->where('activo', true)->orderBy('serial')->get();
        return view('agentes.index', compact('equipos'));
    }
}
