<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WeeklyAnswer extends Model
{
    use HasFactory;

    protected $table = 'weekly_answers';

    protected $fillable = [
        'weekly_attempt_id',
        'weekly_question_id',
        'jawaban_user',
        'is_correct',
    ];

    protected $casts = [
        'is_correct' => 'boolean',
    ];

    public function attempt(): BelongsTo
    {
        return $this->belongsTo(WeeklyAttempt::class, 'weekly_attempt_id');
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(WeeklyQuestion::class, 'weekly_question_id');
    }
}
