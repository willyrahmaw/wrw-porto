<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreContactRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:2', 'max:120'],
            'email' => ['required', 'email', 'max:150'],
            'project_type' => ['required', 'string', 'max:100'],
            'budget_range' => ['nullable', 'string', 'max:100'],
            'message' => ['required', 'string', 'min:15', 'max:3000'],
            'website_url' => ['nullable', 'string'], // Primary honeypot trap
            'phone_confirmation' => ['nullable', 'string'], // Secondary honeypot trap
            '_token_time' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Mohon isi nama lengkap Anda.',
            'name.min' => 'Nama minimal terdiri dari 2 karakter.',
            'email.required' => 'Alamat email wajib diisi agar saya dapat membalas pesan Anda.',
            'email.email' => 'Format alamat email tidak valid.',
            'project_type.required' => 'Silakan pilih jenis proyek yang ingin didiskusikan.',
            'message.required' => 'Mohon ceritakan sedikit detail mengenai ide atau proyek Anda.',
            'message.min' => 'Pesan terlalu singkat (minimal 15 karakter).',
            'website_url.max' => 'Terdeteksi aktivitas spam.',
        ];
    }
}

