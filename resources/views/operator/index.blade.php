@extends('layouts.app')

@section('title', 'Riwayat Laporan Shift - Operator')

@section('content')
<div class="space-y-6">

    <!-- Header Section (Clean & Structured) -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-2 border-b border-slate-200/70">
        <div>
            <div class="flex items-center gap-2 mb-1.5">
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold tracking-wider uppercase bg-sky-50 text-sky-700 border border-sky-100">
                    <i class="fa-solid fa-helmet-safety text-[9px]"></i>
                    Dashboard Operator
                </span>
                <span class="text-xs text-slate-300">·</span>
                <span class="text-xs text-slate-500 font-medium">Pencatatan Shift</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Laporan produksi Anda</h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">
                Pantau progres validasi dan kelola laporan akhir shift dalam satu tempat.
            </p>
        </div>
        <div class="flex items-center gap-2.5 self-start sm:self-auto shrink-0">
            <a href="{{ route('operator.laporan.create') }}" 
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-sky-600 hover:bg-sky-700 text-white font-semibold text-xs sm:text-sm shadow-sm transition active:scale-[0.98]">
                <i class="fa-solid fa-plus-circle text-xs"></i>
                <span>Buat laporan shift</span>
            </a>
        </div>
    </div>

    <!-- Summary KPI Cards (Modern & Minimalist) -->
    <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4" aria-label="Ringkasan laporan">
        <!-- Total Laporan -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs hover:border-slate-300 transition-all">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Total laporan</span>
                <span class="w-8 h-8 rounded-lg bg-sky-50 text-sky-600 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-clipboard-list"></i>
                </span>
            </div>
            <div class="mt-2.5">
                <span class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight tabular-nums">{{ number_format($summary->total_laporan ?? 0, 0, ',', '.') }}</span>
                <span class="text-xs font-medium text-slate-400 ml-1">shift</span>
            </div>
            <div class="mt-2.5 flex items-center gap-1.5 text-xs text-slate-500">
                <span class="inline-block w-1.5 h-1.5 rounded-full bg-sky-500"></span>
                <span>Seluruh shift yang dicatat</span>
            </div>
        </div>

        <!-- Menunggu Validasi -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs hover:border-slate-300 transition-all">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Menunggu validasi</span>
                <span class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center text-xs">
                    <i class="fa-regular fa-clock"></i>
                </span>
            </div>
            <div class="mt-2.5">
                <span class="text-2xl sm:text-3xl font-extrabold text-amber-600 tracking-tight tabular-nums">{{ number_format($summary->pending ?? 0, 0, ',', '.') }}</span>
                <span class="text-xs font-medium text-slate-400 ml-1">laporan</span>
            </div>
            <div class="mt-2.5 flex items-center gap-1.5 text-xs text-slate-500">
                <span class="inline-block w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                <span>Sedang ditinjau admin</span>
            </div>
        </div>

        <!-- Perlu Diperbaiki -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs hover:border-slate-300 transition-all">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Perlu diperbaiki</span>
                <span class="w-8 h-8 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-arrow-rotate-left"></i>
                </span>
            </div>
            <div class="mt-2.5">
                <span class="text-2xl sm:text-3xl font-extrabold text-rose-600 tracking-tight tabular-nums">{{ number_format($summary->revisi ?? 0, 0, ',', '.') }}</span>
                <span class="text-xs font-medium text-slate-400 ml-1">laporan</span>
            </div>
            <div class="mt-2.5 flex items-center gap-1.5 text-xs text-slate-500">
                <span class="inline-block w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                <span>Cek catatan dari admin</span>
            </div>
        </div>

        <!-- Laporan Tervalidasi -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs hover:border-slate-300 transition-all">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Laporan tervalidasi</span>
                <span class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-circle-check"></i>
                </span>
            </div>
            <div class="mt-2.5">
                <span class="text-2xl sm:text-3xl font-extrabold text-emerald-600 tracking-tight tabular-nums">{{ number_format($summary->validated ?? 0, 0, ',', '.') }}</span>
                <span class="text-xs font-medium text-slate-400 ml-1">sah</span>
            </div>
            <div class="mt-2.5 flex items-center gap-1.5 text-xs text-slate-500">
                <span class="inline-block w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                <span>Total output: {{ number_format($summary->total_output ?? 0, 2, ',', '.') }} kg</span>
            </div>
        </div>
    </section>

    <!-- Banner Standar Mutu Komoditas DTY & Parameter Reject -->
    <div class="rounded-2xl border border-sky-200/90 bg-gradient-to-r from-sky-50/80 via-white to-slate-50 p-4 sm:p-5 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-start gap-3.5">
            <div class="w-10 h-10 rounded-xl bg-sky-100 text-sky-700 flex items-center justify-center text-lg shrink-0">
                <i class="fa-solid fa-scale-balanced"></i>
            </div>
            <div class="space-y-1">
                <div class="flex items-center gap-2 flex-wrap">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-900">Ketentuan Standar Mutu Komoditas DTY</span>
                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800 border border-emerald-200">
                        Grade AX = 5.5 Kg / Bobbin
                    </span>
                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-amber-100 text-amber-800 border border-amber-200">
                        &lt; 5.5 Kg = Downgrade Grade B/C
                    </span>
                </div>
                <p class="text-xs text-slate-600 leading-relaxed">
                    Benang DTY kualitas tertinggi (Grade AX) wajib memiliki bobot mutlak <strong>5.5 Kg per bobbin</strong>. Gulungan di bawah bobot tersebut otomatis di-downgrade menjadi <strong>Product Reject</strong> yang total beratnya (Kg) wajib diinput sebagai komponen penambah Reject Ratio.
                </p>
            </div>
        </div>
        <button type="button" onclick="openStandarMutuModal()" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs font-semibold text-sky-700 bg-white border border-sky-200 hover:bg-sky-50 transition shadow-xs shrink-0 self-start md:self-auto">
            <i class="fa-solid fa-circle-question text-sky-500"></i>
            <span>Pelajari Aturan Mutu</span>
        </button>
    </div>

    <!-- Info Status Box (Clean Minimalist Banner) -->
    <div class="flex items-center gap-3 px-4 py-3 rounded-xl border border-sky-100 bg-sky-50/70 text-xs text-slate-600">
        <i class="fa-solid fa-circle-info text-sky-600 shrink-0"></i>
        <div class="flex-1 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-1">
            <span>Laporan berstatus <strong>Pending</strong> atau <strong>Revisi</strong> masih dapat diedit. Laporan <strong>Validated</strong> terkunci demi integritas data.</span>
        </div>
    </div>

    <!-- Table Card (Structured & Neat) -->
    <div class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-xs">
        <div class="flex flex-col gap-1 border-b border-slate-100 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-sm font-bold text-slate-900">Riwayat laporan shift</h2>
                <p class="text-xs text-slate-400 mt-0.5">Status dan rincian produksi yang telah Anda kirim</p>
            </div>
            <span class="text-xs font-medium text-slate-400">Menampilkan {{ $laporans->count() }} dari {{ $laporans->total() }} laporan</span>
        </div>
        <div class="overflow-x-auto">
            <table class="workspace-table w-full min-w-[950px] text-left text-xs sm:text-sm text-slate-600">
                <thead class="border-b border-slate-200/70 bg-slate-50/70 text-[11px] font-bold uppercase text-slate-400">
                    <tr>
                        <th class="py-3 px-4">Tanggal & Shift</th>
                        <th class="py-3 px-4 text-right">Output Produksi</th>
                        <th class="py-3 px-4">Rincian Limbah / Reject</th>
                        <th class="py-3 px-4 text-right">Total Waste</th>
                        <th class="py-3 px-4 text-center">Reject Ratio (%)</th>
                        <th class="py-3 px-4 text-center">Status</th>
                        <th class="py-3 px-4">Catatan Admin</th>
                        <th class="py-3 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($laporans as $item)
                        <tr class="transition hover:bg-sky-50/25">
                            <!-- Tanggal & Shift -->
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <div class="font-bold text-slate-900">{{ $item->tanggal->format('d/m/Y') }}</div>
                                <span class="inline-block text-[11px] px-2 py-0.5 rounded-full font-semibold bg-slate-100 text-slate-600 border border-slate-200/80 mt-0.5">
                                    Shift {{ $item->shift }}
                                </span>
                            </td>

                            <!-- Total Output Produksi -->
                            <td class="py-3.5 px-4 text-right whitespace-nowrap font-semibold text-slate-800 tabular-nums">
                                {{ number_format($item->total_output_kg, 2, ',', '.') }} kg
                            </td>

                            <!-- Rincian Waste -->
                            <td class="py-3.5 px-4">
                                @php $wasteGroups = $item->detailWaste->groupBy('kategori_waste_id'); @endphp
                                <div class="min-w-52 space-y-2">
                                    @forelse($wasteGroups as $details)
                                        @php
                                            $category = $details->first()->kategoriWaste;
                                            $categoryTotal = $details->sum('volume_kg');
                                        @endphp
                                        <div class="rounded-xl border border-slate-200/70 bg-slate-50/80 px-3 py-2">
                                            <div class="flex items-center justify-between gap-2">
                                                <span class="text-xs font-semibold text-slate-800">{{ $category->nama_kategori ?? 'Limbah' }}</span>
                                                <strong class="whitespace-nowrap text-xs tabular-nums text-slate-900">{{ number_format($categoryTotal, 2, ',', '.') }} kg</strong>
                                            </div>
                                            @if($category?->rincian_per_lane)
                                                <div class="mt-2 space-y-1 border-t border-slate-200/70 pt-2">
                                                    @foreach($details as $detail)
                                                        <div class="flex justify-between gap-2 text-[11px] text-slate-500">
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

                            <!-- Total Waste -->
                            <td class="py-3.5 px-4 text-right whitespace-nowrap font-bold text-slate-900 tabular-nums">
                                {{ number_format($item->total_waste, 2, ',', '.') }} kg
                            </td>

                            <!-- Reject Ratio -->
                            <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                @php
                                    $ratio = $item->reject_ratio;
                                    $badgeColor = $ratio > 5 ? 'text-rose-700 bg-rose-50 border-rose-100' : ($ratio > 3 ? 'text-amber-700 bg-amber-50 border-amber-100' : 'text-emerald-700 bg-emerald-50 border-emerald-100');
                                @endphp
                                <span class="inline-block px-2 py-0.5 rounded-md border text-xs font-bold tabular-nums {{ $badgeColor }}">
                                    {{ number_format($ratio, 2, ',', '.') }}%
                                </span>
                            </td>

                            <!-- Status Validasi -->
                            <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                @if($item->status_validasi === 'Validated')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-150">
                                        <i class="fa-solid fa-check-circle text-emerald-600 text-[10px]"></i>
                                        <span>Validated</span>
                                    </span>
                                @elseif($item->status_validasi === 'Revisi')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-150">
                                        <i class="fa-solid fa-rotate-left text-rose-600 text-[10px]"></i>
                                        <span>Revisi</span>
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-150">
                                        <i class="fa-solid fa-clock text-amber-600 text-[10px]"></i>
                                        <span>Pending</span>
                                    </span>
                                @endif
                            </td>

                            <!-- Catatan Admin -->
                            <td class="py-3.5 px-4 text-xs">
                                @if($item->catatan_admin)
                                    <div class="p-2 rounded-lg {{ $item->status_validasi === 'Revisi' ? 'bg-rose-50 text-rose-800 border border-rose-150 font-medium' : 'bg-slate-50 text-slate-600' }}">
                                        {{ $item->catatan_admin }}
                                    </div>
                                @else
                                    <span class="text-slate-400 italic">- Tidak ada -</span>
                                @endif
                            </td>

                            <!-- Aksi (Hak Edit) -->
                            <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                @if($item->status_validasi === 'Validated')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-medium text-slate-400 bg-slate-100 border border-slate-200 cursor-not-allowed" title="Data telah terkunci">
                                        <i class="fa-solid fa-lock text-[10px]"></i>
                                        <span>Terkunci</span>
                                    </span>
                                @else
                                    <a href="{{ route('operator.laporan.edit', $item->id) }}" 
                                       class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold text-sky-700 bg-sky-50 hover:bg-sky-100 border border-sky-200 transition">
                                        <i class="fa-solid fa-pen-to-square text-[10px]"></i>
                                        <span>Edit Data</span>
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-12 text-center text-slate-400">
                                <i class="fa-solid fa-clipboard-list text-3xl mb-2 text-slate-300"></i>
                                <p class="text-sm font-medium">Belum ada laporan produksi yang disubmit.</p>
                                <a href="{{ route('operator.laporan.create') }}" class="text-xs text-sky-600 hover:underline mt-1 inline-block">
                                    Klik di sini untuk mengisi laporan akhir shift pertama Anda &rarr;
                                </a>
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
