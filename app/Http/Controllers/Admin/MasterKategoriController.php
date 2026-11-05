<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MasterKategoriWaste;
use App\Models\MasterTradingLane;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class MasterKategoriController extends Controller
{
    /**
     * Tampilkan daftar master kategori waste & reject.
     */
    public function index()
    {
        $kategoris = MasterKategoriWaste::withCount('detailWaste')
            ->latest('id')
            ->paginate(10);
        $lanes = MasterTradingLane::withCount('detailWaste')
            ->orderBy('nama_lane')
            ->get();

        return view('admin.kategori.index', compact('kategoris', 'lanes'));
    }

    /**
     * Tambah jenis kategori limbah baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => ['required', 'string', 'max:100', 'unique:master_kategori_wastes,nama_kategori'],
            'deskripsi' => ['nullable', 'string', 'max:255'],
            'status_aktif' => ['nullable', 'boolean'],
            'rincian_per_lane' => ['nullable', 'boolean'],
        ], [
            'nama_kategori.required' => 'Nama kategori waste wajib diisi.',
            'nama_kategori.unique' => 'Kategori waste dengan nama ini sudah ada.',
        ]);

        MasterKategoriWaste::create([
            'nama_kategori' => $request->input('nama_kategori'),
            'deskripsi' => $request->input('deskripsi'),
            'status_aktif' => $request->boolean('status_aktif', true),
            'rincian_per_lane' => $request->boolean('rincian_per_lane'),
        ]);

        return redirect()->route('admin.kategori.index')
            ->with('success', 'Kategori limbah berhasil ditambahkan.');
    }

    /**
     * Update kategori limbah.
     */
    public function update(Request $request, int $id)
    {
        $kategori = MasterKategoriWaste::findOrFail($id);

        $request->validate([
            'nama_kategori' => ['required', 'string', 'max:100', 'unique:master_kategori_wastes,nama_kategori,' . $id],
            'deskripsi' => ['nullable', 'string', 'max:255'],
            'status_aktif' => ['nullable', 'boolean'],
            'rincian_per_lane' => ['nullable', 'boolean'],
        ], [
            'nama_kategori.required' => 'Nama kategori waste wajib diisi.',
            'nama_kategori.unique' => 'Kategori waste dengan nama ini sudah ada.',
        ]);

        $rincianPerLane = $request->boolean('rincian_per_lane');
        if ($kategori->detailWaste()->exists() && $kategori->rincian_per_lane !== $rincianPerLane) {
            throw ValidationException::withMessages([
                'rincian_per_lane' => 'Mode pencatatan per lane tidak dapat diubah karena kategori ini sudah dipakai pada laporan.',
            ]);
        }

        $kategori->update([
            'nama_kategori' => $request->input('nama_kategori'),
            'deskripsi' => $request->input('deskripsi'),
            'status_aktif' => $request->boolean('status_aktif'),
            'rincian_per_lane' => $rincianPerLane,
        ]);

        return redirect()->route('admin.kategori.index')
            ->with('success', 'Kategori limbah berhasil diperbarui.');
    }

    /**
     * Hapus kategori atau nonaktifkan jika sudah berelasi dengan data laporan.
     */
    public function destroy(int $id)
    {
        $kategori = MasterKategoriWaste::withCount('detailWaste')->findOrFail($id);

        if ($kategori->detail_waste_count > 0) {
            // Lindungi integritas data historis: ubah status menjadi non-aktif saja
            $kategori->update(['status_aktif' => false]);
            return redirect()->route('admin.kategori.index')
                ->with('info', "Kategori '{$kategori->nama_kategori}' sudah pernah digunakan pada laporan. Statusnya dialihkan menjadi Non-Aktif.");
        }

        $kategori->delete();

        return redirect()->route('admin.kategori.index')
            ->with('success', 'Kategori limbah berhasil dihapus permanen.');
    }

    public function storeLane(Request $request)
    {
        $validated = $request->validate([
            'nama_lane' => ['required', 'string', 'max:100', 'unique:master_trading_lanes,nama_lane'],
        ], [
            'nama_lane.required' => 'Nama lane wajib diisi.',
            'nama_lane.unique' => 'Nama lane tersebut sudah digunakan.',
        ]);

        MasterTradingLane::create([
            'nama_lane' => trim($validated['nama_lane']),
            'status_aktif' => true,
        ]);

        return redirect()->route('admin.kategori.index')->with('success', 'Lane Trading berhasil ditambahkan.');
    }

    public function updateLane(Request $request, int $id)
    {
        $lane = MasterTradingLane::findOrFail($id);
        $validated = $request->validate([
            'nama_lane' => ['required', 'string', 'max:100', 'unique:master_trading_lanes,nama_lane,' . $id],
            'status_aktif' => ['nullable', 'boolean'],
        ], [
            'nama_lane.required' => 'Nama lane wajib diisi.',
            'nama_lane.unique' => 'Nama lane tersebut sudah digunakan.',
        ]);

        $lane->update([
            'nama_lane' => trim($validated['nama_lane']),
            'status_aktif' => $request->boolean('status_aktif'),
        ]);

        return redirect()->route('admin.kategori.index')->with('success', 'Pengaturan lane Trading berhasil diperbarui.');
    }

    public function destroyLane(int $id)
    {
        $lane = MasterTradingLane::withCount('detailWaste')->findOrFail($id);

        if ($lane->detail_waste_count > 0) {
            $lane->update(['status_aktif' => false]);

            return redirect()->route('admin.kategori.index')
                ->with('info', "Lane '{$lane->nama_lane}' sudah digunakan pada laporan. Lane dinonaktifkan agar data historis tetap terjaga.");
        }

        $lane->delete();

        return redirect()->route('admin.kategori.index')->with('success', 'Lane Trading berhasil dihapus.');
    }
}
