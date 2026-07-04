@extends('layouts.app')

@section('title', 'Dashboard - NAVIER Counter System')
@section('page-title', 'Dashboard')

@section('content')
<!-- Stats Row -->
<div class="row g-3 mb-4">
    <div class="col-md-3 col-sm-6">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">EQUIPOS ACTIVOS</div>
                    <div class="stat-value">{{ $totalEquipos }}</div>
                </div>
                <div class="stat-icon" style="background: rgba(59,130,246,0.1); color: var(--accent);">
                    <i class="bi bi-printer-fill"></i>
                </div>
            </div>
            <small class="text-muted">de {{ $totalEquiposRegistrados }} registrados</small>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">CLIENTES</div>
                    <div class="stat-value">{{ $totalClientes }}</div>
                </div>
                <div class="stat-icon" style="background: rgba(16,185,129,0.1); color: var(--success);">
                    <i class="bi bi-building"></i>
                </div>
            </div>
            <small class="text-muted">con equipos asignados</small>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">LECTURAS HOY</div>
                    <div class="stat-value">{{ $lecturasHoy }}</div>
                </div>
                <div class="stat-icon" style="background: rgba(139,92,246,0.1); color: #8b5cf6;">
                    <i class="bi bi-bar-chart-fill"></i>
                </div>
            </div>
            <small class="text-muted">última: {{ $ultimaLectura ?? 'Sin datos' }}</small>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-label">ALERTAS ACTIVAS</div>
                    <div class="stat-value {{ $totalAlertas > 0 ? 'text-danger' : 'text-success' }}">{{ $totalAlertas }}</div>
                </div>
                <div class="stat-icon" style="background: rgba(239,68,68,0.1); color: var(--danger);">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                </div>
            </div>
            <small class="text-muted">{{ $alertasToner }} tóner bajo</small>
        </div>
    </div>
</div>

