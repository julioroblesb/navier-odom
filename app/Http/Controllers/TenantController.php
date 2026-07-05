<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TenantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tenants = \App\Models\Tenant::withCount(['users', 'clientes', 'equipos'])->get();
        return view('tenants.index', compact('tenants'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('tenants.form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre_empresa' => 'required|string|max:255',
            'plan_type' => 'required|in:Demo,Mensual,Anual,Lifetime',
            'billing_status' => 'required|in:Al día,Pendiente',
            'demo_expires_at' => 'nullable|date',
            'admin_name' => 'required|string|max:255',
            'admin_email' => 'required|string|email|max:255|unique:users,email',
            'admin_password' => 'required|string|min:8',
        ]);

        \Illuminate\Support\Facades\DB::transaction(function () use ($request) {
            $tenant = \App\Models\Tenant::create([
                'nombre_empresa' => $request->nombre_empresa,
                'estado' => 'activo',
                'plan_type' => $request->plan_type,
                'billing_status' => $request->billing_status,
                'demo_expires_at' => $request->demo_expires_at,
            ]);

            \App\Models\User::create([
                'name' => $request->admin_name,
                'email' => $request->admin_email,
                'password' => bcrypt($request->admin_password),
                'tenant_id' => $tenant->id,
            ]);
        });

        return redirect()->route('tenants.index')->with('success', 'Empresa (Tenant) creada exitosamente con su usuario administrador.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $tenant = \App\Models\Tenant::findOrFail($id);
        return view('tenants.form', compact('tenant'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $tenant = \App\Models\Tenant::findOrFail($id);
        
        $request->validate([
            'nombre_empresa' => 'required|string|max:255',
            'estado' => 'required|in:activo,suspendido',
            'plan_type' => 'required|in:Demo,Mensual,Anual,Lifetime',
            'billing_status' => 'required|in:Al día,Pendiente',
            'demo_expires_at' => 'nullable|date',
        ]);

        $tenant->update([
            'nombre_empresa' => $request->nombre_empresa,
            'estado' => $request->estado,
            'plan_type' => $request->plan_type,
            'billing_status' => $request->billing_status,
            'demo_expires_at' => $request->demo_expires_at,
        ]);

        return redirect()->route('tenants.index')->with('success', 'Empresa actualizada exitosamente.');
    }
}
