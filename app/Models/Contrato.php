<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Contrato extends Model
{
    protected $table = 'contratos';

    protected $fillable = [
        'cliente_id',
        'equipo_id',
        'fecha_inicio',
        'fecha_fin',
        'tarifa_bn',
        'tarifa_color',
        'paginas_incluidas_bn',
        'paginas_incluidas_color',
        'renta_mensual',
        'activo',
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
        'tarifa_bn' => 'decimal:4',
        'tarifa_color' => 'decimal:4',
        'paginas_incluidas_bn' => 'integer',
        'paginas_incluidas_color' => 'integer',
        'renta_mensual' => 'decimal:2',
        'activo' => 'boolean',
    ];

    /**
     * Cliente del contrato.
     */
    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    /**
     * Equipo del contrato.
     */
    public function equipo(): BelongsTo
    {
        return $this->belongsTo(Equipo::class);
    }
}
