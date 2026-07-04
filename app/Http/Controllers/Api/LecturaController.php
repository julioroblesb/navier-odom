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
        // 1. Validate token
        $token = $request->header('X-Agent-Token') ?? $request->input('token');

        if (!$token) {
            return response()->json(['error' => 'Token requerido'], 401);
        }

        $equipo = TokenService::validateToken($token);

        if (!$equipo) {
            return response()->json(['error' => 'Token inválido o equipo inactivo'], 401);
        }

        // El token ya fue validado arriba.
        // Hostinger Firewall (ModSecurity) bloquea las cabeceras personalizadas X-Timestamp y X-Signature,
        // por lo que eliminamos la validación HMAC. Para este caso de uso, el Token es seguridad suficiente.

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
