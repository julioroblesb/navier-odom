<?php

namespace App\Http\Controllers;

use App\Models\Equipo;
use App\Models\Cliente;
use App\Services\TokenService;
use Illuminate\Http\Request;

class EquipoController extends Controller
{
    public function index()
    {
        $equipos = Equipo::with(['cliente', 'ultimaLectura'])->latest()->paginate(15);
        return view('equipos.index', compact('equipos'));
    }

    public function create()
    {
        $clientes = Cliente::where('activo', true)->orderBy('razon_social')->get();
        return view('equipos.form', ['equipo' => new Equipo(), 'clientes' => $clientes]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'cliente_id' => 'nullable|exists:clientes,id',
            'serial' => 'required|string|max:30|unique:equipos,serial',
            'modelo' => 'required|string|max:100',
            'ip_local' => 'nullable|string|max:15',
            'fecha_instalacion' => 'nullable|date',
            'activo' => 'boolean'
        ]);

        $validated['activo'] = $request->has('activo');
        // Generate a unique token for this new equipment
        $validated['agente_token'] = TokenService::generateToken($validated['serial']);

        Equipo::create($validated);
        return redirect()->route('equipos.index')->with('success', 'Equipo registrado correctamente.');
    }

    public function show(Equipo $equipo)
    {
        $equipo->load(['cliente', 'alertas' => function($q) {
            $q->where('resuelta', false);
        }]);
        $lecturas = $equipo->lecturasContador()->latest()->take(30)->get();
        return view('equipos.show', compact('equipo', 'lecturas'));
    }

    public function edit(Equipo $equipo)
    {
        $clientes = Cliente::where('activo', true)->orderBy('razon_social')->get();
        return view('equipos.form', compact('equipo', 'clientes'));
    }

    public function update(Request $request, Equipo $equipo)
    {
        $validated = $request->validate([
            'cliente_id' => 'nullable|exists:clientes,id',
            'serial' => 'required|string|max:30|unique:equipos,serial,'.$equipo->id,
            'modelo' => 'required|string|max:100',
            'ip_local' => 'nullable|string|max:15',
            'fecha_instalacion' => 'nullable|date',
            'activo' => 'boolean'
        ]);

        $validated['activo'] = $request->has('activo');

        $equipo->update($validated);
        return redirect()->route('equipos.index')->with('success', 'Equipo actualizado correctamente.');
    }

    public function destroy(Equipo $equipo)
    {
        if ($equipo->lecturasContador()->count() > 0) {
            return back()->with('error', 'No se puede eliminar el equipo porque ya tiene lecturas registradas. Puede desactivarlo en su lugar.');
        }
        $equipo->delete();
        return redirect()->route('equipos.index')->with('success', 'Equipo eliminado correctamente.');
    }
}
