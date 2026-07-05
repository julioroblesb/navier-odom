<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    public $timestamps = false;
    const UPDATED_AT = null;
    
    protected $fillable = [
        'user_id',
        'tenant_id',
        'target_type',
        'target_id',
        'action',
        'ip_address',
        'previous_hash',
        'current_hash',
        'created_at'
    ];

    protected $casts = [
        'created_at' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Calcula el hash criptográfico para la cadena de auditoría (Tamper-Evident)
     */
    public static function calculateHash(string $previousHash, array $data): string
    {
        // Asegurar consistencia en el payload (valores en string, orden definido)
        $payload = implode('|', [
            $data['tenant_id'] ?? '',
            $data['user_id'] ?? '',
            $data['target_type'] ?? '',
            $data['target_id'] ?? '',
            $data['action'] ?? '',
            $data['ip_address'] ?? '',
            isset($data['created_at']) ? (\Carbon\Carbon::parse($data['created_at'])->toIso8601String()) : ''
        ]);

        return hash('sha256', $previousHash . '|' . $payload);
    }
}
