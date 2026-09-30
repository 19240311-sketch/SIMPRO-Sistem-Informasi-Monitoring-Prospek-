<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WeeklyQuestion extends Model
{
    use HasFactory;

    protected $table = 'weekly_questions';

    protected $fillable = [
        'weekly_test_id',
        'pertanyaan',
        'pilihan_a',
        'pilihan_b',
        'pilihan_c',
        'pilihan_d',
        'jawaban_benar',
        'penjelasan',
        'urutan',
    ];

    public function test(): BelongsTo
    {
        return $this->belongsTo(WeeklyTest::class, 'weekly_test_id');
    }

    public function answers(): HasMany
    {
        return $this->hasMany(WeeklyAnswer::class, 'weekly_question_id');
    }

    public function getOptionText(string $optionKey): string
    {
        return match (strtoupper($optionKey)) {
            'A' => $this->pilihan_a,
            'B' => $this->pilihan_b,
            'C' => $this->pilihan_c,
            'D' => $this->pilihan_d,
            default => '',
        };
    }
}
