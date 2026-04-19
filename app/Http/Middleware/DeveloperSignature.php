<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DeveloperSignature
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        $response->headers->set('X-Developed-By', 'khoirony.com');
        $response->headers->set('X-Developer-Contact', 'khoironyarief08@gmail.com');

        return $response;
    }
}
