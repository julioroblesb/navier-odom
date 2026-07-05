<?php

namespace App\Http\Controllers;

use App\Models\Sucursal;
use App\Models\Cliente;
use Illuminate\Http\Request;

class SucursalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'nombre' => 'required|string|max:150',
            'direccion' => 'nullable|string|max:255',
            'contacto' => 'nullable|string|max:100',
            'telefono' => 'nullable|string|max:50',
        ]);

        Sucursal::create($validated);
        
        return back()->with('success', 'Sucursal registrada correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $sucursal = Sucursal::findOrFail($id);
        
        $validated = $request->validate([
            'nombre' => 'required|string|max:150',
            'direccion' => 'nullable|string|max:255',
            'contacto' => 'nullable|string|max:100',
            'telefono' => 'nullable|string|max:50',
        ]);

        $sucursal->update($validated);
        
        return back()->with('success', 'Sucursal actualizada correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $sucursal = Sucursal::findOrFail($id);
        
        if ($sucursal->equipos()->count() > 0) {
            return back()->with('error', 'No se puede eliminar la sucursal porque tiene equipos asignados.');
        }

        $sucursal->delete();
        
        return back()->with('success', 'Sucursal eliminada correctamente.');
    }
}
