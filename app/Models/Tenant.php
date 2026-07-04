<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tenant extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre_empresa',
        'estado'
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
