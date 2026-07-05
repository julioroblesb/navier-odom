@extends('layouts.app')

@section('title', 'Dashboard - NAVIER')
@section('page-title', 'Dashboard General')

@section('content')
<div class="row">
    <!-- Equipos Activos -->
    <div class="col-sm-4">
        <div class="card widget-flat">
            <div class="card-body">
                <div class="float-end">
                    <i class="ri-printer-line widget-icon bg-success-lighten text-success"></i>
                </div>
                <h5 class="text-muted fw-normal mt-0" title="Equipos Activos">Equipos Activos</h5>
                <h3 class="mt-3 mb-3">{{ $stats['equipos'] ?? 0 }}</h3>
                <p class="mb-0 text-muted">
                    <span class="text-success me-2"><i class="ri-checkbox-circle-line"></i> En monitoreo</span>
                </p>
            </div> <!-- end card-body-->
        </div> <!-- end card-->
    </div> <!-- end col-->

    <!-- Clientes -->
    <div class="col-sm-4">
        <div class="card widget-flat">
            <div class="card-body">
                <div class="float-end">
                    <i class="ri-building-line widget-icon bg-primary-lighten text-primary"></i>
                </div>
                <h5 class="text-muted fw-normal mt-0" title="Clientes Registrados">Clientes</h5>
                <h3 class="mt-3 mb-3">{{ $stats['clientes'] ?? 0 }}</h3>
                <p class="mb-0 text-muted">
                    <span class="text-primary me-2"><i class="ri-user-line"></i> Total registrados</span>
                </p>
            </div> <!-- end card-body-->
        </div> <!-- end card-->
    </div> <!-- end col-->

    <!-- Lecturas del Mes -->
    <div class="col-sm-4">
        <div class="card widget-flat">
            <div class="card-body">
                <div class="float-end">
                    <i class="ri-bar-chart-box-line widget-icon bg-info-lighten text-info"></i>
                </div>
                <h5 class="text-muted fw-normal mt-0" title="Lecturas del mes">Lecturas (Mes)</h5>
                <h3 class="mt-3 mb-3">{{ $stats['lecturas_mes'] ?? 0 }}</h3>
                <p class="mb-0 text-muted">
                    <span class="text-info me-2"><i class="ri-calendar-event-line"></i> Registros de este mes</span>
                </p>
            </div> <!-- end card-body-->
        </div> <!-- end card-->
    </div> <!-- end col-->
</div>

<div class="row">
    <!-- Últimas Lecturas -->
    <div class="col-xl-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="header-title">Últimas Lecturas Registradas</h4>
                <a href="{{ route('lecturas.index') }}" class="btn btn-sm btn-light">Ver Todas</a>
            </div>
            <div class="card-body pt-0">
                <div class="table-responsive">
                    <table class="table table-striped dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Equipo</th>
                                <th>Cliente</th>
                                <th>B/N</th>
                                <th>Color</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($ultimasLecturas ?? [] as $lectura)
                            <tr>
                                <td>{{ $lectura->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <a href="{{ route('equipos.show', $lectura->equipo_id) }}" class="text-body fw-semibold">{{ $lectura->equipo->modelo }}</a>
                                    <small class="d-block text-muted">{{ $lectura->equipo->numero_serie }}</small>
                                </td>
                                <td>{{ $lectura->equipo->cliente->razon_social ?? 'Sin cliente' }}</td>
                                <td><span class="badge bg-dark rounded-pill">{{ number_format($lectura->total_bn) }}</span></td>
                                <td><span class="badge bg-primary rounded-pill">{{ number_format($lectura->total_color) }}</span></td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">No hay lecturas recientes.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Alertas Activas -->
    <div class="col-xl-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="header-title text-danger"><i class="ri-error-warning-line"></i> Equipos con Alertas</h4>
            </div>
            <div class="card-body pt-0">
                <div class="table-responsive">
                    <table class="table table-borderless table-centered table-nowrap mb-0">
                        <tbody>
                            @forelse($alertas ?? [] as $alerta)
                            <tr>
                                <td>
                                    <h5 class="font-14 my-1 fw-normal">
                                        <a href="{{ route('equipos.show', $alerta->equipo_id) }}" class="text-body fw-bold">{{ $alerta->equipo->modelo }}</a>
                                    </h5>
                                    <span class="text-muted font-13">{{ $alerta->equipo->cliente->razon_social ?? 'N/A' }}</span>
                                </td>
                                <td>
                                    @if($alerta->tipo == 'OFFLINE')
                                        <span class="badge badge-danger-lighten">Desconectado</span>
                                    @elseif($alerta->tipo == 'TONER_LOW')
                                        <span class="badge badge-warning-lighten">Tóner Bajo</span>
                                    @else
                                        <span class="badge badge-info-lighten">{{ $alerta->tipo }}</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="2" class="text-center text-muted py-3">Todo en orden. No hay alertas.</td>
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
