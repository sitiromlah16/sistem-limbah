<?php

namespace Database\Seeders;

use App\Models\DetailWasteShift;
use App\Models\LaporanProduksiShift;
use App\Models\MasterKategoriWaste;
use App\Models\MasterTradingLane;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class LaporanProduksiDummySeeder extends Seeder
{
    public function run(): void
    {
        $operator = User::where('role', 'operator')->first();
        if (!$operator) return;

        $kategoris = MasterKategoriWaste::all()->keyBy('nama_kategori');
        if ($kategoris->isEmpty()) return;

        $tradingId = $kategoris['Trading']->id ?? 1;
        $cuttingId = $kategoris['Cutting']->id ?? 2;
        $strippingId = $kategoris['Stripping']->id ?? 3;
        $lanes = MasterTradingLane::where('status_aktif', true)->orderBy('id')->get();

        // Data sampel 1: Kemarin Shift 1 (Validated)
        $lap1 = LaporanProduksiShift::create([
            'user_id' => $operator->id,
            'tanggal' => Carbon::now()->subDays(2)->format('Y-m-d'),
            'shift' => 1,
            'total_output_kg' => 15000.00,
            'status_validasi' => 'Validated',
            'catatan_admin' => 'Data sesuai standar toleransi limbah shift pagi.',
        ]);
        $this->createTradingDetails($lap1->id, $tradingId, 120.50, $lanes);
        DetailWasteShift::create(['laporan_produksi_id' => $lap1->id, 'kategori_waste_id' => $cuttingId, 'volume_kg' => 95.00]);
        DetailWasteShift::create(['laporan_produksi_id' => $lap1->id, 'kategori_waste_id' => $strippingId, 'volume_kg' => 80.20]);

        // Data sampel 2: Kemarin Shift 2 (Validated)
        $lap2 = LaporanProduksiShift::create([
            'user_id' => $operator->id,
            'tanggal' => Carbon::now()->subDays(2)->format('Y-m-d'),
            'shift' => 2,
            'total_output_kg' => 14200.00,
            'status_validasi' => 'Validated',
            'catatan_admin' => 'Tervalidasi.',
        ]);
        $this->createTradingDetails($lap2->id, $tradingId, 140.00, $lanes);
        DetailWasteShift::create(['laporan_produksi_id' => $lap2->id, 'kategori_waste_id' => $cuttingId, 'volume_kg' => 110.50]);
        DetailWasteShift::create(['laporan_produksi_id' => $lap2->id, 'kategori_waste_id' => $strippingId, 'volume_kg' => 95.00]);

        // Data sampel 3: Kemarin Shift 3 (Validated)
        $lap3 = LaporanProduksiShift::create([
            'user_id' => $operator->id,
            'tanggal' => Carbon::now()->subDays(1)->format('Y-m-d'),
            'shift' => 3,
            'total_output_kg' => 13500.00,
            'status_validasi' => 'Validated',
            'catatan_admin' => 'Tervalidasi, waste stripping sedikit naik di malam hari.',
        ]);
        $this->createTradingDetails($lap3->id, $tradingId, 115.00, $lanes);
        DetailWasteShift::create(['laporan_produksi_id' => $lap3->id, 'kategori_waste_id' => $cuttingId, 'volume_kg' => 130.00]);
        DetailWasteShift::create(['laporan_produksi_id' => $lap3->id, 'kategori_waste_id' => $strippingId, 'volume_kg' => 125.40]);

        // Data sampel 4: Hari ini Shift 1 (Pending)
        $lap4 = LaporanProduksiShift::create([
            'user_id' => $operator->id,
            'tanggal' => Carbon::now()->format('Y-m-d'),
            'shift' => 1,
            'total_output_kg' => 16000.00,
            'status_validasi' => 'Pending',
            'catatan_admin' => null,
        ]);
        $this->createTradingDetails($lap4->id, $tradingId, 110.00, $lanes);
        DetailWasteShift::create(['laporan_produksi_id' => $lap4->id, 'kategori_waste_id' => $cuttingId, 'volume_kg' => 88.50]);
        DetailWasteShift::create(['laporan_produksi_id' => $lap4->id, 'kategori_waste_id' => $strippingId, 'volume_kg' => 75.00]);

        // Data sampel 5: Hari ini Shift 2 (Revisi)
        $lap5 = LaporanProduksiShift::create([
            'user_id' => $operator->id,
            'tanggal' => Carbon::now()->format('Y-m-d'),
            'shift' => 2,
            'total_output_kg' => 14000.00,
            'status_validasi' => 'Revisi',
            'catatan_admin' => 'Volume waste cutting tampak terlalu tinggi (450 kg), tolong cek apakah salah input atau kalibrasi timbangan?',
        ]);
        $this->createTradingDetails($lap5->id, $tradingId, 130.00, $lanes);
        DetailWasteShift::create(['laporan_produksi_id' => $lap5->id, 'kategori_waste_id' => $cuttingId, 'volume_kg' => 450.00]);
        DetailWasteShift::create(['laporan_produksi_id' => $lap5->id, 'kategori_waste_id' => $strippingId, 'volume_kg' => 90.00]);
    }

    private function createTradingDetails(int $laporanId, int $kategoriId, float $volume, $lanes): void
    {
        if ($lanes->isEmpty()) {
            DetailWasteShift::create([
                'laporan_produksi_id' => $laporanId,
                'kategori_waste_id' => $kategoriId,
                'volume_kg' => $volume,
            ]);

            return;
        }

        $totalCents = (int) round($volume * 100);
        $baseCents = intdiv($totalCents, $lanes->count());
        $remainingCents = $totalCents % $lanes->count();

        foreach ($lanes as $index => $lane) {
            $laneCents = $baseCents + ($index < $remainingCents ? 1 : 0);
            DetailWasteShift::create([
                'laporan_produksi_id' => $laporanId,
                'kategori_waste_id' => $kategoriId,
                'lane_id' => $lane->id,
                'volume_kg' => $laneCents / 100,
            ]);
        }
    }
}
