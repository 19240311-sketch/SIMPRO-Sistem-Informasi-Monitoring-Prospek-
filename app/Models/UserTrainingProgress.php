<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserTrainingProgress extends Model
{
    use HasFactory;

    protected $table = 'user_training_progress';

    protected $fillable = [
        'user_id',
        'training_id',
        'completed_material_ids',
        'progress_persen',
        'video_progress',
        'video_completed',
        'waktu_mulai_video',
        'waktu_selesai_video',
        'nilai_kuis',
        'jumlah_percobaan_kuis',
        'status_lulus',
        'tanggal_terakhir_belajar',
        'tanggal_lulus',
        'status',
        'last_material_id',
        'waktu_mulai',
        'waktu_selesai',
    ];

    protected $casts = [
        'completed_material_ids' => 'array',
        'progress_persen' => 'integer',
        'video_progress' => 'integer',
        'video_completed' => 'boolean',
        'status_lulus' => 'boolean',
        'nilai_kuis' => 'integer',
        'jumlah_percobaan_kuis' => 'integer',
        'waktu_mulai_video' => 'datetime',
        'waktu_selesai_video' => 'datetime',
        'tanggal_terakhir_belajar' => 'datetime',
        'tanggal_lulus' => 'datetime',
        'waktu_mulai' => 'datetime',
        'waktu_selesai' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function training(): BelongsTo
    {
        return $this->belongsTo(Training::class, 'training_id');
    }

    /**
     * Compute effective status based on video & quiz completion.
     */
    public function getComputedStatusAttribute(): string
    {
        if ($this->status_lulus || $this->status === 'selesai' || $this->status === 'lulus') {
            return 'lulus';
        }
        if ($this->nilai_kuis !== null && !$this->status_lulus) {
            return 'belum_lulus';
        }
        if ($this->video_completed || ($this->video_progress >= 100)) {
            return 'video_selesai';
        }
        if ($this->video_progress > 0 || $this->status === 'sedang_berjalan') {
            return 'sedang_dipelajari';
        }
        return 'belum_mulai';
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->computed_status) {
            'lulus' => 'Lulus ✓',
            'belum_lulus' => 'Belum Lulus',
            'video_selesai' => 'Video Selesai',
            'sedang_dipelajari' => 'Sedang Dipelajari',
            default => 'Belum Mulai',
        };
    }

    public function getStatusBadgeClassesAttribute(): string
    {
        return match ($this->computed_status) {
            'lulus' => 'bg-emerald-50 text-emerald-700 border border-emerald-200',
            'belum_lulus' => 'bg-red-50 text-red-700 border border-red-200',
            'video_selesai' => 'bg-blue-50 text-blue-700 border border-blue-200',
            'sedang_dipelajari' => 'bg-amber-50 text-amber-700 border border-amber-200',
            default => 'bg-slate-100 text-slate-700 border border-slate-200',
        };
    }
}
