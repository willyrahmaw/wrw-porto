<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Profile;
use App\Models\Project;
use App\Models\Service;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database with clean, realistic, professional content.
     */
    public function run(): void
    {
        // 0. Seed Default Admin Account
        User::query()->updateOrCreate(
            ['email' => 'admin@wrw.studio'],
            [
                'name' => 'Willy Rahmat Wijaya',
                'password' => Hash::make('password123'),
            ]
        );

        // 1. Seed Profile
        Profile::query()->delete();
        Profile::create([
            'name' => 'Willy Rahmat Wijaya',
            'initials' => 'WRW.',
            'profession' => 'Full-Stack Web Developer',
            'tagline' => 'Membantu bisnis membangun website yang rapi, cepat, dan mudah dikelola.',
            'subheadline' => 'Fokus pada pembuatan website baru, penambahan fitur, dan maintenance rutin. Bukan sekadar membangun lalu pergi — saya tetap siap mendampingi saat Anda butuh bantuan.',
            'bio' => "Saya adalah Web Developer yang berfokus pada pembuatan dan pengembangan aplikasi web menggunakan ekosistem Laravel dan Tailwind CSS. Saya terbiasa mengerjakan proyek mulai dari perancangan database hingga tampilan antarmuka yang responsif di HP maupun laptop.\n\nPrinsip kerja saya jelas: saya tidak sekadar membuat website lalu lepas tangan. Saya memastikan sistem berjalan lancar, memberikan panduan penggunaan yang mudah, dan tetap siap mendampingi setiap kali Anda menghadapi kendala teknis.",
            'location' => 'Indonesia',
            'availability_status' => 'Slot Terbuka • Sistem 1 Klien, 1 Fokus Penuh',
            'unavailable_status' => 'Slot Sedang Penuh • Fokus Pengerjaan Proyek Berjalan',
            'is_available' => true,
            'photo' => '/storage/profile/i5fFK6hDgVSKFL2w0mvszfFuyVD7JO6gKQxAbKr3.webp',
            'cv_path' => '/storage/cv-willy-rahmat-wijaya.pdf',
            'email' => 'hello@wrw.studio',
            'whatsapp' => '6281234567890',
            'linkedin' => 'https://linkedin.com/in/wrwstudio',
            'instagram' => 'https://instagram.com/wrw.studio',
            'github' => 'https://github.com/wrwstudio',
            'behance' => null,
            'dribbble' => null,
            'stats' => [
                ['value' => 4, 'suffix' => '+', 'label' => 'Tahun Pengalaman'],
                ['value' => 25, 'suffix' => '+', 'label' => 'Proyek Selesai'],
                ['value' => 98, 'suffix' => '%', 'label' => 'Skor Kecepatan Web'],
                ['value' => 24, 'suffix' => ' Jam', 'label' => 'Respon Support'],
            ],
            'skills' => [
                'Backend & Database' => ['Laravel', 'PHP 8+', 'MySQL / MariaDB', 'SQLite', 'REST API', 'Integrasi Payment / WA Gateway'],
                'Frontend & UI' => ['Tailwind CSS', 'Bootstrap', 'Alpine.js', 'JavaScript', 'Blade Templating', 'Responsive Web Design'],
                'Tools & Deployment' => ['Git & GitHub', 'cPanel / Shared Hosting', 'VPS Linux', 'Optimasi SEO Dasar', 'Troubleshooting & Bug Fixing'],
            ],
            'certificates' => [
                [
                    'title' => 'Laravel Certified Developer',
                    'issuer' => 'Laravel LLC / Global Certification',
                    'year' => '2024',
                    'credential_id' => 'LCD-89214-ID',
                    'file_path' => null,
                    'url' => 'https://certification.laravel.com',
                    'badge' => 'Official Certification',
                ],
                [
                    'title' => 'Menjadi Back-End Developer Expert dengan Clean Architecture',
                    'issuer' => 'Dicoding Indonesia',
                    'year' => '2023',
                    'credential_id' => 'DCD-BE-980122',
                    'file_path' => null,
                    'url' => 'https://www.dicoding.com',
                    'badge' => 'Expert Level',
                ],
                [
                    'title' => 'Advanced Database Optimization & Indexing Strategies',
                    'issuer' => 'BuildWithAngga',
                    'year' => '2023',
                    'credential_id' => 'BWA-DB-44109',
                    'file_path' => null,
                    'url' => 'https://buildwithangga.com',
                    'badge' => 'Specialization',
                ],
                [
                    'title' => 'RESTful API Security & OWASP Top 10 Hardening',
                    'issuer' => 'HackerRank & DevSec Indonesia',
                    'year' => '2022',
                    'credential_id' => 'SEC-API-7712',
                    'file_path' => null,
                    'url' => 'https://hackerrank.com',
                    'badge' => 'Security Audit',
                ],
            ],
            'seo' => [
                'meta_title' => 'Willy Rahmat Wijaya | Full-Stack Web Developer & UI Engineer',
                'meta_description' => 'Membantu bisnis membangun website yang rapi, cepat, dan mudah dikelola dengan Laravel & Tailwind CSS.',
                'meta_keywords' => 'Full-Stack Developer, Laravel Developer, Tailwind CSS, Jasa Pembuatan Website, Web Developer Indonesia, Pembuatan Web Portofolio',
                'og_image' => '/storage/og/og-image.webp',
                'twitter_handle' => '@wrwstudio',
                'robots_index' => true,
                'google_verification' => null,
                'bing_verification' => null,
                'google_analytics_id' => null,
                'schema_type' => 'Person',
            ],
            'trusted_clients_count' => 20,
            'trusted_industries_count' => 8,
        ]);

        // 2. Seed Services (Layanan Profesional & To-The-Point)
        Service::query()->delete();
        $services = [
            [
                'number' => '01',
                'title' => 'Pembuatan Website Baru',
                'description' => 'Pembuatan website dari nol sesuai kebutuhan bisnis atau organisasi Anda. Mulai dari landing page promosi, website company profile, katalog produk, hingga sistem informasi dan aplikasi web kustom berbasis Laravel.',
                'deliverables' => ['Desain Responsif (HP & Desktop)', 'Halaman Admin / CMS Mudah Dipakai', 'Optimasi SEO Dasar', 'Bantuan Upload ke Hosting & Domain'],
                'icon' => 'code',
                'sort_order' => 1,
            ],
            [
                'number' => '02',
                'title' => 'Penambahan & Pengembangan Fitur',
                'description' => 'Punya website yang sudah berjalan tapi butuh modul atau fitur tambahan? Saya bantu kembangkan fitur baru pada website Anda tanpa mengganggu data dan sistem yang sudah ada.',
                'deliverables' => ['Integrasi Payment Gateway / Midtrans', 'Laporan Export PDF & Excel', 'Notifikasi WhatsApp / Email Otomatis', 'Pembuatan Modul & Menu Baru'],
                'icon' => 'plus-circle',
                'sort_order' => 2,
            ],
            [
                'number' => '03',
                'title' => 'Maintenance & Perbaikan Bug (Error)',
                'description' => 'Layanan perawatan berkala dan perbaikan cepat jika website Anda mengalami error, tampilan berantakan, lambat diakses, atau butuh pembaruan versi PHP/Laravel agar tetap aman.',
                'deliverables' => ['Perbaikan Bug & Error Sistem', 'Optimasi Kecepatan Loading & Database', 'Backup Data & Update Keamanan', 'Dukungan Teknis & Konsultasi'],
                'icon' => 'wrench',
                'sort_order' => 3,
            ],
            [
                'number' => '04',
                'title' => 'Redesign & Peremajaan Tampilan',
                'description' => 'Memperbarui tampilan website lama Anda agar terlihat lebih modern, bersih, profesional, dan nyaman dibuka di layar smartphone masa kini.',
                'deliverables' => ['UI/UX Lebih Modern & Rapi', 'Perbaikan Struktur Navigasi', 'Peningkatan Skor Performa Mobile', 'Tetap Mempertahankan Data Lama'],
                'icon' => 'layout',
                'sort_order' => 4,
            ],
        ];

        foreach ($services as $service) {
            Service::create($service);
        }

        // 3. Seed Projects (Format Simpel: Judul, Kategori, Tahun, Demo, Deskripsi, Tech Stack, Galeri > 1 Foto)
        Project::query()->delete();
        $projects = [
            [
                'title' => 'Sistem Informasi Manajemen Inventaris & Kasir (POS)',
                'slug' => 'sistem-manajemen-inventaris-pos',
                'category' => 'Aplikasi Web',
                'year' => '2026',
                'link' => 'https://pos-demo.example.com',
                'thumbnail' => 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?auto=format&fit=crop&w=1200&q=80',
                'description' => "Aplikasi berbasis web untuk pencatatan stok keluar-masuk barang gudang, transaksi kasir penjualan harian, dan pembuatan laporan keuangan otomatis.\n\nDilengkapi dengan fitur multi-cabang, peringatan otomatis ketika stok barang menipis, cetak nota struk kasir, serta ekspor rekapitulasi data penjualan ke format Excel dan PDF.",
                'tech_stack' => ['Laravel 11', 'MySQL', 'Tailwind CSS', 'Alpine.js', 'Chart.js', 'DomPDF'],
                'gallery' => [
                    'https://images.unsplash.com/photo-1460925895917-afdab827c52f?auto=format&fit=crop&w=1200&q=80',
                    'https://images.unsplash.com/photo-1551288049-bebda4e38f71?auto=format&fit=crop&w=1200&q=80',
                    'https://images.unsplash.com/photo-1507238691740-187a5b1d37b8?auto=format&fit=crop&w=1200&q=80',
                ],
                'is_featured' => true,
                'sort_order' => 1,
            ],
            [
                'title' => 'Website Company Profile & Katalog Konstruksi',
                'slug' => 'website-company-profile-konstruksi',
                'category' => 'Company Profile',
                'year' => '2026',
                'link' => 'https://karyakonstruksi.example.com',
                'thumbnail' => 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=1200&q=80',
                'description' => "Website profil perusahaan kontraktor lengkap dengan showcase proyek yang pernah dikerjakan, daftar spesialisasi layanan, dan formulir permintaan penawaran harga.\n\nWebsite dirancang bersih, cepat diakses dari HP, dan dilengkapi panel admin agar staf internal dapat menambah foto proyek baru kapan saja.",
                'tech_stack' => ['Laravel', 'Blade', 'Tailwind CSS', 'Alpine.js', 'AOS Animation'],
                'gallery' => [
                    'https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&w=1200&q=80',
                    'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=1200&q=80',
                ],
                'is_featured' => true,
                'sort_order' => 2,
            ],
            [
                'title' => 'Sistem Booking Antrean & Notifikasi WhatsApp Klinik',
                'slug' => 'fitur-booking-pembayaran-klinik',
                'category' => 'Penambahan Fitur',
                'year' => '2025',
                'link' => null,
                'thumbnail' => 'https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?auto=format&fit=crop&w=1200&q=80',
                'description' => "Pengembangan modul pendaftaran antrean pasien online 24 jam dengan pemilihan jadwal dokter praktik dan pengiriman nomor antrean otomatis ke WhatsApp pasien.\n\nMengurangi penumpukan antrean fisik di ruang tunggu dan mempermudah staf administrasi dalam mendata pasien harian.",
                'tech_stack' => ['Laravel', 'MySQL', 'WhatsApp Gateway', 'Tailwind CSS', 'Cron Job'],
                'gallery' => [
                    'https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?auto=format&fit=crop&w=1200&q=80',
                    'https://images.unsplash.com/photo-1505751172876-fa1923c5c528?auto=format&fit=crop&w=1200&q=80',
                ],
                'is_featured' => true,
                'sort_order' => 3,
            ],
            [
                'title' => 'Toko Online & Katalog Pemesanan UMKM Kopi',
                'slug' => 'toko-online-katalog-kopi-nusantara',
                'category' => 'Toko Online',
                'year' => '2025',
                'link' => null,
                'thumbnail' => 'https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?auto=format&fit=crop&w=1200&q=80',
                'description' => "Website katalog produk roastery kopi dengan fitur pemilihan variasi biji/bubuk, hitung estimasi ongkos kirim otomatis, keranjang belanja, dan checkout pesanan langsung ke WhatsApp.\n\nMemberikan kemudahan bagi pelanggan tetap untuk memesan langsung tanpa potongan komisi pihak ketiga.",
                'tech_stack' => ['Laravel', 'Tailwind CSS', 'Alpine.js', 'RajaOngkir API', 'Midtrans'],
                'gallery' => [
                    'https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?auto=format&fit=crop&w=1200&q=80',
                    'https://images.unsplash.com/photo-1501339847302-ac426a4a7cbb?auto=format&fit=crop&w=1200&q=80',
                ],
                'is_featured' => true,
                'sort_order' => 4,
            ],
            [
                'title' => 'Optimasi Kecepatan & Pembersihan Bug Portal Berita',
                'slug' => 'optimasi-maintenance-portal-berita',
                'category' => 'Maintenance',
                'year' => '2025',
                'link' => null,
                'thumbnail' => 'https://images.unsplash.com/photo-1504711434969-e33886168f5c?auto=format&fit=crop&w=1200&q=80',
                'description' => "Pekerjaan pemeliharaan menyeluruh: perbaikan kueri database yang lambat, perbaikan celah keamanan, dan penerapan caching agar portal berita tetap stabil saat diakses ribuan pembaca secara bersamaan.\n\nKecepatan waktu muat halaman meningkat drastis di bawah 1 detik.",
                'tech_stack' => ['Laravel Caching', 'MySQL Indexing', 'Redis', 'PHP 8.2 OPcache'],
                'gallery' => [
                    'https://images.unsplash.com/photo-1504711434969-e33886168f5c?auto=format&fit=crop&w=1200&q=80',
                    'https://images.unsplash.com/photo-1585829365295-ab7cd400c167?auto=format&fit=crop&w=1200&q=80',
                ],
                'is_featured' => true,
                'sort_order' => 5,
            ],
            [
                'title' => 'Sistem Pendaftaran Event & Unduh E-Sertifikat Otomatis',
                'slug' => 'sistem-pendaftaran-event-esertifikat',
                'category' => 'Aplikasi Web',
                'year' => '2025',
                'link' => null,
                'thumbnail' => 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?auto=format&fit=crop&w=1200&q=80',
                'description' => "Sistem pendaftaran peserta pelatihan online lengkap dengan tiket QR Code untuk presensi di lokasi dan cetak file E-Sertifikat format PDF secara otomatis begitu acara selesai.\n\nMemangkas waktu kerja panitia dalam pengetikan ratusan nama sertifikat satu per satu.",
                'tech_stack' => ['Laravel', 'DomPDF', 'QR Code Generator', 'Tailwind CSS', 'SMTP Mailer'],
                'gallery' => [
                    'https://images.unsplash.com/photo-1540575467063-178a50c2df87?auto=format&fit=crop&w=1200&q=80',
                    'https://images.unsplash.com/photo-1511578314322-379afb476865?auto=format&fit=crop&w=1200&q=80',
                ],
                'is_featured' => true,
                'sort_order' => 6,
            ],
        ];

        foreach ($projects as $project) {
            Project::create($project);
        }

        // 4. Seed Clients (Marquee Logos / Partners)
        Client::query()->delete();
        $clients = [
            ['name' => 'MITRA BISNIS UTAMA', 'industry' => 'Klien Perusahaan', 'sort_order' => 1],
            ['name' => 'KLIEN INDIVIDU & FREELANCE', 'industry' => 'Proyek Perorangan', 'sort_order' => 2],
            ['name' => 'USAHA LOKAL & UMKM', 'industry' => 'Bisnis & Toko', 'sort_order' => 3],
            ['name' => 'KOLABORASI KREATIF', 'industry' => 'Kolega & Mitra', 'sort_order' => 4],
            ['name' => 'SISTEM INFORMASI KUSTOM', 'industry' => 'Aplikasi Web', 'sort_order' => 5],
        ];

        foreach ($clients as $client) {
            Client::create($client);
        }

        // 5. Seed Testimonials (3 Testimoni Profesional & Wajar)
        Testimonial::query()->delete();
        $testimonials = [
            [
                'name' => 'Hendra Wijaya',
                'role' => 'Direktur Operasional',
                'company' => 'CV Mitra Niaga Utama',
                'avatar' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=300&q=80',
                'quote' => 'Komunikasinya sangat enak dan paham kebutuhan operasional kami. Sistem stok dan kasir yang dibuat benar-benar rapi, staf gudang kami yang awalnya gaptek bisa langsung lancar menggunakannya.',
                'rating' => 5,
                'is_approved' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'dr. Rina Kartika',
                'role' => 'Penanggung Jawab Klinik',
                'company' => 'Klinik Pratama Sehat',
                'avatar' => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=300&q=80',
                'quote' => 'Penambahan fitur booking antrean online di website klinik kami berjalan mulus tanpa mengganggu data lama. Kalau ada kendala atau butuh penyesuaian kecil, responnya juga selalu cepat.',
                'rating' => 5,
                'is_approved' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Bagus Santoso',
                'role' => 'Manajer Proyek',
                'company' => 'PT Karya Konstruksi Mandiri',
                'avatar' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=300&q=80',
                'quote' => 'Pengerjaan website company profile kami tepat waktu sesuai jadwal. Tampilannya bersih, profesional, dan halaman adminnya sangat memudahkan tim kami saat ingin meng-upload proyek baru.',
                'rating' => 5,
                'is_approved' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Farhan Maulana',
                'role' => 'Founder & Roaster',
                'company' => 'Kopi Nusantara Roastery',
                'avatar' => 'https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?auto=format&fit=crop&w=300&q=80',
                'quote' => 'Website katalog dan checkout pesanan WhatsApp yang dibuat Mas Willy sangat membantu penjualan roastery kami. Pelanggan bisa order sendiri dengan mudah tanpa perlu chat manual berkali-kali.',
                'rating' => 5,
                'is_approved' => true,
                'sort_order' => 4,
            ],
            [
                'name' => 'Siti Nurhaliza',
                'role' => 'Ketua Panitia Pelaksana',
                'company' => 'Semnas Edukasi Digital',
                'avatar' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=300&q=80',
                'quote' => 'Sistem cetak e-sertifikat otomatisnya luar biasa efisien. Ribuan peserta seminar bisa unduh sertifikat ber-QR Code sendiri dalam hitungan menit setelah acara usai. Panitia sangat terbantu!',
                'rating' => 5,
                'is_approved' => true,
                'sort_order' => 5,
            ],
            [
                'name' => 'Dimas Prasetyo',
                'role' => 'Chief Editor',
                'company' => 'Portal Warta Terkini',
                'avatar' => 'https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?auto=format&fit=crop&w=300&q=80',
                'quote' => 'Kecepatan portal berita kami naik drastis setelah dioptimasi caching dan indexing database oleh Willy. Server tetap stabil dan enteng meski ada lonjakan pengunjung saat berita breaking news.',
                'rating' => 5,
                'is_approved' => true,
                'sort_order' => 6,
            ],
        ];

        foreach ($testimonials as $testimonial) {
            Testimonial::create($testimonial);
        }
    }
}
