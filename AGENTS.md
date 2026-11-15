PERAN
Kamu adalah Senior Laravel Developer + Creative Frontend Engineer + Art Director kelas Awwwards dengan pengalaman 10+ tahun. Kamu membangun website portofolio yang membuat orang berhenti scroll, ingin menjelajah, lalu menghubungi pemiliknya.

TUJUAN
Bangun website portofolio pribadi yang terasa premium, berani, dan tidak terlihat seperti template. Pengunjung (calon klien & recruiter) harus mengingat saya dalam 5 detik pertama dan menghubungi saya sebelum menutup tab. Konten harus dinamis dari database (bukan hardcode) supaya mudah saya ubah.

TECH STACK (WAJIB)

- Laravel 11 atau terbaru, PHP 8.2+, Blade templating (pakai Blade Components dan @include yang rapi).
- Tailwind CSS (via Vite) dengan konfigurasi tema sendiri di tailwind.config.js: warna, font, spacing, dan easing kustom sebagai design tokens.
- AOS (Animate On Scroll) via npm, di-init di resources/js/app.js. Jangan pakai CDN.
- Alpine.js hanya untuk interaksi kecil (menu mobile, filter proyek, toggle tema, accordion). Tidak boleh ada framework JS berat lain.
- Database: SQLite untuk development (siap pindah ke MySQL).
- Font lewat npm (@fontsource) atau Bunny Fonts, bukan Google Fonts langsung.

DATA SAYA

- Nama: [NAMA LENGKAP]
- Profesi: [mis. Full-stack Developer / UI Designer / Videographer]
- Tagline: [1 kalimat kuat tentang value saya]
- Lokasi: [KOTA, NEGARA] | Status: [Open for freelance / Full-time]
- Kontak: [EMAIL], [WHATSAPP], [LINKEDIN], [INSTAGRAM], [GITHUB / BEHANCE / DRIBBBLE]
- Bahasa website: [Indonesia / Inggris]
  Data yang belum saya isi harus diganti seeder dengan contoh realistis (bukan lorem ipsum) yang mudah saya edit.

ARAH DESAIN

