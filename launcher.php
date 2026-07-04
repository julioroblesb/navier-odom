<?php
/**
 * NAVIER Launcher
 * Este script inteligente se encarga de iniciar el servidor en cualquier PC.
 * - Encuentra un puerto libre dinámicamente si el 8000 está ocupado.
 * - Detecta si el sistema ya está corriendo para no duplicar procesos.
 * - Espera inteligentemente a que el servidor esté listo antes de abrir el navegador.
 * - Ejecuta todo en modo 100% oculto usando php-win.exe y COM.
 */

$host = '127.0.0.1';
$port = 8000;
$server_found = false;

// 1. Buscar un puerto disponible o detectar si ya estamos corriendo
for ($i = 0; $i < 50; $i++) {
    $current_port = $port + $i;
    $connection = @fsockopen($host, $current_port, $errno, $errstr, 1);
    
    if (is_resource($connection)) {
        fclose($connection);
        // El puerto está en uso. ¿Es nuestro servidor NAVIER?
        $context = stream_context_create(['http' => ['timeout' => 1]]);
        $response = @file_get_contents("http://$host:$current_port/api/status", false, $context);
        
        if ($response && strpos($response, 'online') !== false) {
            // ¡Nuestro servidor ya está corriendo aquí!
            $port = $current_port;
            $server_found = true;
            break;
        }
    } else {
        // El puerto está libre. Lo usaremos.
        $port = $current_port;
        break;
    }
}

$WshShell = new COM("WScript.Shell");

if (!$server_found) {
    // 2. Iniciar el servidor usando php-win.exe (Totalmente invisible)
    $cmd = '..\php\php-win.exe artisan serve --port=' . $port;
    $WshShell->Run($cmd, 0, false);
    
    // 3. Esperar inteligentemente (hasta 15 segundos) a que el servidor despierte
    $attempts = 0;
    while ($attempts < 30) {
        usleep(500000); // Esperar medio segundo (0.5s)
        $connection = @fsockopen($host, $port, $errno, $errstr, 1);
        if (is_resource($connection)) {
            fclose($connection);
            break; // ¡Servidor listo!
        }
        $attempts++;
    }
}

// 4. Abrir el navegador exactamente en el puerto correcto
$WshShell->Run("http://$host:$port", 1, false);
