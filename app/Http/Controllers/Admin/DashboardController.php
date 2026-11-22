<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\IpBan;
use App\Models\Message;
use App\Models\Profile;
use App\Models\Project;
use App\Models\Service;
use App\Models\Testimonial;
use App\Services\ImageOptimizerService;
use App\Services\IpBanService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Clear cached homepage data after any CMS update.
     */
    private function flushPortfolioCache(): void
    {
        Cache::forget('portfolio.home_data');
    }

    /**
     * Main Command Center Dashboard.
     */
    public function index(): View
    {
        $profile = Profile::query()->first();
        $projects = Project::query()->orderBy('sort_order')->get();
        $services = Service::query()->orderBy('sort_order')->get();
        $clients = Client::query()->orderBy('sort_order')->get();
        $testimonials = Testimonial::query()->orderBy('sort_order')->get();
        $messages = Message::query()->latest()->get();
        $unreadCount = $messages->where('is_read', false)->count();
        $bannedIps = IpBan::query()->latest()->get();

        return view('admin.dashboard', compact(
            'profile',
            'projects',
            'services',
            'clients',
            'testimonials',
            'messages',
            'unreadCount',
            'bannedIps'
        ));
    }

    /**
     * Update Profile, Bio, Socials, Photo, CV, Stats & Skills.
     */
    public function updateProfile(Request $request): RedirectResponse
    {
        $profile = Profile::query()->firstOrFail();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'initials' => ['required', 'string', 'max:10'],
            'profession' => ['required', 'string', 'max:150'],
            'tagline' => ['required', 'string', 'max:255'],
            'subheadline' => ['nullable', 'string', 'max:500'],
            'bio' => ['required', 'string'],
            'location' => ['required', 'string', 'max:100'],
            'availability_status' => ['required', 'string', 'max:150'],
            'unavailable_status' => ['nullable', 'string', 'max:150'],
            'is_available' => ['nullable', 'boolean'],
            'email' => ['required', 'email', 'max:150'],
            'whatsapp' => ['required', 'string', 'max:30'],
            'linkedin' => ['nullable', 'url', 'max:255'],
            'instagram' => ['nullable', 'url', 'max:255'],
            'github' => ['nullable', 'url', 'max:255'],
            'behance' => ['nullable', 'url', 'max:255'],
            'dribbble' => ['nullable', 'url', 'max:255'],
            'trusted_clients_count' => ['required', 'integer', 'min:1'],
            'trusted_industries_count' => ['required', 'integer', 'min:1'],
            'photo_file' => ['nullable', 'image', 'max:4096'],
            'cv_file' => ['nullable', 'mimes:pdf', 'max:8192'],
            'stats' => ['nullable', 'array'],
            'skills_raw' => ['nullable', 'array'],
            'certificates' => ['nullable', 'array'],
            'certificate_files' => ['nullable', 'array'],
            'certificate_files.*' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png,webp', 'max:10240'],
        ]);

        $photo = $profile->photo;
        if ($request->hasFile('photo_file')) {
            $photo = ImageOptimizerService::convertAndStore($request->file('photo_file'), 'profile');
        }

        $cvPath = $profile->cv_path;
        if ($request->hasFile('cv_file')) {
            $path = $request->file('cv_file')->store('cv', 'public');
            $cvPath = '/storage/' . $path;
        }

        // Parse skills groups (comma-separated string per group -> array)
        $skills = $profile->skills ?? [];
        if ($request->filled('skills_raw') && is_array($request->input('skills_raw'))) {
            $parsedSkills = [];
            foreach ($request->input('skills_raw') as $groupName => $csvItems) {
                $items = array_values(array_filter(array_map('trim', explode(',', (string) $csvItems))));
                if (!empty($groupName) && !empty($items)) {
                    $parsedSkills[$groupName] = $items;
                }
            }
            if (!empty($parsedSkills)) {
                $skills = $parsedSkills;
            }
        }

        // Parse stats
        $stats = $profile->stats;
        if ($request->has('stats') && is_array($request->input('stats'))) {
            $parsedStats = [];
            foreach ($request->input('stats') as $s) {
                if (isset($s['value']) && isset($s['label'])) {
                    $parsedStats[] = [
                        'value' => (int) $s['value'],
                        'suffix' => (string) ($s['suffix'] ?? ''),
                        'label' => (string) $s['label'],
                    ];
                }
            }
            if (!empty($parsedStats)) {
                $stats = $parsedStats;
            }
        }

        // Parse certificates with optional file uploads
        $certificates = $profile->certificates ?? [];
        if ($request->has('certificates') && is_array($request->input('certificates'))) {
            $parsedCerts = [];
            foreach ($request->input('certificates') as $idx => $cert) {
                if (is_array($cert) && !empty(trim((string) ($cert['title'] ?? '')))) {
                    $filePath = trim((string) ($cert['file_path'] ?? ''));

                    // Check if a new file was uploaded for this certificate index
                    if ($request->hasFile("certificate_files.{$idx}")) {
                        $file = $request->file("certificate_files.{$idx}");
                        $mime = $file->getMimeType();
                        if (str_starts_with($mime, 'image/')) {
                            $filePath = ImageOptimizerService::convertAndStore($file, 'certificates');
                        } else {
                            $storedPath = $file->store('certificates', 'public');
                            $filePath = '/storage/' . $storedPath;
                        }
                    }

                    $parsedCerts[] = [
                        'title' => trim((string) $cert['title']),
                        'issuer' => trim((string) ($cert['issuer'] ?? '')),
                        'year' => trim((string) ($cert['year'] ?? date('Y'))),
                        'credential_id' => trim((string) ($cert['credential_id'] ?? '')),
                        'file_path' => !empty($filePath) ? $filePath : null,
                        'url' => !empty(trim((string) ($cert['url'] ?? ''))) ? trim((string) $cert['url']) : null,
                        'badge' => !empty(trim((string) ($cert['badge'] ?? ''))) ? trim((string) $cert['badge']) : null,
                    ];
                }
            }
            $certificates = $parsedCerts;
        }

        $profile->update([
            'name' => $validated['name'],
            'initials' => $validated['initials'],
            'profession' => $validated['profession'],
            'tagline' => $validated['tagline'],
            'subheadline' => $validated['subheadline'] ?? null,
            'bio' => $validated['bio'],
            'location' => $validated['location'],
            'availability_status' => $validated['availability_status'],
            'unavailable_status' => $validated['unavailable_status'] ?? ($profile->unavailable_status ?? 'Slot Sedang Penuh • Fokus Pengerjaan Proyek Berjalan'),
            'is_available' => $request->boolean('is_available'),
            'photo' => $photo,
            'cv_path' => $cvPath,
            'email' => $validated['email'],
            'whatsapp' => $validated['whatsapp'],
            'linkedin' => $validated['linkedin'] ?? null,
            'instagram' => $validated['instagram'] ?? null,
            'github' => $validated['github'] ?? null,
            'behance' => $validated['behance'] ?? null,
            'dribbble' => $validated['dribbble'] ?? null,
            'trusted_clients_count' => $validated['trusted_clients_count'],
            'trusted_industries_count' => $validated['trusted_industries_count'],
            'stats' => $stats,
            'skills' => $skills,
            'certificates' => $certificates,
        ]);

        $this->flushPortfolioCache();

        return back()->with('success', 'Profil & Pengaturan Utama berhasil diperbarui.')->withFragment('tab-profile');
    }

    /**
     * Instantly toggle project slot availability status (Open / Closed).
     */
    public function toggleAvailability(): RedirectResponse
    {
        $profile = Profile::query()->firstOrFail();
        $newState = !$profile->is_available;
        $profile->update([
            'is_available' => $newState,
        ]);

        $this->flushPortfolioCache();

        $statusLabel = $newState
            ? 'SLOT DIBUKA ("' . ($profile->availability_status ?? 'Slot Terbuka') . '")'
            : 'SLOT DITUTUP / PENUH ("' . ($profile->unavailable_status ?? 'Slot Sedang Penuh') . '")';

        return back()->with('success', "Status ketersediaan proyek berhasil diperbarui: {$statusLabel}.");
    }

    /**
     * Show form to create a new Project (Dedicated Page).
     */
    public function createProject(): View
    {
        $project = new Project([
            'year' => date('Y'),
            'category' => 'Aplikasi Web',
            'is_featured' => true,
            'sort_order' => Project::count() + 1,
            'tech_stack' => ['Laravel 11', 'Tailwind CSS', 'Alpine.js', 'MySQL'],
        ]);

        return view('admin.projects.form', [
            'project' => $project,
            'isEdit' => false,
        ]);
    }

    /**
     * Show form to edit an existing Project (Dedicated Page).
     */
    public function editProject(Project $project): View
    {
        return view('admin.projects.form', [
            'project' => $project,
            'isEdit' => true,
        ]);
    }

    /**
     * Store or Update a Project.
     */
    public function saveProject(Request $request, ?Project $project = null): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:180'],
            'category' => ['required', 'string', 'max:80'],
            'year' => ['required', 'string', 'max:4'],
            'thumbnail_url' => ['nullable', 'string', 'max:500'],
            'thumbnail_file' => ['nullable', 'image', 'max:5120'],
            'description' => ['required', 'string'],
            'tech_stack_raw' => ['nullable', 'string'],
            'existing_gallery' => ['nullable', 'array'],
            'gallery_lines' => ['nullable', 'string'],
            'gallery_files' => ['nullable', 'array'],
            'gallery_files.*' => ['nullable', 'image', 'max:5120'],
            'link' => ['nullable', 'url', 'max:255'],
            'is_featured' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer'],
        ]);

        $thumbnail = $validated['thumbnail_url'] ?? $project?->thumbnail;
        if ($request->hasFile('thumbnail_file')) {
            $thumbnail = ImageOptimizerService::convertAndStore($request->file('thumbnail_file'), 'projects');
        }

        // Tech stack: parse comma-separated string to array
        $techStack = $project?->tech_stack ?? [];
        if ($request->has('tech_stack_raw')) {
            $techStack = array_values(array_filter(array_map('trim', explode(',', (string) $request->input('tech_stack_raw')))));
        }

        // Existing gallery (if editing and keeping selected ones)
        $gallery = [];
        if ($request->has('existing_gallery') && is_array($request->input('existing_gallery'))) {
            $gallery = array_values(array_filter(array_map('trim', $request->input('existing_gallery'))));
        }

        // Gallery lines from textarea (URLs)
        if (!empty($validated['gallery_lines'])) {
            $urlLines = array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', (string) $validated['gallery_lines']))));
            $gallery = array_merge($gallery, $urlLines);
        }

        // Gallery files uploaded directly from computer
        if ($request->hasFile('gallery_files')) {
            foreach ($request->file('gallery_files') as $gFile) {
                if ($gFile && $gFile->isValid()) {
                    $gallery[] = ImageOptimizerService::convertAndStore($gFile, 'projects/gallery');
                }
            }
        }
        $gallery = array_values(array_unique($gallery));

        $slug = $project?->slug ?? Str::slug($validated['title']) . '-' . Str::lower(Str::random(4));

        $payload = [
            'title' => $validated['title'],
            'slug' => $slug,
            'category' => $validated['category'],
            'year' => $validated['year'],
            'thumbnail' => $thumbnail,
            'description' => $validated['description'],
            'tech_stack' => $techStack,
            'gallery' => $gallery,
            'link' => $validated['link'] ?? null,
            'is_featured' => $request->boolean('is_featured'),
            'sort_order' => $validated['sort_order'] ?? ($project?->sort_order ?? (Project::count() + 1)),
        ];

        if ($project && $project->exists) {
            $project->update($payload);
            $msg = "Proyek \"{$project->title}\" berhasil diperbarui.";
        } else {
            Project::create($payload);
            $msg = 'Proyek baru berhasil ditambahkan.';
        }

        $this->flushPortfolioCache();

        return redirect()->route('admin.dashboard', ['tab' => 'projects'])->with('success', $msg);
    }

    /**
     * Delete a Project.
     */
    public function deleteProject(Project $project): RedirectResponse
    {
        $title = $project->title;
        $project->delete();
        $this->flushPortfolioCache();

        return redirect()->route('admin.dashboard', ['tab' => 'projects'])
            ->with('success', "Proyek \"{$title}\" telah dihapus.");
    }

    /**
     * Store or Update a Service.
     */
    public function saveService(Request $request, ?Service $service = null): RedirectResponse
    {
        $validated = $request->validate([
            'number' => ['required', 'string', 'max:10'],
            'title' => ['required', 'string', 'max:150'],
            'description' => ['required', 'string'],
            'deliverables_csv' => ['required', 'string'],
            'sort_order' => ['nullable', 'integer'],
        ]);

        $deliverables = array_values(array_filter(array_map('trim', explode(',', $validated['deliverables_csv']))));

        $payload = [
            'number' => $validated['number'],
            'title' => $validated['title'],
            'description' => $validated['description'],
            'deliverables' => $deliverables,
            'sort_order' => $validated['sort_order'] ?? ($service?->sort_order ?? (Service::count() + 1)),
        ];

        if ($service && $service->exists) {
            $service->update($payload);
            $msg = "Layanan \"{$service->title}\" berhasil diperbarui.";
        } else {
            Service::create($payload);
            $msg = 'Layanan baru berhasil ditambahkan.';
        }

        $this->flushPortfolioCache();

        return redirect()->route('admin.dashboard', ['tab' => 'services'])->with('success', $msg);
    }

    /**
     * Delete a Service.
     */
    public function deleteService(Service $service): RedirectResponse
    {
        $service->delete();
        $this->flushPortfolioCache();

        return redirect()->route('admin.dashboard', ['tab' => 'services'])
            ->with('success', 'Layanan berhasil dihapus.');
    }

    /**
     * Store a Client.
     */
    public function storeClient(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'industry' => ['nullable', 'string', 'max:100'],
            'sort_order' => ['nullable', 'integer'],
        ]);

        Client::create([
            'name' => strtoupper($validated['name']),
            'industry' => $validated['industry'] ?? null,
            'sort_order' => $validated['sort_order'] ?? (Client::count() + 1),
        ]);

        $this->flushPortfolioCache();

        return redirect()->route('admin.dashboard', ['tab' => 'clients'])
            ->with('success', 'Logo/Klien baru berhasil ditambahkan ke Marquee.');
    }

    /**
     * Delete a Client.
     */
    public function deleteClient(Client $client): RedirectResponse
    {
        $client->delete();
        $this->flushPortfolioCache();

        return redirect()->route('admin.dashboard', ['tab' => 'clients'])
            ->with('success', 'Klien berhasil dihapus.');
    }

    /**
     * Store or Update a Testimonial.
     */
    public function saveTestimonial(Request $request, ?Testimonial $testimonial = null): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'role' => ['required', 'string', 'max:120'],
            'company' => ['required', 'string', 'max:120'],
            'avatar' => ['nullable', 'string', 'max:500'],
            'avatar_file' => ['nullable', 'image', 'max:4096'],
            'quote' => ['required', 'string'],
            'rating' => ['nullable', 'integer', 'min:1', 'max:5'],
            'is_approved' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer'],
        ]);

        $avatar = $validated['avatar'] ?? $testimonial?->avatar;
        if ($request->hasFile('avatar_file')) {
            $avatar = ImageOptimizerService::convertAndStore($request->file('avatar_file'), 'testimonials');
        }

        $payload = [
            'name' => $validated['name'],
            'role' => $validated['role'],
            'company' => $validated['company'],
            'avatar' => $avatar,
            'quote' => $validated['quote'],
            'rating' => (int) ($validated['rating'] ?? 5),
            'is_approved' => $request->boolean('is_approved', true),
            'sort_order' => $validated['sort_order'] ?? ($testimonial?->sort_order ?? (Testimonial::count() + 1)),
        ];

        if ($testimonial && $testimonial->exists) {
            $testimonial->update($payload);
            $msg = "Testimoni dari \"{$testimonial->name}\" berhasil diperbarui.";
        } else {
            Testimonial::create($payload);
            $msg = 'Testimoni baru berhasil ditambahkan.';
        }

        $this->flushPortfolioCache();

        return redirect()->route('admin.dashboard', ['tab' => 'clients'])->with('success', $msg);
    }

    /**
     * Toggle Testimonial Approval Status (Moderation).
     */
    public function toggleTestimonialApproval(Testimonial $testimonial): RedirectResponse
    {
        $testimonial->update([
            'is_approved' => !$testimonial->is_approved,
        ]);

        $this->flushPortfolioCache();

        $statusText = $testimonial->is_approved ? 'disetujui & tayang di portofolio' : 'disembunyikan dari portofolio';

        return redirect()->route('admin.dashboard', ['tab' => 'clients'])
            ->with('success', "Testimoni dari \"{$testimonial->name}\" kini {$statusText}.");
    }

    /**
     * Delete a Testimonial.
     */
    public function deleteTestimonial(Testimonial $testimonial): RedirectResponse
    {
        $testimonial->delete();
        $this->flushPortfolioCache();

        return redirect()->route('admin.dashboard', ['tab' => 'clients'])
            ->with('success', 'Testimoni berhasil dihapus.');
    }

    /**
     * Toggle Message Read Status.
     */
    public function toggleMessageRead(Message $message): RedirectResponse
    {
        $message->update(['is_read' => !$message->is_read]);

        return redirect()->route('admin.dashboard', ['tab' => 'inbox'])
            ->with('success', 'Status pesan diperbarui.');
    }

    /**
     * Delete a Message.
     */
    public function deleteMessage(Message $message): RedirectResponse
    {
        $message->delete();

        return redirect()->route('admin.dashboard', ['tab' => 'inbox'])
            ->with('success', 'Pesan berhasil dihapus dari inbox.');
    }

    /**
     * Ban Spammer IP and Delete the Message.
     */
    public function banAndDestroyMessage(Message $message): RedirectResponse
    {
        $ip = $message->ip_address;
        if (!empty($ip) && $ip !== '127.0.0.1' && $ip !== '::1') {
            IpBanService::ban(
                ip: $ip,
                reason: 'Spam / bot submission via contact form (' . substr($message->message, 0, 80) . ')',
                userAgent: request()->userAgent()
            );
        }

        $message->delete();

        return redirect()->route('admin.dashboard', ['tab' => 'inbox'])
            ->with('success', 'IP pengirim (' . ($ip ?: 'N/A') . ') berhasil diblokir ke Firewall dan pesan telah dihapus.');
    }

    /**
     * Purge all messages from inbox.
     */
    public function purgeAllMessages(): RedirectResponse
    {
        $total = Message::query()->count();
        Message::query()->delete();

        return redirect()->route('admin.dashboard', ['tab' => 'inbox'])
            ->with('success', "Seluruh ({$total}) pesan di kotak masuk berhasil dibersihkan.");
    }

    /**
     * Update Admin Security Credentials (Email & Password).
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'admin_email' => ['required', 'email'],
            'current_password' => ['required', 'current_password'],
            'new_password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ], [
            'current_password.current_password' => 'Kata sandi saat ini tidak sesuai.',
            'new_password.min' => 'Kata sandi baru minimal 8 karakter.',
            'new_password.confirmed' => 'Konfirmasi kata sandi baru tidak cocok.',
        ]);

        $user = $request->user();
        $user->email = $validated['admin_email'];
        if (!empty($validated['new_password'])) {
            $user->password = Hash::make($validated['new_password']);
        }
        $user->save();

        return redirect()->route('admin.dashboard', ['tab' => 'security'])
            ->with('success', 'Kredensial keamanan Admin berhasil diperbarui.');
    }

    /**
     * Unban an IP address and remove it from blacklist.
     */
    public function unbanIp(IpBan $ipBan): RedirectResponse
    {
        $ip = $ipBan->ip_address;
        IpBanService::unban($ipBan);

        return redirect()->route('admin.dashboard', ['tab' => 'firewall'])
            ->with('success', "Alamat IP {$ip} berhasil dihapus dari daftar blokir. Akses telah dibuka kembali.");
    }

    /**
     * Manually ban an IP address from Admin Panel.
     */
    public function manualBanIp(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'ip_address' => ['required', 'ip'],
            'reason' => ['required', 'string', 'max:255'],
            'duration' => ['required', 'in:1_hour,3_hours,permanent'],
        ], [
            'ip_address.required' => 'Alamat IP wajib diisi.',
            'ip_address.ip' => 'Format alamat IP tidak valid (contoh: 192.168.1.1).',
            'reason.required' => 'Alasan pemblokiran wajib diisi.',
            'duration.required' => 'Pilih durasi pemblokiran.',
        ]);

        $ip = $validated['ip_address'];

        $strike = match ($validated['duration']) {
            '1_hour' => 1,
            '3_hours' => 2,
            'permanent' => 3,
        };

        $bannedUntil = match ($validated['duration']) {
            '1_hour' => now()->addHour(),
            '3_hours' => now()->addHours(3),
            'permanent' => null,
        };

        IpBan::query()->updateOrCreate(
            ['ip_address' => $ip],
            [
                'reason' => $validated['reason'] . ' [Ditambahkan Manual oleh Admin]',
                'strike_count' => $strike,
                'banned_until' => $bannedUntil,
                'is_permanent' => $validated['duration'] === 'permanent',
                'incident_hash' => 'MAN-' . strtoupper(substr(md5($ip . microtime(true)), 0, 8)),
                'last_violation_at' => now(),
            ]
        );

        Cache::forget('ip_ban_state:' . md5($ip));

        return redirect()->route('admin.dashboard', ['tab' => 'firewall'])
            ->with('success', "Alamat IP {$ip} berhasil dimasukkan ke daftar blokir.");
    }

    /**
     * Update SEO Meta Tags, Social OG Image, and Webmaster Verification.
     */
    public function updateSeo(Request $request): RedirectResponse
    {
        $profile = Profile::query()->firstOrFail();

        $validated = $request->validate([
            'meta_title' => ['required', 'string', 'max:150'],
            'meta_description' => ['required', 'string', 'max:300'],
            'meta_keywords' => ['nullable', 'string', 'max:500'],
            'favicon_file' => ['nullable', 'file', 'mimes:ico,png,svg,webp,jpg,jpeg', 'max:2048'],
            'og_image_file' => ['nullable', 'image', 'max:4096'],
            'twitter_handle' => ['nullable', 'string', 'max:100'],
            'robots_index' => ['nullable', 'boolean'],
            'google_verification' => ['nullable', 'string', 'max:255'],
            'bing_verification' => ['nullable', 'string', 'max:255'],
            'google_analytics_id' => ['nullable', 'string', 'max:50'],
            'schema_type' => ['required', 'string', 'in:Person,ProfessionalService'],
        ], [
            'meta_title.required' => 'Judul Meta (Meta Title) wajib diisi.',
            'meta_description.required' => 'Deskripsi Meta (Meta Description) wajib diisi.',
            'favicon_file.mimes' => 'Format favicon harus berupa .ico, .png, .svg, .webp, atau .jpg.',
            'favicon_file.max' => 'Ukuran file favicon maksimal 2MB.',
        ]);

        $seo = $profile->seo ?? [];
        $favicon = $seo['favicon'] ?? null;
        $ogImage = $seo['og_image'] ?? null;

        // Process Favicon / Web App Icon
        if ($request->hasFile('favicon_file')) {
            $file = $request->file('favicon_file');
            $ext = strtolower($file->getClientOriginalExtension());
            if (in_array($ext, ['svg', 'ico', 'png'])) {
                $path = $file->store('seo', 'public');
                $favicon = '/storage/' . $path;
            } else {
                $favicon = ImageOptimizerService::convertAndStore($file, 'seo', 90, 512);
            }

            // Sync with root public/ favicon for legacy crawlers and direct /favicon.ico queries
            try {
                if ($ext === 'svg') {
                    @copy($file->getRealPath(), public_path('favicon.svg'));
                } elseif ($ext === 'ico') {
                    @copy($file->getRealPath(), public_path('favicon.ico'));
                }
            } catch (\Throwable) {
                // Ignore if filesystem permissions prevent copy
            }
        } elseif ($request->boolean('remove_favicon')) {
            $favicon = null;
        }

        // Process Open Graph Banner
        if ($request->hasFile('og_image_file')) {
            $ogImage = ImageOptimizerService::convertAndStore($request->file('og_image_file'), 'seo');
        } elseif ($request->boolean('remove_og_image')) {
            $ogImage = null;
        }

        $seo['meta_title'] = $validated['meta_title'];
        $seo['meta_description'] = $validated['meta_description'];
        $seo['meta_keywords'] = !empty($validated['meta_keywords']) ? trim((string) $validated['meta_keywords']) : null;
        $seo['favicon'] = $favicon;
        $seo['og_image'] = $ogImage;
        $seo['twitter_handle'] = !empty($validated['twitter_handle']) ? trim((string) $validated['twitter_handle']) : null;
        $seo['robots_index'] = $request->boolean('robots_index');
        $seo['google_verification'] = !empty($validated['google_verification']) ? trim((string) $validated['google_verification']) : null;
        $seo['bing_verification'] = !empty($validated['bing_verification']) ? trim((string) $validated['bing_verification']) : null;
        $seo['google_analytics_id'] = !empty($validated['google_analytics_id']) ? trim((string) $validated['google_analytics_id']) : null;
        $seo['schema_type'] = $validated['schema_type'];

        $profile->seo = $seo;
        $profile->save();

        $this->flushPortfolioCache();

        return redirect()->route('admin.dashboard', ['tab' => 'seo'])
            ->with('success', 'Konfigurasi SEO, Favicon & Metadata berhasil diperbarui.');
    }
}

