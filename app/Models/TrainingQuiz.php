<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TrainingQuiz extends Model
{
    use HasFactory;

    protected $table = 'training_quizzes';

    protected $fillable = [
        'training_id',
        'pertanyaan',
        'pilihan_jawaban',
        'jawaban_benar',
        'penjelasan',
        'urutan',
    ];

    protected $casts = [
        'pilihan_jawaban' => 'array',
    ];

    public function training(): BelongsTo
    {
        return $this->belongsTo(Training::class, 'training_id');
    }
}
