<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterTradingLane extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_lane',
        'status_aktif',
    ];

    protected $casts = [
        'status_aktif' => 'boolean',
    ];

    public function detailWaste()
    {
        return $this->hasMany(DetailWasteShift::class, 'lane_id');
    }
}
