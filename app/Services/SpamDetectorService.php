<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;

class SpamDetectorService
{
    /**
     * Minimum human filling time in seconds.
     * Bots typically submit in < 2 seconds.
     */
    public const MIN_HUMAN_SECONDS = 2.5;

    /**
     * High-risk spam keyword patterns (case-insensitive).
     */
    protected static array $spamPatterns = [
        '/\b(casino|slot\s*gacor|slot\s*online|judi\s*online|poker\s*online|sbobet)\b/i',
        '/\b(crypto|bitcoin|ethereum|forex\s*trading|invest\s*\$|binary\s*option)\b/i',
        '/\b(viagra|cialis|pharmacy\s*online|diet\s*pills)\b/i',
        '/\b(seo\s*ranking|rank\s*your\s*website|first\s*page\s*of\s*google|page\s*1\s*on\s*google|backlink\s*package|pbn\s*links?|guest\s*posts?)\b/i',
        '/\b(telegram\s*@|t\.me\/|wa\.me\/|whatsapp\s*blast)\b/i',
        '/\b(adult\s*dating|sugar\s*mommy|sugar\s*daddy|onlyfans|porn|xxx)\b/i',
        '/[\x{0400}-\x{04FF}]/u', // Cyrillic alphabet (common Russian spam bot signatures)
    ];

    /**
     * Generate an encrypted, timestamped anti-bot token for the form.
     */
    public static function generateTimeToken(): string
    {
        return Crypt::encryptString((string) time());
    }

    /**
     * Evaluate incoming contact submission for spam indicators.
     * Returns ['is_spam' => bool, 'reason' => ?string].
     */
    public static function check(Request $request, array $data): array
    {
        $ip = $request->ip() ?? 'unknown';

        // 1. Primary Honeypot Check (website_url)
        if (!empty($request->input('website_url'))) {
            self::logSpam($ip, 'Honeypot field (website_url) filled.');
            return ['is_spam' => true, 'reason' => 'Honeypot triggered'];
        }

        // 2. Secondary Honeypot Check (phone_confirmation)
        if (!empty($request->input('phone_confirmation'))) {
            self::logSpam($ip, 'Secondary honeypot (phone_confirmation) filled.');
            return ['is_spam' => true, 'reason' => 'Secondary honeypot triggered'];
        }

        // 3. Time-Gate Check (submission speed)
        $timeToken = $request->input('_token_time');
        if (!empty($timeToken)) {
            try {
                $renderedTime = (int) Crypt::decryptString($timeToken);
                $elapsedSeconds = time() - $renderedTime;

                if ($elapsedSeconds < self::MIN_HUMAN_SECONDS) {
                    self::logSpam($ip, "Submitted too fast ({$elapsedSeconds}s < " . self::MIN_HUMAN_SECONDS . 's). Probable bot script.');
                    return ['is_spam' => true, 'reason' => 'Submission speed under human threshold'];
                }

                // If form is older than 48 hours, reject stale bot replay
                if ($elapsedSeconds > 172800) {
                    self::logSpam($ip, "Stale form token ({$elapsedSeconds}s > 48h).");
                    return ['is_spam' => true, 'reason' => 'Stale form token'];
                }
            } catch (\Throwable $e) {
                // If token was tampered with, treat as suspicious bot
                self::logSpam($ip, 'Invalid or forged time token.');
                return ['is_spam' => true, 'reason' => 'Invalid time token'];
            }
        }

        // 4. Excessive Hyperlink Count in Message
        $messageContent = $data['message'] ?? '';
        $nameContent = $data['name'] ?? '';
        $fullPayload = $nameContent . ' ' . $messageContent;

        $linkCount = preg_match_all('/https?:\/\//i', $fullPayload);
        if ($linkCount > 1) {
            self::logSpam($ip, "Excessive link count ({$linkCount} URLs detected).");
            return ['is_spam' => true, 'reason' => 'Excessive links in payload'];
        }

        // 5. Keyword & Language Heuristics
        foreach (self::$spamPatterns as $pattern) {
            if (preg_match($pattern, $fullPayload)) {
                self::logSpam($ip, "Matched spam pattern: {$pattern}");
                return ['is_spam' => true, 'reason' => 'Spam keyword signature detected'];
            }
        }

        return ['is_spam' => false, 'reason' => null];
    }

    /**
     * Log spam detection to security audit log.
     */
    protected static function logSpam(string $ip, string $reason): void
    {
        Log::warning("[SPAM DEFENSE] Blocked bot contact inquiry from IP {$ip}. Reason: {$reason}");
    }
}
