<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Equipo extends Model
{
    use \Illuminate\Database\Eloquent\SoftDeletes;
    use \App\Models\Concerns\BelongsToTenant;

    protected $table = 'equipos';

    protected $fillable = [
        'tenant_id',
        'cliente_id',
        'sucursal_id',
        'serial',
        'modelo',
        'ip_local',
        'fecha_instalacion',
        'activo',
        'agente_token',
    ];

    protected $casts = [
        'activo' => 'boolean',
        'fecha_instalacion' => 'date',
    ];

    protected $hidden = [
        'agente_token',
    ];

    /**
     * Cliente propietario del equipo (directo).
     */
    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    /**
     * Sucursal física del equipo.
     */
    public function sucursal(): BelongsTo
    {
        return $this->belongsTo(Sucursal::class);
    }

    /**
     * Lecturas de contadores del equipo.
     */
    public function lecturas(): HasMany
    {
        return $this->hasMany(LecturaContador::class);
    }

    /**
     * Última lectura de contadores.
     */
    public function ultimaLectura(): HasOne
    {
        return $this->hasOne(LecturaContador::class)->latestOfMany('timestamp');
    }

    /**
     * Alertas del equipo.
     */
    public function alertas(): HasMany
    {
        return $this->hasMany(Alerta::class);
    }

    /**
     * Alertas activas (no resueltas).
     */
    public function alertasActivas(): HasMany
    {
        return $this->hasMany(Alerta::class)->where('resuelta', false);
    }

    /**
     * Contrato activo del equipo.
     */
    public function contratoActivo(): HasOne
    {
        return $this->hasOne(Contrato::class)->where('activo', true);
    }

    /**
     * Todos los contratos del equipo.
     */
    public function contratos(): HasMany
    {
        return $this->hasMany(Contrato::class);
    }
}
