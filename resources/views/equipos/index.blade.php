@extends('layouts.app')

@section('title', 'Equipos - NAVIER')
@section('page-title', 'Directorio de Equipos')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="header-title">Lista de Equipos Ricoh</h4>
                <a href="{{ route('equipos.create') }}" class="btn btn-primary btn-sm">
                    <i class="ri-add-line"></i> Agregar Equipo
                </a>
            </div>
            
            <div class="card-body pt-0">
                <div class="table-responsive">
                    <table class="table table-centered table-nowrap table-hover mb-0">
                        <thead class="table-light">
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
                                    <a href="{{ route('equipos.show', $equipo) }}" class="text-body fw-bold">
                                        {{ $equipo->serial }}
                                    </a>
                                    <br><small class="text-muted">{{ $equipo->modelo }}</small>
                                </td>
                                <td>
                                    @if($equipo->cliente)
                                        <a href="{{ route('clientes.edit', $equipo->cliente) }}" class="text-body">
                                            {{ Str::limit($equipo->cliente->razon_social, 35) }}
                                        </a>
                                    @else
                                        <span class="text-muted fst-italic">Sin asignar</span>
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
                                        <span class="badge bg-success-lighten text-success">Activo</span>
                                    @else
                                        <span class="badge bg-danger-lighten text-danger">Inactivo</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('equipos.show', $equipo) }}" class="action-icon text-info" title="Ver Detalles">
                                        <i class="ri-eye-line"></i>
                                    </a>
                                    <a href="{{ route('equipos.edit', $equipo) }}" class="action-icon text-success" title="Editar">
                                        <i class="ri-edit-box-line"></i>
                                    </a>
                                    <form action="{{ route('equipos.destroy', $equipo) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Está seguro de eliminar este equipo?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-link action-icon text-danger p-0 border-0" title="Eliminar">
                                            <i class="ri-delete-bin-line"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <i class="ri-printer-line text-muted display-4"></i>
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
                <div class="mt-3">
                    {{ $equipos->links('pagination::bootstrap-5') }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
