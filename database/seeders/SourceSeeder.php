<?php

namespace Database\Seeders;

use App\Models\Source;
use Illuminate\Database\Seeder;

class SourceSeeder extends Seeder
{
    public function run(): void
    {
        $sources = [
            'Walk-in Dealer',
            'Pameran Mall',
            'Media Sosial (Instagram/FB)',
            'Referensi Konsumen',
            'Telepon / WhatsApp',
        ];

        foreach ($sources as $source) {
            Source::updateOrCreate(['nama_sumber' => $source], ['status_aktif' => true]);
        }
    }
}
