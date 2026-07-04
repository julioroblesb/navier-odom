<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LecturaContador extends Model
{
    protected $table = 'lecturas_contadores';

    /**
     * Lecturas son registros inmutables (solo created_at, sin updated_at).
     */
    const UPDATED_AT = null;

    protected $fillable = [
        'equipo_id',
        'timestamp',
        'copia_bn',
        'copia_color',
        'impresion_bn',
        'impresion_color',
        'fax_bn',
        'total_bn',
        'total_color',
        'total_global',
        'duplex',
        'escaneos',
        'toner_negro',
        'toner_cyan',
        'toner_magenta',
        'toner_amarillo',
    ];

    protected $casts = [
        'timestamp' => 'datetime',
        'copia_bn' => 'integer',
        'copia_color' => 'integer',
        'impresion_bn' => 'integer',
        'impresion_color' => 'integer',
        'fax_bn' => 'integer',
        'total_bn' => 'integer',
        'total_color' => 'integer',
        'total_global' => 'integer',
        'duplex' => 'integer',
        'escaneos' => 'integer',
        'toner_negro' => 'integer',
        'toner_cyan' => 'integer',
        'toner_magenta' => 'integer',
        'toner_amarillo' => 'integer',
    ];

    /**
     * Equipo al que pertenece la lectura.
     */
    public function equipo(): BelongsTo
    {
        return $this->belongsTo(Equipo::class);
    }
}
