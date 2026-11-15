<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow, noarchive">
    <title>ACCESS DENIED // IP RESTRICTED</title>
    @vite(['resources/css/app.css'])
    <style>
        @keyframes scanline {
            0% { transform: translateY(-100%); }
            100% { transform: translateY(1000%); }
        }
        .scanline-effect {
            background: linear-gradient(to bottom, transparent 50%, rgba(255, 42, 85, 0.04) 51%);
            background-size: 100% 4px;
        }
        .alert-glow {
            text-shadow: 0 0 20px rgba(255, 42, 85, 0.6);
        }
    </style>
</head>
<body class="bg-[#050505] text-[#EDEDE8] font-mono selection:bg-[#FF2A55] selection:text-black min-h-screen flex flex-col justify-between relative overflow-hidden p-6 md:p-12">

    {{-- Scanlines & Grain Background Layer --}}
    <div class="pointer-events-none fixed inset-0 z-0 scanline-effect opacity-80" aria-hidden="true"></div>
    <div class="pointer-events-none fixed inset-0 z-0 bg-[radial-gradient(ellipse_at_center,_var(--tw-gradient-stops))] from-[#FF2A55]/10 via-transparent to-transparent opacity-40" aria-hidden="true"></div>

    {{-- Top HUD Terminal Header --}}
    <header class="relative z-10 flex flex-wrap items-center justify-between gap-4 border-b border-[#2A1218] pb-6">
        <div class="flex items-center gap-3">
            <span class="relative flex h-3 w-3">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#FF2A55] opacity-75"></span>
                <span class="relative inline-flex rounded-full h-3 w-3 bg-[#FF2A55]"></span>
            </span>
            <span class="text-xs uppercase tracking-[0.25em] text-[#FF2A55] font-bold">
                DEFENSE PROTOCOL // LEVEL-4 LOCKOUT
            </span>
        </div>

        <div class="text-[11px] uppercase tracking-widest text-neutral-500">
            INCIDENT ID: <span class="text-neutral-300 font-bold">{{ $ban->incident_hash ?? 'SEC-' . strtoupper(substr(md5($ip), 0, 8)) }}</span>
        </div>
    </header>

    {{-- Center Security Lockout Dossier --}}
    <main class="relative z-10 max-w-4xl mx-auto w-full my-auto py-10">
        <div class="border-2 border-[#FF2A55]/80 bg-[#0A0607]/90 backdrop-blur-xl shadow-[0_0_50px_rgba(255,42,85,0.15)] p-6 sm:p-10 md:p-14 space-y-8">

            {{-- Lockout Title & Tier Badge --}}
            <div class="space-y-4">
                <div class="inline-flex items-center gap-2 px-3 py-1 bg-[#FF2A55]/15 border border-[#FF2A55] text-[11px] uppercase tracking-[0.22em] text-[#FF2A55] font-bold">
                    <span>⚠ PERINGATAN SISTEM: {{ $ban->tier_label ?? 'TIER 1 (1 JAM)' }}</span>
                </div>

                <h1 class="font-display italic text-4xl sm:text-6xl md:text-7xl text-white tracking-tight leading-none alert-glow">
                    Akses Alamat IP <span class="text-[#FF2A55] not-italic">Diblokir.</span>
                </h1>

                <p class="text-sm sm:text-base text-neutral-400 font-sans leading-relaxed max-w-2xl">
                    Perangkat dengan alamat IP Anda telah dibatasi secara otomatis oleh firewall keamanan studio karena terdeteksi melakukan tindakan yang melanggar kebijakan integritas sistem.
                </p>
            </div>

            {{-- Terminal Details Table --}}
            <div class="border border-[#38161D] bg-[#050304] divide-y divide-[#2A1218] text-xs font-mono">
                <div class="p-3.5 sm:p-4 grid grid-cols-1 sm:grid-cols-12 gap-2">
                    <span class="sm:col-span-4 text-neutral-500 uppercase tracking-widest">ALAMAT IP KLIEN</span>
                    <span class="sm:col-span-8 text-[#FF2A55] font-bold tracking-wider">{{ $ip }}</span>
                </div>

                <div class="p-3.5 sm:p-4 grid grid-cols-1 sm:grid-cols-12 gap-2">
                    <span class="sm:col-span-4 text-neutral-500 uppercase tracking-widest">ALASAN PEMBLOKIRAN</span>
                    <span class="sm:col-span-8 text-neutral-200 font-medium">{{ $ban->reason ?? 'Anomali Keamanan Terdeteksi' }}</span>
                </div>

                <div class="p-3.5 sm:p-4 grid grid-cols-1 sm:grid-cols-12 gap-2">
                    <span class="sm:col-span-4 text-neutral-500 uppercase tracking-widest">TINGKAT PELANGGARAN</span>
                    <span class="sm:col-span-8 text-neutral-300">
                        Strike {{ $ban->strike_count ?? 1 }} / 3
                        @if(($ban->strike_count ?? 1) >= 3 || $ban->is_permanent)
                            <span class="ml-2 px-2 py-0.5 bg-[#FF2A55] text-black font-bold uppercase text-[10px]">PEMBLOKIRAN PERMANEN</span>
                        @elseif(($ban->strike_count ?? 1) == 2)
                            <span class="ml-2 text-[#FF5B1F] font-bold">(Durasi: 3 Jam)</span>
                        @else
                            <span class="ml-2 text-neutral-400">(Durasi: 1 Jam)</span>
                        @endif
                    </span>
                </div>

                <div class="p-3.5 sm:p-4 grid grid-cols-1 sm:grid-cols-12 gap-2">
                    <span class="sm:col-span-4 text-neutral-500 uppercase tracking-widest">STATUS SISA WAKTU</span>
                    <span class="sm:col-span-8 font-bold {{ $ban->is_permanent ? 'text-[#FF2A55]' : 'text-neutral-200' }}">
                        @if($ban->is_permanent)
                            PERMANEN — TIDAK AKAN DIBUKA OTOMATIS
                        @elseif($ban->banned_until)
                            Hingga {{ $ban->banned_until->format('d M Y, H:i:s') }} WIB ({{ $ban->banned_until->diffForHumans(now(), true) }} lagi)
                        @else
                            1 Jam dari waktu kejadian
                        @endif
                    </span>
                </div>
            </div>

            {{-- Policy Notice --}}
            <div class="p-4 border border-[#2A1218] bg-[#12080A]/60 text-[11px] text-neutral-400 leading-relaxed font-sans">
                <span class="text-[#FF2A55] font-mono font-bold block mb-1">KETENTUAN ATURAN FIREWALL:</span>
                • <strong>Pelanggaran Ke-1:</strong> Pemblokiran akses selama 1 jam.<br>
                • <strong>Pelanggaran Ke-2:</strong> Pemblokiran akses selama 3 jam.<br>
                • <strong>Pelanggaran Ke-3:</strong> Alamat IP dimasukkan ke daftar hitam permanen (*Permanent Blacklist*).
            </div>

            {{-- Reload & Contact --}}
            <div class="pt-4 flex flex-col sm:flex-row items-center justify-between gap-4">
                <button
                    type="button"
                    onclick="window.location.reload()"
                    class="w-full sm:w-auto px-6 py-3.5 bg-[#FF2A55] hover:bg-[#FF456A] text-black font-mono text-xs uppercase tracking-widest font-bold transition-colors cursor-pointer"
                >
                    ↺ Cek Status Pembukaan Akses
                </button>

                <div class="text-[11px] text-neutral-500 text-center sm:text-right">
                    Jika Anda merasa ini adalah kesalahan sistem, hubungi administrator website.
                </div>
            </div>

        </div>
    </main>

    {{-- Bottom Terminal Footer --}}
    <footer class="relative z-10 border-t border-[#2A1218] pt-6 flex flex-wrap items-center justify-between gap-4 text-[10px] uppercase tracking-widest text-neutral-600">
        <div>
            SECURITY APPARATUS // AUTO-ENFORCEMENT ENGINE v2.6
        </div>
        <div>
            TIMESTAMP: {{ now()->format('Y-m-d H:i:s') }} UTC+7
        </div>
    </footer>

</body>
</html>

