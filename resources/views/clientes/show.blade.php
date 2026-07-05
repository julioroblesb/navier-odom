@extends('layouts.app')

@section('title', 'Detalle del Cliente - ' . $cliente->razon_social)
@section('page-title', 'Detalle del Cliente')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="header-title mb-0">{{ $cliente->razon_social }}</h4>
                    <div>
                        <a href="{{ route('clientes.edit', $cliente) }}" class="btn btn-primary btn-sm"><i class="ri-edit-line"></i> Editar Cliente</a>
                        <a href="{{ route('clientes.index') }}" class="btn btn-light btn-sm"><i class="ri-arrow-left-line"></i> Volver</a>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <p class="mb-1"><strong>RUC:</strong> {{ $cliente->ruc ?? 'N/A' }}</p>
                        <p class="mb-1"><strong>Dirección:</strong> {{ $cliente->direccion ?? 'N/A' }}</p>
                        <p class="mb-1"><strong>Distrito:</strong> {{ $cliente->distrito ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-6">
                        <p class="mb-1"><strong>Contacto:</strong> {{ $cliente->contacto_nombre ?? 'N/A' }}</p>
                        <p class="mb-1"><strong>Teléfono:</strong> {{ $cliente->contacto_telefono ?? 'N/A' }}</p>
                        <p class="mb-1"><strong>Email:</strong> {{ $cliente->contacto_email ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-3">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="header-title mb-0">Sucursales / Locales</h4>
                <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#modalSucursal">
                    <i class="ri-add-line"></i> Agregar Sucursal
                </button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Dirección</th>
                                <th>Contacto / Teléfono</th>
                                <th>Equipos Asignados</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($cliente->sucursales as $sucursal)
                            <tr>
                                <td class="fw-bold">{{ $sucursal->nombre }}</td>
                                <td>{{ $sucursal->direccion ?? '-' }}</td>
                                <td>
                                    {{ $sucursal->contacto ?? '-' }} <br>
                                    <small class="text-muted">{{ $sucursal->telefono }}</small>
                                </td>
                                <td>{{ $sucursal->equipos->count() }}</td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-info" onclick="editSucursal({{ $sucursal }})" data-bs-toggle="modal" data-bs-target="#modalSucursalEdit"><i class="ri-edit-2-line"></i></button>
                                    @if($sucursal->equipos->count() === 0)
                                    <form action="{{ route('sucursales.destroy', $sucursal->id) }}" method="POST" class="d-inline-block" onsubmit="return confirm('¿Eliminar esta sucursal?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger"><i class="ri-delete-bin-line"></i></button>
                                    </form>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">No hay sucursales registradas.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Agregar Sucursal -->
<div class="modal fade" id="modalSucursal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('sucursales.store') }}" method="POST">
                @csrf
                <input type="hidden" name="cliente_id" value="{{ $cliente->id }}">
                <div class="modal-header">
                    <h5 class="modal-title">Nueva Sucursal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nombre de la Sucursal <span class="text-danger">*</span></label>
                        <input type="text" name="nombre" class="form-control" required placeholder="Ej: Sede Central, Notaría López...">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Dirección</label>
                        <input type="text" name="direccion" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Persona de Contacto</label>
                        <input type="text" name="contacto" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Teléfono</label>
                        <input type="text" name="telefono" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Editar Sucursal -->
<div class="modal fade" id="modalSucursalEdit" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formEditSucursal" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Editar Sucursal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nombre de la Sucursal <span class="text-danger">*</span></label>
                        <input type="text" name="nombre" id="edit_nombre" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Dirección</label>
                        <input type="text" name="direccion" id="edit_direccion" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Persona de Contacto</label>
                        <input type="text" name="contacto" id="edit_contacto" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Teléfono</label>
                        <input type="text" name="telefono" id="edit_telefono" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Actualizar</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function editSucursal(sucursal) {
    document.getElementById('formEditSucursal').action = '/sucursales/' + sucursal.id;
    document.getElementById('edit_nombre').value = sucursal.nombre;
    document.getElementById('edit_direccion').value = sucursal.direccion;
    document.getElementById('edit_contacto').value = sucursal.contacto;
    document.getElementById('edit_telefono').value = sucursal.telefono;
}
</script>
@endpush
