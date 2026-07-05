@extends('layouts.app')

@section('title', 'Dashboard (Super Admin) - NAVIER')
@section('page-title', 'Dashboard Global')

@section('content')
<div class="row">
    <div class="col-xl-3 col-lg-4">
        <div class="card tilebox-one">
            <div class="card-body">
                <i class="ri-admin-fill float-end text-muted fs-3"></i>
                <h6 class="text-uppercase mt-0">Total Empresas (Tenants)</h6>
                <h2 class="my-2" id="active-users-count">{{ $stats['tenants'] }}</h2>
                <p class="mb-0 text-muted">
                    <span class="text-success me-2"><span class="mdi mdi-arrow-up-bold"></span> {{ $stats['tenants_activos'] }}</span>
                    <span class="text-nowrap">Empresas Activas</span>  
                </p>
            </div> <!-- end card-body-->
        </div>
        <!--end card-->
    </div> <!-- end col -->

    <div class="col-xl-3 col-lg-4">
        <div class="card tilebox-one">
            <div class="card-body">
                <i class="ri-building-fill float-end text-muted fs-3"></i>
                <h6 class="text-uppercase mt-0">Total Clientes Globales</h6>
                <h2 class="my-2" id="active-users-count">{{ $stats['clientes_global'] }}</h2>
                <p class="mb-0 text-muted">
                    <span class="text-nowrap">Clientes de todas las empresas</span>  
                </p>
            </div> <!-- end card-body-->
        </div>
        <!--end card-->
    </div> <!-- end col -->

    <div class="col-xl-3 col-lg-4">
        <div class="card tilebox-one">
            <div class="card-body">
                <i class="ri-printer-fill float-end text-muted fs-3"></i>
                <h6 class="text-uppercase mt-0">Total Equipos Globales</h6>
                <h2 class="my-2" id="active-users-count">{{ $stats['equipos_global'] }}</h2>
                <p class="mb-0 text-muted">
                    <span class="text-nowrap">Equipos monitoreados en todo el sistema</span>  
                </p>
            </div> <!-- end card-body-->
        </div>
        <!--end card-->
    </div> <!-- end col -->
</div> <!-- end row -->

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title mb-3">Bienvenido, Super Admin</h4>
                <p>Estás visualizando el panel global de NAVIER. Desde el menú lateral izquierdo puedes acceder al <strong>Directorio de Empresas</strong> para administrar a tus clientes (Tenants), asignarles planes y controlar su acceso.</p>
                <a href="{{ route('tenants.index') }}" class="btn btn-primary mt-2">Ir al Directorio de Empresas</a>
            </div>
        </div>
    </div>
</div>
@endsection
