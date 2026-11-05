<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MasterKategoriWaste;

class MasterKategoriWasteSeeder extends Seeder
{
    public function run(): void
    {
        $kategori = [
            [
                'nama_kategori' => 'Trading',
                'deskripsi' => 'Limbah benang DTY jenis Trading (rincian per lane mesin)',
                'rincian_per_lane' => true,
            ],
            [
                'nama_kategori' => 'Cutting',
                'deskripsi' => 'Limbah sisa pemotongan benang DTY saat doffing',
                'rincian_per_lane' => false,
            ],
            [
                'nama_kategori' => 'Stripping',
                'deskripsi' => 'Limbah sisa pengupasan benang pada bobbin/cones DTY',
                'rincian_per_lane' => false,
            ],
            [
                'nama_kategori' => 'Product Reject',
                'deskripsi' => 'Produk cacat benang DTY hasil downgrade di bawah batas mutlak Grade AX (5.5 Kg/bobbin), seperti Grade B atau C',
                'rincian_per_lane' => false,
            ],
        ];

        foreach ($kategori as $kat) {
            MasterKategoriWaste::updateOrCreate(
                ['nama_kategori' => $kat['nama_kategori']],
                [
                    'deskripsi' => $kat['deskripsi'],
                    'status_aktif' => true,
                    'rincian_per_lane' => $kat['rincian_per_lane'],
                ],
            );
        }
    }
}