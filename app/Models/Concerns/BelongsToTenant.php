<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;

trait BelongsToTenant
{
    /**
     * Boot the BelongsToTenant trait for a model.
     */
    protected static function bootBelongsToTenant(): void
    {
        static::addGlobalScope('tenant', function (Builder $builder) {
            // Usar hasUser() previene un bucle infinito de recursión al iniciar sesión
            if (\Illuminate\Support\Facades\Auth::hasUser() && $tenantId = \Illuminate\Support\Facades\Auth::user()->tenant_id) {
                // Prevent ambiguous column names in joins by prefixing with table name
                $builder->where($builder->getModel()->getTable() . '.tenant_id', $tenantId);
            }
        });

        static::creating(function ($model) {
            // Automatically assign tenant_id on creation
            if (empty($model->tenant_id) && \Illuminate\Support\Facades\Auth::hasUser()) {
                $model->tenant_id = \Illuminate\Support\Facades\Auth::user()->tenant_id;
            }
        });
    }

    /**
     * Get the tenant that owns the model.
     */
    public function tenant()
    {
        return $this->belongsTo(\App\Models\Tenant::class);
    }
}
