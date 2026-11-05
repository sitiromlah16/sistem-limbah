@extends('layouts.app')

@section('title', 'Laporan Periodik Rekapitulasi Limbah - Manajer')

@section('content')
<div class="space-y-6">

    <!-- Header Section (Clean & Structured) -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-2 border-b border-slate-200/70">
        <div>
            <div class="flex items-center gap-2 mb-1.5">
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold tracking-wider uppercase bg-indigo-50 text-indigo-700 border border-indigo-100">
                    <i class="fa-solid fa-file-lines text-[9px]"></i>
                    Modul F-06 · Rekap tervalidasi
                </span>
                <span class="text-xs text-slate-300">·</span>
                <span class="text-xs text-slate-500 font-medium">Laporan Rekapitulasi</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Laporan produksi periodik</h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Konsolidasi data shift yang telah disahkan Admin Produksi, siap ditinjau dan diunduh sebagai PDF.</p>
        </div>
        <div class="flex items-center gap-2.5 self-start sm:self-auto shrink-0">
            <a href="{{ route('manajer.cetak', request()->all()) }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-semibold text-xs sm:text-sm shadow-xs transition active:scale-[0.98]">
                <i class="fa-solid fa-file-pdf text-xs text-rose-400"></i>
                <span>Unduh PDF</span>
            </a>
        </div>
    </div>

    <!-- Filter Bar (Neat & Compact) -->
    <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs">
        <div class="mb-3 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-indigo-50 text-indigo-600 text-xs"><i class="fa-solid fa-sliders"></i></span>
                <h2 class="text-xs font-bold uppercase tracking-wider text-slate-700">Atur Periode Laporan</h2>
            </div>
            @if(request()->hasAny(['start_date', 'end_date', 'shift']))
                <a href="{{ route('manajer.laporan') }}" class="text-xs text-rose-600 hover:underline font-medium inline-flex items-center gap-1">
                    <i class="fa-solid fa-xmark text-[10px]"></i> Reset Filter
                </a>
            @endif
        </div>
        <form action="{{ route('manajer.laporan') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-4 gap-3 items-end">
            <!-- Dari Tanggal -->
            <div>
                <label for="start_date" class="block text-[11px] font-semibold text-slate-500 uppercase tracking-wider mb-1">
                    Dari Tanggal
                </label>
                <input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}"
                       class="w-full px-3 py-2 text-xs sm:text-sm rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-100 outline-none bg-slate-50/50">
            </div>

            <!-- Sampai Tanggal -->
            <div>
                <label for="end_date" class="block text-[11px] font-semibold text-slate-500 uppercase tracking-wider mb-1">
                    Sampai Tanggal
                </label>
                <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}"
                       class="w-full px-3 py-2 text-xs sm:text-sm rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-100 outline-none bg-slate-50/50">
            </div>

            <!-- Shift -->
            <div>
                <label for="shift" class="block text-[11px] font-semibold text-slate-500 uppercase tracking-wider mb-1">
                    Shift Kerja
                </label>
                <select name="shift" id="shift" class="w-full px-3 py-2 text-xs sm:text-sm rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-100 outline-none bg-slate-50/50">
                    <option value="">Semua Shift (1, 2, 3)</option>
                    <option value="1" {{ request('shift') == '1' ? 'selected' : '' }}>Shift 1 (Pagi)</option>
                    <option value="2" {{ request('shift') == '2' ? 'selected' : '' }}>Shift 2 (Sore)</option>
                    <option value="3" {{ request('shift') == '3' ? 'selected' : '' }}>Shift 3 (Malam)</option>
                </select>
            </div>

            <!-- Buttons -->
            <div class="flex items-center gap-2">
                <button type="submit" 
                        class="flex-1 py-2 px-4 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-xs sm:text-sm transition flex items-center justify-center gap-2 active:scale-[0.98]">
                    <i class="fa-solid fa-filter text-xs"></i>
                    <span>Terapkan</span>
                </button>
                <a href="{{ route('manajer.laporan') }}" 
                   class="py-2 px-3 rounded-xl border border-slate-200 text-slate-500 hover:bg-slate-100 text-xs font-semibold transition"
                   title="Reset Filter">
                    <i class="fa-solid fa-rotate-left"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Ringkasan Periode Terpilih (Standard Minimalist KPI Cards) -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <!-- Output -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs hover:border-slate-300 transition">
            <div class="flex items-center justify-between mb-3">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Output Periode</span>
                <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-boxes-stacked"></i>
                </div>
            </div>
            <div class="text-2xl font-black text-slate-900 tracking-tight tabular-nums">
                {{ number_format($summaryOutput, 2, ',', '.') }} <span class="text-xs font-semibold text-slate-400">kg</span>
            </div>
            <p class="text-[11px] text-slate-400 mt-1">Total benang jadi yang diproduksi.</p>
        </div>

        <!-- Limbah -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs hover:border-slate-300 transition">
            <div class="flex items-center justify-between mb-3">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Limbah Periode</span>
                <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-trash-can"></i>
                </div>
            </div>
            <div class="text-2xl font-black text-amber-600 tracking-tight tabular-nums">
                {{ number_format($summaryWaste, 2, ',', '.') }} <span class="text-xs font-semibold text-slate-400">kg</span>
            </div>
            <p class="text-[11px] text-slate-400 mt-1">Akumulasi limbah & produk reject.</p>
        </div>

        <!-- Reject Ratio -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs hover:border-slate-300 transition">
            <div class="flex items-center justify-between mb-3">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Rata-rata Reject Ratio</span>
                <div class="w-8 h-8 rounded-lg bg-sky-50 text-sky-600 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-percent"></i>
                </div>
            </div>
            <div class="text-2xl font-black text-sky-600 tracking-tight tabular-nums">
                {{ number_format($summaryRatio, 2, ',', '.') }} <span class="text-xs font-semibold text-slate-400">%</span>
            </div>
            <p class="text-[11px] text-slate-400 mt-1">(Total Waste &divide; Total Output) &times; 100%</p>
        </div>
    </div>

    <!-- Standar Mutu Komoditas DTY & Parameter Reject Banner -->
    <div class="rounded-xl border border-indigo-100 bg-indigo-50/50 p-4 text-xs text-slate-700 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div class="flex items-start gap-2.5">
            <i class="fa-solid fa-award text-indigo-600 mt-0.5 text-sm shrink-0"></i>
            <div>
                <strong class="text-slate-900">Ketentuan Baku Standar Mutu DTY:</strong> Standardisasi berat mutlak Grade AX adalah <strong>5.5 Kg per bobbin</strong>. Gulungan dengan bobot akhir di bawah ambang minimum tersebut otomatis mengalami <em>downgrade</em> ke <strong>Product Reject (Grade B/C)</strong> yang diakumulasikan ke dalam total limbah untuk menghitung persentase <strong>Reject Ratio</strong>.
            </div>
        </div>
        <button type="button" onclick="openStandarMutuModal()" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-indigo-200 bg-white text-indigo-700 font-semibold text-xs hover:bg-indigo-50 transition shrink-0 self-start sm:self-auto">
            <i class="fa-solid fa-circle-info"></i>
            <span>Parameter Baku</span>
        </button>
    </div>

    @php
        $laneVolumeTotal = (float) $laneAnalytics['lanes']->sum('volume');
        $tradingVolumeTotal = $laneVolumeTotal + $laneAnalytics['unassigned_volume'];
    @endphp
    <!-- Analisis Trading per lane -->
    <section class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-xs">
        <div class="flex flex-col gap-3 border-b border-slate-100 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-3">
                <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-sky-50 text-sky-600 text-xs"><i class="fa-solid fa-chart-column"></i></span>
                <div>
                    <h2 class="text-sm font-bold tracking-tight text-slate-900">Analisis Trading per lane</h2>
                    <p class="text-xs text-slate-400 mt-0.5">Komposisi volume untuk rentang tanggal dan shift terpilih</p>
                </div>
            </div>
            <span class="inline-flex self-start items-center gap-1.5 rounded-full bg-emerald-50 px-2.5 py-0.5 text-xs font-semibold text-emerald-700 border border-emerald-100 sm:self-auto">
                <i class="fa-solid fa-circle-check text-[10px]"></i> Data tervalidasi
            </span>
        </div>
        <div class="grid gap-6 p-5 sm:p-6 lg:grid-cols-[minmax(0,1fr)_16rem]">
            <div class="space-y-4">
                @forelse($laneAnalytics['lanes'] as $lane)
                    @php $laneShare = $laneVolumeTotal > 0 ? min(($lane['volume'] / $laneVolumeTotal) * 100, 100) : 0; @endphp
                    <article>
                        <div class="mb-1.5 flex flex-wrap items-end justify-between gap-x-4 gap-y-1">
                            <div>
                                <h3 class="text-xs font-bold text-slate-900">{{ $lane['nama'] }}</h3>
                                <p class="text-[11px] text-slate-400">{{ $lane['jumlah_laporan'] }} laporan tervalidasi</p>
                            </div>
                            <p class="text-right text-xs font-bold tabular-nums text-slate-900">
                                {{ number_format($lane['volume'], 2, ',', '.') }} <span class="text-[11px] font-medium text-slate-400">kg</span>
                                <span class="ml-1 text-[11px] font-medium text-slate-400">· {{ number_format($laneShare, 1, ',', '.') }}%</span>
                            </p>
                        </div>
                        <div class="h-2 overflow-hidden rounded-full bg-slate-100" role="img" aria-label="Porsi {{ $lane['nama'] }}: {{ number_format($laneShare, 1, ',', '.') }} persen dari Trading per lane">
                            <div class="h-full rounded-full bg-indigo-600 transition-all duration-500" style="width: {{ number_format($laneShare, 2, '.', '') }}%"></div>
                        </div>
                    </article>
                @empty
                    <p class="rounded-xl border border-dashed border-slate-200 p-5 text-xs text-slate-400 text-center">Belum ada lane Trading pada periode dan shift yang dipilih.</p>
                @endforelse
            </div>
            <aside class="relative overflow-hidden rounded-xl bg-slate-900 p-5 text-white flex flex-col justify-between">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Trading</p>
                    <p class="mt-2 text-2xl font-black tracking-tight tabular-nums text-white">{{ number_format($tradingVolumeTotal, 2, ',', '.') }} <span class="text-xs font-semibold text-slate-400">kg</span></p>
                    <p class="mt-1 text-xs text-slate-400">Akumulasi volume sesuai filter laporan.</p>
                </div>
                @if($laneAnalytics['unassigned_volume'] > 0)
                    <div class="mt-4 border-t border-slate-800 pt-3">
                        <p class="text-xs font-semibold text-amber-300">Trading belum dirinci</p>
                        <p class="mt-1 text-base font-bold tabular-nums text-white">{{ number_format($laneAnalytics['unassigned_volume'], 2, ',', '.') }} <span class="text-xs font-medium text-slate-400">kg</span></p>
                        <p class="mt-0.5 text-[10px] leading-4 text-slate-400">Data historis tanpa rincian lane.</p>
                    </div>
                @endif
            </aside>
        </div>
    </section>

    <!-- Rincian Laporan per shift Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-sm font-bold text-slate-900">Rincian laporan per shift</h2>
                <p class="text-xs text-slate-400 mt-0.5">Setiap baris adalah satu shift tervalidasi, dengan rincian volume untuk setiap kategori waste.</p>
            </div>
            <span class="text-xs text-slate-400 font-semibold">{{ $laporans->count() }} sesi laporan</span>
        </div>

        <div class="overflow-x-auto">
            <table class="workspace-table w-full min-w-[1100px] text-left text-xs sm:text-sm text-slate-600">
                <thead class="bg-slate-50/70 text-[11px] uppercase font-bold text-slate-400 border-b border-slate-200/70">
                    <tr>
                        <th class="py-3 px-4">Tanggal & Shift</th>
                        <th class="py-3 px-4">Operator</th>
                        <th class="py-3 px-4 text-right">Output (kg)</th>
                        <th class="py-3 px-4">Rincian per waste</th>
                        <th class="py-3 px-4 text-right">Total Waste (kg)</th>
                        <th class="py-3 px-4 text-center">Reject Ratio (%)</th>
                        <th class="py-3 px-4">Catatan Validasi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($laporans as $item)
                        <tr class="hover:bg-indigo-50/20 transition">
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <div class="font-bold text-slate-900">{{ $item->tanggal->format('d/m/Y') }}</div>
                                <span class="text-[11px] font-semibold text-slate-600 bg-slate-100 px-2 py-0.5 rounded-full border border-slate-200/70 mt-0.5 inline-block">
                                    Shift {{ $item->shift }}
                                </span>
                            </td>

                            <td class="py-3.5 px-4 whitespace-nowrap font-medium text-slate-800">
                                {{ $item->user->name ?? 'Operator' }}
                            </td>

                            <td class="py-3.5 px-4 text-right whitespace-nowrap font-semibold text-slate-800 tabular-nums">
                                {{ number_format($item->total_output_kg, 2, ',', '.') }}
                            </td>

                            <td class="min-w-64 py-3.5 px-4">
                                <div class="space-y-1.5">
                                    @forelse($item->detailWaste->groupBy('kategori_waste_id') as $details)
                                        @php
                                            $category = $details->first()->kategoriWaste;
                                            $categoryTotal = $details->sum('volume_kg');
                                        @endphp
                                        <div class="rounded-lg border border-slate-200/70 bg-slate-50/70 px-2.5 py-1.5">
                                            <div class="flex items-center justify-between gap-2">
                                                <span class="text-xs font-semibold text-slate-800">{{ $category->nama_kategori ?? 'Limbah' }}</span>
                                                <strong class="whitespace-nowrap text-xs tabular-nums text-slate-900">{{ number_format($categoryTotal, 2, ',', '.') }} kg</strong>
                                            </div>
                                            @if($category?->rincian_per_lane)
                                                <div class="mt-1 space-y-0.5 border-t border-slate-200/70 pt-1">
                                                    @foreach($details as $detail)
                                                        <div class="flex justify-between gap-2 text-[10px] text-slate-500">
                                                            <span>{{ $detail->lane->nama_lane ?? 'Belum dirinci' }}</span>
                                                            <span class="whitespace-nowrap tabular-nums">{{ number_format($detail->volume_kg, 2, ',', '.') }} kg</span>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    @empty
                                        <span class="text-xs text-slate-400">Belum ada rincian waste.</span>
                                    @endforelse
                                </div>
                            </td>

                            <td class="py-3.5 px-4 text-right whitespace-nowrap font-bold text-amber-600 tabular-nums">
                                {{ number_format($item->total_waste, 2, ',', '.') }}
                            </td>

                            <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                @php
                                    $ratio = $item->reject_ratio;
                                    $badgeColor = $ratio > 5 ? 'text-rose-700 bg-rose-50 border-rose-100' : ($ratio > 3 ? 'text-amber-700 bg-amber-50 border-amber-100' : 'text-emerald-700 bg-emerald-50 border-emerald-100');
                                @endphp
                                <span class="inline-block px-2 py-0.5 rounded-md border text-xs font-bold tabular-nums {{ $badgeColor }}">
                                    {{ number_format($ratio, 2, ',', '.') }}%
                                </span>
                            </td>

                            <td class="py-3.5 px-4 text-xs text-slate-500">
                                {{ $item->catatan_admin ?: 'Disetujui Admin' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-slate-400">
                                <span class="mx-auto mb-2 flex h-10 w-10 items-center justify-center rounded-xl bg-slate-100 text-slate-400"><i class="fa-solid fa-file-excel text-lg"></i></span>
                                <p class="text-sm font-medium text-slate-600">Tidak ada data laporan tervalidasi pada kriteria ini.</p>
                                <p class="mt-0.5 text-xs text-slate-400">Coba ubah tanggal atau shift pada filter pencarian.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Rekap waste per shift Table -->
    <section class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-xs">
        <div class="border-b border-slate-100 px-5 py-4">
            <h2 class="text-sm font-bold text-slate-900">Rekap waste per shift</h2>
            <p class="text-xs text-slate-400 mt-0.5">Volume tiap kategori dipisahkan menurut shift, termasuk rincian Trading per lane.</p>
        </div>
        <div class="overflow-x-auto">
            <table class="workspace-table w-full min-w-[1100px] text-left text-xs sm:text-sm text-slate-600">
                <thead class="border-b border-slate-200/70 bg-slate-50/70 text-[11px] font-bold uppercase text-slate-400">
                    <tr>
                        <th class="px-5 py-3">Waste / lane</th>
                        <th class="px-5 py-3 text-right">Shift 1 (kg)</th>
                        <th class="px-5 py-3 text-right">Shift 2 (kg)</th>
                        <th class="px-5 py-3 text-right">Shift 3 (kg)</th>
                        <th class="px-5 py-3 text-right">Total (kg)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($kategoris as $kategori)
                        @php
                            $categoryDetails = $laporans->flatMap(fn ($laporan) => $laporan->detailWaste->where('kategori_waste_id', $kategori->id));
                            $categoryShiftVolumes = [];
                            foreach ([1, 2, 3] as $shiftNumber) {
                                $categoryShiftVolumes[$shiftNumber] = $laporans->where('shift', $shiftNumber)->sum(
                                    fn ($laporan) => $laporan->detailWaste->where('kategori_waste_id', $kategori->id)->sum('volume_kg')
                                );
                            }
                            $categoryTotal = array_sum($categoryShiftVolumes);
                        @endphp
                        <tr class="bg-slate-50/50">
                            <td class="px-5 py-3 font-semibold text-slate-800">{{ $kategori->nama_kategori }}</td>
                            @foreach([1, 2, 3] as $shiftNumber)
                                <td class="px-5 py-3 text-right tabular-nums text-slate-700">{{ number_format($categoryShiftVolumes[$shiftNumber], 2, ',', '.') }}</td>
                            @endforeach
                            <td class="px-5 py-3 text-right font-bold tabular-nums text-slate-900">{{ number_format($categoryTotal, 2, ',', '.') }}</td>
                        </tr>
                        @if($kategori->rincian_per_lane)
                            @foreach($categoryDetails->groupBy(fn ($detail) => $detail->lane_id ?? 'unassigned') as $laneKey => $laneDetails)
                                @php
                                    $laneName = $laneDetails->first()->lane->nama_lane ?? 'Belum dirinci';
                                    $laneShiftVolumes = [];
                                    foreach ([1, 2, 3] as $shiftNumber) {
                                        $laneShiftVolumes[$shiftNumber] = $laporans->where('shift', $shiftNumber)->sum(
                                            fn ($laporan) => $laporan->detailWaste
                                                ->where('kategori_waste_id', $kategori->id)
                                                ->where('lane_id', $laneDetails->first()->lane_id)
                                                ->sum('volume_kg')
                                        );
                                    }
                                @endphp
                                <tr>
                                    <td class="px-5 py-2 pl-9 text-xs text-slate-500">↳ {{ $laneName }}</td>
                                    @foreach([1, 2, 3] as $shiftNumber)
                                        <td class="px-5 py-2 text-right text-xs tabular-nums text-slate-500">{{ number_format($laneShiftVolumes[$shiftNumber], 2, ',', '.') }}</td>
                                    @endforeach
                                    <td class="px-5 py-2 text-right text-xs font-semibold tabular-nums text-slate-700">{{ number_format(array_sum($laneShiftVolumes), 2, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>

</div>
@endsection

