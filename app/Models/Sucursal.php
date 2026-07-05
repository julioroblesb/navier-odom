<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sucursal extends Model
{
    protected $table = 'sucursales';

    protected $fillable = [
        'cliente_id',
        'nombre',
        'direccion',
        'contacto',
        'telefono',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function equipos()
    {
        return $this->hasMany(Equipo::class);
    }
}
