<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailWasteShift extends Model
{
    use HasFactory;

    protected $fillable = [
        'laporan_produksi_id',
        'kategori_waste_id',
        'lane_id',
        'volume_kg',
    ];

    // Relasi Many-to-One: Detail ini milik 1 Laporan Shift
    public function laporanProduksi()
    {
        return $this->belongsTo(LaporanProduksiShift::class, 'laporan_produksi_id');
    }

    // Relasi Many-to-One: Detail ini merujuk pada 1 Kategori Waste
    public function kategoriWaste()
    {
        return $this->belongsTo(MasterKategoriWaste::class, 'kategori_waste_id');
    }

    public function lane()
    {
        return $this->belongsTo(MasterTradingLane::class, 'lane_id');
    }
}