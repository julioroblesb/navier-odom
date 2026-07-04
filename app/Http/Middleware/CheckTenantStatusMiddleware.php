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

        // Check if the user belongs to a tenant and if it's suspended
        if (auth()->check() && auth()->user()->tenant) {
            if (auth()->user()->tenant->estado === 'suspendido') {
                return response()->view('errors.suspended');
            }
        }

        return $next($request);
    }
}
