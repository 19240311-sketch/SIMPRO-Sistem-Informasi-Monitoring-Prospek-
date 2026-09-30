<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserQuizResult extends Model
{
    use HasFactory;

    protected $table = 'user_quiz_results';

    protected $fillable = [
        'user_id',
        'training_id',
        'nilai',
        'jumlah_benar',
        'total_soal',
        'status_lulus',
        'jawaban_user',
        'waktu_selesai',
    ];

    protected $casts = [
        'nilai' => 'integer',
        'jumlah_benar' => 'integer',
        'total_soal' => 'integer',
        'status_lulus' => 'boolean',
        'jawaban_user' => 'array',
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
}
