@extends('layouts.app')

@section('title', 'Equipos - NAVIER Counter System')
@section('page-title', 'Directorio de Equipos')

@section('content')
<div class="table-card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="mb-0 fw-bold">Lista de Equipos Ricoh</h6>
        <a href="{{ route('equipos.create') }}" class="btn btn-sm btn-primary">
            <i class="bi bi-plus-lg"></i> Agregar Equipo
        </a>
    </div>
    
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Serial / Modelo</th>
                    <th>Cliente Asignado</th>
                    <th>IP Local</th>
                    <th>Última Lectura</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($equipos as $equipo)
                <tr>
                    <td>
                        <a href="{{ route('equipos.show', $equipo) }}" class="text-decoration-none fw-bold">
                            {{ $equipo->serial }}
                        </a>
                        <br><small class="text-muted">{{ $equipo->modelo }}</small>
                    </td>
                    <td>
                        @if($equipo->cliente)
                            <a href="{{ route('clientes.edit', $equipo->cliente) }}" class="text-decoration-none text-dark">
                                {{ Str::limit($equipo->cliente->razon_social, 35) }}
                            </a>
                        @else
                            <span class="text-muted fst-italic">En almacén / Sin asignar</span>
                        @endif
                    </td>
                    <td>
                        <span class="font-monospace text-muted">{{ $equipo->ip_local ?? '-' }}</span>
                    </td>
                    <td>
                        @if($equipo->ultimaLectura)
                            {{ $equipo->ultimaLectura->created_at->format('d/m/Y H:i') }}
                            <br>
                            <small class="text-muted">{{ $equipo->ultimaLectura->created_at->diffForHumans() }}</small>
                        @else
                            <span class="text-muted">Sin datos</span>
                        @endif
                    </td>
                    <td>
                        @if($equipo->activo)
                            <span class="badge-status badge-online">Activo</span>
                        @else
                            <span class="badge-status badge-offline">Inactivo</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('equipos.show', $equipo) }}" class="btn btn-sm btn-light text-primary" title="Ver Detalles">
                            <i class="bi bi-eye"></i>
                        </a>
                        <a href="{{ route('equipos.edit', $equipo) }}" class="btn btn-sm btn-light text-success" title="Editar">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form action="{{ route('equipos.destroy', $equipo) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Está seguro de eliminar este equipo? Solo se puede si no tiene lecturas.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-light text-danger" title="Eliminar">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-5">
                        <i class="bi bi-printer text-muted" style="font-size: 2.5rem;"></i>
                        <p class="text-muted mt-2 mb-0">No hay equipos registrados aún.</p>
                        <a href="{{ route('equipos.create') }}" class="btn btn-primary btn-sm mt-3">
                            Registrar el primer equipo
                        </a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($equipos->hasPages())
    <div class="card-footer bg-white border-top border-light px-3 py-2">
        {{ $equipos->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>
@endsection
