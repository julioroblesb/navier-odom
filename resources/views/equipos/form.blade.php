@extends('layouts.app')

@section('title', ($equipo->exists ? 'Editar' : 'Nuevo') . ' Equipo - NAVIER Counter System')
@section('page-title', ($equipo->exists ? 'Editar' : 'Nuevo') . ' Equipo')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="table-card">
            <div class="card-header">
                <h6 class="mb-0 fw-bold">Datos del Equipo Ricoh</h6>
            </div>
            <div class="p-4">
                <form action="{{ $equipo->exists ? route('equipos.update', $equipo) : route('equipos.store') }}" method="POST">
                    @csrf
                    @if($equipo->exists)
                        @method('PUT')
                    @endif

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted small fw-bold">Número de Serie *</label>
                            <input type="text" name="serial" class="form-control text-uppercase @error('serial') is-invalid @enderror" value="{{ old('serial', $equipo->serial) }}" required>
                            @error('serial') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            @if(!$equipo->exists)
                            <div class="form-text"><i class="bi bi-info-circle"></i> Al guardar se generará un Token de Agente único para este serial.</div>
                            @endif
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label text-muted small fw-bold">Modelo *</label>
                            <input type="text" name="modelo" placeholder="Ej. Ricoh IM C3000" class="form-control @error('modelo') is-invalid @enderror" value="{{ old('modelo', $equipo->modelo) }}" required>
                            @error('modelo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-12">
                            <label class="form-label text-muted small fw-bold">Cliente Asignado</label>
                            <select name="cliente_id" class="form-select @error('cliente_id') is-invalid @enderror">
                                <option value="">-- Sin asignar (En almacén) --</option>
                                @foreach($clientes as $cliente)
                                    <option value="{{ $cliente->id }}" {{ old('cliente_id', $equipo->cliente_id) == $cliente->id ? 'selected' : '' }}>
                                        {{ $cliente->razon_social }}
                                    </option>
                                @endforeach
                            </select>
                            @error('cliente_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-muted small fw-bold">Dirección IP Local (Opcional)</label>
                            <input type="text" name="ip_local" placeholder="Ej. 192.168.1.50" class="form-control @error('ip_local') is-invalid @enderror" value="{{ old('ip_local', $equipo->ip_local) }}">
                            @error('ip_local') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-muted small fw-bold">Fecha de Instalación (Opcional)</label>
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
                    <div class="mt-4 p-3 bg-light rounded border">
                        <label class="form-label text-muted small fw-bold"><i class="bi bi-shield-lock"></i> Token del Agente (Solo lectura)</label>
                        <div class="input-group">
                            <input type="text" class="form-control font-monospace" value="{{ $equipo->agente_token }}" id="agentToken" readonly>
                            <button class="btn btn-outline-secondary" type="button" onclick="navigator.clipboard.writeText(document.getElementById('agentToken').value); alert('Token copiado');">
                                <i class="bi bi-clipboard"></i> Copiar
                            </button>
                        </div>
                        <div class="form-text">Este token debe configurarse en el archivo `config.json` del agente instalado en la red del cliente.</div>
                    </div>
                    @endif

                    <div class="mt-4 pt-3 border-top d-flex justify-content-end gap-2">
                        <a href="{{ route('equipos.index') }}" class="btn btn-light">Cancelar</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-1"></i> Guardar Equipo
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
