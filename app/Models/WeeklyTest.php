<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class WeeklyTest extends Model
{
    use HasFactory;

    protected $table = 'weekly_tests';

    protected $fillable = [
        'nama_tes',
        'slug',
        'kategori',
        'deskripsi',
        'tanggal_mulai',
        'tanggal_selesai',
        'durasi_menit',
        'nilai_minimum',
        'status',
    ];

    protected $casts = [
        'tanggal_mulai' => 'datetime',
        'tanggal_selesai' => 'datetime',
        'durasi_menit' => 'integer',
        'nilai_minimum' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($test) {
            if (empty($test->slug)) {
                $test->slug = Str::slug($test->nama_tes) . '-' . Str::random(5);
            }
        });
    }

    public function questions(): HasMany
    {
        return $this->hasMany(WeeklyQuestion::class, 'weekly_test_id')->orderBy('urutan', 'asc');
    }

    public function attempts(): HasMany
    {
        return $this->hasMany(WeeklyAttempt::class, 'weekly_test_id');
    }

    public function attemptForUser(int $userId): ?WeeklyAttempt
    {
        return $this->attempts()->where('user_id', $userId)->first();
    }

    /**
     * Check whether the test is currently active (within period).
     */
    public function isActive(): bool
    {
        $now = now();
        return $this->status === 'published' && $now->between($this->tanggal_mulai, $this->tanggal_selesai);
    }

    /**
     * Check whether the test period has expired.
     */
    public function isExpired(): bool
    {
        return now()->isAfter($this->tanggal_selesai);
    }

    /**
     * Check whether the test is upcoming.
     */
    public function isUpcoming(): bool
    {
        return now()->isBefore($this->tanggal_mulai);
    }

    public function getStatusLabelAttribute(): string
    {
        if ($this->status === 'draft') return 'Draft';
        if ($this->isExpired()) return 'Berakhir';
        if ($this->isUpcoming()) return 'Mendatang';
        return 'Aktif';
    }

    public function getStatusBadgeClassesAttribute(): string
    {
        if ($this->status === 'draft') return 'bg-slate-100 text-slate-600 border border-slate-200';
        if ($this->isExpired()) return 'bg-rose-50 text-rose-700 border border-rose-200';
        if ($this->isUpcoming()) return 'bg-amber-50 text-amber-700 border border-amber-200';
        return 'bg-emerald-50 text-emerald-700 border border-emerald-200';
    }
}
