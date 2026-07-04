<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Alerta extends Model
{
    use \App\Models\Concerns\BelongsToTenant;

    protected $table = 'alertas';

    protected $fillable = [
        'tenant_id',
        'equipo_id',
        'tipo',
        'mensaje',
        'resuelta',
    ];

    protected $casts = [
        'resuelta' => 'boolean',
    ];

    /**
     * Tipos de alerta válidos.
     */
    const TIPO_SIN_REPORTE = 'sin_reporte';
    const TIPO_TONER_BAJO = 'toner_bajo';
    const TIPO_EQUIPO_OFFLINE = 'equipo_offline';

    /**
     * Equipo al que pertenece la alerta.
     */
    public function equipo(): BelongsTo
    {
        return $this->belongsTo(Equipo::class);
    }

    /**
     * Scope para alertas no resueltas.
     */
    public function scopeActivas($query)
    {
        return $query->where('resuelta', false);
    }
}
