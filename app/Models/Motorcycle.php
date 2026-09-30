<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Motorcycle extends Model
{
    use HasFactory;

    protected $table = 'sepeda_motor';

    protected $fillable = [
        'merk',
        'nama_model',
        'varian',
        'harga_otr',
        'status_aktif',
    ];

    protected $casts = [
        'harga_otr' => 'integer',
        'status_aktif' => 'boolean',
    ];

    public function colors(): HasMany
    {
        return $this->hasMany(MotorcycleColor::class, 'sepeda_motor_id');
    }

    public function installments(): HasMany
    {
        return $this->hasMany(MotorcycleInstallment::class, 'sepeda_motor_id')->orderBy('tenor_bulan');
    }

    /**
     * Get clean dropdown label: "Yamaha Fazzio Hybrid — Neo" or "Yamaha XSR 155" (WITHOUT OTR price)
     */
    public function getFullDisplayNameAttribute(): string
    {
        $name = ($this->merk ? $this->merk . ' ' : '') . $this->nama_model;
        if (!empty($this->varian) && strtolower($this->varian) !== 'standard') {
            $name .= ' — ' . $this->varian;
        }
        return $name;
    }

    // Accessor aliases
    public function getBrandAttribute(): ?string
    {
        return $this->attributes['merk'] ?? null;
    }

    public function setBrandAttribute($value): void
    {
        $this->attributes['merk'] = $value;
    }

    public function getNameAttribute(): ?string
    {
        return $this->attributes['nama_model'] ?? null;
    }

    public function setNameAttribute($value): void
    {
        $this->attributes['nama_model'] = $value;
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
        return $this->hasMany(Prospect::class, 'sepeda_motor_id');
    }
}
