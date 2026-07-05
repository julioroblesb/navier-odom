<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tenant extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre_empresa',
        'estado',
        'plan_type',
        'demo_expires_at',
        'billing_status'
    ];

    protected $casts = [
        'demo_expires_at' => 'date'
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function clientes()
    {
        return $this->hasMany(Cliente::class);
    }

    public function equipos()
    {
        return $this->hasMany(Equipo::class);
    }
}
