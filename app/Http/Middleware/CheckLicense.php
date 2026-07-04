<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckLicense
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Ignorar la ruta de licencia en sí misma para evitar bucles
        if ($request->is('licencia') || $request->is('licencia/*') || $request->is('api/*')) {
            return $next($request);
        }

        $licenseFile = storage_path('license.key');
        
        if (!file_exists($licenseFile)) {
            return redirect()->route('licencia.index');
        }

        $licenseKey = trim(file_get_contents($licenseFile));
        
        // Obtener Hardware ID real
        $hwid = $this->getHardwareId();
        
        // Validar la firma
        if (!$this->verifyLicense($licenseKey, $hwid)) {
            return redirect()->route('licencia.index')->with('error', 'Licencia inválida o equipo no autorizado.');
        }

        return $next($request);
    }
    
    private function getHardwareId() {
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $output = shell_exec('wmic csproduct get uuid');
            if ($output) {
                $lines = explode("\n", trim($output));
                if (isset($lines[1])) {
                    return trim($lines[1]);
                }
            }
        }
        return 'UNKNOWN-HWID-'.php_uname('n');
    }
    
    private function verifyLicense($key, $hwid) {
        // Formato: NAV-PAYLOAD-SIGNATURE
        $parts = explode('-', $key);
        if (count($parts) < 3) return false;
        
        $prefix = array_shift($parts); // NAV
        if ($prefix !== 'NAV') return false;
        
        $signature = array_pop($parts);
        $payload = implode('-', $parts);
        
        // Secreto duro ofuscable
        $secret = 'N@V1ER_PR0DUCTI0N_K3Y_99887766';
        
        $expectedSignature = substr(hash_hmac('sha256', $payload . $hwid, $secret), 0, 12);
        
        return hash_equals($expectedSignature, $signature);
    }
}
