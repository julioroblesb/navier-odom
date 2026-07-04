<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Licencia extends Model
{
    protected $table = 'licencia';

    protected $fillable = [
        'license_key',
        'machine_id',
        'empresa_nombre',
        'max_equipos',
        'fecha_vencimiento',
        'activa',
    ];

    protected $casts = [
        'max_equipos' => 'integer',
        'fecha_vencimiento' => 'date',
        'activa' => 'boolean',
    ];

    protected $hidden = [
        'license_key',
    ];

    /**
     * Verificar si la licencia está vigente.
     */
    public function estaVigente(): bool
    {
        return $this->activa && $this->fecha_vencimiento->isFuture();
    }

    /**
     * Verificar si se pueden agregar más equipos.
     */
    public function permiteNuevoEquipo(): bool
    {
        $equiposActuales = \App\Models\Equipo::where('activo', true)->count();
        return $this->estaVigente() && $equiposActuales < $this->max_equipos;
    }
}
