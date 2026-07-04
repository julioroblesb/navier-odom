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
                                <th>Empresa</th>
                                <th>Usuarios</th>
                                <th>Estado</th>
                                <th>Fecha de Creación</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($tenants as $tenant)
                            <tr>
                                <td>
                                    <strong class="text-body">{{ $tenant->nombre_empresa }}</strong>
                                </td>
                                <td>
                                    <span class="badge bg-secondary rounded-pill">{{ $tenant->users_count }}</span>
                                </td>
                                <td>
                                    @if($tenant->estado === 'activo')
                                        <span class="badge bg-success-lighten text-success">Activo</span>
                                    @else
                                        <span class="badge bg-danger-lighten text-danger">Inactivo</span>
                                    @endif
                                </td>
                                <td>
                                    {{ $tenant->created_at->format('d/m/Y') }}
                                </td>
                                <td>
                                    <a href="{{ route('tenants.edit', $tenant->id) }}" class="action-icon text-success" title="Editar">
                                        <i class="ri-edit-box-line"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
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
