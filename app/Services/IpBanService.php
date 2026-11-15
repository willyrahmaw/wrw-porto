<?php

namespace App\Services;

use App\Models\IpBan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class IpBanService
{
    /**
     * Cache key for quick check to avoid querying database on every request.
     */
    private static function cacheKey(string $ip): string
    {
        return 'ip_ban_state:' . md5($ip);
    }

    /**
     * Check if the given IP address is currently banned.
     */
    public static function isBanned(string $ip): ?IpBan
    {
        // Whitelist local development / loopback if needed, but allow testing
        $cacheKey = self::cacheKey($ip);

        // Check short-lived cache (30s) or query database
        $record = Cache::remember($cacheKey, 20, function () use ($ip) {
            return IpBan::query()->where('ip_address', $ip)->first();
        });

        if ($record instanceof IpBan && $record->isActive()) {
            return $record;
        }

        return null;
    }

    /**
     * Apply an IP ban with tiered duration based on prior strike history:
     * - Strike 1: 1 Hour ban
     * - Strike 2: 3 Hours ban
     * - Strike 3+: Permanent ban
     */
    public static function ban(string $ip, string $reason, ?string $userAgent = null): IpBan
    {
        $existing = IpBan::query()->where('ip_address', $ip)->first();

        $currentStrikes = $existing ? $existing->strike_count : 0;
        $nextStrike = $currentStrikes + 1;

        $isPermanent = false;
        $bannedUntil = null;

        if ($nextStrike === 1) {
            $bannedUntil = now()->addHour();
        } elseif ($nextStrike === 2) {
            $bannedUntil = now()->addHours(3);
        } else {
            $isPermanent = true;
            $bannedUntil = null; // null indicates indefinite/permanent ban
        }

        $incidentHash = 'SEC-' . strtoupper(substr(md5($ip . microtime(true)), 0, 10));

        $ban = IpBan::query()->updateOrCreate(
            ['ip_address' => $ip],
            [
                'reason' => $reason,
                'strike_count' => $nextStrike,
                'banned_until' => $bannedUntil,
                'is_permanent' => $isPermanent,
                'user_agent' => $userAgent ? substr($userAgent, 0, 500) : null,
                'incident_hash' => $incidentHash,
                'last_violation_at' => now(),
            ]
        );

        // Invalidate cache immediately
        Cache::forget(self::cacheKey($ip));
        Cache::put(self::cacheKey($ip), $ban, 20);

        Log::warning("[FIREWALL] IP Banned: {$ip} | Tier: Strike {$nextStrike} | Reason: {$reason} | Hash: {$incidentHash}");

        return $ban;
    }

    /**
     * Unban an IP address and remove its blacklist record completely.
     */
    public static function unban(IpBan|int|string $target): bool
    {
        if ($target instanceof IpBan) {
            $ip = $target->ip_address;
            $target->delete();
        } elseif (is_numeric($target)) {
            $ban = IpBan::find($target);
            if ($ban) {
                $ip = $ban->ip_address;
                $ban->delete();
            } else {
                return false;
            }
        } else {
            $ip = (string) $target;
            IpBan::where('ip_address', $ip)->delete();
        }

        Cache::forget(self::cacheKey($ip));
        Cache::forget('ip_login_fails:' . $ip);
        Cache::forget('ip_throttle_strikes:' . $ip);

        Log::info("[FIREWALL] IP Unbanned: {$ip}");

        return true;
    }

    /**
     * Record a failed admin login attempt.
     * Banned automatically if password is wrong 2 times.
     */
    public static function recordLoginFailure(string $ip, ?string $userAgent = null): int
    {
        $key = 'ip_login_fails:' . $ip;
        $count = (int) Cache::get($key, 0) + 1;
        Cache::put($key, $count, now()->addHours(2));

        if ($count >= 2) {
            self::ban($ip, "Percobaan login administrator gagal {$count}x berturut-turut", $userAgent);
            Cache::forget($key);
        }

        return $count;
    }

    /**
     * Clear failed login attempts after successful authentication.
     */
    public static function clearLoginFailures(string $ip): void
    {
        Cache::forget('ip_login_fails:' . $ip);
    }

    /**
     * Record excessive rate limiting / spamming behavior.
     * Triggers ban if repeated.
     */
    public static function recordThrottlingViolation(string $ip, string $action = 'request', ?string $userAgent = null): int
    {
        $key = 'ip_throttle_strikes:' . $ip;
        $count = (int) Cache::get($key, 0) + 1;
        Cache::put($key, $count, now()->addMinutes(30));

        if ($count >= 2) {
            self::ban($ip, "Aktivitas bot / Pengiriman data berlebihan melampaui batas kecepatan (Throttling {$action})", $userAgent);
            Cache::forget($key);
        }

        return $count;
    }
}

