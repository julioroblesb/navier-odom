@extends('layouts.app')

@section('title', ($cliente->exists ? 'Editar' : 'Nuevo') . ' Cliente - NAVIER Counter System')
@section('page-title', ($cliente->exists ? 'Editar' : 'Nuevo') . ' Cliente')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="table-card">
            <div class="card-header">
                <h6 class="mb-0 fw-bold">Datos del Cliente</h6>
            </div>
            <div class="p-4">
                <form action="{{ $cliente->exists ? route('clientes.update', $cliente) : route('clientes.store') }}" method="POST">
                    @csrf
                    @if($cliente->exists)
                        @method('PUT')
                    @endif

                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label text-muted small fw-bold">Razón Social *</label>
                            <input type="text" name="razon_social" class="form-control @error('razon_social') is-invalid @enderror" value="{{ old('razon_social', $cliente->razon_social) }}" required>
                            @error('razon_social') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-muted small fw-bold">RUC</label>
                            <input type="text" name="ruc" class="form-control @error('ruc') is-invalid @enderror" value="{{ old('ruc', $cliente->ruc) }}" maxlength="11">
                            @error('ruc') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-12">
                            <label class="form-label text-muted small fw-bold">Dirección</label>
                            <input type="text" name="direccion" class="form-control @error('direccion') is-invalid @enderror" value="{{ old('direccion', $cliente->direccion) }}">
                            @error('direccion') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-muted small fw-bold">Distrito/Ciudad</label>
                            <input type="text" name="distrito" class="form-control @error('distrito') is-invalid @enderror" value="{{ old('distrito', $cliente->distrito) }}">
                            @error('distrito') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <hr class="my-4 text-light">

                        <div class="col-md-4">
                            <label class="form-label text-muted small fw-bold">Nombre del Contacto</label>
                            <input type="text" name="contacto_nombre" class="form-control @error('contacto_nombre') is-invalid @enderror" value="{{ old('contacto_nombre', $cliente->contacto_nombre) }}">
                            @error('contacto_nombre') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-muted small fw-bold">Teléfono/Celular</label>
                            <input type="text" name="contacto_telefono" class="form-control @error('contacto_telefono') is-invalid @enderror" value="{{ old('contacto_telefono', $cliente->contacto_telefono) }}">
                            @error('contacto_telefono') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-muted small fw-bold">Email</label>
                            <input type="email" name="contacto_email" class="form-control @error('contacto_email') is-invalid @enderror" value="{{ old('contacto_email', $cliente->contacto_email) }}">
                            @error('contacto_email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-12 mt-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" name="activo" id="activo" {{ old('activo', $cliente->exists ? $cliente->activo : true) ? 'checked' : '' }}>
                                <label class="form-check-label ms-2" for="activo">Cliente Activo</label>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 pt-3 border-top d-flex justify-content-end gap-2">
                        <a href="{{ route('clientes.index') }}" class="btn btn-light">Cancelar</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-1"></i> Guardar Cliente
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
