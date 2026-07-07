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
        $equipos = Equipo::with(['cliente', 'sucursal', 'ultimaLectura'])->latest()->get();
        return view('equipos.index', compact('equipos'));
    }

    public function create()
    {
        $clientes = Cliente::with('sucursales')->where('activo', true)->orderBy('razon_social')->get();
        return view('equipos.form', ['equipo' => new Equipo(), 'clientes' => $clientes]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'sucursal_id' => 'required|exists:sucursales,id',
            'serial' => 'required|string|max:30|unique:equipos,serial',
            'modelo' => 'required|string|max:100',
            'ip_local' => 'nullable|string|max:15',
            'fecha_instalacion' => 'nullable|date',
            'activo' => 'nullable'
        ]);

        $validated['activo'] = $request->has('activo');
        $validated['cliente_id'] = \App\Models\Sucursal::find($validated['sucursal_id'])->cliente_id;
        // Generate a unique token for this new equipment
        $validated['agente_token'] = TokenService::generateToken($validated['serial']);

        Equipo::create($validated);
        return redirect()->route('equipos.index')->with('success', 'Equipo registrado correctamente.');
    }

    public function show(Equipo $equipo)
    {
        $equipo->load(['cliente', 'sucursal', 'alertas' => function($q) {
            $q->where('resuelta', false);
        }]);
        $lecturas = $equipo->lecturas()->latest()->take(30)->get();
        
        $lecturasMensuales = $equipo->lecturas()
            ->orderBy('created_at', 'asc')
            ->get()
            ->groupBy(function($val) {
                return \Carbon\Carbon::parse($val->created_at)->format('M y');
            })
            ->map(function($group) {
                return $group->last();
            });

        return view('equipos.show', compact('equipo', 'lecturas', 'lecturasMensuales'));
    }

    public function edit(Equipo $equipo)
    {
        $clientes = Cliente::with('sucursales')->where('activo', true)->orderBy('razon_social')->get();
        return view('equipos.form', compact('equipo', 'clientes'));
    }

    public function update(Request $request, Equipo $equipo)
    {
        $validated = $request->validate([
            'sucursal_id' => 'required|exists:sucursales,id',
            'serial' => 'required|string|max:30|unique:equipos,serial,'.$equipo->id,
            'modelo' => 'required|string|max:100',
            'ip_local' => 'nullable|string|max:15',
            'fecha_instalacion' => 'nullable|date',
            'activo' => 'nullable'
        ]);

        $validated['activo'] = $request->has('activo');
        $validated['cliente_id'] = \App\Models\Sucursal::find($validated['sucursal_id'])->cliente_id;

        $equipo->update($validated);
        return redirect()->route('equipos.index')->with('success', 'Equipo actualizado correctamente.');
    }

    public function destroy(Equipo $equipo)
    {
        // Liberar el número de serie para que otro tenant pueda registrarlo
        // Conservamos el registro original (Soft Delete) para no perder el historial de lecturas
        $oldSerial = $equipo->serial;
        $equipo->serial = $oldSerial . '_LIBERADO_' . time();
        $equipo->save();

        $equipo->delete(); // Soft Delete

        return redirect()->route('equipos.index')->with('success', 'Equipo eliminado y número de serie liberado correctamente.');
    }

    /**
     * Retrieve the agent token securely via AJAX
     */
    public function revealToken(Equipo $equipo)
    {
        return response()->json([
            'token' => $equipo->agente_token
        ]);
    }
}
