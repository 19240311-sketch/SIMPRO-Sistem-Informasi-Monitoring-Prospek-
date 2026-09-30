<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Source extends Model
{
    use HasFactory;

    protected $table = 'sumber_prospek';

    protected $fillable = [
        'nama_sumber',
        'status_aktif',
    ];

    protected $casts = [
        'status_aktif' => 'boolean',
    ];

    // Accessor aliases
    public function getNameAttribute(): ?string
    {
        return $this->attributes['nama_sumber'] ?? null;
    }

    public function setNameAttribute($value): void
    {
        $this->attributes['nama_sumber'] = $value;
    }

    public function getIsActiveAttribute(): bool
    {
        return (bool) ($this->attributes['status_aktif'] ?? false);
    }

    public function setIsActiveAttribute($value): void
    {
        $this->attributes['status_aktif'] = $value;
    }

    public function prospects(): HasMany
    {
        return $this->hasMany(Prospect::class, 'sumber_prospek_id');
    }
}
