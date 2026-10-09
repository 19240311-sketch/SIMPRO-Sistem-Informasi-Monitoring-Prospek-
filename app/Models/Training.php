<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
        'youtube_url',
        'youtube_video_id',
        'thumbnail_url',
        'durasi_video',
        'jumlah_soal',
        'passing_grade',
        'estimasi_waktu',
        'icon',
        'status',
        'is_active',
        'urutan',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'jumlah_soal' => 'integer',
        'passing_grade' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($training) {
            if (empty($training->slug)) {
                $training->slug = Str::slug($training->nama_training) . '-' . Str::random(5);
            }
            if (!empty($training->youtube_url) && empty($training->youtube_video_id)) {
                $training->youtube_video_id = static::extractYouTubeId($training->youtube_url);
            }
            if (!empty($training->youtube_video_id) && empty($training->thumbnail_url)) {
                $training->thumbnail_url = "https://img.youtube.com/vi/{$training->youtube_video_id}/hqdefault.jpg";
            }
        });

        static::updating(function ($training) {
            if ($training->isDirty('youtube_url') && !empty($training->youtube_url)) {
                $training->youtube_video_id = static::extractYouTubeId($training->youtube_url);
                if (!empty($training->youtube_video_id)) {
                    $training->thumbnail_url = "https://img.youtube.com/vi/{$training->youtube_video_id}/hqdefault.jpg";
                }
            }
        });
    }

    /**
     * Parse YouTube Video ID from various link formats.
     */
    public static function extractYouTubeId(?string $url): ?string
    {
        if (empty($url)) {
            return null;
        }

        $pattern = '%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?|shorts)/|.*[?&]v=)|youtu\.be/)([^"&?/\s]{11})%i';
        if (preg_match($pattern, trim($url), $matches)) {
            return $matches[1];
        }

        // If user already typed the 11-char ID directly
        if (preg_match('/^[a-zA-Z0-9_-]{11}$/', trim($url))) {
            return trim($url);
        }

        return null;
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

    public function progressForUser(int $userId): ?UserTrainingProgress
    {
        return $this->userProgresses()->where('user_id', $userId)->first();
    }

    public function latestQuizResultForUser(int $userId): ?UserQuizResult
    {
        return $this->userQuizResults()->where('user_id', $userId)->latest('waktu_selesai')->first();
    }

    public function getCategoryLabelAttribute(): string
    {
        return match ($this->kategori) {
            'product_knowledge' => 'Product Knowledge',
            'sales_skill' => 'Sales Skill',
            'product_update' => 'Product Update',
            'training' => 'Training',
            'lainnya' => 'Lainnya',
            default => ucwords(str_replace('_', ' ', $this->kategori ?? 'Product Knowledge')),
        };
    }

    public function getCategoryColorClassesAttribute(): string
    {
        return match ($this->kategori) {
            'product_knowledge' => 'bg-blue-50 text-blue-700 border-blue-200',
            'sales_skill' => 'bg-amber-50 text-amber-700 border-amber-200',
            'product_update' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
            'training' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
            default => 'bg-slate-50 text-slate-700 border-slate-200',
        };
    }

    /**
     * Get effective YouTube thumbnail or fallback.
     */
    public function getEffectiveThumbnailAttribute(): string
    {
        if (!empty($this->thumbnail_url)) {
            return $this->thumbnail_url;
        }
        if (!empty($this->youtube_video_id)) {
            return "https://img.youtube.com/vi/{$this->youtube_video_id}/hqdefault.jpg";
        }
        return asset('images/logo.png');
    }
}
