<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class Training extends Model
{
    use HasFactory;

    protected $table = 'trainings';

    protected $fillable = [
        'nama_training',
        'slug',
        'kategori',
        'deskripsi',
        'estimasi_waktu',
        'icon',
        'status',
        'urutan',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($training) {
            if (empty($training->slug)) {
                $training->slug = Str::slug($training->nama_training) . '-' . Str::random(5);
            }
        });
    }

    public function materials(): HasMany
    {
        return $this->hasMany(TrainingMaterial::class, 'training_id')->orderBy('urutan', 'asc');
    }

    public function quizzes(): HasMany
    {
        return $this->hasMany(TrainingQuiz::class, 'training_id')->orderBy('urutan', 'asc');
    }

    public function userProgresses(): HasMany
    {
        return $this->hasMany(UserTrainingProgress::class, 'training_id');
    }

    public function userQuizResults(): HasMany
    {
        return $this->hasMany(UserQuizResult::class, 'training_id');
    }

    /**
     * Get the progress for a specific user.
     */
    public function progressForUser(int $userId): ?UserTrainingProgress
    {
        return $this->userProgresses()->where('user_id', $userId)->first();
    }

    /**
     * Get the latest quiz result for a specific user.
     */
    public function latestQuizResultForUser(int $userId): ?UserQuizResult
    {
        return $this->userQuizResults()->where('user_id', $userId)->latest('waktu_selesai')->first();
    }

    public function getCategoryLabelAttribute(): string
    {
        return $this->kategori === 'product_knowledge' ? 'Product Knowledge' : 'Sales Skill';
    }

    public function getCategoryColorClassesAttribute(): string
    {
        return $this->kategori === 'product_knowledge'
            ? 'bg-blue-50 text-blue-700 border-blue-200'
            : 'bg-amber-50 text-amber-700 border-amber-200';
    }
}
