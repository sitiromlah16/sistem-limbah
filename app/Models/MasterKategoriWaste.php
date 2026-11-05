<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterKategoriWaste extends Model
{
    use HasFactory;

    // Field yang diizinkan untuk diisi massal (mass assignment)
    protected $fillable = [
        'nama_kategori',
        'deskripsi',
        'status_aktif',
        'rincian_per_lane',
    ];

    protected $casts = [
        'status_aktif' => 'boolean',
        'rincian_per_lane' => 'boolean',
    ];

    // Relasi One-to-Many: 1 Kategori bisa ada di banyak detail laporan
    public function detailWaste()
    {
        return $this->hasMany(DetailWasteShift::class, 'kategori_waste_id');
    }
}