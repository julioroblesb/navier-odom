@extends('layouts.app')

@section('title', 'Estado Actual de Contadores - NAVIER')
@section('page-title', 'Estado Actual de Contadores')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="header-title">Última Lectura por Equipo</h4>
            </div>
            
            <div class="card-body pt-0">
                <div class="table-responsive">
                    <table id="tabla-lecturas" class="table table-centered table-nowrap table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Equipo (Serial)</th>
                                <th>Cliente</th>
                                <th>Última Lectura</th>
                                <th class="text-end">Total General</th>
                                <th class="text-end">Total B/N</th>
                                <th class="text-end">Total Color</th>
                                <th class="text-end">Escaneos</th>
                                <th>Tóner N/C/M/Y</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($equipos as $equipo)
                            @php $lectura = $equipo->ultimaLectura; @endphp
                            <tr>
                                <td>
                                    <a href="{{ route('equipos.show', $equipo) }}" class="text-body fw-bold">
                                        {{ $equipo->serial }}
                                    </a>
                                    <br><small class="text-muted">{{ Str::limit($equipo->modelo, 20) }}</small>
                                </td>
                                <td>
                                    @if($equipo->cliente)
                                        <span class="text-body">{{ Str::limit($equipo->cliente->razon_social, 25) }}</span>
                                    @else
                                        <span class="text-muted fst-italic">Sin asignar</span>
                                    @endif
                                </td>
                                <td>
                                    @if($lectura)
                                        <span class="fw-semibold">{{ $lectura->created_at->format('d/m/Y') }}</span><br>
                                        <small class="text-muted">{{ $lectura->created_at->format('H:i') }} · {{ $lectura->created_at->diffForHumans() }}</small>
                                    @endif
                                </td>
                                <td class="text-end fw-bold"><span class="badge bg-secondary rounded-pill">{{ number_format($lectura->total_global ?? 0) }}</span></td>
                                <td class="text-end fw-bold"><span class="badge bg-dark rounded-pill">{{ number_format($lectura->total_bn ?? 0) }}</span></td>
                                <td class="text-end fw-bold"><span class="badge bg-primary rounded-pill">{{ number_format($lectura->total_color ?? 0) }}</span></td>
                                <td class="text-end text-muted">{{ number_format($lectura->escaneos ?? 0) }}</td>
                                <td>
                                    @if($lectura && $lectura->toner_negro !== null)
                                        <span class="badge bg-dark" title="Negro">{{ $lectura->toner_negro }}%</span>
                                        @if($lectura->toner_cyan !== null)
                                        <span class="badge bg-info text-dark" title="Cyan">{{ $lectura->toner_cyan }}%</span>
                                        <span class="badge text-white" style="background-color: #ec4899;" title="Magenta">{{ $lectura->toner_magenta }}%</span>
                                        <span class="badge bg-warning text-dark" title="Amarillo">{{ $lectura->toner_amarillo }}%</span>
                                        @endif
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('equipos.show', $equipo) }}" class="btn btn-sm btn-light" title="Ver historial completo">
                                        <i class="ri-history-line"></i> Historial
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center py-5">
                                    <i class="ri-bar-chart-box-line text-muted display-4"></i>
                                    <p class="text-muted mt-2 mb-0">No se encontraron lecturas en el historial.</p>
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
    $('#tabla-lecturas').DataTable({
        responsive: true,
        language: {
            search: 'Buscar:',
            lengthMenu: 'Mostrar _MENU_ registros',
            info: 'Mostrando _START_ a _END_ de _TOTAL_ equipos',
            infoEmpty: 'No hay lecturas registradas',
            infoFiltered: '(filtrado de _MAX_ registros)',
            zeroRecords: 'No se encontraron resultados',
            paginate: { first: 'Primero', last: 'Último', next: 'Siguiente', previous: 'Anterior' }
        },
        pageLength: 15,
        order: [[2, 'desc']]
    });
});
</script>
@endpush
