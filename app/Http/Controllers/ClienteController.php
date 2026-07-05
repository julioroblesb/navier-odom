<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function index(Request $request)
    {
        \App\Jobs\LogAuditAction::dispatch([
            'user_id' => auth()->id(),
            'tenant_id' => auth()->user()->tenant_id,
            'target_type' => 'Cliente',
            'target_id' => null,
            'action' => 'view_list',
            'ip_address' => $request->ip(),
        ]);

        $clientes = Cliente::withCount('equipos')->latest()->get();
        return view('clientes.index', compact('clientes'));
    }

    public function create()
    {
        return view('clientes.form', ['cliente' => new Cliente()]);
    }

    public function show(Cliente $cliente, Request $request)
    {
        \App\Jobs\LogAuditAction::dispatch([
            'user_id' => auth()->id(),
            'tenant_id' => auth()->user()->tenant_id,
            'target_type' => 'Cliente',
            'target_id' => $cliente->id,
            'action' => 'view_detail',
            'ip_address' => $request->ip(),
        ]);

        $cliente->load('sucursales.equipos');
        return view('clientes.show', compact('cliente'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'razon_social' => 'required|string|max:200',
            'ruc' => 'nullable|string|max:11',
            'direccion' => 'nullable|string|max:300',
            'distrito' => 'nullable|string|max:100',
            'contacto_nombre' => 'nullable|string|max:150',
            'contacto_telefono' => 'nullable|string|max:20',
            'contacto_email' => 'nullable|email|max:150',
            'activo' => 'nullable'
        ]);

        $validated['activo'] = $request->has('activo');

        $cliente = Cliente::create($validated);
        
        \App\Jobs\LogAuditAction::dispatch([
            'user_id' => auth()->id(),
            'tenant_id' => auth()->user()->tenant_id,
            'target_type' => 'Cliente',
            'target_id' => $cliente->id,
            'action' => 'create',
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('clientes.index')->with('success', 'Cliente registrado correctamente.');
    }

    public function edit(Cliente $cliente)
    {
        return view('clientes.form', compact('cliente'));
    }

    public function update(Request $request, Cliente $cliente)
    {
        $validated = $request->validate([
            'razon_social' => 'required|string|max:200',
            'ruc' => 'nullable|string|max:11',
            'direccion' => 'nullable|string|max:300',
            'distrito' => 'nullable|string|max:100',
            'contacto_nombre' => 'nullable|string|max:150',
            'contacto_telefono' => 'nullable|string|max:20',
            'contacto_email' => 'nullable|email|max:150',
            'activo' => 'nullable'
        ]);

        $validated['activo'] = $request->has('activo');

        $cliente->update($validated);

        \App\Jobs\LogAuditAction::dispatch([
            'user_id' => auth()->id(),
            'tenant_id' => auth()->user()->tenant_id,
            'target_type' => 'Cliente',
            'target_id' => $cliente->id,
            'action' => 'update',
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('clientes.index')->with('success', 'Cliente actualizado correctamente.');
    }

    public function destroy(Cliente $cliente)
    {
        if ($cliente->equipos()->count() > 0) {
            return back()->with('error', 'No se puede eliminar el cliente porque tiene equipos asignados.');
        }
        $clienteId = $cliente->id;
        $cliente->delete();

        \App\Jobs\LogAuditAction::dispatch([
            'user_id' => auth()->id(),
            'tenant_id' => auth()->user()->tenant_id,
            'target_type' => 'Cliente',
            'target_id' => $clienteId,
            'action' => 'delete',
            'ip_address' => request()->ip(),
        ]);

        return redirect()->route('clientes.index')->with('success', 'Cliente eliminado correctamente.');
    }
}
