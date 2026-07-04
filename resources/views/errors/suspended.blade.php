<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cuenta Suspendida - NAVIER</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f0f2f5;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Inter', sans-serif;
        }
        .suspended-card {
            width: 100%;
            max-width: 500px;
            padding: 3rem 2rem;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            background: white;
            text-align: center;
        }
        .icon-container {
            font-size: 4rem;
            color: #ef4444;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <div class="suspended-card">
        <div class="icon-container">
            <i class="bi bi-x-circle-fill"></i>
        </div>
        <h3 class="mb-3" style="font-weight: 700;">Cuenta Suspendida</h3>
        <p class="text-muted mb-4">
            El acceso a tu sistema ha sido temporalmente desactivado por el administrador. 
            Tu información y configuraciones están a salvo, pero no podrás realizar ninguna acción hasta que tu cuenta sea reactivada.
        </p>
        
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-outline-secondary px-4 py-2">
                <i class="bi bi-box-arrow-right me-2"></i>Cerrar Sesión
            </button>
        </form>
    </div>
</body>
</html>
