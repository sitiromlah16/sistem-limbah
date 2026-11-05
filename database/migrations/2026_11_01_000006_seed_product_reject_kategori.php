<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('master_kategori_wastes')) {
            $exists = DB::table('master_kategori_wastes')
                ->where('nama_kategori', 'Product Reject')
                ->orWhere('nama_kategori', 'like', '%Product Reject%')
                ->exists();

            if (!$exists) {
                DB::table('master_kategori_wastes')->insert([
                    'nama_kategori' => 'Product Reject',
                    'deskripsi' => 'Produk cacat benang DTY hasil downgrade di bawah batas mutlak Grade AX (5.5 Kg/bobbin), seperti Grade B atau C.',
                    'status_aktif' => true,
                    'rincian_per_lane' => false,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('master_kategori_wastes')) {
            DB::table('master_kategori_wastes')
                ->where('nama_kategori', 'Product Reject')
                ->delete();
        }
    }
};

