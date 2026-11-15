<x-mail::message>
# Inkuiri Proyek Baru Masuk

Anda menerima pesan baru dari formulir kontak portofolio Anda.

**Detail Pengirim:**
- **Nama:** {{ $contactMessage->name }}
- **Email:** {{ $contactMessage->email }}
- **Jenis Layanan:** {{ $contactMessage->project_type }}
@if($contactMessage->budget_range)
- **Estimasi Anggaran:** {{ $contactMessage->budget_range }}
@endif
- **Waktu:** {{ $contactMessage->created_at->format('d M Y, H:i') }} WIB

---

**Pesan Proyek:**

{{ $contactMessage->message }}

<x-mail::button :url="'mailto:' . $contactMessage->email">
Balas Email Sekarang
</x-mail::button>

Terima kasih,<br>
{{ config('app.name') }}
</x-mail::message>

