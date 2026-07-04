<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LicenciaController extends Controller
{
    public function index()
    {
        $hardwareId = $this->getHardwareId();
        
        $licenseFile = storage_path('license.key');
        $hasLicense = file_exists($licenseFile);
        $licenseStatus = $hasLicense ? 'Activa (Pendiente de Verificación)' : 'No Registrada';
        
        if ($hasLicense) {
            $key = trim(file_get_contents($licenseFile));
            if ($this->verifyLicense($key, $hardwareId)) {
                $licenseStatus = 'Activa y Válida';
            } else {
                $licenseStatus = 'Inválida / Expirada';
            }
        }
        
        return view('licencia.index', compact('hardwareId', 'licenseStatus'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'license_key' => 'required|string'
        ]);

        $hwid = $this->getHardwareId();
        
        if ($this->verifyLicense($request->license_key, $hwid)) {
            file_put_contents(storage_path('license.key'), $request->license_key);
            return redirect()->route('dashboard')->with('success', 'Licencia activada correctamente.');
        }

        return back()->with('error', 'La clave de licencia es inválida o no corresponde a este equipo.');
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
        $parts = explode('-', $key);
        if (count($parts) < 3) return false;
        
        $prefix = array_shift($parts); // NAV
        if ($prefix !== 'NAV') return false;
        
        $signature = array_pop($parts);
        $payload = implode('-', $parts);
        
        $secret = 'N@V1ER_PR0DUCTI0N_K3Y_99887766';
        $expectedSignature = substr(hash_hmac('sha256', $payload . $hwid, $secret), 0, 12);
        
        return hash_equals($expectedSignature, $signature);
    }
}
