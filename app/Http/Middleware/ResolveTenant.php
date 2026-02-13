<?php

namespace App\Http\Middleware;

use App\Services\Tenant\TenantService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ResolveTenant
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        if (! auth()->check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $user = auth()->user();

        if ($user->is_super_admin) {
            return $next($request);
        }

        if (! $user->tenant_id) {
            return response()->json(['error' => 'Tenant not found'], 403);
        }

        app(TenantService::class)->setTenant($user->tenant_id);

        return $next($request);
    }
}
