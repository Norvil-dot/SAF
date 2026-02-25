<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TenantMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->tenant_id) {
            // Logica adicional para establecer el tenant activo en la aplicacion, si es necesario.
            // Con el TenantScope global, esto se hace en las consultas.
            
            // Ejemplo de aborto si el tenant esta inactivo
            if (auth()->user()->tenant->estado !== 'activo') {
                abort(403, 'Tenant inactivo.');
            }
        }

        return $next($request);
    }
}
