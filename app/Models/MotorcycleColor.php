<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MotorcycleColor extends Model
{
    use HasFactory;

    protected $table = 'warna_motor';

    protected $fillable = [
        'sepeda_motor_id',
        'nama_warna',
        'kode_hex',
    ];

    public function motorcycle(): BelongsTo
    {
        return $this->belongsTo(Motorcycle::class, 'sepeda_motor_id');
    }
}
