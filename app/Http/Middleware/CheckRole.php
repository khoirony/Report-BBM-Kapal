<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Pastikan user sudah login dan rolenya sesuai dengan parameter yang diminta di route
        if (!auth()->check() || auth()->user()->role !== $role) {
            // Jika tidak sesuai, lemparkan error 403 Forbidden
            abort(403, 'Akses ditolak. Anda tidak memiliki izin untuk melihat halaman ini.');
        }

        return $next($request);
    }
}
