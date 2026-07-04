<?php

namespace App\Services;

class TokenService
{
    /**
     * Generate a unique token for an equipment/agent
     * The token is an HMAC-SHA256 hash of the equipment serial + a secret key
     * This ensures each token is unique and tied to a specific equipment
     */
    public static function generateToken(string $serial): string
    {
        $secret = config('app.key'); // Laravel's app key as secret
        $data = $serial . '|' . time() . '|' . bin2hex(random_bytes(16));
        return hash_hmac('sha256', $data, $secret);
    }

    /**
     * Validate that a token exists and matches an equipment
     */
    public static function validateToken(string $token): ?\App\Models\Equipo
    {
        return \App\Models\Equipo::where('agente_token', $token)
            ->where('activo', true)
            ->first();
    }
}
