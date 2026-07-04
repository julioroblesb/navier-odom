@extends('layouts.app')

@section('title', 'Centro de Alertas - NAVIER Counter System')
@section('page-title', 'Centro de Alertas')

@section('content')
<div class="row">
    <!-- Active Alerts -->
    <div class="col-lg-8">
        <h6 class="fw-bold mb-3 d-flex align-items-center gap-2">
            <i class="bi bi-exclamation-triangle-fill text-warning"></i> Alertas Pendientes
            <span class="badge bg-danger rounded-pill">{{ $alertas->total() }}</span>
        </h6>

        @forelse($alertas as $alerta)
            <div class="table-card p-3 mb-3 border-start border-4 
                {{ $alerta->nivel_severidad == 3 ? 'border-danger bg-danger bg-opacity-10' : 
                   ($alerta->nivel_severidad == 2 ? 'border-warning bg-warning bg-opacity-10' : 'border-info') }}">
                
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="d-flex align-items-center gap-2 mb-1">
                            @if($alerta->tipo === 'toner_bajo')
                                <span class="badge bg-dark"><i class="bi bi-droplet-fill"></i> Tóner Bajo</span>
                            @elseif($alerta->tipo === 'offline')
                                <span class="badge bg-danger"><i class="bi bi-wifi-off"></i> Offline</span>
                            @else
                                <span class="badge bg-secondary"><i class="bi bi-info-circle"></i> Info</span>
                            @endif
                            <small class="text-muted">{{ $alerta->created_at->diffForHumans() }}</small>
                        </div>
                        <h6 class="fw-bold mb-1">{{ $alerta->mensaje }}</h6>
                        
                        <p class="mb-0 text-muted small">
                            Equipo: <a href="{{ route('equipos.show', $alerta->equipo) }}" class="fw-bold text-decoration-none">{{ $alerta->equipo->serial }}</a>
                            @if($alerta->equipo->cliente)
                             — Cliente: <strong>{{ $alerta->equipo->cliente->razon_social }}</strong>
                            @endif
                        </p>
                    </div>
                    
                    <form action="{{ route('alertas.resolve', $alerta) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-sm btn-success" title="Marcar como resuelta">
                            <i class="bi bi-check2-all"></i> Resuelta
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="table-card p-5 text-center">
                <i class="bi bi-check-circle text-success mb-3 d-block" style="font-size: 3rem;"></i>
                <h5 class="fw-bold text-success">¡Todo está perfecto!</h5>
                <p class="text-muted mb-0">No hay alertas pendientes por resolver.</p>
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
        <h6 class="fw-bold mb-3 d-flex align-items-center gap-2 text-muted">
            <i class="bi bi-clock-history"></i> Últimas Resueltas
        </h6>

        <div class="table-card p-3">
            @forelse($alertasResueltas as $alerta)
                <div class="mb-3 {{ !$loop->last ? 'border-bottom pb-3' : 'mb-0' }}">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <small class="fw-bold text-muted"><del>{{ $alerta->equipo->serial }}</del></small>
                        <small class="text-muted" style="font-size: 0.75rem;">{{ $alerta->updated_at->diffForHumans() }}</small>
                    </div>
                    <p class="mb-0 text-muted small text-decoration-line-through">{{ Str::limit($alerta->mensaje, 50) }}</p>
                </div>
            @empty
                <p class="text-muted text-center small mb-0 py-3">No hay historial de alertas resueltas.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
