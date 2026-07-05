@extends('layouts.app')

@section('title', 'Empresas - NAVIER')
@section('page-title', 'Gestión de Empresas (SA)')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="header-title">Lista de Empresas Registradas</h4>
                <a href="{{ route('tenants.create') }}" class="btn btn-primary btn-sm">
                    <i class="ri-add-line"></i> Nueva Empresa
                </a>
            </div>
            
            <div class="card-body pt-0">
                <div class="table-responsive">
                    <table class="table table-centered table-nowrap table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Empresa / Registro</th>
                                <th>Plan Comercial</th>
                                <th>Facturación</th>
                                <th>Clientes Finales</th>
                                <th>Equipos</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($tenants as $tenant)
                            <tr>
                                <td>
                                    <strong class="text-body">{{ $tenant->nombre_empresa }}</strong><br>
                                    <small class="text-muted">{{ $tenant->created_at->format('d/m/Y') }}</small>
                                </td>
                                <td>
                                    @if($tenant->plan_type === 'Demo')
                                        <span class="badge bg-warning-lighten text-warning">Demo</span>
                                        @if($tenant->demo_expires_at)
                                            <br><small class="text-muted">Exp: {{ $tenant->demo_expires_at->format('d/m/Y') }}</small>
                                        @endif
                                    @elseif($tenant->plan_type === 'Mensual')
                                        <span class="badge bg-info-lighten text-info">Mensual</span>
                                    @elseif($tenant->plan_type === 'Anual')
                                        <span class="badge bg-primary-lighten text-primary">Anual</span>
                                    @else
                                        <span class="badge bg-success-lighten text-success">Lifetime</span>
                                    @endif
                                </td>
                                <td>
                                    @if($tenant->billing_status === 'Al día')
                                        <span class="badge bg-success"><i class="mdi mdi-check-circle"></i> Al día</span>
                                    @else
                                        <span class="badge bg-danger"><i class="mdi mdi-alert-circle"></i> Pendiente</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-secondary rounded-pill">{{ $tenant->clientes_count }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-secondary rounded-pill">{{ $tenant->equipos_count }}</span>
                                </td>
                                <td>
                                    <form action="{{ route('tenants.update', $tenant->id) }}" method="POST" class="d-inline-block">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="nombre_empresa" value="{{ $tenant->nombre_empresa }}">
                                        <input type="hidden" name="plan_type" value="{{ $tenant->plan_type }}">
                                        <input type="hidden" name="billing_status" value="{{ $tenant->billing_status }}">
                                        @if($tenant->demo_expires_at)
                                            <input type="hidden" name="demo_expires_at" value="{{ $tenant->demo_expires_at->format('Y-m-d') }}">
                                        @endif
                                        
                                        <div class="form-check form-switch">
                                            <input type="checkbox" class="form-check-input" name="estado" value="activo" 
                                                onchange="this.form.submit()" 
                                                {{ $tenant->estado === 'activo' ? 'checked' : '' }}>
                                            <label class="form-check-label {{ $tenant->estado === 'activo' ? 'text-success' : 'text-danger' }}">
                                                {{ $tenant->estado === 'activo' ? 'Activo' : 'Suspendido' }}
                                            </label>
                                        </div>
                                    </form>
                                </td>
                                <td>
                                    <a href="{{ route('tenants.edit', $tenant->id) }}" class="action-icon text-success" title="Editar">
                                        <i class="ri-edit-box-line"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <i class="ri-building-4-line text-muted display-4"></i>
                                    <p class="text-muted mt-2 mb-0">No hay empresas registradas.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
