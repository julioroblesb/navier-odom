@extends('layouts.app')

@section('title', 'Centro de Alertas - NAVIER')
@section('page-title', 'Centro de Alertas')

@section('content')
<div class="row">
    <!-- Active Alerts -->
    <div class="col-lg-8">
        <h5 class="fw-bold mb-3 d-flex align-items-center gap-2">
            <i class="ri-error-warning-fill text-warning"></i> Alertas Pendientes
            <span class="badge bg-danger rounded-pill">{{ $alertas->total() }}</span>
        </h5>

        @forelse($alertas as $alerta)
            <div class="card mb-3 border-start border-4 
                {{ $alerta->nivel_severidad == 3 ? 'border-danger' : 
                   ($alerta->nivel_severidad == 2 ? 'border-warning' : 'border-info') }}">
                
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="d-flex align-items-center gap-2 mb-2">
                                @if($alerta->tipo === 'toner_bajo')
                                    <span class="badge bg-dark"><i class="ri-drop-fill"></i> Tóner Bajo</span>
                                @elseif($alerta->tipo === 'offline')
                                    <span class="badge bg-danger"><i class="ri-wifi-off-line"></i> Offline</span>
                                @else
                                    <span class="badge bg-secondary"><i class="ri-information-line"></i> Info</span>
                                @endif
                                <small class="text-muted">{{ $alerta->created_at->diffForHumans() }}</small>
                            </div>
                            <h5 class="fw-bold mb-1">{{ $alerta->mensaje }}</h5>
                            
                            <p class="mb-0 text-muted">
                                Equipo: <a href="{{ route('equipos.show', $alerta->equipo) }}" class="fw-bold text-body">{{ $alerta->equipo->serial }}</a>
                                @if($alerta->equipo->cliente)
                                 — Cliente: <strong>{{ $alerta->equipo->cliente->razon_social }}</strong>
                                @endif
                            </p>
                        </div>
                        
                        <form action="{{ route('alertas.resolve', $alerta) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-sm btn-success fw-bold" title="Marcar como resuelta">
                                <i class="ri-check-double-line"></i> Resuelta
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="ri-checkbox-circle-fill text-success display-4 mb-3"></i>
                    <h4 class="fw-bold text-success">¡Todo está perfecto!</h4>
                    <p class="text-muted mb-0">No hay alertas pendientes por resolver.</p>
                </div>
            </div>
        @endforelse

        @if($alertas->hasPages())
            <div class="mt-3">
                {{ $alertas->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>

    <!-- Resolved Alerts (Sidebar) -->
    <div class="col-lg-4">
        <h5 class="fw-bold mb-3 d-flex align-items-center gap-2 text-muted">
            <i class="ri-history-line"></i> Últimas Resueltas
        </h5>

        <div class="card">
            <div class="card-body">
                @forelse($alertasResueltas as $alerta)
                    <div class="mb-3 {{ !$loop->last ? 'border-bottom pb-3' : 'mb-0' }}">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <small class="fw-bold text-muted"><del>{{ $alerta->equipo->serial }}</del></small>
                            <small class="text-muted" style="font-size: 0.75rem;">{{ $alerta->updated_at->diffForHumans() }}</small>
                        </div>
                        <p class="mb-0 text-muted small text-decoration-line-through">{{ Str::limit($alerta->mensaje, 50) }}</p>
                    </div>
                @empty
                    <p class="text-muted text-center mb-0 py-3">No hay historial de alertas resueltas.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
