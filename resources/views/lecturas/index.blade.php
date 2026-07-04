@extends('layouts.app')

@section('title', 'Historial de Lecturas - NAVIER Counter System')
@section('page-title', 'Historial de Lecturas')

@section('content')
<div class="table-card">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h6 class="mb-0 fw-bold">Todas las Lecturas Recibidas</h6>
        <form action="{{ route('lecturas.index') }}" method="GET" class="d-flex gap-2">
            <input type="text" name="q" class="form-control form-control-sm" placeholder="Buscar serial o cliente..." value="{{ request('q') }}">
            <button type="submit" class="btn btn-sm btn-primary"><i class="bi bi-search"></i> Buscar</button>
            @if(request('q'))
                <a href="{{ route('lecturas.index') }}" class="btn btn-sm btn-light">Limpiar</a>
            @endif
        </form>
    </div>
    
    <div class="table-responsive">
        <table class="table table-hover mb-0 text-nowrap">
            <thead>
                <tr>
                    <th>Fecha y Hora</th>
                    <th>Equipo (Serial)</th>
                    <th>Cliente</th>
                    <th class="text-end">Total B/N</th>
                    <th class="text-end">Total Color</th>
                    <th class="text-end">Dúplex</th>
                    <th class="text-end">Escaneos</th>
                    <th>Tóner N/C/M/Y</th>
                </tr>
            </thead>
            <tbody>
                @forelse($lecturas as $lectura)
                <tr>
                    <td>
                        {{ $lectura->created_at->format('d/m/Y') }}<br>
                        <small class="text-muted">{{ $lectura->created_at->format('H:i:s') }}</small>
                    </td>
                    <td>
                        <a href="{{ route('equipos.show', $lectura->equipo) }}" class="text-decoration-none fw-bold text-dark">
                            {{ $lectura->equipo->serial }}
                        </a>
                        <br><small class="text-muted">{{ Str::limit($lectura->equipo->modelo, 20) }}</small>
                    </td>
                    <td>
                        @if($lectura->equipo->cliente)
                            {{ Str::limit($lectura->equipo->cliente->razon_social, 25) }}
                        @else
                            <span class="text-muted fst-italic">Sin asignar</span>
                        @endif
                    </td>
                    <td class="text-end fw-bold">{{ number_format($lectura->total_bn) }}</td>
                    <td class="text-end fw-bold text-primary">{{ number_format($lectura->total_color) }}</td>
                    <td class="text-end text-muted">{{ number_format($lectura->duplex) }}</td>
                    <td class="text-end text-muted">{{ number_format($lectura->escaneos) }}</td>
                    <td>
                        @if($lectura->toner_negro !== null)
                            <span class="badge bg-dark" title="Negro">{{ $lectura->toner_negro }}%</span>
                            @if($lectura->toner_cyan !== null)
                            <span class="badge bg-info text-dark" title="Cyan">{{ $lectura->toner_cyan }}%</span>
                            <span class="badge bg-danger" title="Magenta">{{ $lectura->toner_magenta }}%</span>
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
                        <i class="bi bi-reception-0 text-muted" style="font-size: 2.5rem;"></i>
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
    <div class="card-footer bg-white border-top border-light px-3 py-2">
        {{ $lecturas->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>
@endsection
