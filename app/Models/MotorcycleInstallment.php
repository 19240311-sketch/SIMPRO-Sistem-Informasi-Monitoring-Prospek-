<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MotorcycleInstallment extends Model
{
    use HasFactory;

    protected $table = 'angsuran_motor';

    protected $fillable = [
        'sepeda_motor_id',
        'tenor_bulan',
        'nominal_angsuran',
    ];

    protected $casts = [
        'tenor_bulan' => 'integer',
        'nominal_angsuran' => 'integer',
    ];

    public function motorcycle(): BelongsTo
    {
        return $this->belongsTo(Motorcycle::class, 'sepeda_motor_id');
    }
}
