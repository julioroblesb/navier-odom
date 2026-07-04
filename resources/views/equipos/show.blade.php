@extends('layouts.app')

@section('title', 'Detalle de Equipo - ' . $equipo->serial)
@section('page-title', 'Detalle de Equipo')

@section('content')
<div class="row g-3">
    <!-- Header Card -->
    <div class="col-12">
        <div class="table-card p-4 d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon" style="background: rgba(59,130,246,0.1); color: var(--accent); width: 64px; height: 64px; font-size: 2rem;">
                    <i class="bi bi-printer-fill"></i>
                </div>
                <div>
                    <h4 class="mb-0 fw-bold">{{ $equipo->serial }}</h4>
                    <p class="text-muted mb-0">{{ $equipo->modelo }}</p>
                </div>
            </div>
            <div class="text-end">
                @if($equipo->activo)
                    <span class="badge-status badge-online fs-6 mb-2 d-inline-block">Equipo Activo</span>
                @else
                    <span class="badge-status badge-offline fs-6 mb-2 d-inline-block">Equipo Inactivo</span>
                @endif
                <br>
                <a href="{{ route('equipos.edit', $equipo) }}" class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-pencil"></i> Editar
                </a>
            </div>
        </div>
    </div>

    <!-- Info & Toner -->
    <div class="col-lg-4">
        <div class="table-card mb-3">
            <div class="card-header">
                <h6 class="mb-0 fw-bold">Información General</h6>
            </div>
            <div class="p-3">
                <ul class="list-unstyled mb-0">
                    <li class="mb-2">
                        <small class="text-muted d-block">Cliente Asignado</small>
                        @if($equipo->cliente)
                            <a href="{{ route('clientes.edit', $equipo->cliente) }}" class="text-decoration-none fw-bold text-dark">
                                {{ $equipo->cliente->razon_social }}
                            </a>
                        @else
                            <span class="text-muted fst-italic">Sin asignar</span>
                        @endif
                    </li>
                    <li class="mb-2">
                        <small class="text-muted d-block">IP Local (Red del cliente)</small>
                        <span class="font-monospace">{{ $equipo->ip_local ?? 'No especificada' }}</span>
                    </li>
                    <li class="mb-2">
                        <small class="text-muted d-block">Fecha de Instalación</small>
                        <span>{{ $equipo->fecha_instalacion ? \Carbon\Carbon::parse($equipo->fecha_instalacion)->format('d/m/Y') : 'No especificada' }}</span>
                    </li>
                    <li class="mt-4 pt-3 border-top">
                        <small class="text-muted d-block mb-1">Token de Agente (API)</small>
                        <div class="input-group input-group-sm">
                            <input type="password" class="form-control font-monospace" value="{{ $equipo->agente_token }}" id="tokenInput" readonly>
                            <button class="btn btn-outline-secondary" type="button" onclick="const i = document.getElementById('tokenInput'); i.type = i.type === 'password' ? 'text' : 'password';">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                    </li>
                </ul>
            </div>
        </div>

        @if($equipo->alertas->count() > 0)
        <div class="table-card mb-3 border-warning">
            <div class="card-header bg-warning bg-opacity-10 border-warning">
                <h6 class="mb-0 fw-bold text-warning-emphasis"><i class="bi bi-exclamation-triangle-fill me-1"></i> Alertas Activas</h6>
            </div>
            <div class="p-3">
                @foreach($equipo->alertas as $alerta)
                <div class="alert-item alert-{{ $alerta->tipo === 'toner_bajo' ? 'toner' : 'offline' }} mb-2 border-warning">
                    <small class="text-dark">{{ $alerta->mensaje }}</small>
                    <br><small class="text-muted">{{ $alerta->created_at->diffForHumans() }}</small>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        @if($lecturas->first())
        <div class="table-card">
            <div class="card-header">
                <h6 class="mb-0 fw-bold">Niveles de Tóner Actual</h6>
                <small class="text-muted">Último reporte: {{ $lecturas->first()->created_at->diffForHumans() }}</small>
            </div>
            <div class="p-4">
                @php $latest = $lecturas->first(); @endphp
                
                @if($latest->toner_negro !== null)
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <small class="fw-bold">Negro</small>
                        <small class="fw-bold">{{ $latest->toner_negro }}%</small>
                    </div>
                    <div class="toner-bar toner-black" style="height: 12px;">
                        <div class="toner-fill" style="width: {{ $latest->toner_negro }}%"></div>
                    </div>
                </div>
                @endif
                
                @if($latest->toner_cyan !== null)
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <small class="fw-bold text-info">Cyan</small>
                        <small class="fw-bold">{{ $latest->toner_cyan }}%</small>
                    </div>
                    <div class="toner-bar toner-cyan" style="height: 12px;">
                        <div class="toner-fill" style="width: {{ $latest->toner_cyan }}%"></div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <small class="fw-bold text-danger">Magenta</small>
                        <small class="fw-bold">{{ $latest->toner_magenta }}%</small>
                    </div>
                    <div class="toner-bar toner-magenta" style="height: 12px;">
                        <div class="toner-fill" style="width: {{ $latest->toner_magenta }}%"></div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <small class="fw-bold text-warning">Amarillo</small>
                        <small class="fw-bold">{{ $latest->toner_amarillo }}%</small>
                    </div>
                    <div class="toner-bar toner-yellow" style="height: 12px;">
                        <div class="toner-fill" style="width: {{ $latest->toner_amarillo }}%"></div>
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
        <div class="table-card mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold">Evolución de Contadores</h6>
            </div>
            <div class="p-3" style="height: 300px;">
                @if($lecturas->count() > 1)
                    <canvas id="countersChart"></canvas>
                @else
                    <div class="h-100 d-flex flex-column align-items-center justify-content-center">
                        <i class="bi bi-graph-up text-muted mb-2" style="font-size: 2rem;"></i>
                        <p class="text-muted">Se necesitan al menos 2 lecturas para generar el gráfico.</p>
                    </div>
                @endif
            </div>
        </div>

        <div class="table-card">
            <div class="card-header">
                <h6 class="mb-0 fw-bold">Historial de Lecturas (Últimas 30)</h6>
            </div>
            <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                <table class="table table-hover table-sm mb-0">
                    <thead class="sticky-top bg-white">
                        <tr>
                            <th>Fecha y Hora</th>
                            <th>Total B/N</th>
                            <th>Total Color</th>
                            <th>Dúplex</th>
                            <th>Escaneos</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($lecturas as $lectura)
                        <tr>
                            <td>{{ $lectura->created_at->format('d/m/Y H:i:s') }}</td>
                            <td><strong>{{ number_format($lectura->total_bn) }}</strong></td>
                            <td><strong>{{ number_format($lectura->total_color) }}</strong></td>
                            <td>{{ number_format($lectura->duplex) }}</td>
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
@endsection

@push('scripts')
@if($lecturas->count() > 1)
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById('countersChart').getContext('2d');
        
        // Data prep (reverse so oldest is left, newest right)
        const labels = {!! json_encode($lecturas->reverse()->map(fn($l) => $l->created_at->format('d/m H:i'))->values()) !!};
        const dataBn = {!! json_encode($lecturas->reverse()->pluck('total_bn')->values()) !!};
        const dataColor = {!! json_encode($lecturas->reverse()->pluck('total_color')->values()) !!};
        const dataScans = {!! json_encode($lecturas->reverse()->pluck('escaneos')->values()) !!};

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Total B/N',
                        data: dataBn,
                        borderColor: '#1a1d23',
                        backgroundColor: 'rgba(26,29,35,0.1)',
                        tension: 0.3,
                        fill: true
                    },
                    {
                        label: 'Total Color',
                        data: dataColor,
                        borderColor: '#3b82f6',
                        backgroundColor: 'rgba(59,130,246,0.1)',
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
