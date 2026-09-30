<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'nama',
        'email',
        'no_hp',
        'foto_profil',
        'cabang',
        'password',
        'peran',
        'status_aktif',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'status_aktif' => 'boolean',
        'password' => 'hashed',
    ];

    // Accessor aliases for name, role, is_active
    public function getNameAttribute(): ?string
    {
        return $this->attributes['nama'] ?? null;
    }

    public function setNameAttribute($value): void
    {
        $this->attributes['nama'] = $value;
    }

    public function getRoleAttribute(): ?string
    {
        return $this->attributes['peran'] ?? null;
    }

    public function setRoleAttribute($value): void
    {
        $this->attributes['peran'] = $value;
    }

    public function getIsActiveAttribute(): bool
    {
        return (bool) ($this->attributes['status_aktif'] ?? false);
    }

    public function setIsActiveAttribute($value): void
    {
        $this->attributes['status_aktif'] = $value;
    }

    public function getAvatarInitialsAttribute(): string
    {
        $words = preg_split('/\s+/', trim($this->nama ?? ''));
        if (count($words) >= 2) {
            return strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1));
        }
        return strtoupper(substr($this->nama ?? 'US', 0, 2));
    }

    public function getFotoUrlAttribute(): ?string
    {
        if ($this->foto_profil && file_exists(public_path('storage/' . $this->foto_profil))) {
            return asset('storage/' . $this->foto_profil);
        }
        return null;
    }

    public function isAdmin(): bool
    {
        return ($this->peran ?? $this->role) === 'admin';
    }

    public function isSales(): bool
    {
        return ($this->peran ?? $this->role) === 'sales';
    }

    public function prospects(): HasMany
    {
        return $this->hasMany(Prospect::class, 'user_id');
    }

    public function prospectActivities(): HasMany
    {
        return $this->hasMany(ProspectActivity::class, 'user_id');
    }

    public function trainingProgresses(): HasMany
    {
        return $this->hasMany(UserTrainingProgress::class, 'user_id');
    }

    public function quizResults(): HasMany
    {
        return $this->hasMany(UserQuizResult::class, 'user_id');
    }

    public function weeklyAttempts(): HasMany
    {
        return $this->hasMany(WeeklyAttempt::class, 'user_id');
    }
}
