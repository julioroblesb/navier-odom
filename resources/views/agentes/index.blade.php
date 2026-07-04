@extends('layouts.app')

@section('title', 'Descarga de Agentes - NAVIER')
@section('page-title', 'Centro de Instalación de Agentes')

@section('content')
<div class="row">
    <!-- Instrucciones -->
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-body">
                <h4 class="header-title mb-4"><i class="ri-information-fill text-primary me-2"></i>¿Cómo instalar un Agente?</h4>
                
                <div class="d-flex mb-4">
                    <div class="me-3">
                        <span class="avatar-sm d-flex align-items-center justify-content-center bg-primary rounded-circle text-white fw-bold">1</span>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-1">Descarga el Agente</h5>
                        <p class="text-muted mb-2">Este instalador `navier-agent-V10.exe` es el mismo para todas las fotocopiadoras. Descárgalo y envíaselo por WhatsApp a tu cliente.</p>
                        <a href="{{ asset('downloads/navier-agent-V10.exe') }}" download="navier-agent-V10.exe" class="btn btn-sm btn-outline-primary fw-bold">
                            <i class="ri-download-line me-1"></i> Descargar navier-agent-V10.exe
                        </a>
                    </div>
                </div>

                <div class="d-flex mb-4">
                    <div class="me-3">
                        <span class="avatar-sm d-flex align-items-center justify-content-center bg-primary rounded-circle text-white fw-bold">2</span>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-1">Genera el Código de Instalación</h5>
                        <p class="text-muted mb-0">Usa el panel de la derecha para generar un código único para la red del cliente. Cópialo y envíaselo por WhatsApp.</p>
                    </div>
                </div>

                <div class="d-flex mb-4">
                    <div class="me-3">
                        <span class="avatar-sm d-flex align-items-center justify-content-center bg-primary rounded-circle text-white fw-bold">3</span>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-1">El cliente lo instala</h5>
                        <p class="text-muted mb-0">El cliente abre el `.exe`, pega el código que le enviaste y ¡listo! El agente se configura solo y se programa para ejecutarse cada vez que la PC se encienda.</p>
                    </div>
                </div>
                
                <div class="alert alert-info mt-4 border-0 mb-0">
                    <i class="ri-shield-keyhole-line me-2"></i> Cada equipo debe tener su propio código de agente para asegurar que los datos no se crucen.
                </div>
            </div>
        </div>
    </div>

    <!-- Generador -->
    <div class="col-lg-6 mt-4 mt-lg-0">
        <div class="card h-100 border-top border-primary border-3">
            <div class="card-body">
                <h4 class="header-title mb-1"><i class="ri-file-code-line text-primary me-2"></i>Generador de Código para WhatsApp</h4>
                <p class="text-muted mb-4">Selecciona los equipos de una misma oficina para generar su código de auto-instalación.</p>

                <div class="mb-4">
                    <label class="form-label fw-bold">Seleccionar Equipo (Puedes agregar varios)</label>
                    <div class="input-group">
                        <select id="equipoSelect" class="form-select">
                            <option value="">-- Selecciona un equipo --</option>
                            @foreach($equipos as $equipo)
                                <option value="{{ $equipo->id }}" 
                                        data-serial="{{ $equipo->serial }}" 
                                        data-ip="{{ $equipo->ip_local ?? '192.168.1.XX' }}"
                                        data-token="{{ $equipo->agente_token }}"
                                        data-cliente="{{ $equipo->cliente ? $equipo->cliente->razon_social : 'Sin Asignar' }}">
                                    {{ $equipo->serial }} - {{ $equipo->modelo }} 
                                </option>
                            @endforeach
                        </select>
                        <button class="btn btn-primary" type="button" onclick="addPrinterToConfig()">
                            <i class="ri-add-line"></i> Agregar
                        </button>
                    </div>
                </div>

                <div class="position-relative mt-4">
                    <div class="bg-dark text-light p-3 rounded d-flex align-items-center font-monospace" style="min-height: 150px; font-size: 0.85rem; word-break: break-all;">
<pre id="jsonPreview" class="mb-0 text-white w-100 text-wrap">
Selecciona un equipo y dale a "Agregar" para generar el código...
</pre>
                    </div>
                    <button id="copyBtn" class="btn btn-primary btn-sm position-absolute top-0 end-0 m-2 fw-bold" style="display: none;" onclick="copyJson()" title="Copiar código">
                        <i class="ri-whatsapp-line fs-16 me-1 align-middle"></i> Copiar
                    </button>
                </div>
                
                <div class="mt-3 text-end">
                    <button class="btn btn-soft-danger btn-sm fw-bold" onclick="clearConfig()">
                        <i class="ri-delete-bin-line"></i> Limpiar y empezar de nuevo
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let currentConfig = {
        server_url: window.location.origin,
        interval_hours: 4,
        empresa: "Nombre-del-Cliente",
        printers: []
    };
    let currentBase64 = '';
    
    function renderPreview() {
        if (currentConfig.printers.length === 0) {
            document.getElementById('jsonPreview').textContent = 'Selecciona un equipo y dale a "Agregar" para generar el código...';
            document.getElementById('copyBtn').style.display = 'none';
        } else {
            const jsonStr = JSON.stringify(currentConfig);
            currentBase64 = 'navier://' + btoa(unescape(encodeURIComponent(jsonStr)));
            document.getElementById('jsonPreview').textContent = currentBase64;
            document.getElementById('copyBtn').style.display = 'inline-block';
        }
    }

    function addPrinterToConfig() {
        const select = document.getElementById('equipoSelect');
        if (!select.value) return;
        
        const selected = select.options[select.selectedIndex];
        
        currentConfig.empresa = selected.dataset.cliente;
        
        const exists = currentConfig.printers.find(p => p.token === selected.dataset.token);
        if (!exists) {
            currentConfig.printers.push({
                ip: selected.dataset.ip,
                token: selected.dataset.token
            });
        }
        
        renderPreview();
    }
    
    function clearConfig() {
        currentConfig.printers = [];
        renderPreview();
    }

    function copyJson() {
        navigator.clipboard.writeText(currentBase64).then(() => {
            const btn = document.getElementById('copyBtn');
            const originalHtml = btn.innerHTML;
            btn.innerHTML = '<i class="ri-check-double-line text-success fs-16 me-1 align-middle"></i> ¡Copiado!';
            setTimeout(() => btn.innerHTML = originalHtml, 2000);
        });
    }
</script>
@endpush
