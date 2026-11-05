<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('detail_waste_shifts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('laporan_produksi_id')->constrained('laporan_produksi_shifts')->onDelete('cascade');
            $table->foreignId('kategori_waste_id')->constrained('master_kategori_wastes')->onDelete('cascade');
            $table->decimal('volume_kg', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_waste_shifts');
    }
};

