@extends('layouts.app')

@section('title', 'Validasi & Verifikasi Laporan - Admin')

@section('content')
<div class="space-y-6">

    <!-- Header Section (Clean & Structured) -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-2 border-b border-slate-200/70">
        <div>
            <div class="flex items-center gap-2 mb-1.5">
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold tracking-wider uppercase bg-emerald-50 text-emerald-700 border border-emerald-100">
                    <i class="fa-solid fa-clipboard-check text-[9px]"></i>
                    Modul F-04 · Admin Produksi
                </span>
                <span class="text-xs text-slate-300">·</span>
                <span class="text-xs text-slate-500 font-medium">Verifikasi Data</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Antrean validasi laporan</h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Periksa kewajaran output dan limbah sebelum laporan disahkan untuk rekap manajerial.</p>
        </div>
        <div class="flex items-center gap-2.5 self-start sm:self-auto shrink-0">
            <a href="{{ route('admin.dashboard') }}" 
               class="inline-flex items-center gap-2 px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 font-semibold text-xs sm:text-sm transition">
                <i class="fa-solid fa-arrow-left text-xs"></i>
                <span>Kembali ke Dashboard</span>
            </a>
        </div>
    </div>

    <!-- Filter Bar (Neat & Compact) -->
    <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs">
        <div class="mb-3 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-emerald-50 text-emerald-600 text-xs"><i class="fa-solid fa-filter"></i></span>
                <h2 class="text-xs font-bold uppercase tracking-wider text-slate-700">Filter Pencarian Laporan</h2>
            </div>
            @if(request()->hasAny(['status', 'shift', 'tanggal']))
                <a href="{{ route('admin.validasi.index') }}" class="text-xs text-rose-600 hover:underline font-medium inline-flex items-center gap-1">
                    <i class="fa-solid fa-xmark text-[10px]"></i> Reset Filter
                </a>
            @endif
        </div>
        <form action="{{ route('admin.validasi.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-4 gap-3 items-end">
            <!-- Filter Status -->
            <div>
                <label for="status" class="block text-[11px] font-semibold text-slate-500 uppercase tracking-wider mb-1">
                    Status Validasi
                </label>
                <select name="status" id="status" class="w-full px-3 py-2 text-xs sm:text-sm rounded-xl border border-slate-200 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-100 outline-none bg-slate-50/50">
                    <option value="">Semua Status</option>
                    <option value="Pending" {{ request('status') === 'Pending' ? 'selected' : '' }}>Pending (Menunggu)</option>
                    <option value="Validated" {{ request('status') === 'Validated' ? 'selected' : '' }}>Validated (Disetujui)</option>
                    <option value="Revisi" {{ request('status') === 'Revisi' ? 'selected' : '' }}>Revisi (Perbaikan)</option>
                </select>
            </div>

            <!-- Filter Shift -->
            <div>
                <label for="shift" class="block text-[11px] font-semibold text-slate-500 uppercase tracking-wider mb-1">
                    Shift Kerja
                </label>
                <select name="shift" id="shift" class="w-full px-3 py-2 text-xs sm:text-sm rounded-xl border border-slate-200 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-100 outline-none bg-slate-50/50">
                    <option value="">Semua Shift</option>
                    <option value="1" {{ request('shift') == '1' ? 'selected' : '' }}>Shift 1 (Pagi)</option>
                    <option value="2" {{ request('shift') == '2' ? 'selected' : '' }}>Shift 2 (Sore)</option>
                    <option value="3" {{ request('shift') == '3' ? 'selected' : '' }}>Shift 3 (Malam)</option>
                </select>
            </div>

            <!-- Filter Tanggal -->
            <div>
                <label for="tanggal" class="block text-[11px] font-semibold text-slate-500 uppercase tracking-wider mb-1">
                    Tanggal Spesifik
                </label>
                <input type="date" name="tanggal" id="tanggal" value="{{ request('tanggal') }}"
                       class="w-full px-3 py-2 text-xs sm:text-sm rounded-xl border border-slate-200 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-100 outline-none bg-slate-50/50">
            </div>

            <!-- Buttons -->
            <div class="flex items-center gap-2">
                <button type="submit" 
                        class="flex-1 py-2 px-4 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-semibold text-xs sm:text-sm transition flex items-center justify-center gap-2 active:scale-[0.98]">
                    <i class="fa-solid fa-magnifying-glass text-xs"></i>
                    <span>Terapkan</span>
                </button>
                <a href="{{ route('admin.validasi.index') }}" 
                   class="py-2 px-3 rounded-xl border border-slate-200 text-slate-500 hover:bg-slate-100 text-xs font-semibold transition"
                   title="Reset Filter">
                    <i class="fa-solid fa-rotate-left"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Table Card (Neat & Clean) -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="flex flex-col gap-1 border-b border-slate-100 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-sm font-bold text-slate-900">Daftar laporan antrean</h2>
                <p class="text-xs text-slate-400 mt-0.5">Buka laporan untuk meninjau rincian timbangan dan memberikan keputusan validasi</p>
            </div>
            <span class="text-xs font-medium text-slate-400">{{ $laporans->count() }} dari {{ $laporans->total() }} laporan</span>
        </div>
        <div class="overflow-x-auto">
            <table class="workspace-table w-full min-w-[950px] text-left text-xs sm:text-sm text-slate-600">
                <thead class="bg-slate-50/70 text-[11px] uppercase font-bold text-slate-400 border-b border-slate-200/70">
                    <tr>
                        <th class="py-3 px-4">Tanggal & Shift</th>
                        <th class="py-3 px-4">Operator Penginput</th>
                        <th class="py-3 px-4 text-right">Output Produksi</th>
                        <th class="py-3 px-4">Rincian Limbah</th>
                        <th class="py-3 px-4 text-right">Total Waste</th>
                        <th class="py-3 px-4 text-center">Reject Ratio (%)</th>
                        <th class="py-3 px-4 text-center">Status</th>
                        <th class="py-3 px-4 text-center">Tindakan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($laporans as $item)
                        <tr class="hover:bg-emerald-50/20 transition">
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <div class="font-bold text-slate-900">{{ $item->tanggal->format('d/m/Y') }}</div>
                                <span class="inline-block text-[11px] font-semibold text-slate-600 bg-slate-100 px-2 py-0.5 rounded-full border border-slate-200/70 mt-0.5">
                                    Shift {{ $item->shift }}
                                </span>
                            </td>

                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <div class="font-semibold text-slate-900">{{ $item->user->name ?? 'Operator' }}</div>
                                <div class="text-[11px] text-slate-400">{{ $item->user->email ?? '-' }}</div>
                            </td>

                            <td class="py-3.5 px-4 text-right whitespace-nowrap font-semibold text-slate-800 tabular-nums">
                                {{ number_format($item->total_output_kg, 2, ',', '.') }} kg
                            </td>

                            <td class="py-3.5 px-4">
                                @php $wasteGroups = $item->detailWaste->groupBy('kategori_waste_id'); @endphp
                                <div class="min-w-48 space-y-1.5">
                                    @forelse($wasteGroups as $details)
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

                            <td class="py-3.5 px-4 text-right whitespace-nowrap font-bold text-slate-900 tabular-nums">
                                {{ number_format($item->total_waste, 2, ',', '.') }} kg
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

                            <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                @if($item->status_validasi === 'Validated')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-150">
                                        <i class="fa-solid fa-check-circle text-emerald-600 text-[10px]"></i>
                                        <span>Validated</span>
                                    </span>
                                @elseif($item->status_validasi === 'Revisi')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-150">
                                        <i class="fa-solid fa-rotate-left text-rose-600 text-[10px]"></i>
                                        <span>Revisi</span>
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-150">
                                        <i class="fa-solid fa-clock text-amber-600 text-[10px]"></i>
                                        <span>Pending</span>
                                    </span>
                                @endif
                            </td>

                            <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                <a href="{{ route('admin.validasi.show', $item->id) }}" 
                                   class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 transition">
                                    <i class="fa-solid fa-magnifying-glass text-[10px]"></i>
                                    <span>Periksa</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-12 text-center text-slate-400">
                                <span class="mx-auto mb-2 flex h-10 w-10 items-center justify-center rounded-xl bg-slate-100 text-slate-400"><i class="fa-solid fa-clipboard-question text-lg"></i></span>
                                <p class="text-sm font-medium text-slate-600">Tidak ada laporan yang cocok dengan filter ini.</p>
                                <p class="mt-0.5 text-xs text-slate-400">Coba sesuaikan filter status, shift, atau tanggal.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($laporans->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $laporans->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
