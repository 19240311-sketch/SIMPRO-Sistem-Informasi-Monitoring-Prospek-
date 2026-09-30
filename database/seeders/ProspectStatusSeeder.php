<?php

namespace Database\Seeders;

use App\Models\ProspectStatus;
use Illuminate\Database\Seeder;

class ProspectStatusSeeder extends Seeder
{
    public function run(): void
    {
        $statuses = [
            ['kode' => 'baru', 'nama_status' => 'Baru', 'urutan' => 1, 'status_akhir' => false],
            ['kode' => 'follow_up', 'nama_status' => 'Follow-up', 'urutan' => 2, 'status_akhir' => false],
            ['kode' => 'hot', 'nama_status' => 'Hot', 'urutan' => 3, 'status_akhir' => false],
            ['kode' => 'deal', 'nama_status' => 'Deal', 'urutan' => 4, 'status_akhir' => true],
            ['kode' => 'batal', 'nama_status' => 'Batal', 'urutan' => 5, 'status_akhir' => true],
        ];

        foreach ($statuses as $status) {
            ProspectStatus::updateOrCreate(['kode' => $status['kode']], $status);
        }
    }
}
