<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Agrega headers de seguridad HTTP estándar a cada respuesta.
 *
 * Protege contra:
 *  - Clickjacking (X-Frame-Options)
 *  - MIME-type sniffing (X-Content-Type-Options)
 *  - XSS reflejado en navegadores legacy (X-XSS-Protection)
 *  - Referrer information leak (Referrer-Policy)
 *  - Acceso innecesario a APIs del navegador (Permissions-Policy)
 */
class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        /** @var Response $response */
        $response = $next($request);

        $response->headers->set('X-Frame-Options',        'SAMEORIGIN');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-XSS-Protection',       '1; mode=block');
        $response->headers->set('Referrer-Policy',        'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy',     'camera=(), microphone=(), geolocation=(), payment=()');

        return $response;
    }
}
