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
        'status',
        'last_material_id',
        'waktu_mulai',
        'waktu_selesai',
    ];

    protected $casts = [
        'completed_material_ids' => 'array',
        'progress_persen' => 'integer',
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

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'selesai' => 'Selesai',
            'sedang_berjalan' => 'Sedang Berjalan',
            default => 'Belum Mulai',
        };
    }

    public function getStatusBadgeClassesAttribute(): string
    {
        return match ($this->status) {
            'selesai' => 'bg-emerald-50 text-emerald-700 border border-emerald-200',
            'sedang_berjalan' => 'bg-blue-50 text-blue-700 border border-blue-200',
            default => 'bg-slate-100 text-slate-700 border border-slate-200',
        };
    }
}
