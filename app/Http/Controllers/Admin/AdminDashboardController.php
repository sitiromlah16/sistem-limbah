<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LaporanProduksiShift;
use App\Models\MasterKategoriWaste;

class AdminDashboardController extends Controller
{
    /**
     * Dashboard ringkasan untuk Admin Produksi.
     */
    public function index()
    {
        $totalPending = LaporanProduksiShift::where('status_validasi', 'Pending')->count();
        $totalValidated = LaporanProduksiShift::where('status_validasi', 'Validated')->count();
        $totalRevisi = LaporanProduksiShift::where('status_validasi', 'Revisi')->count();
        $totalKategori = MasterKategoriWaste::count();

        // 5 Laporan terbaru yang membutuhkan verifikasi (Pending)
        $pendingLaporans = LaporanProduksiShift::with(['user', 'detailWaste.kategoriWaste'])
            ->where('status_validasi', 'Pending')
            ->latest('tanggal')
            ->latest('id')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalPending',
            'totalValidated',
            'totalRevisi',
            'totalKategori',
            'pendingLaporans'
        ));
    }
}

