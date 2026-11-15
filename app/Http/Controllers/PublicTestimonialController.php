<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Models\Testimonial;
use App\Services\ImageOptimizerService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PublicTestimonialController extends Controller
{
    /**
     * Display the public customer review / testimonial submission form.
     */
    public function create(): View
    {
        $profile = Profile::query()->first();

        return view('testimonials.create', compact('profile'));
    }

    /**
     * Store incoming customer testimonial submission.
     * Saved as `is_approved = false` by default for admin moderation.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'min:2', 'max:120'],
            'role' => ['required', 'string', 'max:120'],
            'company' => ['required', 'string', 'max:120'],
            'quote' => ['required', 'string', 'min:20', 'max:2500'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'avatar_file' => ['nullable', 'image', 'max:4096'],
            'avatar_url' => ['nullable', 'url', 'max:500'],
            'website_url' => ['nullable', 'max:0'], // Anti-bot honeypot field
        ], [
            'name.required' => 'Mohon masukkan nama lengkap Anda.',
            'role.required' => 'Mohon isi jabatan atau profesi Anda (misal: Pemilik Toko, Manajer, Personal).',
            'company.required' => 'Mohon isi nama perusahaan, toko, atau proyek Anda.',
            'quote.required' => 'Mohon tuliskan ulasan atau pengalaman kerja sama Anda.',
            'quote.min' => 'Ulasan minimal 20 karakter agar bermanfaat bagi calon klien lain.',
            'avatar_file.image' => 'File foto profil harus berupa gambar valid (JPG, PNG, atau WebP).',
            'avatar_file.max' => 'Ukuran foto maksimal 4MB.',
            'website_url.max' => 'Terdeteksi aktivitas bot tidak wajar.',
        ]);

        $avatar = $validated['avatar_url'] ?? null;
        if ($request->hasFile('avatar_file')) {
            $avatar = ImageOptimizerService::convertAndStore($request->file('avatar_file'), 'testimonials');
        }

        Testimonial::create([
            'name' => $validated['name'],
            'role' => $validated['role'],
            'company' => $validated['company'],
            'quote' => $validated['quote'],
            'rating' => (int) $validated['rating'],
            'avatar' => $avatar,
            'is_approved' => false, // Requires admin moderation
            'sort_order' => Testimonial::count() + 1,
        ]);

        return back()->with('success', 'Terima kasih banyak atas ulasan dan kebaikan Anda! Testimoni Anda telah kami terima dan akan segera ditampilkan di portofolio setelah diverifikasi.');
    }
}

