<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cliente extends Model
{
    protected $table = 'clientes';

    protected $fillable = [
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
     * Equipos asignados al cliente.
     */
    public function equipos(): HasMany
    {
        return $this->hasMany(Equipo::class);
    }

    /**
     * Contratos del cliente.
     */
    public function contratos(): HasMany
    {
        return $this->hasMany(Contrato::class);
    }
}
