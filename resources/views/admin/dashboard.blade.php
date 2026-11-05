@extends('layouts.app')

@section('title', 'Dashboard Admin Produksi')

@section('content')
<div class="space-y-6">

    <!-- Header Section (Modern Minimalist & Structured) -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-2 border-b border-slate-200/70">
        <div>
            <div class="flex items-center gap-2 mb-1.5">
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold tracking-wider uppercase bg-emerald-50 text-emerald-700 border border-emerald-100">
                    <i class="fa-solid fa-shield-halved text-[9px]"></i>
                    Admin Produksi
                </span>
                <span class="text-xs text-slate-300">·</span>
                <span class="text-xs text-slate-500 font-medium">Pengawasan Mutu</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Pusat validasi produksi</h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">
                Tinjau laporan shift, pastikan data limbah akurat, dan teruskan hasil tervalidasi ke rekap manajerial.
            </p>
        </div>
        <div class="flex items-center gap-2.5 self-start sm:self-auto shrink-0">
            <a href="{{ route('admin.validasi.index') }}" 
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs sm:text-sm shadow-sm transition active:scale-[0.98]">
                <i class="fa-solid fa-clipboard-check text-xs"></i>
                <span>Tinjau antrean</span>
            </a>
            <a href="{{ route('admin.kategori.index') }}" 
               class="inline-flex items-center gap-2 px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 font-semibold text-xs sm:text-sm transition">
                <i class="fa-solid fa-tags text-xs"></i>
                <span>Master Kategori</span>
            </a>
        </div>
    </div>

    <!-- Summary KPI Cards (Modern & Minimalist) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Pending -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs hover:border-slate-300 transition-all">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Menunggu Validasi</span>
                <span class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center text-xs">
                    <i class="fa-regular fa-clock"></i>
                </span>
            </div>
            <div class="mt-2.5">
                <span class="text-2xl sm:text-3xl font-extrabold text-amber-600 tracking-tight tabular-nums">{{ $totalPending }}</span>
                <span class="text-xs font-medium text-slate-400 ml-1">laporan</span>
            </div>
            <div class="mt-2.5 flex items-center gap-1.5 text-xs text-slate-500">
                <span class="inline-block w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                <span>Perlu ditinjau segera</span>
            </div>
        </div>

        <!-- Validated -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs hover:border-slate-300 transition-all">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Laporan Disetujui</span>
                <span class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-circle-check"></i>
                </span>
            </div>
            <div class="mt-2.5">
                <span class="text-2xl sm:text-3xl font-extrabold text-emerald-600 tracking-tight tabular-nums">{{ $totalValidated }}</span>
                <span class="text-xs font-medium text-slate-400 ml-1">laporan</span>
            </div>
            <div class="mt-2.5 flex items-center gap-1.5 text-xs text-slate-500">
                <span class="inline-block w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                <span>Data tervalidasi & terkunci</span>
            </div>
        </div>

        <!-- Revisi -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs hover:border-slate-300 transition-all">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Dalam Perbaikan</span>
                <span class="w-8 h-8 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-arrow-rotate-left"></i>
                </span>
            </div>
            <div class="mt-2.5">
                <span class="text-2xl sm:text-3xl font-extrabold text-rose-600 tracking-tight tabular-nums">{{ $totalRevisi }}</span>
                <span class="text-xs font-medium text-slate-400 ml-1">laporan</span>
            </div>
            <div class="mt-2.5 flex items-center gap-1.5 text-xs text-slate-500">
                <span class="inline-block w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                <span>Dikembalikan ke operator</span>
            </div>
        </div>

        <!-- Master Kategori -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs hover:border-slate-300 transition-all">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Kategori Waste</span>
                <span class="w-8 h-8 rounded-lg bg-sky-50 text-sky-600 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-tags"></i>
                </span>
            </div>
            <div class="mt-2.5">
                <span class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight tabular-nums">{{ $totalKategori }}</span>
                <span class="text-xs font-medium text-slate-400 ml-1">kategori</span>
            </div>
            <div class="mt-2.5 flex items-center gap-1.5 text-xs text-slate-500">
                <span class="inline-block w-1.5 h-1.5 rounded-full bg-sky-500"></span>
                <span>Trading, Cutting, Stripping, Reject</span>
            </div>
        </div>
    </div>

    <!-- Standar Mutu Komoditas DTY & Parameter Reject Card -->
    <div class="rounded-2xl border border-emerald-200/90 bg-gradient-to-r from-emerald-50/70 via-white to-sky-50/60 p-4 sm:p-5 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-start gap-3.5">
            <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-800 flex items-center justify-center text-lg shrink-0">
                <i class="fa-solid fa-award"></i>
            </div>
            <div class="space-y-1">
                <div class="flex items-center gap-2 flex-wrap">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-900">Standar Mutu Komoditas &amp; Parameter Reject DTY</span>
                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800 border border-emerald-200">
                        Grade AX = 5.5 Kg / Bobbin
                    </span>
                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-amber-100 text-amber-800 border border-amber-200">
                        Downgrade Otomatis &lt; 5.5 Kg
                    </span>
                </div>
                <p class="text-xs text-slate-600 leading-relaxed">
                    Mutu benang DTY tertinggi (Grade AX) memiliki berat mutlak <strong>5.5 Kg/bobbin</strong>. Gulungan benang di bawah batas tersebut otomatis mengalami <strong>downgrade ke Product Reject (Grade B/C)</strong> dan diakumulasikan sebagai penambah <strong>Reject Ratio</strong> pada laporan shift.
                </p>
            </div>
        </div>
        <button type="button" onclick="openStandarMutuModal()" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs font-semibold text-emerald-800 bg-white border border-emerald-200 hover:bg-emerald-50 transition shadow-xs shrink-0 self-start md:self-auto">
            <i class="fa-solid fa-circle-info text-emerald-600"></i>
            <span>Detail Parameter Baku</span>
        </button>
    </div>

    <!-- Antrean Laporan Menunggu Verifikasi (Neat Table) -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-3">
                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-amber-50 text-amber-600 text-xs">
                    <i class="fa-solid fa-hourglass-half"></i>
                </span>
                <div>
                    <h2 class="text-sm font-bold text-slate-900">Antrean menunggu validasi</h2>
                    <p class="text-xs text-slate-400 mt-0.5">Laporan shift yang perlu diverifikasi oleh Admin Produksi</p>
                </div>
            </div>
            <a href="{{ route('admin.validasi.index', ['status' => 'Pending']) }}" class="text-xs text-emerald-600 hover:text-emerald-700 font-semibold inline-flex items-center gap-1 transition">
                <span>Lihat semua antrean</span>
                <i class="fa-solid fa-arrow-right text-[10px]"></i>
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="workspace-table w-full min-w-[900px] text-left text-xs sm:text-sm text-slate-600">
                <thead class="bg-slate-50/70 text-[11px] uppercase font-bold text-slate-400 border-b border-slate-200/70">
                    <tr>
                        <th class="py-3 px-4">Tanggal & Shift</th>
                        <th class="py-3 px-4">Operator</th>
                        <th class="py-3 px-4 text-right">Output Produksi</th>
                        <th class="py-3 px-4 text-right">Total Waste</th>
                        <th class="py-3 px-4 text-center">Reject Ratio (%)</th>
                        <th class="py-3 px-4 text-center">Status</th>
                        <th class="py-3 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($pendingLaporans as $item)
                        <tr class="hover:bg-emerald-50/20 transition">
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <div class="font-bold text-slate-900">{{ $item->tanggal->format('d/m/Y') }}</div>
                                <span class="text-[11px] text-slate-400">Shift {{ $item->shift }}</span>
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap font-medium text-slate-800">
                                <i class="fa-regular fa-user mr-1 text-slate-400 text-xs"></i>
                                {{ $item->user->name ?? 'Operator' }}
                            </td>
                            <td class="py-3.5 px-4 text-right whitespace-nowrap font-semibold text-slate-800 tabular-nums">
                                {{ number_format($item->total_output_kg, 2, ',', '.') }} kg
                            </td>
                            <td class="py-3.5 px-4 text-right whitespace-nowrap font-bold text-slate-900 tabular-nums">
                                {{ number_format($item->total_waste, 2, ',', '.') }} kg
                            </td>
                            <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                <span class="px-2 py-0.5 rounded text-xs font-bold tabular-nums bg-amber-50 text-amber-700 border border-amber-100">
                                    {{ number_format($item->reject_ratio, 2, ',', '.') }}%
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-150">
                                    Pending
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                <a href="{{ route('admin.validasi.show', $item->id) }}" 
                                   class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold text-white bg-emerald-600 hover:bg-emerald-700 shadow-xs transition active:scale-[0.98]">
                                    <i class="fa-solid fa-magnifying-glass text-[10px]"></i>
                                    <span>Periksa & Validasi</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-10 text-center text-slate-400">
                                <i class="fa-solid fa-circle-check text-2xl text-emerald-400 mb-1"></i>
                                <p class="text-sm font-medium">Semua laporan telah divalidasi. Tidak ada antrean pending saat ini.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
