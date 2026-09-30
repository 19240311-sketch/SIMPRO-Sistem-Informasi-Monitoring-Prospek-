<?php

namespace Database\Seeders;

use App\Models\Motorcycle;
use App\Models\MotorcycleColor;
use App\Models\MotorcycleInstallment;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MotorcycleSeeder extends Seeder
{
    public function run(): void
    {
        $motorcycles = [
            [
                'merk' => 'Yamaha',
                'nama_model' => 'Fazzio Hybrid',
                'varian' => 'Neo',
                'harga_otr' => 22700000,
                'warna' => ['Neo Silver', 'Neo Dull Blue', 'Neo Orange', 'Neo Red', 'Neo Mint'],
                'angsuran' => [
                    11 => 2250000,
                    23 => 1290000,
                    35 => 980000,
                    47 => 835000,
                ],
            ],
            [
                'merk' => 'Yamaha',
                'nama_model' => 'Fazzio Hybrid',
                'varian' => 'Lux',
                'harga_otr' => 23350000,
                'warna' => ['Lux White Pearl', 'Lux Prestige Silver', 'Lux Matte Black'],
                'angsuran' => [
                    11 => 2315000,
                    23 => 1325000,
                    35 => 1010000,
                    47 => 860000,
                ],
            ],
            [
                'merk' => 'Yamaha',
                'nama_model' => 'Grand Filano Hybrid',
                'varian' => 'Neo',
                'harga_otr' => 27050000,
                'warna' => ['Neo Dull Blue', 'Neo Matte Black', 'Neo Red', 'Neo Pink Mauve'],
                'angsuran' => [
                    11 => 2680000,
                    23 => 1535000,
                    35 => 1170000,
                    47 => 995000,
                ],
            ],
            [
                'merk' => 'Yamaha',
                'nama_model' => 'Grand Filano Hybrid',
                'varian' => 'Lux',
                'harga_otr' => 27800000,
                'warna' => ['Lux White Pearl', 'Lux Dark Gray', 'Lux Magma Black'],
                'angsuran' => [
                    11 => 2755000,
                    23 => 1575000,
                    35 => 1200000,
                    47 => 1025000,
                ],
            ],
            [
                'merk' => 'Yamaha',
                'nama_model' => 'NMAX Turbo 155',
                'varian' => 'Tech MAX Ultimate',
                'harga_otr' => 45250000,
                'warna' => ['Magma Black', 'Elixir Dark'],
                'angsuran' => [
                    11 => 4480000,
                    23 => 2565000,
                    35 => 1955000,
                    47 => 1665000,
                ],
            ],
            [
                'merk' => 'Yamaha',
                'nama_model' => 'NMAX Turbo 155',
                'varian' => 'Standard',
                'harga_otr' => 37750000,
                'warna' => ['Magma Black', 'Elixir Dark'],
                'angsuran' => [
                    11 => 3740000,
                    23 => 2140000,
                    35 => 1630000,
                    47 => 1390000,
                ],
            ],
            [
                'merk' => 'Yamaha',
                'nama_model' => 'NMAX Neo 155',
                'varian' => 'Version Standard',
                'harga_otr' => 32700000,
                'warna' => ['Dull Blue', 'Red', 'Black', 'White'],
                'angsuran' => [
                    11 => 3240000,
                    23 => 1855000,
                    35 => 1415000,
                    47 => 1205000,
                ],
            ],
            [
                'merk' => 'Yamaha',
                'nama_model' => 'Aerox 155',
                'varian' => 'Connected/ABS',
                'harga_otr' => 31560000,
                'warna' => ['Prestige Silver', 'Signature Black'],
                'angsuran' => [
                    11 => 3125000,
                    23 => 1790000,
                    35 => 1365000,
                    47 => 1160000,
                ],
            ],
            [
                'merk' => 'Yamaha',
                'nama_model' => 'Aerox 155',
                'varian' => 'CyberCity',
                'harga_otr' => 27975000,
                'warna' => ['Cyber City Livery', 'Yellow'],
                'angsuran' => [
                    11 => 2770000,
                    23 => 1585000,
                    35 => 1210000,
                    47 => 1030000,
                ],
            ],
            [
                'merk' => 'Yamaha',
                'nama_model' => 'XSR 155',
                'varian' => 'Standard',
                'harga_otr' => 38075000,
                'warna' => ['Metallic Black Elegance', 'Matte Silver Premium', 'Light Blue Wanderlust'],
                'angsuran' => [
                    11 => 3770000,
                    23 => 2160000,
                    35 => 1645000,
                    47 => 1400000,
                ],
            ],
            [
                'merk' => 'Yamaha',
                'nama_model' => 'YZF-R15 Connected',
                'varian' => 'Connected',
                'harga_otr' => 40175000,
                'warna' => ['Racing Blue', 'Metallic Grey', 'Matte Dark Blue'],
                'angsuran' => [
                    11 => 3980000,
                    23 => 2280000,
                    35 => 1735000,
                    47 => 1480000,
                ],
            ],
            [
                'merk' => 'Yamaha',
                'nama_model' => 'WR 155 R',
                'varian' => 'Standard',
                'harga_otr' => 38900000,
                'warna' => ['Yamaha Blue', 'Yamaha Black'],
                'angsuran' => [
                    11 => 3855000,
                    23 => 2205000,
                    35 => 1680000,
                    47 => 1430000,
                ],
            ],
            [
                'merk' => 'Yamaha',
                'nama_model' => 'GEAR 125',
                'varian' => 'Standard',
                'harga_otr' => 18500000,
                'warna' => ['Dull Blue', 'Matte Navy', 'Metallic Black', 'Metallic Red'],
                'angsuran' => [
                    11 => 1835000,
                    23 => 1050000,
                    35 => 800000,
                    47 => 680000,
                ],
            ],
        ];

        foreach ($motorcycles as $motoData) {
            $moto = Motorcycle::updateOrCreate(
                [
                    'merk' => $motoData['merk'],
                    'nama_model' => $motoData['nama_model'],
                    'varian' => $motoData['varian'],
                ],
                [
                    'harga_otr' => $motoData['harga_otr'],
                    'status_aktif' => true,
                ]
            );

            // Re-seed colors cleanly
            $moto->colors()->delete();
            foreach ($motoData['warna'] as $col) {
                $moto->colors()->create([
                    'nama_warna' => $col,
                ]);
            }

            // Re-seed installments cleanly
            $moto->installments()->delete();
            foreach ($motoData['angsuran'] as $tenor => $nominal) {
                $moto->installments()->create([
                    'tenor_bulan' => $tenor,
                    'nominal_angsuran' => $nominal,
                ]);
            }
        }
    }
}
