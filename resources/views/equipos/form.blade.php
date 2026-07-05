@extends('layouts.app')

@section('title', ($equipo->exists ? 'Editar' : 'Nuevo') . ' Equipo - NAVIER')
@section('page-title', ($equipo->exists ? 'Editar' : 'Nuevo') . ' Equipo')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4 class="header-title">Datos del Equipo Ricoh</h4>
            </div>
            <div class="card-body">
                <form action="{{ $equipo->exists ? route('equipos.update', $equipo) : route('equipos.store') }}" method="POST">
                    @csrf
                    @if($equipo->exists)
                        @method('PUT')
                    @endif

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Número de Serie *</label>
                            <input type="text" name="serial" class="form-control text-uppercase @error('serial') is-invalid @enderror" value="{{ old('serial', $equipo->serial) }}" required>
                            @error('serial') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            @if(!$equipo->exists)
                            <div class="form-text text-muted"><i class="ri-information-line"></i> Al guardar se generará un Token de Agente único para este serial.</div>
                            @endif
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Modelo *</label>
                            <input type="text" name="modelo" placeholder="Ej. Ricoh IM C3000" class="form-control @error('modelo') is-invalid @enderror" value="{{ old('modelo', $equipo->modelo) }}" required>
                            @error('modelo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-bold">Cliente / Sucursal Asignada *</label>
                            <select name="sucursal_id" class="form-control select2 @error('sucursal_id') is-invalid @enderror" data-toggle="select2" required>
                                <option value="">-- Seleccionar --</option>
                                @foreach($clientes as $cliente)
                                    @if($cliente->sucursales->count() > 0)
                                        <optgroup label="{{ $cliente->razon_social }}">
                                            @foreach($cliente->sucursales as $sucursal)
                                                <option value="{{ $sucursal->id }}" {{ old('sucursal_id', $equipo->sucursal_id) == $sucursal->id ? 'selected' : '' }}>
                                                    {{ $sucursal->nombre }} ({{ $sucursal->direccion ?? 'Sin dir.' }})
                                                </option>
                                            @endforeach
                                        </optgroup>
                                    @endif
                                @endforeach
                            </select>
                            @error('sucursal_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Dirección IP Local (Opcional)</label>
                            <input type="text" name="ip_local" placeholder="Ej. 192.168.1.50" class="form-control @error('ip_local') is-invalid @enderror" value="{{ old('ip_local', $equipo->ip_local) }}">
                            @error('ip_local') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Fecha de Instalación (Opcional)</label>
                            <input type="date" name="fecha_instalacion" class="form-control @error('fecha_instalacion') is-invalid @enderror" value="{{ old('fecha_instalacion', $equipo->fecha_instalacion) }}">
                            @error('fecha_instalacion') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-12 mt-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" name="activo" id="activo" {{ old('activo', $equipo->exists ? $equipo->activo : true) ? 'checked' : '' }}>
                                <label class="form-check-label ms-2" for="activo">Equipo Activo</label>
                            </div>
                        </div>
                    </div>

                    @if($equipo->exists)
                    <div class="mt-4 p-3 bg-light rounded border border-light">
                        <label class="form-label fw-bold"><i class="ri-shield-keyhole-line"></i> Token del Agente (Solo lectura)</label>
                        <div class="input-group">
                            <input type="text" class="form-control font-monospace" value="{{ $equipo->agente_token }}" id="agentToken" readonly>
                            <button class="btn btn-primary" type="button" onclick="navigator.clipboard.writeText(document.getElementById('agentToken').value); alert('Token copiado');">
                                <i class="ri-clipboard-line"></i> Copiar
                            </button>
                        </div>
                        <div class="form-text text-muted">Este token debe configurarse en el archivo `config.json` del agente instalado en la red del cliente.</div>
                    </div>
                    @endif

                    <div class="mt-4 pt-3 text-end">
                        <a href="{{ route('equipos.index') }}" class="btn btn-light me-2">Cancelar</a>
                        <button type="submit" class="btn btn-primary fw-bold">
                            <i class="ri-save-line me-1 align-middle"></i> Guardar Equipo
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('vendor-styles')
<link href="{{ asset('assets/vendor/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
@endpush



@push('scripts')
<script src="{{ asset('assets/vendor/select2/js/select2.min.js') }}"></script>
<script>
    $(document).ready(function() {
        $('[data-toggle="select2"]').select2();
    });
</script>
@endpush
