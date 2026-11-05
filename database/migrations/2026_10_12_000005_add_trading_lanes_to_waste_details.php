<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('master_trading_lanes', function (Blueprint $table) {
            $table->id();
            $table->string('nama_lane', 100)->unique();
            $table->boolean('status_aktif')->default(true);
            $table->timestamps();
        });

        Schema::table('master_kategori_wastes', function (Blueprint $table) {
            $table->boolean('rincian_per_lane')->default(false)->after('status_aktif');
        });

        Schema::table('detail_waste_shifts', function (Blueprint $table) {
            $table->foreignId('lane_id')
                ->nullable()
                ->after('kategori_waste_id')
                ->constrained('master_trading_lanes')
                ->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('detail_waste_shifts', function (Blueprint $table) {
            $table->dropConstrainedForeignId('lane_id');
        });

        Schema::table('master_kategori_wastes', function (Blueprint $table) {
            $table->dropColumn('rincian_per_lane');
        });

        Schema::dropIfExists('master_trading_lanes');
    }
};
