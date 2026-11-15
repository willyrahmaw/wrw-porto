<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'initials',
        'profession',
        'tagline',
        'subheadline',
        'bio',
        'location',
        'availability_status',
        'unavailable_status',
        'is_available',
        'photo',
        'cv_path',
        'email',
        'whatsapp',
        'linkedin',
        'instagram',
        'github',
        'behance',
        'dribbble',
        'stats',
        'skills',
        'certificates',
        'seo',
        'trusted_clients_count',
        'trusted_industries_count',
    ];

    protected function casts(): array
    {
        return [
            'is_available' => 'boolean',
            'stats' => 'array',
            'skills' => 'array',
            'certificates' => 'array',
            'seo' => 'array',
            'trusted_clients_count' => 'integer',
            'trusted_industries_count' => 'integer',
        ];
    }
}

