<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProspectStatus extends Model
{
    use HasFactory;

    protected $table = 'status_prospek';

    protected $fillable = [
        'kode',
        'nama_status',
        'urutan',
        'status_akhir',
    ];

    protected $casts = [
        'status_akhir' => 'boolean',
        'urutan' => 'integer',
    ];

    // Accessor aliases
    public function getCodeAttribute(): ?string
    {
        return $this->attributes['kode'] ?? null;
    }

    public function setCodeAttribute($value): void
    {
        $this->attributes['kode'] = $value;
    }

    public function getNameAttribute(): ?string
    {
        return $this->attributes['nama_status'] ?? null;
    }

    public function setNameAttribute($value): void
    {
        $this->attributes['nama_status'] = $value;
    }

    public function getSortOrderAttribute(): int
    {
        return (int) ($this->attributes['urutan'] ?? 0);
    }

    public function setSortOrderAttribute($value): void
    {
        $this->attributes['urutan'] = $value;
    }

    public function getIsFinalAttribute(): bool
    {
        return (bool) ($this->attributes['status_akhir'] ?? false);
    }

    public function setIsFinalAttribute($value): void
    {
        $this->attributes['status_akhir'] = $value;
    }

    public function prospects(): HasMany
    {
        return $this->hasMany(Prospect::class, 'status_prospek_id');
    }

    public function prospectActivities(): HasMany
    {
        return $this->hasMany(ProspectActivity::class, 'status_prospek_id');
    }
}