<div class="row g-3">
    <!-- Equipment Table -->
    <div class="col-lg-8">
        <div class="table-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold">Últimas Lecturas por Equipo</h6>
                <a href="{{ route('equipos.index') }}" class="btn btn-sm btn-outline-primary">Ver todos</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Equipo</th>
                            <th>Cliente</th>
                            <th>Total B/N</th>
                            <th>Total Color</th>
                            <th>Tóner</th>
                            <th>Estado</th>
                            <th>Última Lectura</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($equiposRecientes as $equipo)
                        <tr>
                            <td>
                                <a href="{{ route('equipos.show', $equipo) }}" class="text-decoration-none">
                                    <strong>{{ $equipo->serial }}</strong>
                                    <br><small class="text-muted">{{ $equipo->modelo }}</small>
                                </a>
                            </td>
                            <td>
                                @if($equipo->cliente)
                                    {{ Str::limit($equipo->cliente->razon_social, 25) }}
                                @else
                                    <span class="text-muted">Sin asignar</span>
                                @endif
                            </td>
                            <td>
                                @if($equipo->ultimaLectura)
                                    <strong>{{ number_format($equipo->ultimaLectura->total_bn) }}</strong>
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                @if($equipo->ultimaLectura)
                                    <strong>{{ number_format($equipo->ultimaLectura->total_color) }}</strong>
                                @else
                                    -
                                @endif
                            </td>
                            <td style="min-width: 100px;">
                                @if($equipo->ultimaLectura && $equipo->ultimaLectura->toner_negro !== null)
                                    <div class="toner-bar toner-black mb-1" title="Negro: {{ $equipo->ultimaLectura->toner_negro }}%">
                                        <div class="toner-fill" style="width: {{ $equipo->ultimaLectura->toner_negro }}%"></div>
                                    </div>
                                    @if($equipo->ultimaLectura->toner_cyan !== null)
                                    <div class="toner-bar toner-cyan mb-1" title="Cyan: {{ $equipo->ultimaLectura->toner_cyan }}%">
                                        <div class="toner-fill" style="width: {{ $equipo->ultimaLectura->toner_cyan }}%"></div>
                                    </div>
                                    <div class="toner-bar toner-magenta mb-1" title="Magenta: {{ $equipo->ultimaLectura->toner_magenta }}%">
                                        <div class="toner-fill" style="width: {{ $equipo->ultimaLectura->toner_magenta }}%"></div>
                                    </div>
                                    <div class="toner-bar toner-yellow" title="Amarillo: {{ $equipo->ultimaLectura->toner_amarillo }}%">
                                        <div class="toner-fill" style="width: {{ $equipo->ultimaLectura->toner_amarillo }}%"></div>
                                    </div>
                                    @endif
                                @else
                                    <small class="text-muted">Sin datos</small>
                                @endif
                            </td>
                            <td>
                                @php
                                    $lastReport = $equipo->ultimaLectura ? $equipo->ultimaLectura->created_at : null;
                                    $isOnline = $lastReport && $lastReport->diffInHours(now()) < 48;
                                @endphp
                                @if($isOnline)
                                    <span class="badge-status badge-online">
                                        <i class="bi bi-circle-fill" style="font-size: 0.5rem;"></i> Activo
                                    </span>
                                @elseif($lastReport)
                                    <span class="badge-status badge-offline">
                                        <i class="bi bi-circle-fill" style="font-size: 0.5rem;"></i> Sin reportar
                                    </span>
                                @else
                                    <span class="badge-status badge-warning">
                                        <i class="bi bi-circle-fill" style="font-size: 0.5rem;"></i> Nuevo
                                    </span>
                                @endif
                            </td>
                            <td>
                                @if($equipo->ultimaLectura)
                                    <small>{{ $equipo->ultimaLectura->created_at->diffForHumans() }}</small>
                                @else
                                    <small class="text-muted">Nunca</small>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <i class="bi bi-printer text-muted" style="font-size: 2rem;"></i>
                                <p class="text-muted mt-2 mb-0">No hay equipos registrados aún.</p>
                                <a href="{{ route('equipos.create') }}" class="btn btn-primary btn-sm mt-2">
                                    <i class="bi bi-plus-lg"></i> Agregar Equipo
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Right Column: Alerts + Quick Stats -->
    <div class="col-lg-4">
        <!-- Active Alerts -->
        <div class="table-card mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold">
                    <i class="bi bi-exclamation-triangle-fill text-warning me-1"></i> Alertas Recientes
                </h6>
                <a href="{{ route('alertas.index') }}" class="btn btn-sm btn-outline-warning">Ver todas</a>
            </div>
            <div class="p-3">
                @forelse($alertasRecientes as $alerta)
                <div class="alert-item alert-{{ $alerta->tipo === 'toner_bajo' ? 'toner' : 'offline' }}">
                    <small class="fw-bold">
                        @if($alerta->tipo === 'toner_bajo')
                            <i class="bi bi-droplet-half text-warning"></i>
                        @else
                            <i class="bi bi-wifi-off text-danger"></i>
                        @endif
                        {{ $alerta->equipo->serial ?? 'Desconocido' }}
                    </small>
                    <br>
                    <small class="text-muted">{{ $alerta->mensaje }}</small>
                    <br>
                    <small class="text-muted">{{ $alerta->created_at->diffForHumans() }}</small>
                </div>
                @empty
                <div class="text-center py-3">
                    <i class="bi bi-check-circle text-success" style="font-size: 1.5rem;"></i>
                    <p class="text-muted mb-0 mt-1" style="font-size: 0.85rem;">Sin alertas pendientes</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- License Info -->
        <div class="table-card">
            <div class="card-header">
                <h6 class="mb-0 fw-bold"><i class="bi bi-key-fill text-primary me-1"></i> Licencia</h6>
            </div>
            <div class="p-3">
                @if($licencia)
                <div class="d-flex justify-content-between mb-2">
                    <small class="text-muted">Estado</small>
                    <span class="badge-status badge-online">Activa</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <small class="text-muted">Equipos</small>
                    <small><strong>{{ $totalEquiposRegistrados }}</strong> / {{ $licencia->max_equipos }}</small>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <small class="text-muted">Vence</small>
                    <small>{{ \Carbon\Carbon::parse($licencia->fecha_vencimiento)->format('d/m/Y') }}</small>
                </div>
                <div class="d-flex justify-content-between">
                    <small class="text-muted">Días restantes</small>
                    <small class="fw-bold {{ $licencia->diasRestantes < 30 ? 'text-danger' : 'text-success' }}">
                        {{ $licencia->diasRestantes ?? '—' }}
                    </small>
                </div>
                @else
                <div class="text-center py-2">
                    <i class="bi bi-key text-warning" style="font-size: 1.5rem;"></i>
                    <p class="text-muted mb-2 mt-1" style="font-size: 0.85rem;">Sin licencia activada</p>
                    <a href="{{ route('licencia.index') }}" class="btn btn-primary btn-sm">Activar Licencia</a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
