<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LecturaContador;
use App\Models\Alerta;
use App\Services\TokenService;
use Illuminate\Http\Request;

class LecturaController extends Controller
{
    /**
     * POST /api/lecturas
     * Receives counter data from an agent
     */
    public function store(Request $request)
    {
        $serial = $request->input('serial');
        $signature = $request->input('signature');
        $timestamp = $request->input('timestamp');
        $data = $request->input('data', []);

        if (!$serial || !$signature || !$timestamp) {
            return response()->json(['error' => 'Parámetros de seguridad HMAC incompletos (serial, signature, timestamp)'], 400);
        }

        // 1. Validar ventana de tiempo (prevenir replay attacks)
        $requestTime = (int) $timestamp;
        $currentTime = time();
        if (abs($currentTime - $requestTime) > 300) { // ±5 minutes
            return response()->json(['error' => 'Timestamp fuera de la ventana permitida (±5 min)'], 401);
        }

        // 2. Prevenir Replay usando Cache Atómico
        // Cache::add() solo devuelve true si la llave no existía.
        if (!\Illuminate\Support\Facades\Cache::add('hmac_replay_' . $signature, true, 300)) {
            return response()->json(['error' => 'Ataque de Replay detectado'], 401);
        }

        // 3. Obtener el equipo por serial
        $equipo = \App\Models\Equipo::where('serial', $serial)->where('activo', true)->first();

        if (!$equipo || !$equipo->agente_token) {
            return response()->json(['error' => 'Equipo no encontrado o inactivo'], 401);
        }

        // 4. Calcular el HMAC localmente
        // El payload a firmar será el JSON de "data" sin espacios + timestamp
        // Ejemplo: json_encode($data) . $timestamp
        // Aseguramos un orden consistente ordenando las llaves del array
        ksort($data);
        $payloadToSign = json_encode($data) . $timestamp;
        $secretKey = $equipo->agente_token;

        $expectedSignature = hash_hmac('sha256', $payloadToSign, $secretKey);

        if (!hash_equals($expectedSignature, $signature)) {
            return response()->json(['error' => 'Firma HMAC inválida'], 401);
        }

        // 2. Validate incoming data
        $validated = $request->validate([
            'data.copia_bn' => 'nullable|integer|min:0',
            'data.copia_color' => 'nullable|integer|min:0',
            'data.impresion_bn' => 'nullable|integer|min:0',
            'data.impresion_color' => 'nullable|integer|min:0',
            'data.fax_bn' => 'nullable|integer|min:0',
            'data.total_bn' => 'nullable|integer|min:0',
            'data.total_color' => 'nullable|integer|min:0',
            'data.total_global' => 'nullable|integer|min:0',
            'data.duplex' => 'nullable|integer|min:0',
            'data.escaneos' => 'nullable|integer|min:0',
            'data.toner_negro' => 'nullable|integer|min:0|max:100',
            'data.toner_cyan' => 'nullable|integer|min:0|max:100',
            'data.toner_magenta' => 'nullable|integer|min:0|max:100',
            'data.toner_amarillo' => 'nullable|integer|min:0|max:100',
            'data.modelo' => 'nullable|string',
            'data.serial' => 'nullable|string',
        ]);

        $data = $request->input('data', []);

        // 3. Save the reading
        $lectura = LecturaContador::create([
            'tenant_id' => $equipo->tenant_id, // Inyectar tenant_id del equipo
            'equipo_id' => $equipo->id,
            'timestamp' => now(),
            'copia_bn' => $data['copia_bn'] ?? 0,
            'copia_color' => $data['copia_color'] ?? 0,
            'impresion_bn' => $data['impresion_bn'] ?? 0,
            'impresion_color' => $data['impresion_color'] ?? 0,
            'fax_bn' => $data['fax_bn'] ?? 0,
            'total_bn' => $data['total_bn'] ?? 0,
            'total_color' => $data['total_color'] ?? 0,
            'total_global' => $data['total_global'] ?? 0,
            'duplex' => $data['duplex'] ?? 0,
            'escaneos' => $data['escaneos'] ?? 0,
            'toner_negro' => $data['toner_negro'] ?? null,
            'toner_cyan' => $data['toner_cyan'] ?? null,
            'toner_magenta' => $data['toner_magenta'] ?? null,
            'toner_amarillo' => $data['toner_amarillo'] ?? null,
        ]);

        // 4. Update equipment model if provided
        if (!empty($data['modelo']) && $equipo->modelo !== $data['modelo']) {
            $equipo->update(['modelo' => $data['modelo']]);
        }

        // 5. Check for toner alerts
        $this->checkTonerAlerts($equipo, $data);

        return response()->json([
            'status' => 'ok',
            'message' => 'Lectura registrada exitosamente',
            'lectura_id' => $lectura->id
        ], 201);
    }

    /**
     * Generate alerts if toner is low
     */
    private function checkTonerAlerts($equipo, $data)
    {
        $tonerFields = [
            'toner_negro' => 'Negro',
            'toner_cyan' => 'Cyan',
            'toner_magenta' => 'Magenta',
            'toner_amarillo' => 'Amarillo'
        ];

        foreach ($tonerFields as $field => $name) {
            if (isset($data[$field]) && $data[$field] !== null && $data[$field] <= 10) {
                // Check if there's already an unresolved alert for this
                $existingAlert = Alerta::where('equipo_id', $equipo->id)
                    ->where('tipo', 'toner_bajo')
                    ->where('mensaje', 'like', "%{$name}%")
                    ->where('resuelta', false)
                    ->first();

                if (!$existingAlert) {
                    Alerta::create([
                        'tenant_id' => $equipo->tenant_id, // Inyectar tenant_id
                        'equipo_id' => $equipo->id,
                        'tipo' => 'toner_bajo',
                        'mensaje' => "Tóner {$name} bajo: {$data[$field]}% - Equipo {$equipo->serial}",
                    ]);
                }
            }
        }
    }

    /**
     * GET /api/status
     * Health check endpoint
     */
    public function status()
    {
        return response()->json([
            'status' => 'online',
            'version' => '1.0.0',
            'timestamp' => now()->toISOString()
        ]);
    }
}
