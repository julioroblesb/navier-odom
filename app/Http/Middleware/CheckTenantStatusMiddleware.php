<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckTenantStatusMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // If it's a super admin, they don't have a tenant, skip
        if (auth()->check() && auth()->user()->is_super_admin) {
            return $next($request);
        }

        // Suspension logic is now handled in the views (Read-Only mode)

        return $next($request);
    }
}
