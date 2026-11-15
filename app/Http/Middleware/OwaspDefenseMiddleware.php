<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class OwaspDefenseMiddleware
{
    /**
     * Inspect incoming HTTP requests to defend against common OWASP Top 10 attack vectors:
     * - A01: Broken Access Control (Path traversal probes)
     * - A03: Injection (SQL injection patterns, null-byte payloads, XSS triggers)
     * - A09: Security Logging and Monitoring (Audit logs for attack probes)
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Detect Null-Byte Injection (A03)
        $rawUri = $request->getRequestUri();
        if (str_contains(urldecode($rawUri), "\0") || str_contains($rawUri, '%00')) {
            $this->logAndAbort($request, 'Null-byte injection probe detected in URI');
        }

        // 2. Detect Directory / Path Traversal attempts in request path (A01 & A03)
        $path = $request->path();
        if (preg_match('/\.\.[\/\\\\]/', $path) || str_contains($path, '../') || str_contains($path, '..\\')) {
            $this->logAndAbort($request, 'Directory traversal attempt detected in URL path');
        }

        // 3. Inspect Query Parameters for known probe signatures
        $queryString = $request->getQueryString();
        if ($queryString) {
            $decodedQuery = urldecode($queryString);
            $probeSignatures = [
                '/<script[\s>]/i',
                '/javascript\s*:/i',
                '/(union(\s+all)?\s+select)/i',
                '/information_schema/i',
                '/benchmark\s*\(/i',
                '/sleep\s*\(/i',
                '/concat\s*\(/i',
                '/--[\s\r\n]/',
                '/\/\*.*?\*\//s',
            ];

            foreach ($probeSignatures as $signature) {
                if (preg_match($signature, $decodedQuery)) {
                    $this->logAndAbort($request, 'Malicious injection probe signature detected in query string');
                }
            }
        }

        return $next($request);
    }

    /**
     * Log the security alert with client metadata and abort with 400 Bad Request.
     */
    private function logAndAbort(Request $request, string $reason): void
    {
        $clientIp = $request->ip();

        Log::warning("[OWASP Security Alert] {$reason}", [
            'ip' => $clientIp,
            'method' => $request->method(),
            'full_url' => $request->fullUrl(),
            'user_agent' => $request->userAgent(),
            'timestamp' => now()->toIso8601String(),
        ]);

        if ($clientIp) {
            $ban = \App\Services\IpBanService::ban($clientIp, "Pelanggaran Keamanan / Scripting Injection Attack ({$reason})", $request->userAgent());

            if ($request->expectsJson() || $request->is('api/*')) {
                abort(response()->json([
                    'error' => 'IP_BANNED',
                    'message' => 'Alamat IP Anda diblokir karena terdeteksi percobaan scripting injection.',
                    'incident_ref' => $ban->incident_hash,
                ], 403));
            }

            abort(response()->view('errors.ip-banned', [
                'ban' => $ban,
                'ip' => $clientIp,
            ], 403));
        }

        abort(400, 'Permintaan tidak valid atau terdeteksi potensi anomali keamanan.');
    }
}

