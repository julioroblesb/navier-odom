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

                        <div class="col-12 mt-3 mb-1">
                            <h5 class="font-14 text-muted border-bottom pb-2">Información Comercial y Facturación</h5>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-bold">Plan Contratado *</label>
                            <div class="d-flex flex-wrap gap-2">
                                @php $currentPlan = old('plan_type', $tenant->plan_type ?? ''); @endphp
                                
                                <input type="radio" class="btn-check" name="plan_type" id="plan_demo" value="Demo" autocomplete="off" {{ $currentPlan === 'Demo' ? 'checked' : '' }} required>
                                <label class="btn btn-outline-warning" for="plan_demo">Demo</label>

                                <input type="radio" class="btn-check" name="plan_type" id="plan_mensual" value="Mensual" autocomplete="off" {{ $currentPlan === 'Mensual' ? 'checked' : '' }} required>
                                <label class="btn btn-outline-info" for="plan_mensual">Mensual</label>

                                <input type="radio" class="btn-check" name="plan_type" id="plan_anual" value="Anual" autocomplete="off" {{ $currentPlan === 'Anual' ? 'checked' : '' }} required>
                                <label class="btn btn-outline-primary" for="plan_anual">Anual</label>

                                <input type="radio" class="btn-check" name="plan_type" id="plan_lifetime" value="Lifetime" autocomplete="off" {{ $currentPlan === 'Lifetime' ? 'checked' : '' }} required>
                                <label class="btn btn-outline-success" for="plan_lifetime">Lifetime</label>
                            </div>
                            @error('plan_type') <div class="text-danger mt-1 small">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold" for="billing_status">Estado de Facturación *</label>
                            <select class="form-select @error('billing_status') is-invalid @enderror" id="billing_status" name="billing_status" required>
                                <option value="Al día" {{ (old('billing_status', $tenant->billing_status ?? 'Al día') === 'Al día') ? 'selected' : '' }}>Al día</option>
                                <option value="Pendiente" {{ (old('billing_status', $tenant->billing_status ?? '') === 'Pendiente') ? 'selected' : '' }}>Pendiente</option>
                            </select>
                            @error('billing_status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6" id="demo_expires_container" style="display: none;">
                            <label class="form-label fw-bold text-warning" for="demo_expires_at"><i class="ri-timer-line"></i> Vencimiento del Demo</label>
                            <input class="form-control border-warning @error('demo_expires_at') is-invalid @enderror" id="demo_expires_at" name="demo_expires_at" type="date" value="{{ old('demo_expires_at', isset($tenant) && $tenant->demo_expires_at ? $tenant->demo_expires_at->format('Y-m-d') : '') }}">
                            @error('demo_expires_at') <div class="invalid-feedback">{{ $message }}</div> @enderror
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

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const planRadios = document.querySelectorAll('input[name="plan_type"]');
        const demoContainer = document.getElementById('demo_expires_container');
        
        function toggleDemoField() {
            const selected = document.querySelector('input[name="plan_type"]:checked');
            if (selected && selected.value === 'Demo') {
                demoContainer.style.display = 'block';
            } else {
                demoContainer.style.display = 'none';
            }
        }
        
        planRadios.forEach(radio => {
            radio.addEventListener('change', toggleDemoField);
        });
        
        // Initial check
        toggleDemoField();
    });
</script>
@endpush
