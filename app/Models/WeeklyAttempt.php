<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WeeklyAttempt extends Model
{
    use HasFactory;

    protected $table = 'weekly_attempts';

    protected $fillable = [
        'weekly_test_id',
        'user_id',
        'waktu_mulai',
        'waktu_selesai',
        'nilai',
        'jumlah_benar',
        'jumlah_salah',
        'total_soal',
        'status',
        'status_lulus',
    ];

    protected $casts = [
        'waktu_mulai' => 'datetime',
        'waktu_selesai' => 'datetime',
        'nilai' => 'integer',
        'jumlah_benar' => 'integer',
        'jumlah_salah' => 'integer',
        'total_soal' => 'integer',
        'status_lulus' => 'boolean',
    ];

    public function test(): BelongsTo
    {
        return $this->belongsTo(WeeklyTest::class, 'weekly_test_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function answers(): HasMany
    {
        return $this->hasMany(WeeklyAnswer::class, 'weekly_attempt_id');
    }

    public function isPassed(): bool
    {
        return (bool) $this->status_lulus;
    }

    public function getDurationSpentAttribute(): string
    {
        if (!$this->waktu_selesai || !$this->waktu_mulai) return '-';
        $diffSeconds = $this->waktu_mulai->diffInSeconds($this->waktu_selesai);
        $minutes = floor($diffSeconds / 60);
        $seconds = $diffSeconds % 60;
        return "{$minutes}m {$seconds}s";
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'selesai', 'waktu_habis' => $this->status_lulus ? 'Lulus' : 'Belum Lulus',
            'sedang_mengerjakan' => 'Sedang Mengerjakan',
            default => 'Belum Mulai',
        };
    }

    public function getStatusBadgeClassesAttribute(): string
    {
        if (in_array($this->status, ['selesai', 'waktu_habis'])) {
            return $this->status_lulus
                ? 'bg-emerald-50 text-emerald-700 border border-emerald-200'
                : 'bg-rose-50 text-rose-700 border border-rose-200';
        }
        return 'bg-blue-50 text-blue-700 border border-blue-200';
    }
}
