@extends('layouts.app')

@section('title', 'Detail Validasi Laporan #' . $laporan->id . ' - Admin')

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
                <a href="{{ route('admin.validasi.index') }}" class="text-xs text-slate-500 hover:text-emerald-600 font-medium transition">Daftar Antrean</a>
                <span class="text-xs text-slate-300">·</span>
                <span class="text-xs text-emerald-700 font-bold">Laporan #{{ $laporan->id }}</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Pemeriksaan laporan shift</h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">
                Operator: <strong class="text-slate-800">{{ $laporan->user->name ?? 'Operator' }}</strong> 
                <span class="mx-1 text-slate-300">·</span> Tanggal: <strong class="text-slate-800">{{ $laporan->tanggal->format('d/m/Y') }}</strong>
                <span class="mx-1 text-slate-300">·</span> Shift: <strong class="text-slate-800">{{ $laporan->shift }}</strong>
            </p>
        </div>
        <div class="flex items-center gap-2.5 self-start sm:self-auto shrink-0">
            <a href="{{ route('admin.validasi.index') }}" 
               class="inline-flex items-center gap-2 px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 font-semibold text-xs sm:text-sm transition">
                <i class="fa-solid fa-arrow-left text-xs"></i>
                <span>Kembali ke Antrean</span>
            </a>
        </div>
    </div>

    <!-- Status Banner (Neat & Clean) -->
    <div class="p-4 sm:p-5 rounded-2xl border flex items-center justify-between gap-4 {{ $laporan->status_validasi === 'Validated' ? 'bg-emerald-50/70 border-emerald-200/80 text-emerald-900' : ($laporan->status_validasi === 'Revisi' ? 'bg-rose-50/70 border-rose-200/80 text-rose-900' : 'bg-amber-50/70 border-amber-200/80 text-amber-900') }}">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center text-lg {{ $laporan->status_validasi === 'Validated' ? 'bg-emerald-100 text-emerald-700' : ($laporan->status_validasi === 'Revisi' ? 'bg-rose-100 text-rose-700' : 'bg-amber-100 text-amber-700') }}">
                @if($laporan->status_validasi === 'Validated')
                    <i class="fa-solid fa-circle-check"></i>
                @elseif($laporan->status_validasi === 'Revisi')
                    <i class="fa-solid fa-rotate-left"></i>
                @else
                    <i class="fa-solid fa-clock"></i>
                @endif
            </div>
            <div>
                <span class="text-[10px] font-bold uppercase tracking-wider opacity-75">Status Laporan Saat Ini</span>
                <div class="text-base font-extrabold flex items-center gap-2">
                    <span>{{ $laporan->status_validasi }}</span>
                    <span class="text-xs font-normal opacity-70">
                        @if($laporan->status_validasi === 'Validated')
                            (Telah disahkan & terkunci)
                        @elseif($laporan->status_validasi === 'Revisi')
                            (Memerlukan koreksi oleh operator)
                        @else
                            (Menunggu evaluasi admin)
                        @endif
                    </span>
                </div>
            </div>
        </div>

        @if($laporan->catatan_admin)
            <div class="text-right text-xs max-w-md hidden sm:block bg-white/70 p-2.5 rounded-xl border border-slate-200/60">
                <span class="font-bold block text-[10px] uppercase text-slate-500">Catatan Admin Terakhir:</span>
                <span class="italic text-slate-700">"{{ $laporan->catatan_admin }}"</span>
            </div>
        @endif
    </div>

    <!-- Ringkasan Angka Produksi (Standard Minimalist KPI Cards) -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <!-- Output Produksi -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs hover:border-slate-300 transition">
            <div class="flex items-center justify-between mb-3">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Output Produksi</span>
                <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-boxes-stacked"></i>
                </div>
            </div>
            <div class="text-2xl font-black text-slate-900 tracking-tight tabular-nums">
                {{ number_format($laporan->total_output_kg, 2, ',', '.') }} <span class="text-xs font-semibold text-slate-400">kg</span>
            </div>
            <p class="text-[11px] text-slate-400 mt-1">Hasil produksi benang DTY shift ini.</p>
        </div>

        <!-- Total Limbah -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs hover:border-slate-300 transition">
            <div class="flex items-center justify-between mb-3">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Limbah & Reject</span>
                <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-trash-can"></i>
                </div>
            </div>
            <div class="text-2xl font-black text-amber-600 tracking-tight tabular-nums">
                {{ number_format($laporan->total_waste, 2, ',', '.') }} <span class="text-xs font-semibold text-slate-400">kg</span>
            </div>
            <p class="text-[11px] text-slate-400 mt-1">Akumulasi seluruh kategori waste shift.</p>
        </div>

        <!-- Reject Ratio -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs hover:border-slate-300 transition">
            <div class="flex items-center justify-between mb-3">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Reject Ratio</span>
                <div class="w-8 h-8 rounded-lg bg-sky-50 text-sky-600 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-percent"></i>
                </div>
            </div>
            <div class="text-2xl font-black text-sky-600 tracking-tight tabular-nums">
                {{ number_format($laporan->reject_ratio, 2, ',', '.') }} <span class="text-xs font-semibold text-slate-400">%</span>
            </div>
            <p class="text-[11px] text-slate-400 mt-1">Persentase reject terhadap total output.</p>
        </div>
    </div>

    <!-- Rincian Limbah per Kategori -->
    <!-- Rincian Timbangan Limbah per Kategori -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider">
                    Rincian Timbangan Limbah &amp; Product Reject per Kategori
                </h2>
                <p class="text-xs text-slate-400 mt-0.5">Daftar timbangan waste dan bobbin downgrade hasil pencatatan operator shift</p>
            </div>
            <span class="text-xs font-semibold text-slate-400">{{ $laporan->detailWaste->groupBy('kategori_waste_id')->count() }} Kategori</span>
        </div>
        <div class="p-5 sm:p-6 space-y-4">
            <!-- Parameter Reference Note -->
            <div class="p-3.5 rounded-xl border border-sky-100 bg-sky-50/60 text-xs text-slate-600 flex items-start gap-2.5">
                <i class="fa-solid fa-award text-sky-600 mt-0.5 shrink-0"></i>
                <div class="leading-relaxed">
                    <strong class="text-slate-800">Standar Mutu Baku DTY:</strong> Mutu tertinggi (Grade AX) memiliki bobot mutlak <strong>5.5 Kg/bobbin</strong>. Bobot di bawah ambang tersebut otomatis mengalami <em>downgrade</em> ke <strong>Product Reject (Grade B/C)</strong> dan berkontribusi langsung menaikkan persentase <strong>Reject Ratio</strong> laporan ini.
                </div>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                @forelse($laporan->detailWaste->groupBy('kategori_waste_id') as $details)
                    @php
                        $category = $details->first()->kategoriWaste;
                        $categoryTotal = $details->sum('volume_kg');
                        $prop = $laporan->total_waste > 0 ? ($categoryTotal / $laporan->total_waste) * 100 : 0;
                        $isRejectCat = \Illuminate\Support\Str::contains(\Illuminate\Support\Str::lower($category->nama_kategori ?? ''), 'reject');
                    @endphp
                    <article class="rounded-xl border {{ $isRejectCat ? 'border-amber-300 bg-amber-50/40 ring-1 ring-amber-200/60' : 'border-slate-200/80 bg-slate-50/50' }} p-4 transition hover:bg-slate-50">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <div class="flex items-center gap-1.5 flex-wrap">
                                    <h3 class="text-sm font-bold text-slate-900">{{ $category->nama_kategori ?? 'Limbah' }}</h3>
                                    @if($isRejectCat)
                                        <span class="text-[9px] font-bold uppercase tracking-wider text-amber-800 bg-amber-100 px-1.5 py-0.5 rounded border border-amber-200">
                                            Downgrade &lt; 5.5 Kg
                                        </span>
                                    @endif
                                </div>
                                <p class="mt-0.5 text-[11px] text-slate-400">
                                    @if($isRejectCat)
                                        Gulungan DTY downgrade di bawah standar bobot Grade AX (5.5 kg/bobbin)
                                    @else
                                        {{ $category->deskripsi ?: 'Limbah benang DTY' }}
                                    @endif
                                </p>
                            </div>
                            <div class="text-right">
                                <p class="text-base font-extrabold tabular-nums {{ $isRejectCat ? 'text-amber-700' : 'text-slate-900' }}">{{ number_format($categoryTotal, 2, ',', '.') }} kg</p>
                                <p class="text-[10px] font-semibold text-slate-400">{{ number_format($prop, 1) }}% dari total waste</p>
                            </div>
                        </div>
                        @if($category?->rincian_per_lane)
                            <div class="mt-3 space-y-2 border-t border-slate-200/70 pt-3">
                                @foreach($details as $detail)
                                    <div class="flex items-center justify-between gap-3 text-xs">
                                        <span class="inline-flex items-center gap-1.5 text-slate-600 font-medium">
                                            <i class="fa-solid fa-road text-[10px] text-sky-600"></i>
                                            {{ $detail->lane->nama_lane ?? 'Belum dirinci' }}
                                        </span>
                                        <strong class="tabular-nums text-slate-800">{{ number_format($detail->volume_kg, 2, ',', '.') }} kg</strong>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </article>
                @empty
                    <div class="col-span-2 rounded-xl border border-dashed border-slate-300 py-8 text-center text-xs text-slate-400">
                        Tidak ada rincian detail limbah yang tercatat.
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Tindakan Keputusan Validasi Admin -->
    <div class="bg-white p-5 sm:p-6 rounded-2xl border border-slate-200/80 shadow-xs space-y-5">
        <div>
            <div class="flex items-center gap-2">
                <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-emerald-50 text-emerald-600 text-xs"><i class="fa-solid fa-gavel"></i></span>
                <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Tindakan Keputusan Validasi Admin</h3>
            </div>
            <p class="text-xs text-slate-500 mt-1">
                Pilih apakah data laporan ini sudah wajar dan siap <strong>Divalidasi</strong>, atau terdapat kesalahan input sehingga memerlukan <strong>Revisi</strong> oleh Operator.
            </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-1">
            <!-- Form Setujui (Validate) -->
            <form action="{{ route('admin.validasi.approve', $laporan->id) }}" method="POST" 
                  onsubmit="return confirm('Apakah Anda yakin ingin MEMVALIDASI laporan ini? Data akan terkunci dan masuk ke laporan eksekutif Manajer.')"
                  class="p-5 rounded-xl border border-emerald-200 bg-emerald-50/40 space-y-3 flex flex-col justify-between">
                @csrf
                <div class="space-y-2">
                    <div class="flex items-center gap-2 text-emerald-800 font-bold text-sm">
                        <i class="fa-solid fa-check-double text-emerald-600"></i>
                        <span>Setujui & Kunci Laporan (Validated)</span>
                    </div>
                    <p class="text-xs text-emerald-700/80 leading-relaxed">
                        Data dinyatakan wajar dan sah. Operator tidak dapat lagi mengedit data ini, dan angka akan diikutsertakan dalam rekapan eksekutif manajer.
                    </p>
                    <div>
                        <label for="approve_catatan" class="block text-[11px] font-semibold text-emerald-800 mb-1">Catatan Persetujuan (Opsional)</label>
                        <input type="text" id="approve_catatan" name="catatan_admin" placeholder="Contoh: Angka wajar, sudah sesuai fisik..." 
                               class="w-full px-3 py-2 text-xs rounded-xl border border-emerald-200 focus:border-emerald-500 outline-none bg-white">
                    </div>
                </div>
                <button type="submit" 
                        class="w-full py-2.5 px-4 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs shadow-xs transition flex items-center justify-center gap-2 active:scale-[0.98]">
                    <i class="fa-solid fa-lock text-xs"></i>
                    <span>Validasi Sekarang (Kunci Data)</span>
                </button>
            </form>

            <!-- Form Minta Revisi (Reject) -->
            <form action="{{ route('admin.validasi.reject', $laporan->id) }}" method="POST" 
                  onsubmit="return confirm('Apakah Anda yakin ingin meminta REVISI untuk laporan ini?')"
                  class="p-5 rounded-xl border border-rose-200 bg-rose-50/40 space-y-3 flex flex-col justify-between">
                @csrf
                <div class="space-y-2">
                    <div class="flex items-center gap-2 text-rose-800 font-bold text-sm">
                        <i class="fa-solid fa-triangle-exclamation text-rose-600"></i>
                        <span>Tolak & Minta Revisi (Revisi)</span>
                    </div>
                    <p class="text-xs text-rose-700/80 leading-relaxed">
                        Kembalikan laporan ke Operator jika terdapat angka yang tidak masuk akal (misal salah ketik bobot waste atau volume melebihi batas toleransi).
                    </p>
                    <div>
                        <label for="reject_catatan" class="block text-[11px] font-semibold text-rose-800 mb-1">Alasan Penolakan / Revisi <span class="text-rose-500">*</span></label>
                        <textarea id="reject_catatan" name="catatan_admin" required rows="2" 
                                  placeholder="Wajib tuliskan alasan revisi, misal: Bobot cutting terlalu tinggi, mohon timbang ulang..."
                                  class="w-full px-3 py-2 text-xs rounded-xl border border-rose-200 focus:border-rose-500 outline-none bg-white"></textarea>
                    </div>
                </div>
                <button type="submit" 
                        class="w-full py-2.5 px-4 rounded-xl bg-rose-600 hover:bg-rose-500 text-white font-bold text-xs shadow-xs transition flex items-center justify-center gap-2 active:scale-[0.98]">
                    <i class="fa-solid fa-rotate-left text-xs"></i>
                    <span>Kembalikan ke Operator (Revisi)</span>
                </button>
            </form>
        </div>
    </div>

</div>
@endsection

