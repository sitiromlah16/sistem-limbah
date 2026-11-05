<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanProduksiShift extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'tanggal',
        'shift',
        'total_output_kg',
        'status_validasi',
        'catatan_admin',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'total_output_kg' => 'float',
    ];

    // Relasi Many-to-One: Banyak Laporan dimiliki oleh 1 User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi One-to-Many: 1 Laporan memiliki banyak rincian Detail Waste
    public function detailWaste()
    {
        return $this->hasMany(DetailWasteShift::class, 'laporan_produksi_id');
    }

    // Helper: Menghitung total seluruh waste limbah (kg) pada laporan ini
    public function getTotalWasteAttribute(): float
    {
        return (float) $this->detailWaste->sum('volume_kg');
    }

    // Helper: Menghitung Reject Ratio (%): (Total Waste / Total Output) * 100%
    public function getRejectRatioAttribute(): float
    {
        if ($this->total_output_kg > 0) {
            return round(($this->total_waste / $this->total_output_kg) * 100, 2);
        }
        return 0.0;
    }
}