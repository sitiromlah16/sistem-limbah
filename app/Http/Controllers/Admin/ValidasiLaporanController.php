<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LaporanProduksiShift;
use Illuminate\Http\Request;

class ValidasiLaporanController extends Controller
{
    /**
     * Tampilkan daftar laporan masuk untuk validasi oleh Admin.
     */
    public function index(Request $request)
    {
        $status = $request->input('status');
        $shift = $request->input('shift');
        $tanggal = $request->input('tanggal');

        $query = LaporanProduksiShift::with(['user', 'detailWaste.kategoriWaste', 'detailWaste.lane'])
            ->latest('tanggal')
            ->latest('id');

        if ($status && in_array($status, ['Pending', 'Validated', 'Revisi'])) {
            $query->where('status_validasi', $status);
        }

        if ($shift) {
            $query->where('shift', $shift);
        }

        if ($tanggal) {
            $query->whereDate('tanggal', $tanggal);
        }

        $laporans = $query->paginate(12)->withQueryString();

        return view('admin.validasi.index', compact('laporans', 'status', 'shift', 'tanggal'));
    }

    /**
     * Tampilkan detail rincian laporan shift.
     */
    public function show(int $id)
    {
        $laporan = LaporanProduksiShift::with(['user', 'detailWaste.kategoriWaste', 'detailWaste.lane'])->findOrFail($id);

        return view('admin.validasi.show', compact('laporan'));
    }

    /**
     * Setujui laporan (Status: Validated).
     * Data menjadi terkunci dan masuk ke laporan eksekutif.
     */
    public function validateLaporan(Request $request, int $id)
    {
        $laporan = LaporanProduksiShift::findOrFail($id);

        $laporan->update([
            'status_validasi' => 'Validated',
            'catatan_admin' => $request->input('catatan_admin') ?: 'Telah diverifikasi dan disetujui oleh Admin.',
        ]);

        return redirect()->route('admin.validasi.index')
            ->with('success', "Laporan Shift #{$laporan->id} (Tgl: {$laporan->tanggal->format('d/m/Y')}) berhasil divalidasi dan terkunci.");
    }

    /**
     * Tolak laporan untuk perbaikan (Status: Revisi).
     * Catatan wajib diberikan agar Operator tahu apa yang harus diperbaiki.
     */
    public function rejectLaporan(Request $request, int $id)
    {
        $request->validate([
            'catatan_admin' => ['required', 'string', 'min:5', 'max:500'],
        ], [
            'catatan_admin.required' => 'Catatan alasan revisi wajib diisi agar operator mengetahui poin perbaikan.',
            'catatan_admin.min' => 'Catatan revisi minimal berisi 5 karakter.',
        ]);

        $laporan = LaporanProduksiShift::findOrFail($id);

        $laporan->update([
            'status_validasi' => 'Revisi',
            'catatan_admin' => $request->input('catatan_admin'),
        ]);

        return redirect()->route('admin.validasi.index')
            ->with('warning', "Laporan Shift #{$laporan->id} dikembalikan ke Operator dengan status Revisi.");
    }
}
