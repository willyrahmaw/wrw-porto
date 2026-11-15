<?php

namespace App\Http\Middleware;

use App\Services\IpBanService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IpBanMiddleware
{
    /**
     * Intercept every HTTP request to enforce IP blacklist restrictions.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $clientIp = $request->ip();

        if ($clientIp && ($ban = IpBanService::isBanned($clientIp))) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'error' => 'IP_BANNED',
                    'message' => 'Akses ditolak. Alamat IP Anda sedang diblokir oleh protokol keamanan sistem.',
                    'reason' => $ban->reason,
                    'incident_ref' => $ban->incident_hash,
                    'strike_tier' => $ban->tier_label,
                    'is_permanent' => $ban->is_permanent,
                    'banned_until' => $ban->banned_until?->toIso8601String(),
                ], 403);
            }

            $response = response()->view('errors.ip-banned', [
                'ban' => $ban,
                'ip' => $clientIp,
            ], 403);

            if (!$ban->is_permanent && $ban->banned_until) {
                $seconds = max(1, $ban->banned_until->diffInSeconds(now()));
                $response->headers->set('Retry-After', (string) $seconds);
            }

            return $response;
        }

        return $next($request);
    }
}

