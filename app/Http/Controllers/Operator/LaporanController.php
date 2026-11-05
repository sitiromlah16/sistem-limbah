<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use App\Models\DetailWasteShift;
use App\Models\LaporanProduksiShift;
use App\Models\MasterKategoriWaste;
use App\Models\MasterTradingLane;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class LaporanController extends Controller
{
    /**
     * Tampilkan riwayat laporan milik operator.
     */
    public function index()
    {
        $userId = Auth::id();

        $laporans = LaporanProduksiShift::with(['detailWaste.kategoriWaste', 'detailWaste.lane'])
            ->where('user_id', $userId)
            ->latest('tanggal')
            ->latest('id')
            ->paginate(10);

        $summary = LaporanProduksiShift::where('user_id', $userId)
            ->selectRaw(
                'COUNT(*) as total_laporan,
                SUM(CASE WHEN status_validasi = ? THEN 1 ELSE 0 END) as pending,
                SUM(CASE WHEN status_validasi = ? THEN 1 ELSE 0 END) as revisi,
                SUM(CASE WHEN status_validasi = ? THEN 1 ELSE 0 END) as validated,
                COALESCE(SUM(total_output_kg), 0) as total_output',
                ['Pending', 'Revisi', 'Validated'],
            )
            ->first();

        return view('operator.index', compact('laporans', 'summary'));
    }

    /**
     * Tampilkan form input pencatatan akhir shift.
     */
    public function create()
    {
        $kategoris = MasterKategoriWaste::where('status_aktif', true)->get();
        $lanes = MasterTradingLane::where('status_aktif', true)->orderBy('nama_lane')->get();

        return view('operator.create', compact('kategoris', 'lanes'));
    }

    /**
     * Simpan data laporan produksi dan rincian waste.
     */
    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => ['required', 'date'],
            'shift' => ['required', 'integer', 'in:1,2,3'],
            'total_output_kg' => ['required', 'numeric', 'gt:0'],
            'waste' => ['sometimes', 'array'],
            'waste.*' => ['nullable', 'numeric', 'min:0'],
            'lane_waste' => ['sometimes', 'array'],
            'lane_waste.*' => ['array'],
            'lane_waste.*.*' => ['nullable', 'numeric', 'min:0'],
        ], [
            'tanggal.required' => 'Tanggal laporan wajib diisi.',
            'shift.required' => 'Shift kerja wajib dipilih (1, 2, atau 3).',
            'shift.in' => 'Shift kerja harus bernilai 1, 2, atau 3.',
            'total_output_kg.required' => 'Total output produksi wajib diisi.',
            'total_output_kg.gt' => 'Total output produksi harus lebih besar dari 0 kg.',
            'waste.*.numeric' => 'Volume waste harus berupa angka valid.',
            'waste.*.min' => 'Volume waste tidak boleh bernilai negatif.',
        ]);

        // Cek validasi logika kewajaran angka: Total waste tidak boleh lebih besar dari total output
        $this->validateWasteKeys($request);
        $totalWaste = $this->submittedWasteTotal($request);
        $totalOutput = (float) $request->input('total_output_kg');

        if ($totalWaste > $totalOutput) {
            return back()
                ->withInput()
                ->withErrors([
                    'total_output_kg' => "Logika angka tidak wajar: Total volume waste ({$totalWaste} kg) melebihi total output produksi ({$totalOutput} kg)!",
                ]);
        }

        DB::transaction(function () use ($request) {
            $laporan = LaporanProduksiShift::create([
                'user_id' => Auth::id(),
                'tanggal' => $request->input('tanggal'),
                'shift' => $request->input('shift'),
                'total_output_kg' => $request->input('total_output_kg'),
                'status_validasi' => 'Pending',
                'catatan_admin' => null,
            ]);

            $this->saveWasteDetails($laporan, $request);
        });

        return redirect()->route('operator.laporan.index')
            ->with('success', 'Laporan akhir shift berhasil disubmit dan menunggu verifikasi Admin.');
    }

    /**
     * Tampilkan form edit laporan.
     * Hanya diizinkan jika status masih Pending atau Revisi.
     */
    public function edit(int $id)
    {
        $laporan = LaporanProduksiShift::with(['detailWaste.kategoriWaste', 'detailWaste.lane'])->where('user_id', Auth::id())->findOrFail($id);

        if ($laporan->status_validasi === 'Validated') {
            abort(403, 'Akses Ditolak: Laporan yang sudah tervalidasi (Validated) telah terkunci dan tidak dapat diubah.');
        }

        $kategoris = MasterKategoriWaste::where('status_aktif', true)->get();
        $lanes = MasterTradingLane::where('status_aktif', true)
            ->orWhereHas('detailWaste', fn ($query) => $query->where('laporan_produksi_id', $laporan->id))
            ->orderBy('nama_lane')
            ->get();

        $wasteValues = $laporan->detailWaste
            ->whereNull('lane_id')
            ->pluck('volume_kg', 'kategori_waste_id')
            ->toArray();
        $laneWasteValues = $laporan->detailWaste
            ->whereNotNull('lane_id')
            ->groupBy('kategori_waste_id')
            ->map(fn ($details) => $details->pluck('volume_kg', 'lane_id')->toArray())
            ->toArray();
        $legacyTradingWaste = $laporan->detailWaste
            ->filter(fn ($detail) => $detail->kategoriWaste?->rincian_per_lane && $detail->lane_id === null)
            ->sum('volume_kg');
        $preservedTradingWaste = $laporan->detailWaste
            ->filter(fn ($detail) => $detail->kategoriWaste?->rincian_per_lane && ($detail->lane_id === null || !$detail->lane?->status_aktif))
            ->sum('volume_kg');

        return view('operator.edit', compact('laporan', 'kategoris', 'lanes', 'wasteValues', 'laneWasteValues', 'legacyTradingWaste', 'preservedTradingWaste'));
    }

    /**
     * Update data laporan shift yang diedit.
     */
    public function update(Request $request, int $id)
    {
        $laporan = LaporanProduksiShift::where('user_id', Auth::id())->findOrFail($id);

        if ($laporan->status_validasi === 'Validated') {
            abort(403, 'Akses Ditolak: Laporan yang sudah tervalidasi telah terkunci.');
        }

        $request->validate([
            'tanggal' => ['required', 'date'],
            'shift' => ['required', 'integer', 'in:1,2,3'],
            'total_output_kg' => ['required', 'numeric', 'gt:0'],
            'waste' => ['sometimes', 'array'],
            'waste.*' => ['nullable', 'numeric', 'min:0'],
            'lane_waste' => ['sometimes', 'array'],
            'lane_waste.*' => ['array'],
            'lane_waste.*.*' => ['nullable', 'numeric', 'min:0'],
        ]);

        $this->validateWasteKeys($request, $laporan);
        $totalWaste = $this->submittedWasteTotal($request) + (float) $laporan->detailWaste()
            ->whereHas('kategoriWaste', fn ($query) => $query->where('rincian_per_lane', true))
            ->where(function ($query) {
                $query->whereNull('lane_id')
                    ->orWhereHas('lane', fn ($lane) => $lane->where('status_aktif', false));
            })
            ->sum('volume_kg');
        $totalOutput = (float) $request->input('total_output_kg');

        if ($totalWaste > $totalOutput) {
            return back()
                ->withInput()
                ->withErrors([
                    'total_output_kg' => "Logika angka tidak wajar: Total volume waste ({$totalWaste} kg) melebihi total output produksi ({$totalOutput} kg)!",
                ]);
        }

        DB::transaction(function () use ($request, $laporan) {
            $laporan->update([
                'tanggal' => $request->input('tanggal'),
                'shift' => $request->input('shift'),
                'total_output_kg' => $request->input('total_output_kg'),
                'status_validasi' => 'Pending', // Kembalikan ke Pending agar diverifikasi ulang
            ]);

            $this->saveWasteDetails($laporan, $request);
        });

        return redirect()->route('operator.laporan.index')
            ->with('success', 'Laporan berhasil diperbarui dan diajukan ulang ke Admin untuk verifikasi.');
    }

    private function validateWasteKeys(Request $request, ?LaporanProduksiShift $laporan = null): void
    {
        $categoryIds = collect(array_keys($request->input('waste', [])))
            ->merge(array_keys($request->input('lane_waste', [])))
            ->unique();
        $categories = MasterKategoriWaste::whereIn('id', $categoryIds)
            ->where('status_aktif', true)
            ->get()
            ->keyBy('id');

        foreach ($categoryIds as $categoryId) {
            if (!$categories->has($categoryId)) {
                throw ValidationException::withMessages([
                    'waste' => 'Kategori limbah tidak valid atau sudah tidak aktif. Muat ulang formulir.',
                ]);
            }

            $isLaneCategory = $categories[$categoryId]->rincian_per_lane;
            $sentAsLane = array_key_exists($categoryId, $request->input('lane_waste', []));
            $sentAsCategory = array_key_exists($categoryId, $request->input('waste', []));

            if ($isLaneCategory !== $sentAsLane || ($isLaneCategory && $sentAsCategory)) {
                throw ValidationException::withMessages([
                    'waste' => 'Rincian limbah tidak sesuai dengan kategori yang dipilih.',
                ]);
            }
        }

        $submittedLaneIds = collect($request->input('lane_waste', []))
            ->flatMap(fn ($values) => array_keys($values))
            ->unique();
        $validLaneIds = MasterTradingLane::whereIn('id', $submittedLaneIds)
            ->where(function ($query) use ($laporan) {
                $query->where('status_aktif', true);
                if ($laporan) {
                    $query->orWhereHas('detailWaste', fn ($details) => $details->where('laporan_produksi_id', $laporan->id));
                }
            })
            ->pluck('id')
            ->map(fn ($id) => (string) $id)
            ->all();

        foreach ($submittedLaneIds as $laneId) {
            if (!in_array((string) $laneId, $validLaneIds, true)) {
                throw ValidationException::withMessages([
                    'lane_waste' => 'Lane tidak valid atau sudah tidak aktif. Muat ulang formulir.',
                ]);
            }
        }
    }

    private function submittedWasteTotal(Request $request): float
    {
        $categoryWaste = collect($request->input('waste', []))
            ->sum(fn ($volume) => (float) ($volume ?? 0));
        $laneWaste = collect($request->input('lane_waste', []))
            ->flatMap(fn ($values) => $values)
            ->sum(fn ($volume) => (float) ($volume ?? 0));

        return (float) ($categoryWaste + $laneWaste);
    }

    private function saveWasteDetails(LaporanProduksiShift $laporan, Request $request): void
    {
        foreach ($request->input('waste', []) as $categoryId => $volume) {
            DetailWasteShift::updateOrCreate(
                [
                    'laporan_produksi_id' => $laporan->id,
                    'kategori_waste_id' => $categoryId,
                    'lane_id' => null,
                ],
                ['volume_kg' => (float) ($volume ?? 0)],
            );
        }

        foreach ($request->input('lane_waste', []) as $categoryId => $laneValues) {
            foreach ($laneValues as $laneId => $volume) {
                DetailWasteShift::updateOrCreate(
                    [
                        'laporan_produksi_id' => $laporan->id,
                        'kategori_waste_id' => $categoryId,
                        'lane_id' => $laneId,
                    ],
                    ['volume_kg' => (float) ($volume ?? 0)],
                );
            }
        }
    }
}
