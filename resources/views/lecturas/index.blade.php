@extends('layouts.app')

@section('title', 'Historial de Lecturas - NAVIER')
@section('page-title', 'Historial de Lecturas')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h4 class="header-title">Todas las Lecturas Recibidas</h4>
                <form action="{{ route('lecturas.index') }}" method="GET" class="d-flex gap-2">
                    <div class="input-group">
                        <input type="text" name="q" class="form-control form-control-sm" placeholder="Buscar serial o cliente..." value="{{ request('q') }}">
                        <button class="btn btn-primary btn-sm" type="submit"><i class="ri-search-line"></i></button>
                    </div>
                    @if(request('q'))
                        <a href="{{ route('lecturas.index') }}" class="btn btn-sm btn-light">Limpiar</a>
                    @endif
                </form>
            </div>
            
            <div class="card-body pt-0">
                <div class="table-responsive">
                    <table class="table table-centered table-nowrap table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Fecha y Hora</th>
                                <th>Equipo (Serial)</th>
                                <th>Cliente</th>
                                <th class="text-end">Total General</th>
                                <th class="text-end">Total B/N</th>
                                <th class="text-end">Total Color</th>
                                <th class="text-end">Escaneos</th>
                                <th>Tóner N/C/M/Y</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($lecturas as $lectura)
                            <tr>
                                <td>
                                    <span class="fw-semibold">{{ $lectura->created_at->format('d/m/Y') }}</span><br>
                                    <small class="text-muted">{{ $lectura->created_at->format('H:i:s') }}</small>
                                </td>
                                <td>
                                    <a href="{{ route('equipos.show', $lectura->equipo) }}" class="text-body fw-bold">
                                        {{ $lectura->equipo->serial }}
                                    </a>
                                    <br><small class="text-muted">{{ Str::limit($lectura->equipo->modelo, 20) }}</small>
                                </td>
                                <td>
                                    @if($lectura->equipo->cliente)
                                        <span class="text-body">{{ Str::limit($lectura->equipo->cliente->razon_social, 25) }}</span>
                                    @else
                                        <span class="text-muted fst-italic">Sin asignar</span>
                                    @endif
                                </td>
                                <td class="text-end fw-bold"><span class="badge bg-secondary rounded-pill">{{ number_format($lectura->total_global) }}</span></td>
                                <td class="text-end fw-bold"><span class="badge bg-dark rounded-pill">{{ number_format($lectura->total_bn) }}</span></td>
                                <td class="text-end fw-bold"><span class="badge bg-primary rounded-pill">{{ number_format($lectura->total_color) }}</span></td>
                                <td class="text-end text-muted">{{ number_format($lectura->escaneos) }}</td>
                                <td>
                                    @if($lectura->toner_negro !== null)
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
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <i class="ri-bar-chart-box-line text-muted display-4"></i>
                                    <p class="text-muted mt-2 mb-0">No se encontraron lecturas en el historial.</p>
                                    @if(request('q'))
                                        <a href="{{ route('lecturas.index') }}" class="btn btn-link btn-sm mt-2">Ver todas las lecturas</a>
                                    @endif
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($lecturas->hasPages())
                <div class="mt-3">
                    {{ $lecturas->links('pagination::bootstrap-5') }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
