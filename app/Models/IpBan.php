<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IpBan extends Model
{
    use HasFactory;

    protected $fillable = [
        'ip_address',
        'reason',
        'strike_count',
        'banned_until',
        'is_permanent',
        'user_agent',
        'incident_hash',
        'last_violation_at',
    ];

    protected function casts(): array
    {
        return [
            'strike_count' => 'integer',
            'banned_until' => 'datetime',
            'is_permanent' => 'boolean',
            'last_violation_at' => 'datetime',
        ];
    }

    /**
     * Check if this ban is currently active.
     */
    public function isActive(): bool
    {
        if ($this->is_permanent) {
            return true;
        }

        if (!$this->banned_until) {
            return false;
        }

        return $this->banned_until->isFuture();
    }

    /**
     * Human-friendly status label.
     */
    public function getStatusLabelAttribute(): string
    {
        if ($this->is_permanent) {
            return 'PERMANEN (STRIKE 3+)';
        }

        if ($this->isActive()) {
            return 'AKTIF (' . $this->banned_until->diffForHumans(now(), true) . ' lagi)';
        }

        return 'KADALUARSA (SELESAI)';
    }

    /**
     * Human-friendly strike tier label.
     */
    public function getTierLabelAttribute(): string
    {
        return match ($this->strike_count) {
            1 => 'Tier 1 (1 Jam)',
            2 => 'Tier 2 (3 Jam)',
            default => 'Tier 3 (Permanen)',
        };
    }
}

