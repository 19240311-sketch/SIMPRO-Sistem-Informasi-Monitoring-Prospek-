<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TrainingMaterial extends Model
{
    use HasFactory;

    protected $table = 'training_materials';

    protected $fillable = [
        'training_id',
        'judul_materi',
        'isi_materi',
        'media_url',
        'urutan',
    ];

    public function training(): BelongsTo
    {
        return $this->belongsTo(Training::class, 'training_id');
    }
}
