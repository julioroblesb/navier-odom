@extends('layouts.app')

@section('title', 'Clientes - NAVIER')
@section('page-title', 'Directorio de Clientes')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="header-title">Lista de Clientes</h4>
                <a href="{{ route('clientes.create') }}" class="btn btn-primary btn-sm">
                    <i class="ri-add-line"></i> Nuevo Cliente
                </a>
            </div>
            
            <div class="card-body pt-0">
                <div class="table-responsive">
                    <table id="tabla-clientes" class="table table-striped dt-responsive nowrap w-100">
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
                                    <strong class="text-body">{{ $cliente->razon_social }}</strong>
                                    @if($cliente->distrito)
                                    <br><small class="text-muted"><i class="ri-map-pin-line"></i> {{ $cliente->distrito }}</small>
                                    @endif
                                </td>
                                <td>{{ $cliente->ruc ?? '-' }}</td>
                                <td>
                                    @if($cliente->contacto_nombre)
                                        {{ $cliente->contacto_nombre }}
                                        <br><small class="text-muted"><i class="ri-phone-line"></i> {{ $cliente->contacto_telefono ?? '-' }}</small>
                                    @else
                                        <span class="text-muted">Sin contacto</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-secondary rounded-pill">{{ $cliente->equipos_count }}</span>
                                </td>
                                <td>
                                    @if($cliente->activo)
                                        <span class="badge bg-success-lighten text-success">Activo</span>
                                    @else
                                        <span class="badge bg-danger-lighten text-danger" title="No aparece en el selector de equipos. Sus equipos existentes siguen siendo monitoreados.">Inactivo</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('clientes.edit', $cliente) }}" class="action-icon text-success" title="Editar">
                                        <i class="ri-edit-box-line"></i>
                                    </a>
                                    <form action="{{ route('clientes.destroy', $cliente) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Está seguro de eliminar este cliente?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-link action-icon text-danger p-0 border-0" title="Eliminar" {{ $cliente->equipos_count > 0 ? 'disabled' : '' }}>
                                            <i class="ri-delete-bin-line"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <i class="ri-building-line text-muted display-4"></i>
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


            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link href="{{ asset('assets/vendor/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/vendor/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css') }}" rel="stylesheet" type="text/css" />
@endpush

@push('scripts')
<script src="{{ asset('assets/vendor/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/vendor/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
<script src="{{ asset('assets/vendor/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('assets/vendor/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js') }}"></script>
<script>
$(document).ready(function() {
    $('#tabla-clientes').DataTable({
        responsive: true,
        language: {
            search: 'Buscar:',
            lengthMenu: 'Mostrar _MENU_ registros',
            info: 'Mostrando _START_ a _END_ de _TOTAL_ clientes',
            infoEmpty: 'No hay clientes',
            infoFiltered: '(filtrado de _MAX_ registros)',
            zeroRecords: 'No se encontraron clientes',
            paginate: { first: 'Primero', last: 'Último', next: 'Siguiente', previous: 'Anterior' }
        },
        pageLength: 10,
        order: [[0, 'asc']]
    });
});
</script>
@endpush
