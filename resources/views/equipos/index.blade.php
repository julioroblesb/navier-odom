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
                    <table id="tabla-equipos" class="table table-striped dt-responsive nowrap w-100">
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

            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link href="{{ asset('assets/vendor/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/vendor/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/vendor/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css') }}" rel="stylesheet" type="text/css" />
@endpush

@push('scripts')
<script src="{{ asset('assets/vendor/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/vendor/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
<script src="{{ asset('assets/vendor/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('assets/vendor/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js') }}"></script>
<script src="{{ asset('assets/vendor/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('assets/vendor/datatables.net-buttons-bs5/js/buttons.bootstrap5.min.js') }}"></script>
<script src="{{ asset('assets/vendor/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
<script>
$(document).ready(function() {
    var table = $('#tabla-equipos').DataTable({
        responsive: true,
        lengthChange: false,
        buttons: [{
            extend: 'csvHtml5',
            text: '<i class="ri-file-download-line"></i> Exportar CSV',
            className: 'btn btn-primary'
        }],
        language: {
            search: 'Buscar:',
            lengthMenu: 'Mostrar _MENU_ registros',
            info: 'Mostrando _START_ a _END_ de _TOTAL_ equipos',
            infoEmpty: 'No hay equipos',
            infoFiltered: '(filtrado de _MAX_ registros)',
            zeroRecords: 'No se encontraron equipos',
            paginate: { first: 'Primero', last: 'Último', next: 'Siguiente', previous: 'Anterior' }
        },
        pageLength: 15,
        order: [[3, 'desc']]
    });
    
    table.buttons().container().appendTo('#tabla-equipos_wrapper .col-md-6:eq(0)');
});
</script>
@endpush
