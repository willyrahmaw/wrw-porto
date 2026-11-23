<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\IpBanService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * Show the editorial admin login screen.
     */
    public function showLogin(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.login');
    }

    /**
     * Authenticate admin credentials with rate-limiting & security audit logging (OWASP A07 & A09).
     */
    public function login(Request $request): RedirectResponse|\Illuminate\Http\Response
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format alamat email tidak valid.',
            'password.required' => 'Kata sandi wajib diisi.',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            // OWASP A07: Regenerate session ID upon privilege elevation (prevents session fixation)
            $request->session()->regenerate();

            try {
                IpBanService::clearLoginFailures($request->ip());
            } catch (\Throwable $e) {
                // Defensive: prevent cache error from blocking login
            }

            try {
                Log::info('[Security Audit] Admin login successful', [
                    'user_id' => Auth::id(),
                    'email' => $credentials['email'],
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]);
            } catch (\Throwable $e) {
                // Defensive: prevent log file write permission error from crashing login
            }

            return redirect()->intended(route('admin.dashboard'))
                ->with('success', 'Selamat datang kembali di Studio Command Center.');
        }

        Log::warning('[Security Audit] Failed admin login attempt', [
            'attempted_email' => $credentials['email'],
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        $failCount = 1;
        try {
            $failCount = IpBanService::recordLoginFailure($request->ip(), $request->userAgent());
        } catch (\Throwable $e) {}

        if ($failCount >= 5) {
            $ban = IpBanService::isBanned($request->ip());
            if ($ban) {
                return response()->view('errors.ip-banned', [
                    'ban' => $ban,
                    'ip' => $request->ip(),
                ], 403);
            }
        }

        $remaining = max(1, 5 - $failCount);
        $warningMsg = "Kredensial login tidak cocok. Tersisa {$remaining} kesempatan lagi sebelum alamat IP diblokir otomatis.";

        return back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => $warningMsg]);
    }

    /**
     * Emergency unban for the portfolio owner if their IP was accidentally blacklisted.
     */
    public function emergencyUnban(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'string'],
        ]);

        $admin = \App\Models\User::query()->first();

        if ($admin && \Illuminate\Support\Facades\Hash::check($request->input('password'), $admin->password)) {
            $clientIp = $request->ip();
            if ($clientIp) {
                IpBanService::unban($clientIp);
            }

            Auth::login($admin);
            $request->session()->regenerate();

            Log::info("[FIREWALL] Emergency unban authorized by admin password for IP: {$clientIp}");

            return redirect()->route('admin.dashboard')
                ->with('success', "Alamat IP {$clientIp} berhasil dipulihkan dari firewall dan Anda telah berhasil masuk.");
        }

        return back()->with('unban_error', 'Kata sandi administrator salah. Buka blokir darurat ditolak.');
    }

    /**
     * Log out the admin user with session invalidation & token refresh (OWASP A07).
     */
    public function logout(Request $request): RedirectResponse
    {
        $userId = Auth::id();
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        Log::info('[Security Audit] Admin logged out', [
            'user_id' => $userId,
            'ip' => $request->ip(),
        ]);

        return redirect()->route('admin.login')
            ->with('success', 'Sesi Anda telah diakhiri dengan aman.');
    }
}

