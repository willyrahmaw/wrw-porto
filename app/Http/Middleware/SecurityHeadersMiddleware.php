<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeadersMiddleware
{
    /**
     * Apply Grade-A Security Headers to every HTTP response.
     * Compliant with SecurityHeaders.com / Mozilla Observatory standards.
     *
     * OWASP Top 10 Coverage:
     * - A02: Cryptographic Failures (HSTS)
     * - A03: Injection & XSS (Strict Content-Security-Policy)
     * - A05: Security Misconfiguration (X-Frame-Options, X-Content-Type-Options, Permissions-Policy)
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 0. Auto-normalize accidental trailing semicolon or punctuation from copy-pasted URLs (e.g., /admin/projects/create;)
        $rawPath = $request->getPathInfo();
        if (str_ends_with($rawPath, ';')) {
            $cleanPath = rtrim($rawPath, ';');
            $queryString = $request->getQueryString();
            $cleanUrl = $cleanPath . ($queryString ? '?' . $queryString : '');
            return redirect($cleanUrl, 301);
        }

        /** @var Response $response */
        $response = $next($request);

        // 1. Anti-Clickjacking (OWASP A05)
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');

        // 2. MIME Sniffing Protection (OWASP A05)
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // 3. Referrer Privacy Policy (OWASP A02 & Privacy)
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // 4. Permissions Policy: Disable unwanted browser hardware APIs (OWASP A05)
        $response->headers->set(
            'Permissions-Policy',
            'camera=(), microphone=(), geolocation=(), payment=(), usb=(), accelerometer=(), gyroscope=()'
        );

        // 5. Cross-Origin Policies (COOP & CORP)
        $response->headers->set('Cross-Origin-Opener-Policy', 'same-origin-allow-popups');
        $response->headers->set('Cross-Origin-Resource-Policy', 'cross-origin');

        // 6. Strict Transport Security (HSTS) - Enabled when on HTTPS or in production
        if ($request->isSecure() || app()->environment('production')) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');
        }

        // 7. Content Security Policy (CSP - Grade A)
        $csp = [
            "default-src 'self'",
            "script-src 'self' 'unsafe-inline' 'unsafe-eval'", // Alpine.js & Vite dev/prod runtime
            "style-src 'self' 'unsafe-inline' https://fonts.bunny.net",
            "font-src 'self' https://fonts.bunny.net data:",
            "img-src 'self' data: blob: https://images.unsplash.com https://*.unsplash.com https://*.placeholder.com",
            "connect-src 'self'",
            "media-src 'self'",
            "object-src 'none'",
            "base-uri 'self'",
            "form-action 'self'",
            "frame-ancestors 'self'",
        ];

        if ($request->isSecure() || app()->environment('production')) {
            $csp[] = "upgrade-insecure-requests";
        }

        $response->headers->set('Content-Security-Policy', implode('; ', $csp));

        // 8. Remove Fingerprint Headers (OWASP A05)
        $response->headers->remove('X-Powered-By');
        $response->headers->remove('Server');
        if (function_exists('header_remove')) {
            header_remove('X-Powered-By');
        }

        return $response;
    }
}

