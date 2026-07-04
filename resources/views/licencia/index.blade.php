@extends('layouts.app')

@section('title', 'Licencia - NAVIER Counter System')
@section('page-title', 'Activación del Sistema')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="table-card overflow-hidden">
            <div class="bg-dark text-white p-5 text-center position-relative">
                <i class="bi bi-shield-lock-fill position-absolute" style="font-size: 15rem; opacity: 0.05; right: -20px; top: -50px; transform: rotate(-15deg);"></i>
                <h3 class="fw-bold mb-2 position-relative" style="z-index: 1;">NAVIER Counter System</h3>
                <p class="text-white-50 position-relative mb-0" style="z-index: 1;">Panel de Control y Gestión de Contadores Ricoh</p>
            </div>
            
            <div class="p-4 p-md-5">
                <div class="text-center mb-5">
                    <h5 class="fw-bold">Estado de la Licencia</h5>
                    @if($licenseStatus == 'Activa y Válida')
                        <span class="badge bg-success px-3 py-2 rounded-pill fs-6 mt-2 shadow-sm">
                            <i class="bi bi-check-circle-fill me-1"></i> Sistema Activado
                        </span>
                    @else
                        <span class="badge bg-danger px-3 py-2 rounded-pill fs-6 mt-2 shadow-sm">
                            <i class="bi bi-x-circle-fill me-1"></i> {{ $licenseStatus }}
                        </span>
                    @endif
                </div>

                <div class="row g-4 mb-3">
                    <div class="col-md-12">
                        <div class="p-4 bg-light rounded border border-light h-100">
                            <small class="text-muted d-block fw-bold mb-2">Paso 1: ID de Hardware (Machine ID)</small>
                            <p class="text-muted small mb-3">Copie este código y envíeselo al proveedor del software. Él le retornará una clave de licencia única de activación.</p>
                    
                            <div class="bg-dark text-light p-3 rounded mb-4 text-center user-select-all" style="font-family: monospace; font-size: 1.2rem; letter-spacing: 2px;">
                                {{ $hardwareId }}
                            </div>

                            <hr class="text-light my-4">

                            <small class="text-muted d-block fw-bold mb-2">Paso 2: Activar Licencia</small>
                            <form action="{{ route('licencia.store') }}" method="POST">
                                @csrf
                                <div class="input-group mb-2">
                                    <input type="text" name="license_key" class="form-control font-monospace" placeholder="Pegue aquí su clave: NAV-..." required>
                                    <button type="submit" class="btn btn-primary px-4">
                                        <i class="bi bi-key me-2"></i> Activar
                                    </button>
                                </div>
                                <div class="form-text"><i class="bi bi-info-circle"></i> La clave está cifrada criptográficamente para su equipo y no funcionará en otra PC.</div>
                            </form> 
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
