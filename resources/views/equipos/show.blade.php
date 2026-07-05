@extends('layouts.app')

@section('title', 'Detalle de Equipo - ' . $equipo->serial)
@section('page-title', 'Detalle de Equipo')

@section('content')
<div class="row">
    <!-- Header Card -->
    <div class="col-12">
        <div class="card bg-primary text-white">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar-lg bg-white rounded-circle d-flex align-items-center justify-content-center">
                        <i class="ri-printer-fill fs-24 text-primary"></i>
                    </div>
                    <div>
                        <h4 class="mb-0 fw-bold text-white">{{ $equipo->serial }}</h4>
                        <p class="mb-0 text-white-50">{{ $equipo->modelo }}</p>
                    </div>
                </div>
                <div class="text-end">
                    @if($equipo->activo)
                        <span class="badge bg-success rounded-pill px-2 py-1 fs-6 mb-2">Activo</span>
                    @else
                        <span class="badge bg-danger rounded-pill px-2 py-1 fs-6 mb-2">Inactivo</span>
                    @endif
                    <br>
                    <a href="{{ route('equipos.edit', $equipo) }}" class="btn btn-sm btn-light text-primary fw-bold">
                        <i class="ri-edit-box-line"></i> Editar
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Info & Toner -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h4 class="header-title">Información General</h4>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li class="mb-3">
                        <small class="text-muted d-block">Cliente Asignado</small>
                        @if($equipo->cliente)
                            <a href="{{ route('clientes.edit', $equipo->cliente) }}" class="text-body fw-bold">
                                {{ $equipo->cliente->razon_social }}
                            </a>
                        @else
                            <span class="text-muted fst-italic">Sin asignar</span>
                        @endif
                    </li>
                    <li class="mb-3">
                        <small class="text-muted d-block">IP Local (Red del cliente)</small>
                        <span class="font-monospace fw-semibold">{{ $equipo->ip_local ?? 'No especificada' }}</span>
                    </li>
                    <li class="mb-3">
                        <small class="text-muted d-block">Fecha de Instalación</small>
                        <span class="fw-semibold">{{ $equipo->fecha_instalacion ? \Carbon\Carbon::parse($equipo->fecha_instalacion)->format('d/m/Y') : 'No especificada' }}</span>
                    </li>
                    <li class="mt-4 pt-3 border-top border-light">
                        <small class="text-muted d-block mb-2">Token de Agente (API)</small>
                        <div class="input-group input-group-sm">
                            <input type="password" class="form-control font-monospace bg-light" value="{{ $equipo->agente_token }}" id="tokenInput" readonly>
                            <button class="btn btn-primary" type="button" onclick="const i = document.getElementById('tokenInput'); i.type = i.type === 'password' ? 'text' : 'password';">
                                <i class="ri-eye-line"></i>
                            </button>
                        </div>
                    </li>
                </ul>
            </div>
        </div>

        @if($equipo->alertas->count() > 0)
        <div class="card border-warning border">
            <div class="card-header bg-warning-lighten">
                <h4 class="header-title text-warning"><i class="ri-error-warning-fill me-1"></i> Alertas Activas</h4>
            </div>
            <div class="card-body">
                @foreach($equipo->alertas as $alerta)
                <div class="alert {{ $alerta->tipo === 'toner_bajo' ? 'alert-warning' : 'alert-danger' }} mb-2">
                    <small class="fw-bold">{{ $alerta->mensaje }}</small>
                    <br><small class="text-muted">{{ $alerta->created_at->diffForHumans() }}</small>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        @if($lecturas->first())
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="header-title">Niveles de Tóner Actual</h4>
                <small class="text-muted">{{ $lecturas->first()->created_at->diffForHumans() }}</small>
            </div>
            <div class="card-body">
                @php $latest = $lecturas->first(); @endphp
                
                @if($latest->toner_negro !== null)
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <small class="fw-bold text-dark">Negro</small>
                        <small class="fw-bold text-dark">{{ $latest->toner_negro }}%</small>
                    </div>
                    <div class="progress progress-sm">
                        <div class="progress-bar bg-dark" role="progressbar" style="width: {{ $latest->toner_negro }}%" aria-valuenow="{{ $latest->toner_negro }}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
                @endif
                
                @if($latest->toner_cyan !== null)
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <small class="fw-bold text-info">Cyan</small>
                        <small class="fw-bold text-info">{{ $latest->toner_cyan }}%</small>
                    </div>
                    <div class="progress progress-sm">
                        <div class="progress-bar bg-info" role="progressbar" style="width: {{ $latest->toner_cyan }}%" aria-valuenow="{{ $latest->toner_cyan }}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <small class="fw-bold" style="color: #ec4899;">Magenta</small>
                        <small class="fw-bold" style="color: #ec4899;">{{ $latest->toner_magenta }}%</small>
                    </div>
                    <div class="progress progress-sm">
                        <div class="progress-bar" role="progressbar" style="background-color: #ec4899; width: {{ $latest->toner_magenta }}%" aria-valuenow="{{ $latest->toner_magenta }}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <small class="fw-bold text-warning">Amarillo</small>
                        <small class="fw-bold text-warning">{{ $latest->toner_amarillo }}%</small>
                    </div>
                    <div class="progress progress-sm">
                        <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $latest->toner_amarillo }}%" aria-valuenow="{{ $latest->toner_amarillo }}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
                @endif
                
                @if($latest->toner_negro === null)
                <p class="text-center text-muted mb-0">El equipo no reporta niveles de tóner.</p>
                @endif
            </div>
        </div>
        @endif
    </div>

    <!-- History Chart & Table -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h4 class="header-title">Evolución de Contadores</h4>
            </div>
            <div class="card-body">
                <div style="height: 320px;">
                    @if($lecturas->count() > 1)
                        <canvas id="countersChart"></canvas>
                    @else
                        <div class="h-100 d-flex flex-column align-items-center justify-content-center">
                            <i class="ri-line-chart-line text-muted display-4 mb-2"></i>
                            <p class="text-muted">Se necesitan al menos 2 lecturas para generar el gráfico.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h4 class="header-title">Historial de Lecturas (Últimas 30)</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                    <table id="tabla-historial" class="table table-striped dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>Fecha y Hora</th>
                                <th>Total General</th>
                                <th>Total B/N</th>
                                <th>Total Color</th>
                                <th>Escaneos</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($lecturas as $lectura)
                            <tr>
                                <td>{{ $lectura->created_at->format('d/m/Y H:i:s') }}</td>
                                <td>{{ number_format($lectura->total_global) }}</td>
                                <td>{{ number_format($lectura->total_bn) }}</td>
                                <td>{{ number_format($lectura->total_color) }}</td>
                                <td>{{ number_format($lectura->escaneos) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">
                                    No se han recibido lecturas de este equipo todavía.
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
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<!-- DataTables -->
<script src="{{ asset('assets/vendor/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/vendor/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
<script src="{{ asset('assets/vendor/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('assets/vendor/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js') }}"></script>
<script src="{{ asset('assets/vendor/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('assets/vendor/datatables.net-buttons-bs5/js/buttons.bootstrap5.min.js') }}"></script>
<script src="{{ asset('assets/vendor/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>

<script>
$(document).ready(function() {
    var table = $('#tabla-historial').DataTable({
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
            info: 'Mostrando _START_ a _END_ de _TOTAL_ lecturas',
            infoEmpty: 'No hay lecturas registradas',
            infoFiltered: '(filtrado de _MAX_ registros)',
            zeroRecords: 'No se encontraron resultados',
            paginate: { first: 'Primero', last: 'Último', next: 'Siguiente', previous: 'Anterior' }
        },
        pageLength: 10,
        order: [[0, 'desc']]
    });
    
    table.buttons().container().appendTo('#tabla-historial_wrapper .col-md-6:eq(0)');
});
</script>
@if($lecturas->count() > 1)
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById('countersChart').getContext('2d');
        
        const labels = {!! json_encode($lecturas->reverse()->map(fn($l) => $l->created_at->format('d/m H:i'))->values()) !!};
        const dataBn = {!! json_encode($lecturas->reverse()->pluck('total_bn')->values()) !!};
        const dataColor = {!! json_encode($lecturas->reverse()->pluck('total_color')->values()) !!};

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Total B/N',
                        data: dataBn,
                        borderColor: '#313a46',
                        backgroundColor: 'rgba(49, 58, 70, 0.1)',
                        tension: 0.3,
                        fill: true
                    },
                    {
                        label: 'Total Color',
                        data: dataColor,
                        borderColor: '#3b82f6',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        tension: 0.3,
                        fill: true
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'top' },
                },
                scales: {
                    y: { beginAtZero: false }
                }
            }
        });
    });
</script>
@endif
@endpush
