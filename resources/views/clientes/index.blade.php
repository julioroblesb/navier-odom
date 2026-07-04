@extends('layouts.app')

@section('title', 'Clientes - NAVIER Counter System')
@section('page-title', 'Directorio de Clientes')

@section('content')
<div class="table-card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="mb-0 fw-bold">Lista de Clientes</h6>
        <a href="{{ route('clientes.create') }}" class="btn btn-sm btn-primary">
            <i class="bi bi-plus-lg"></i> Nuevo Cliente
        </a>
    </div>
    
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Razón Social</th>
                    <th>RUC</th>
                    <th>Contacto</th>
                    <th>Equipos</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($clientes as $cliente)
                <tr>
                    <td>
                        <strong>{{ $cliente->razon_social }}</strong>
                        @if($cliente->distrito)
                        <br><small class="text-muted"><i class="bi bi-geo-alt"></i> {{ $cliente->distrito }}</small>
                        @endif
                    </td>
                    <td>{{ $cliente->ruc ?? '-' }}</td>
                    <td>
                        @if($cliente->contacto_nombre)
                            {{ $cliente->contacto_nombre }}
                            <br><small class="text-muted"><i class="bi bi-telephone"></i> {{ $cliente->contacto_telefono ?? '-' }}</small>
                        @else
                            <span class="text-muted">Sin contacto</span>
                        @endif
                    </td>
                    <td>
                        <span class="badge bg-secondary">{{ $cliente->equipos_count }}</span>
                    </td>
                    <td>
                        @if($cliente->activo)
                            <span class="badge-status badge-online">Activo</span>
                        @else
                            <span class="badge-status badge-offline">Inactivo</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('clientes.edit', $cliente) }}" class="btn btn-sm btn-light text-primary" title="Editar">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form action="{{ route('clientes.destroy', $cliente) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Está seguro de eliminar este cliente?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-light text-danger" title="Eliminar" {{ $cliente->equipos_count > 0 ? 'disabled' : '' }}>
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-5">
                        <i class="bi bi-building text-muted" style="font-size: 2.5rem;"></i>
                        <p class="text-muted mt-2 mb-0">No hay clientes registrados aún.</p>
                        <a href="{{ route('clientes.create') }}" class="btn btn-primary btn-sm mt-3">
                            Registrar el primero
                        </a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($clientes->hasPages())
    <div class="card-footer bg-white border-top border-light px-3 py-2">
        {{ $clientes->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>
@endsection