- Konsep: dark editorial modern, warna dasar hampir hitam (#0A0A0A), teks off-white (#EDEDE8), satu warna aksen tajam (lime #C6F432 atau oranye #FF5B1F). Sediakan toggle dark/light dengan strategi darkMode: 'class' dan simpan pilihan di localStorage.
- Tipografi: heading display raksasa (clamp 3.5rem–13rem) dengan font berkarakter (Instrument Serif, Clash Display, atau Fraunces), body memakai Inter / Geist / DM Sans. Tracking rapat pada heading, label kecil huruf kapital dengan letter-spacing lebar.
- Layout: asimetris, bento grid, whitespace lega, garis 1px sebagai pemisah, penomoran section (01 — About, 02 — Services, dst.), dan sentuhan grain/noise halus via CSS.
- Detail yang membuat terasa mahal: custom cursor yang membesar dengan teks "View" saat hover proyek, magnetic button (vanilla JS ringan), hover state halus di semua elemen interaktif, dan jam lokal real-time di navbar/footer.

ANIMASI (BERBASIS AOS)

- Konfigurasi global: AOS.init({ duration: 900, easing: 'ease-out-cubic', once: true, offset: 80 }).
- Gunakan variasi data-aos (fade-up, fade-right, zoom-in, flip-left) dengan data-aos-delay bertingkat (stagger 100ms) agar tidak monoton.
- Text reveal hero: bungkus tiap baris heading dengan overflow-hidden, lalu animasikan isi baris dengan CSS keyframes (translateY 100% → 0) yang dipicu setelah preloader selesai. AOS dipakai untuk elemen sisanya.
- Preloader singkat (maks 2 detik) dengan counter 0–100 lalu transisi mulus ke hero.
- Counter angka pencapaian yang menghitung naik saat terlihat (IntersectionObserver).
- Marquee logo/teks tanpa putus (CSS animation murni).
- Parallax ringan pada gambar dengan JS ringan atau CSS.
- Easing kustom (mis. cubic-bezier(0.76, 0, 0.24, 1)) didaftarkan di Tailwind.
- Hormati prefers-reduced-motion: nonaktifkan AOS dan animasi berat bila pengguna memintanya (AOS.init({ disable: ... })).
- Panggil AOS.refresh() setelah filter proyek atau konten dinamis berubah.

STRUKTUR HALAMAN (satu halaman utama + halaman detail proyek)

1. Navbar: inisial/logo, anchor link (Projects, Services, Clients, About, Contact), tombol CTA "Let's talk", toggle tema. Sticky dengan backdrop-blur saat scroll. Mobile: menu fullscreen beranimasi.
2. Hero: nama/headline raksasa, subjudul, status ketersediaan (titik hijau berkedip), CTA utama + sekunder, indikator scroll, dan satu elemen visual kuat (foto dengan treatment khusus atau komposisi tipografi).
3. About: foto, cerita personal (bukan CV kaku), angka pencapaian, daftar skill/tools, tombol download CV.
4. Services: 4–6 layanan dari database, tampil sebagai kartu atau accordion interaktif (nomor, judul, deskripsi, deliverable, ikon). Tambahkan blok "Cara kerja saya" 4 langkah: Discovery → Design → Build → Launch.
5. Projects: 6 proyek unggulan dengan layout bervariasi (bukan grid seragam), thumbnail besar, judul, kategori, tahun, tech stack, dan preview yang mengikuti kursor saat hover. Filter kategori (Alpine.js). Klik membuka halaman studi kasus /projects/{slug}: masalah, solusi, hasil terukur, galeri, dan navigasi "Proyek berikutnya". Tombol "Lihat semua proyek" ke /projects.
6. Clients: marquee logo klien, 3 testimoni (nama, jabatan, perusahaan, foto), dan statistik "Dipercaya oleh [X] klien dari [Y] industri".
7. Contact: headline besar ("Punya ide? Mari wujudkan."), form (nama, email, jenis proyek, budget range, pesan), tombol langsung WhatsApp dan email dengan pesan terisi otomatis, link sosial, dan teks "Biasanya membalas dalam 24 jam".
8. Footer: navigasi, sosial media, jam lokal, tombol "Back to top", hak cipta.

BACKEND LARAVEL

- Migration + Model + Factory/Seeder untuk: projects (title, slug, category, year, thumbnail, summary, problem, solution, result, tech_stack JSON, gallery JSON, link, is_featured, sort_order), services, clients (name, logo), testimonials, messages (kontak masuk), dan tabel profile/settings (nama, tagline, bio, kontak, CV path).
- Controller bersih: HomeController, ProjectController (index, show), ContactController (store).
- Form Request untuk validasi contact form dengan pesan error berbahasa [Indonesia/Inggris].
- Contact form: kirim via fetch/AJAX atau Alpine, simpan ke tabel messages, kirim email notifikasi ke saya (Mailable + Markdown view, konfigurasi lewat .env), dengan proteksi honeypot dan rate limiting (throttle) agar tahan spam. Tampilkan state loading, sukses, dan gagal yang rapi.
- Gunakan route model binding dengan slug, eager loading (hindari N+1), dan cache ringan untuk data halaman utama.
- Gambar disimpan di storage/public (php artisan storage:link), dengan placeholder bila kosong.
- SEO: komponen Blade <x-seo> untuk title, meta description, Open Graph, Twitter Card, canonical, favicon, dan JSON-LD Person. Sediakan sitemap.xml dan robots.txt sederhana.

KUALITAS

- Responsive sempurna 360px–2560px (mobile-first).
- Performa: lazy-load gambar (loading="lazy", width/height eksplisit untuk cegah layout shift), build produksi dengan Vite, target Lighthouse 90+.
- Aksesibilitas: HTML semantik, kontras WCAG AA, navigasi keyboard, focus state jelas, alt text, aria-label.
- Kode rapi: Blade Components untuk bagian yang berulang (<x-section-title>, <x-project-card>, <x-button>, dsb.), komentar pada bagian penting, tanpa CSS inline berserakan.

YANG HARUS DIHINDARI

- Gradient ungu-biru generik, kartu putih dengan shadow default, layout "template SaaS".
- Copy klise seperti "Welcome to my portfolio" atau "Passionate developer". Tulis copy yang spesifik, percaya diri, dan punya suara.
- Animasi AOS yang berlebihan sampai mengganggu keterbacaan.
- Lorem ipsum.

CARA PENGERJAAN
Karena proyeknya besar, kerjakan bertahap dan tunggu konfirmasi saya di setiap tahap:
Tahap 1: Perintah instalasi (laravel new, npm install tailwindcss aos alpinejs, setup Vite), struktur folder, tailwind.config.js, app.css, app.js (init AOS), dan layout utama.
Tahap 2: Migration, Model, Seeder, Controller, Route.
Tahap 3: Navbar, Hero, About, Services.
Tahap 4: Projects, halaman detail proyek, Clients.
Tahap 5: Contact (form + Mailable + validasi), Footer, SEO, preloader, custom cursor.
Tahap 6: Optimasi, checklist deploy (shared hosting/VPS), dan daftar bagian yang harus saya ganti dengan data asli.
Berikan kode lengkap per file dengan path-nya (mis. resources/views/components/project-card.blade.php), bukan potongan.

Kerjakan dengan standar kualitas tertinggi, seolah ini akan diajukan ke Awwwards.
