<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Cliente extends Model
{
    use \App\Models\Concerns\BelongsToTenant;

    protected $table = 'clientes';

    protected $fillable = [
        'tenant_id',
        'razon_social',
        'ruc',
        'direccion',
        'distrito',
        'contacto_nombre',
        'contacto_telefono',
        'contacto_email',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    /**
     * Sucursales del cliente.
     */
    public function sucursales(): HasMany
    {
        return $this->hasMany(Sucursal::class);
    }

    /**
     * Equipos asignados al cliente (a través de sucursales).
     */
    public function equipos(): HasManyThrough
    {
        return $this->hasManyThrough(Equipo::class, Sucursal::class);
    }

    /**
     * Contratos del cliente.
     */
    public function contratos(): HasMany
    {
        return $this->hasMany(Contrato::class);
    }
}
