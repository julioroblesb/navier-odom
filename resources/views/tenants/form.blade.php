@extends('layouts.app')

@section('title', (isset($tenant) ? 'Editar' : 'Nueva') . ' Empresa - NAVIER')
@section('page-title', (isset($tenant) ? 'Editar' : 'Nueva') . ' Empresa')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4 class="header-title">Datos de la Empresa</h4>
            </div>
            <div class="card-body">
                <form action="{{ isset($tenant) ? route('tenants.update', $tenant->id) : route('tenants.store') }}" method="POST">
                    @csrf
                    @if(isset($tenant))
                        @method('PUT')
                    @endif

                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label fw-bold" for="nombre_empresa">Nombre de la Empresa (Tenant) *</label>
                            <input class="form-control @error('nombre_empresa') is-invalid @enderror" id="nombre_empresa" name="nombre_empresa" type="text" placeholder="Ej. Copias Lima SAC" value="{{ old('nombre_empresa', $tenant->nombre_empresa ?? '') }}" required>
                            @error('nombre_empresa') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        @if(isset($tenant))
                        <div class="col-md-6">
                            <label class="form-label fw-bold" for="estado">Estado de la Cuenta</label>
                            <select class="form-select @error('estado') is-invalid @enderror" id="estado" name="estado" required>
                                <option value="activo" {{ (old('estado', $tenant->estado) === 'activo') ? 'selected' : '' }}>Activo</option>
                                <option value="suspendido" {{ (old('estado', $tenant->estado) === 'suspendido') ? 'selected' : '' }}>Suspendido</option>
                            </select>
                            @error('estado') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        @else
                        
                        <div class="col-12 mt-4 mb-2">
                            <h5 class="font-14 text-muted border-bottom pb-2">Cuenta de Acceso Administrador (Para el cliente)</h5>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label fw-bold" for="admin_name">Nombre del Encargado *</label>
                            <input class="form-control @error('admin_name') is-invalid @enderror" id="admin_name" name="admin_name" type="text" placeholder="Ej. Juan Pérez" value="{{ old('admin_name') }}" required>
                            @error('admin_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label fw-bold" for="admin_email">Correo Electrónico (Login) *</label>
                            <input class="form-control @error('admin_email') is-invalid @enderror" id="admin_email" name="admin_email" type="email" placeholder="juan@empresa.com" value="{{ old('admin_email') }}" required>
                            @error('admin_email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        
                        <div class="col-md-12">
                            <label class="form-label fw-bold" for="admin_password">Contraseña (Mínimo 8 caracteres) *</label>
                            <input class="form-control @error('admin_password') is-invalid @enderror" id="admin_password" name="admin_password" type="password" placeholder="********" required>
                            @error('admin_password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        @endif
                    </div>

                    <div class="mt-4 pt-3 border-top text-end">
                        <a class="btn btn-light me-2" href="{{ route('tenants.index') }}">Cancelar</a>
                        <button class="btn btn-primary fw-bold" type="submit">
                            <i class="ri-save-line me-1 align-middle"></i> {{ isset($tenant) ? 'Guardar Cambios' : 'Registrar Empresa' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
