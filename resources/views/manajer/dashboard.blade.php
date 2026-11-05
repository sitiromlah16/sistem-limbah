@extends('layouts.app')

@section('title', 'Executive Dashboard Analitik - Manajer')

@section('content')
<div class="space-y-6">

    <!-- Header Section (Modern Minimalist Executive) -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-2 border-b border-slate-200/70">
        <div>
            <div class="flex items-center gap-2 mb-1.5">
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold tracking-wider uppercase bg-purple-50 text-purple-700 border border-purple-100">
                    <i class="fa-solid fa-chart-line text-[9px]"></i>
                    Executive Overview · Read Only
                </span>
                <span class="text-xs text-slate-300">·</span>
                <span class="text-xs text-slate-500 font-medium">Laporan Tervalidasi</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Analitik produksi & efisiensi</h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">
                Pantau performa tervalidasi, komparasi rasio reject antar-shift, dan efisiensi benang <strong>DTY</strong> (<em>Draw Textured Yarn</em>).
            </p>
        </div>
        <div class="flex items-center gap-2.5 self-start sm:self-auto shrink-0">
            <a href="{{ route('manajer.laporan') }}" 
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-purple-600 hover:bg-purple-700 text-white font-semibold text-xs sm:text-sm shadow-sm transition active:scale-[0.98]">
                <i class="fa-solid fa-file-invoice text-xs"></i>
                <span>Buka laporan periodik</span>
            </a>
        </div>
    </div>

    <!-- Executive KPI Cards (Modern & Minimalist) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Total Output Produksi -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs hover:border-slate-300 transition-all">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Total Output</span>
                <span class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-industry"></i>
                </span>
            </div>
            <div class="mt-2.5">
                <span class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight tabular-nums">{{ number_format($totalOutput, 2, ',', '.') }}</span>
                <span class="text-xs font-medium text-slate-400 ml-1">kg</span>
            </div>
            <div class="mt-2.5 flex items-center gap-1.5 text-xs text-slate-500">
                <span class="inline-block w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                <span>Benang jadi tervalidasi</span>
            </div>
        </div>

        <!-- Total Limbah -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs hover:border-slate-300 transition-all">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Total Limbah</span>
                <span class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-trash-can"></i>
                </span>
            </div>
            <div class="mt-2.5">
                <span class="text-2xl sm:text-3xl font-extrabold text-amber-600 tracking-tight tabular-nums">{{ number_format($totalWaste, 2, ',', '.') }}</span>
                <span class="text-xs font-medium text-slate-400 ml-1">kg</span>
            </div>
            <div class="mt-2.5 flex items-center gap-1.5 text-xs text-slate-500">
                <span class="inline-block w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                <span>Trading, Cutting, Stripping & Reject</span>
            </div>
        </div>

        <!-- Overall Reject Ratio -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs hover:border-slate-300 transition-all">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Reject Ratio</span>
                <span class="w-8 h-8 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-percent"></i>
                </span>
            </div>
            <div class="mt-2.5">
                <span class="text-2xl sm:text-3xl font-extrabold text-purple-600 tracking-tight tabular-nums">{{ number_format($overallRatio, 2, ',', '.') }}</span>
                <span class="text-xs font-medium text-purple-400 ml-0.5">%</span>
            </div>
            <div class="mt-2.5 flex items-center gap-1.5 text-xs">
                @if($overallRatio <= 3.0 && $totalOutput > 0)
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[11px] font-medium bg-emerald-50 text-emerald-700 border border-emerald-100">
                        <i class="fa-solid fa-check text-[9px]"></i> Optimal (&le; 3%)
                    </span>
                @elseif($overallRatio <= 5.0 && $totalOutput > 0)
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[11px] font-medium bg-amber-50 text-amber-700 border border-amber-100">
                        <i class="fa-solid fa-triangle-exclamation text-[9px]"></i> Standar industri
                    </span>
                @else
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[11px] font-medium bg-rose-50 text-rose-700 border border-rose-100">
                        <i class="fa-solid fa-circle-exclamation text-[9px]"></i> Perlu evaluasi
                    </span>
                @endif
            </div>
        </div>

        <!-- Total Laporan Validated -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs hover:border-slate-300 transition-all">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Laporan Validated</span>
                <span class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-circle-check"></i>
                </span>
            </div>
            <div class="mt-2.5">
                <span class="text-2xl sm:text-3xl font-extrabold text-emerald-600 tracking-tight tabular-nums">{{ $totalLaporan }}</span>
                <span class="text-xs font-medium text-slate-400 ml-1">shift</span>
            </div>
            <div class="mt-2.5 flex items-center gap-1.5 text-xs text-slate-500">
                <span class="inline-block w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                <span>Telah disetujui Admin</span>
            </div>
        </div>
    </div>

    <!-- Standar Mutu Komoditas DTY & Parameter Reject (Executive Summary Card) -->
    <div class="rounded-2xl border border-purple-200/90 bg-gradient-to-r from-purple-50/70 via-white to-sky-50/50 p-4 sm:p-5 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-start gap-3.5">
            <div class="w-10 h-10 rounded-xl bg-purple-100 text-purple-700 flex items-center justify-center text-lg shrink-0">
                <i class="fa-solid fa-award"></i>
            </div>
            <div class="space-y-1">
                <div class="flex items-center gap-2 flex-wrap">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-900">Standar Mutu Komoditas DTY &amp; Parameter Reject</span>
                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800 border border-emerald-200">
                        Grade AX = 5.5 Kg / Bobbin
                    </span>
                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-purple-100 text-purple-800 border border-purple-200">
                        Ambang Reject Ratio: Optimal &le; 3%
                    </span>
                </div>
                <p class="text-xs text-slate-600 leading-relaxed">
                    Sistem mengacu pada baku mutu pabrik: Produk benang DTY kualitas tertinggi (<strong>Grade AX</strong>) memiliki berat mutlak <strong>5.5 Kg per bobbin</strong>.
                    Bila bobot per gulungan di bawah 5.5 Kg, diberlakukan <strong>downgrade otomatis ke Product Reject (Grade B/C)</strong> yang berat akumulasinya (Kg) dihitung sebagai penambah persentase <strong>Reject Ratio</strong>.
                </p>
            </div>
        </div>
        <button type="button" onclick="openStandarMutuModal()" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs font-semibold text-purple-700 bg-white border border-purple-200 hover:bg-purple-50 transition shadow-xs shrink-0 self-start md:self-auto">
            <i class="fa-solid fa-book-open text-purple-600"></i>
            <span>Pelajari Ketentuan Mutu</span>
        </button>
    </div>

    <!-- Charts Section: Komparasi Shift & Distribusi Limbah (Minimalist) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
        <!-- Chart 1: Komparasi Antar-Shift -->
        <div class="lg:col-span-2 bg-white p-5 sm:p-6 rounded-2xl border border-slate-200/80 shadow-xs space-y-4">
            <div class="flex flex-col gap-2 border-b border-slate-100 pb-3 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-sm font-bold text-slate-900">Rasio reject antar-shift</h2>
                    <p class="text-xs text-slate-400 mt-0.5">Persentase reject ratio per shift operasional</p>
                </div>
                <div class="flex items-center gap-3 text-xs text-slate-500">
                    <span class="inline-flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-sm bg-purple-500"></span> Shift 1</span>
                    <span class="inline-flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-sm bg-sky-500"></span> Shift 2</span>
                    <span class="inline-flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-sm bg-amber-500"></span> Shift 3</span>
                </div>
            </div>
            <div class="h-60 sm:h-64">
                <canvas id="shiftChart"></canvas>
            </div>
        </div>

        <!-- Chart 2: Komposisi Jenis Limbah -->
        <div class="bg-white p-5 sm:p-6 rounded-2xl border border-slate-200/80 shadow-xs space-y-4">
            <div class="border-b border-slate-100 pb-3">
                <h2 class="text-sm font-bold text-slate-900">Komposisi jenis limbah</h2>
                <p class="text-xs text-slate-400 mt-0.5">Proporsi volume limbah terakumulasi</p>
            </div>
            <div class="h-60 sm:h-64 flex items-center justify-center">
                <canvas id="kategoriChart"></canvas>
            </div>
        </div>
    </div>

    @php
        $laneVolumeTotal = (float) $laneAnalytics['lanes']->sum('volume');
        $tradingVolumeTotal = $laneVolumeTotal + $laneAnalytics['unassigned_volume'];
    @endphp
    <!-- Trading per Lane Breakdown (Modern Minimalist) -->
    <section class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-xs">
        <div class="flex flex-col gap-2 border-b border-slate-100 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-sm font-bold tracking-tight text-slate-900">Analisis Trading per lane</h2>
                <p class="text-xs text-slate-400 mt-0.5">Rincian lane dari seluruh laporan yang telah divalidasi</p>
            </div>
            <a href="{{ route('manajer.laporan') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-purple-600 hover:text-purple-700 transition">
                <span>Rincian periode</span>
                <i class="fa-solid fa-arrow-right text-[10px]"></i>
            </a>
        </div>
        <div class="grid gap-6 p-5 sm:p-6 lg:grid-cols-[minmax(0,1fr)_16rem]">
            <div class="space-y-4">
                @forelse($laneAnalytics['lanes'] as $lane)
                    @php $laneShare = $laneVolumeTotal > 0 ? min(($lane['volume'] / $laneVolumeTotal) * 100, 100) : 0; @endphp
                    <div>
                        <div class="mb-1.5 flex flex-wrap items-baseline justify-between gap-2">
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-bold text-slate-800">{{ $lane['nama'] }}</span>
                                <span class="text-[10px] text-slate-400">({{ $lane['jumlah_laporan'] }} laporan)</span>
                            </div>
                            <div class="text-xs font-bold tabular-nums text-slate-800">
                                {{ number_format($lane['volume'], 2, ',', '.') }} kg
                                <span class="ml-1 text-[11px] font-medium text-slate-400">({{ number_format($laneShare, 1, ',', '.') }}%)</span>
                            </div>
                        </div>
                        <div class="h-2 overflow-hidden rounded-full bg-slate-100" role="img" aria-label="Porsi {{ $lane['nama'] }}: {{ number_format($laneShare, 1, ',', '.') }} persen">
                            <div class="h-full rounded-full bg-gradient-to-r from-sky-500 to-indigo-500 transition-all duration-500" style="width: {{ number_format($laneShare, 2, '.', '') }}%"></div>
                        </div>
                    </div>
                @empty
                    <p class="rounded-xl border border-dashed border-slate-200 p-4 text-xs text-slate-400 text-center">Belum ada lane Trading yang dikonfigurasi.</p>
                @endforelse
            </div>
            
            <aside class="rounded-xl bg-slate-50 border border-slate-200/70 p-4 flex flex-col justify-between">
                <div>
                    <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Total Trading</span>
                    <div class="mt-1">
                        <span class="text-2xl font-black text-slate-900 tabular-nums">{{ number_format($tradingVolumeTotal, 2, ',', '.') }}</span>
                        <span class="text-xs font-semibold text-slate-500 ml-0.5">kg</span>
                    </div>
                    <p class="text-[11px] text-slate-400 mt-1 leading-relaxed">Akumulasi volume limbah trading tervalidasi.</p>
                </div>
                @if($laneAnalytics['unassigned_volume'] > 0)
                    <div class="mt-4 pt-3 border-t border-slate-200/70">
                        <span class="text-[10px] font-semibold uppercase text-amber-600">Trading belum dirinci</span>
                        <div class="text-sm font-bold text-slate-800 mt-0.5 tabular-nums">
                            {{ number_format($laneAnalytics['unassigned_volume'], 2, ',', '.') }} kg
                        </div>
                        <p class="text-[10px] text-slate-400 mt-0.5">Data historis sebelum fitur lane aktif.</p>
                    </div>
                @endif
            </aside>
        </div>
    </section>

    <!-- Tabel Analisis Kinerja Antar-Shift (Modern Minimalist) -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100">
            <h2 class="text-sm font-bold text-slate-900">Analisis kinerja antar-shift</h2>
            <p class="text-xs text-slate-400 mt-0.5">Komparasi output, volume waste, rasio reject, dan status efisiensi</p>
        </div>
        <div class="overflow-x-auto">
            <table class="workspace-table w-full min-w-[900px] text-left text-xs sm:text-sm text-slate-600">
                <thead class="bg-slate-50/70 text-[11px] uppercase font-bold text-slate-400 border-b border-slate-200/70">
                    <tr>
                        <th class="py-3 px-4">Shift Kerja</th>
                        <th class="py-3 px-4">Waktu Operasional</th>
                        <th class="py-3 px-4 text-center">Jumlah Sesi</th>
                        <th class="py-3 px-4 text-right">Output Produksi</th>
                        <th class="py-3 px-4 text-right">Total Limbah</th>
                        <th class="py-3 px-4 text-center">Reject Ratio (%)</th>
                        <th class="py-3 px-4 text-center">Status Efisiensi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @php
                        $shiftNames = [
                            1 => ['name' => 'Shift 1', 'time' => 'Pagi (07:00 - 15:00)'],
                            2 => ['name' => 'Shift 2', 'time' => 'Sore (15:00 - 23:00)'],
                            3 => ['name' => 'Shift 3', 'time' => 'Malam (23:00 - 07:00)'],
                        ];
                    @endphp
                    @foreach($shiftStats as $shiftNum => $data)
                        <tr class="hover:bg-purple-50/20 transition">
                            <td class="py-3 px-4 font-bold text-slate-800">
                                {{ $shiftNames[$shiftNum]['name'] }}
                            </td>
                            <td class="py-3 px-4 text-xs text-slate-400">
                                {{ $shiftNames[$shiftNum]['time'] }}
                            </td>
                            <td class="py-3 px-4 text-center font-medium text-slate-600">
                                {{ $data['count'] }}
                            </td>
                            <td class="py-3 px-4 text-right font-medium text-slate-800 tabular-nums">
                                {{ number_format($data['output'], 2, ',', '.') }} kg
                            </td>
                            <td class="py-3 px-4 text-right font-bold text-amber-600 tabular-nums">
                                {{ number_format($data['waste'], 2, ',', '.') }} kg
                            </td>
                            <td class="py-3 px-4 text-center">
                                <span class="px-2 py-0.5 rounded-md text-xs font-bold tabular-nums {{ $data['ratio'] > 5 ? 'bg-rose-50 text-rose-700 border border-rose-100' : ($data['ratio'] > 3 ? 'bg-amber-50 text-amber-700 border border-amber-100' : 'bg-emerald-50 text-emerald-700 border border-emerald-100') }}">
                                    {{ number_format($data['ratio'], 2, ',', '.') }}%
                                </span>
                            </td>
                            <td class="py-3 px-4 text-center">
                                @if($data['ratio'] <= 3 && $data['output'] > 0)
                                    <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-150">
                                        <i class="fa-solid fa-check text-[9px]"></i> Efisien & Baik
                                    </span>
                                @elseif($data['ratio'] <= 5 && $data['output'] > 0)
                                    <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-amber-600 bg-amber-50 px-2 py-0.5 rounded border border-amber-150">
                                        <i class="fa-solid fa-minus text-[9px]"></i> Standar
                                    </span>
                                @elseif($data['output'] > 0)
                                    <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-rose-600 bg-rose-50 px-2 py-0.5 rounded border border-rose-150">
                                        <i class="fa-solid fa-arrow-up text-[9px]"></i> Tinggi (Evaluasi)
                                    </span>
                                @else
                                    <span class="text-xs text-slate-400">-</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Modern Minimalist Chart.js configuration
        Chart.defaults.font.family = "'Inter', sans-serif";
        Chart.defaults.font.size = 11;

        // Chart 1: Minimalist Bar Chart for Shift Reject Ratio
        const shiftCtx = document.getElementById('shiftChart').getContext('2d');
        new Chart(shiftCtx, {
            type: 'bar',
            data: {
                labels: ['Shift 1 (Pagi)', 'Shift 2 (Sore)', 'Shift 3 (Malam)'],
                datasets: [
                    {
                        label: 'Reject Ratio (%)',
                        data: [
                            {{ $shiftStats[1]['ratio'] }},
                            {{ $shiftStats[2]['ratio'] }},
                            {{ $shiftStats[3]['ratio'] }}
                        ],
                        backgroundColor: [
                            'rgba(147, 51, 234, 0.85)',
                            'rgba(59, 130, 246, 0.85)',
                            'rgba(245, 158, 11, 0.85)'
                        ],
                        borderRadius: 6,
                        barThickness: 38,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return ' Reject Ratio: ' + context.parsed.y.toFixed(2) + ' %';
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) { return value + ' %'; }
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.04)'
                        }
                    }
                }
            }
        });

        // Chart 2: Minimalist Doughnut Chart for Komposisi Jenis Limbah
        const katCtx = document.getElementById('kategoriChart').getContext('2d');
        const katLabels = {!! json_encode($kategoriStats->pluck('nama')) !!};
        const katData = {!! json_encode($kategoriStats->pluck('volume')) !!};

        new Chart(katCtx, {
            type: 'doughnut',
            data: {
                labels: katLabels,
                datasets: [{
                    data: katData,
                    backgroundColor: [
                        '#0284c7', // Sky
                        '#e11d48', // Rose
                        '#f59e0b', // Amber
                        '#10b981', // Emerald
                        '#8b5cf6'  // Purple
                    ],
                    borderWidth: 2,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '74%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            boxWidth: 10,
                            boxHeight: 10,
                            usePointStyle: true,
                            pointStyle: 'circle',
                            padding: 14,
                            font: { size: 11, weight: '500' }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return ' ' + context.label + ': ' + Number(context.parsed).toLocaleString('id-ID', { minimumFractionDigits: 2 }) + ' kg';
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endsection
