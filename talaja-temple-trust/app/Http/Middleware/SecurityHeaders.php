<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $isProduction = app()->environment('production');

        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'geolocation=(), camera=(), microphone=()');

        if ($isProduction) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');
            $response->headers->set(
                'Content-Security-Policy',
                "default-src 'self'; ".
                "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://checkout.razorpay.com https://www.youtube.com; ".
                "style-src 'self' 'unsafe-inline' https://fonts.bunny.net https://fonts.googleapis.com; ".
                "font-src 'self' https://fonts.bunny.net https://fonts.gstatic.com data:; ".
                "img-src 'self' data: blob: https:; ".
                "frame-src 'self' https://www.youtube.com https://player.vimeo.com; ".
                "connect-src 'self' https://*.razorpay.com;"
            );
        }

        return $response;
    }
}
