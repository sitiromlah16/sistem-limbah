<?php

namespace Database\Seeders;

use App\Models\MasterTradingLane;
use Illuminate\Database\Seeder;

class MasterTradingLaneSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['Lane A', 'Lane B', 'Lane C'] as $namaLane) {
            MasterTradingLane::updateOrCreate(
                ['nama_lane' => $namaLane],
                ['status_aktif' => true],
            );
        }
    }
}
