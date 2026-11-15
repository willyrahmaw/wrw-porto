<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->redirectTo(
            guests: '/admin/login',
            users: '/admin'
        );

        // Firewall IP Blacklist, Grade-A Security Headers & OWASP Top 10 Defenses
        $middleware->append(\App\Http\Middleware\IpBanMiddleware::class);
        $middleware->append(\App\Http\Middleware\SecurityHeadersMiddleware::class);
        $middleware->append(\App\Http\Middleware\OwaspDefenseMiddleware::class);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );

        // Auto-ban on excessive rate limit / throttling violations
        $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException $e, Request $request) {
            $clientIp = $request->ip();
            if ($clientIp) {
                \App\Services\IpBanService::recordThrottlingViolation($clientIp, $request->path(), $request->userAgent());
                if ($ban = \App\Services\IpBanService::isBanned($clientIp)) {
                    if ($request->expectsJson() || $request->is('api/*')) {
                        return response()->json([
                            'error' => 'IP_BANNED',
                            'message' => 'Alamat IP Anda diblokir karena melakukan spamming / request berlebihan.',
                            'incident_ref' => $ban->incident_hash,
                        ], 403);
                    }
                    return response()->view('errors.ip-banned', [
                        'ban' => $ban,
                        'ip' => $clientIp,
                    ], 403);
                }
            }
        });
    })->create();
