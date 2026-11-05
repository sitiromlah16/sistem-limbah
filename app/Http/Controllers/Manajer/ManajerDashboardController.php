<?php

namespace App\Http\Controllers\Manajer;

use App\Http\Controllers\Controller;
use App\Models\DetailWasteShift;
use App\Models\LaporanProduksiShift;
use App\Models\MasterKategoriWaste;
use App\Models\MasterTradingLane;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ManajerDashboardController extends Controller
{
    /**
     * Executive Dashboard untuk Manajer Produksi.
     */
    public function index()
    {
        // Hanya laporan yang sudah tervalidasi oleh Admin
        $validatedQuery = LaporanProduksiShift::where('status_validasi', 'Validated');

        $totalLaporan = (clone $validatedQuery)->count();
        $totalOutput = (clone $validatedQuery)->sum('total_output_kg');

        // Total waste dari semua laporan tervalidasi
        $totalWaste = DetailWasteShift::whereHas('laporanProduksi', function ($q) {
            $q->where('status_validasi', 'Validated');
        })->sum('volume_kg');

        // Overall Reject Ratio (%)
        $overallRatio = $totalOutput > 0 ? round(($totalWaste / $totalOutput) * 100, 2) : 0.0;

        // Data Komparasi per Shift (Shift 1, Shift 2, Shift 3)
        $shiftStats = [];
        for ($s = 1; $s <= 3; $s++) {
            $shiftOutput = (clone $validatedQuery)->where('shift', $s)->sum('total_output_kg');
            $shiftWaste = DetailWasteShift::whereHas('laporanProduksi', function ($q) use ($s) {
                $q->where('status_validasi', 'Validated')->where('shift', $s);
            })->sum('volume_kg');

            $shiftRatio = $shiftOutput > 0 ? round(($shiftWaste / $shiftOutput) * 100, 2) : 0.0;

            $shiftStats[$s] = [
                'output' => $shiftOutput,
                'waste' => $shiftWaste,
                'ratio' => $shiftRatio,
                'count' => (clone $validatedQuery)->where('shift', $s)->count(),
            ];
        }

        // Breakdown per Kategori Waste
        $kategoriStats = MasterKategoriWaste::with(['detailWaste' => function ($q) {
            $q->whereHas('laporanProduksi', function ($sub) {
                $sub->where('status_validasi', 'Validated');
            });
        }])->get()->map(function ($kat) {
            $vol = $kat->detailWaste->sum('volume_kg');
            return [
                'nama' => $kat->nama_kategori,
                'volume' => $vol,
            ];
        });
        $laneAnalytics = $this->tradingLaneAnalytics();

        // Tren 7 Laporan Terakhir Tervalidasi
        $recentLaporans = LaporanProduksiShift::with(['detailWaste.kategoriWaste', 'detailWaste.lane', 'user'])
            ->where('status_validasi', 'Validated')
            ->latest('tanggal')
            ->latest('id')
            ->take(7)
            ->get();

        return view('manajer.dashboard', compact(
            'totalLaporan',
            'totalOutput',
            'totalWaste',
            'overallRatio',
            'shiftStats',
            'kategoriStats',
            'laneAnalytics',
            'recentLaporans'
        ));
    }

    /**
     * Laporan Periodik (F-06): Filter rentang tanggal & shift.
     */
    public function laporan(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $shift = $request->input('shift');

        $query = LaporanProduksiShift::with(['user', 'detailWaste.kategoriWaste', 'detailWaste.lane'])
            ->where('status_validasi', 'Validated')
            ->orderBy('tanggal', 'desc')
            ->orderBy('shift', 'asc');

        if ($startDate) {
            $query->whereDate('tanggal', '>=', $startDate);
        }

        if ($endDate) {
            $query->whereDate('tanggal', '<=', $endDate);
        }

        if ($shift) {
            $query->where('shift', $shift);
        }

        $laporans = $query->get();

        $summaryOutput = $laporans->sum('total_output_kg');
        $summaryWaste = $laporans->sum(fn ($l) => $l->total_waste);
        $summaryRatio = $summaryOutput > 0 ? round(($summaryWaste / $summaryOutput) * 100, 2) : 0.0;

        $kategoris = $this->reportCategories($startDate, $endDate, $shift);
        $laneAnalytics = $this->tradingLaneAnalytics($startDate, $endDate, $shift);

        return view('manajer.laporan', compact(
            'laporans',
            'startDate',
            'endDate',
            'shift',
            'summaryOutput',
            'summaryWaste',
            'summaryRatio',
            'kategoris',
            'laneAnalytics'
        ));
    }

    /**
     * Unduh laporan periodik sebagai PDF.
     */
    public function cetak(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $shift = $request->input('shift');

        $query = LaporanProduksiShift::with(['user', 'detailWaste.kategoriWaste', 'detailWaste.lane'])
            ->where('status_validasi', 'Validated')
            ->orderBy('tanggal', 'asc')
            ->orderBy('shift', 'asc');

        if ($startDate) {
            $query->whereDate('tanggal', '>=', $startDate);
        }

        if ($endDate) {
            $query->whereDate('tanggal', '<=', $endDate);
        }

        if ($shift) {
            $query->where('shift', $shift);
        }

        $laporans = $query->get();

        $summaryOutput = $laporans->sum('total_output_kg');
        $summaryWaste = $laporans->sum(fn ($l) => $l->total_waste);
        $summaryRatio = $summaryOutput > 0 ? round(($summaryWaste / $summaryOutput) * 100, 2) : 0.0;

        $kategoris = $this->reportCategories($startDate, $endDate, $shift);
        $laneAnalytics = $this->tradingLaneAnalytics($startDate, $endDate, $shift);

        $pdf = Pdf::loadView('manajer.pdf', compact(
            'laporans',
            'startDate',
            'endDate',
            'shift',
            'summaryOutput',
            'summaryWaste',
            'summaryRatio',
            'kategoris',
            'laneAnalytics'
        ))->setPaper('a4', 'landscape');

        return $pdf->download('laporan-rekap-limbah-' . now()->format('Ymd-His') . '.pdf');
    }

    private function tradingLaneAnalytics(?string $startDate = null, ?string $endDate = null, ?string $shift = null): array
    {
        $applyReportFilters = function (Builder $query) use ($startDate, $endDate, $shift): void {
            $query->where('status_validasi', 'Validated');
            if ($startDate) {
                $query->whereDate('tanggal', '>=', $startDate);
            }
            if ($endDate) {
                $query->whereDate('tanggal', '<=', $endDate);
            }
            if ($shift) {
                $query->where('shift', $shift);
            }
        };

        $laneVolumes = DetailWasteShift::query()
            ->select('lane_id')
            ->selectRaw('SUM(volume_kg) as total_volume')
            ->selectRaw('COUNT(DISTINCT laporan_produksi_id) as report_count')
            ->whereNotNull('lane_id')
            ->whereHas('kategoriWaste', fn ($query) => $query->where('rincian_per_lane', true))
            ->whereHas('laporanProduksi', $applyReportFilters)
            ->groupBy('lane_id')
            ->get()
            ->keyBy('lane_id');

        $lanes = MasterTradingLane::query()
            ->where('status_aktif', true)
            ->orWhereHas('detailWaste', function ($query) use ($applyReportFilters) {
                $query->whereHas('kategoriWaste', fn ($category) => $category->where('rincian_per_lane', true))
                    ->whereHas('laporanProduksi', $applyReportFilters);
            })
            ->orderBy('nama_lane')
            ->get()
            ->map(function (MasterTradingLane $lane) use ($laneVolumes): array {
                $volume = $laneVolumes->get($lane->id);

                return [
                    'id' => $lane->id,
                    'nama' => $lane->nama_lane,
                    'aktif' => $lane->status_aktif,
                    'volume' => (float) ($volume->total_volume ?? 0),
                    'jumlah_laporan' => (int) ($volume->report_count ?? 0),
                ];
            });

        $unassignedVolume = DetailWasteShift::query()
            ->whereNull('lane_id')
            ->whereHas('kategoriWaste', fn ($query) => $query->where('rincian_per_lane', true))
            ->whereHas('laporanProduksi', $applyReportFilters)
            ->sum('volume_kg');

        return [
            'lanes' => $lanes,
            'unassigned_volume' => (float) $unassignedVolume,
        ];
    }

    private function reportCategories(?string $startDate, ?string $endDate, ?string $shift)
    {
        $applyReportFilters = function (Builder $query) use ($startDate, $endDate, $shift): void {
            $query->where('status_validasi', 'Validated');
            if ($startDate) {
                $query->whereDate('tanggal', '>=', $startDate);
            }
            if ($endDate) {
                $query->whereDate('tanggal', '<=', $endDate);
            }
            if ($shift) {
                $query->where('shift', $shift);
            }
        };

        return MasterKategoriWaste::query()
            ->where('status_aktif', true)
            ->orWhereHas('detailWaste.laporanProduksi', $applyReportFilters)
            ->orderBy('nama_kategori')
            ->get();
    }
}
