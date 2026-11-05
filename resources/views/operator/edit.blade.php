@extends('layouts.app')

@section('title', 'Edit Laporan Shift - Operator')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Header Breadcrumb & Title -->
    <div class="relative overflow-hidden bg-gradient-to-br from-slate-950 via-slate-900 to-sky-950 p-6 sm:p-7 rounded-2xl shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-5">
        <div class="pointer-events-none absolute -right-16 -top-24 h-64 w-64 rounded-full border border-sky-200/10 bg-sky-400/10"></div>
        <div class="relative z-10">
            <div class="flex items-center gap-2 text-[10px] font-bold text-sky-200/80 uppercase tracking-wider mb-2">
                <a href="{{ route('operator.laporan.index') }}" class="hover:text-white transition">Riwayat Laporan</a>
                <span>&bull;</span>
                <span class="text-sky-300">Perbaikan / Edit Data #{{ $laporan->id }}</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">Perbarui laporan shift</h1>
            <p class="text-xs sm:text-sm text-slate-300 mt-1">Tanggal {{ $laporan->tanggal->format('d/m/Y') }} · Shift {{ $laporan->shift }} · Laporan #{{ $laporan->id }}</p>
        </div>
        <div class="relative z-10 self-start sm:self-auto shrink-0">
            <a href="{{ route('operator.laporan.index') }}" 
               class="inline-flex items-center gap-2 px-3.5 py-2.5 rounded-xl text-xs font-semibold text-white border border-white/15 bg-white/10 hover:bg-white/15 transition">
                <i class="fa-solid fa-arrow-left text-xs"></i>
                <span>Kembali ke Riwayat</span>
            </a>
        </div>
    </div>

    <!-- Alert Khusus Catatan Revisi dari Admin -->
    @if($laporan->status_validasi === 'Revisi' && $laporan->catatan_admin)
        <div class="p-4 sm:p-5 rounded-2xl bg-rose-50 border border-rose-200/80 text-rose-900 shadow-xs flex items-start gap-3.5">
            <div class="w-8 h-8 rounded-xl bg-rose-100 text-rose-600 flex items-center justify-center shrink-0 text-sm">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            <div class="space-y-1">
                <h3 class="text-xs font-bold uppercase tracking-wider text-rose-900">Catatan Koreksi dari Admin Produksi:</h3>
                <p class="text-xs text-rose-800 leading-relaxed bg-white/80 p-3 rounded-xl border border-rose-200 font-medium">
                    "{{ $laporan->catatan_admin }}"
                </p>
                <p class="text-[11px] text-rose-600">
                    Silakan sesuaikan data angka di bawah ini berdasarkan arahan admin sebelum mengajukan ulang.
                </p>
            </div>
        </div>
    @endif

    <!-- Form Card -->
    <form action="{{ route('operator.laporan.update', $laporan->id) }}" method="POST" id="laporanForm" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Section 1: Header Laporan Shift -->
        <div class="bg-white p-5 sm:p-6 rounded-2xl border border-slate-200/80 shadow-xs space-y-5">
            <div class="border-b border-slate-100 pb-3.5 flex items-center gap-2.5">
                <div class="w-6 h-6 rounded-lg bg-sky-50 text-sky-700 flex items-center justify-center text-xs font-bold border border-sky-100">1</div>
                <div>
                    <h2 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Informasi Waktu & Shift Kerja</h2>
                    <p class="text-[11px] text-slate-400">Tentukan tanggal dan shift operasional yang Anda jalankan</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Tanggal -->
                <div>
                    <label for="tanggal" class="block text-[11px] font-semibold text-slate-500 uppercase tracking-wider mb-1.5">
                        Tanggal Produksi <span class="text-rose-500">*</span>
                    </label>
                    <input type="date" name="tanggal" id="tanggal" required
                           value="{{ old('tanggal', $laporan->tanggal->format('Y-m-d')) }}"
                           class="w-full px-3.5 py-2.5 text-xs sm:text-sm rounded-xl border border-slate-200 focus:border-sky-500 focus:ring-1 focus:ring-sky-100 outline-none transition bg-slate-50/50">
                </div>

                <!-- Shift Kerja -->
                <div>
                    <label for="shift" class="block text-[11px] font-semibold text-slate-500 uppercase tracking-wider mb-1.5">
                        Shift Kerja <span class="text-rose-500">*</span>
                    </label>
                    <select name="shift" id="shift" required
                            class="w-full px-3.5 py-2.5 text-xs sm:text-sm rounded-xl border border-slate-200 focus:border-sky-500 focus:ring-1 focus:ring-sky-100 outline-none transition bg-slate-50/50">
                        <option value="1" {{ old('shift', $laporan->shift) == '1' ? 'selected' : '' }}>Shift 1 (Pagi: 07:00 - 15:00)</option>
                        <option value="2" {{ old('shift', $laporan->shift) == '2' ? 'selected' : '' }}>Shift 2 (Sore: 15:00 - 23:00)</option>
                        <option value="3" {{ old('shift', $laporan->shift) == '3' ? 'selected' : '' }}>Shift 3 (Malam: 23:00 - 07:00)</option>
                    </select>
                </div>
            </div>

            <!-- Total Output Produksi -->
            <div class="pt-2">
                <label for="total_output_kg" class="block text-[11px] font-semibold text-slate-500 uppercase tracking-wider mb-1.5">
                    Total Output Produksi Benang (kg) <span class="text-rose-500">*</span>
                </label>
                <div class="relative max-w-md">
                    <input type="number" step="0.01" min="0.01" name="total_output_kg" id="total_output_kg" required
                           value="{{ old('total_output_kg', $laporan->total_output_kg) }}"
                           placeholder="Contoh: 12500.00"
                           class="w-full pl-3.5 pr-14 py-2.5 text-xs sm:text-sm rounded-xl border border-slate-200 focus:border-sky-500 focus:ring-1 focus:ring-sky-100 outline-none transition bg-slate-50/50 font-bold text-slate-900">
                    <div class="absolute inset-y-0 right-0 pr-3.5 flex items-center pointer-events-none text-xs font-bold text-slate-400">
                        kg
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 2: Input Rincian Limbah & Product Reject -->
        <div class="bg-white p-5 sm:p-6 rounded-2xl border border-slate-200/80 shadow-xs space-y-5">
            <div class="border-b border-slate-100 pb-3.5 flex items-center justify-between gap-4">
                <div class="flex items-center gap-2.5">
                    <div class="w-6 h-6 rounded-lg bg-sky-50 text-sky-700 flex items-center justify-center text-xs font-bold border border-sky-100">2</div>
                    <div>
                        <h2 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Volume Limbah & Reject Berdasarkan Kategori</h2>
                        <p class="text-[11px] text-slate-400">Perbarui berat timbangan untuk setiap kategori limbah benang</p>
                    </div>
                </div>
                <span class="text-xs text-slate-400 font-semibold">Satuan: kg</span>
            </div>

            <!-- Standar Mutu Komoditas & Parameter Reject Notice -->
            <div class="rounded-xl border border-sky-200 bg-gradient-to-r from-sky-50/70 via-indigo-50/40 to-slate-50 p-4 sm:p-5">
                <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4">
                    <div class="flex items-start gap-3">
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-sky-100 text-sky-700 text-base">
                            <i class="fa-solid fa-award"></i>
                        </span>
                        <div>
                            <div class="flex items-center gap-2 flex-wrap">
                                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-900">Standar Mutu Komoditas &amp; Parameter Reject DTY</h3>
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800 border border-emerald-200">
                                    Grade AX = 5.5 Kg / Bobbin
                                </span>
                            </div>
                            <p class="mt-1 text-xs text-slate-600 leading-relaxed">
                                Mutu benang DTY kualitas tertinggi (<strong>Grade AX</strong>) memiliki standardisasi berat mutlak di angka <strong>5.5 Kg per gulungan (bobbin)</strong>.
                                Sesuai aturan bisnis, gulungan dengan bobot akhir di bawah 5.5 Kg mengalami <strong>penurunan mutu (downgrade) otomatis</strong> menjadi <strong>Product Reject (Grade B / C)</strong>. Masukkan total akumulasi bobotnya (Kg) untuk dikalkulasikan sebagai penambah Reject Ratio.
                            </p>
                        </div>
                    </div>
                    <button type="button" onclick="toggleBobbinHelper()" class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl text-xs font-semibold text-sky-700 bg-white border border-sky-200 hover:bg-sky-50 shadow-xs transition shrink-0 self-start">
                        <i class="fa-solid fa-calculator text-xs"></i>
                        <span id="btnHelperText">Bantu Hitung Bobbin</span>
                    </button>
                </div>

                <!-- Kalkulator Pembantu Bobbin Downgrade (Accordion / Collapsible) -->
                <div id="bobbinHelperCard" class="hidden mt-4 pt-4 border-t border-sky-200/60 transition-all">
                    <div class="bg-white p-4 rounded-xl border border-sky-100 shadow-xs space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-slate-800 flex items-center gap-2">
                                <i class="fa-solid fa-calculator text-sky-600"></i>
                                Kalkulator Estimasi Bobbin Downgrade (Product Reject)
                            </span>
                            <span class="text-[11px] text-slate-400">Ambang Downgrade: &lt; 5.50 kg/bobbin</span>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            <div>
                                <label for="helperBobbinCount" class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">
                                    Jumlah Gulungan / Bobbin Reject
                                </label>
                                <input type="number" id="helperBobbinCount" min="0" step="1" placeholder="Contoh: 8"
                                       class="w-full px-3 py-2 text-xs rounded-lg border border-slate-200 bg-slate-50 focus:bg-white focus:border-sky-500 outline-none">
                            </div>
                            <div>
                                <label for="helperBobbinWeight" class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">
                                    Rata-rata Bobot / Bobbin (kg)
                                </label>
                                <input type="number" id="helperBobbinWeight" min="0" max="5.49" step="0.01" placeholder="Maks. 5.49"
                                       class="w-full px-3 py-2 text-xs rounded-lg border border-slate-200 bg-slate-50 focus:bg-white focus:border-sky-500 outline-none">
                                <span class="text-[9px] text-amber-600 mt-0.5 block">*Harus &lt; 5.5 kg (Grade B/C)</span>
                            </div>
                            <div class="flex flex-col justify-end">
                                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">
                                    Total Estimasi Berat
                                </label>
                                <div class="flex items-center gap-2">
                                    <div class="flex-1 px-3 py-2 text-xs font-bold text-sky-700 bg-sky-50 rounded-lg border border-sky-200 tabular-nums" id="helperTotalWeight">
                                        0.00 kg
                                    </div>
                                    <button type="button" id="btnApplyHelper" class="px-3 py-2 bg-sky-600 hover:bg-sky-500 text-white text-xs font-semibold rounded-lg transition disabled:opacity-50" disabled>
                                        Terapkan
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($kategoris as $kat)
                    @php
                        $curVal = old('waste.' . $kat->id, $wasteValues[$kat->id] ?? 0);
                        $isProductReject = \Illuminate\Support\Str::contains(\Illuminate\Support\Str::lower($kat->nama_kategori), 'reject');
                    @endphp
                    @if($kat->rincian_per_lane)
                        <div class="col-span-full rounded-xl border border-sky-200 bg-sky-50/30 p-4 sm:p-5">
                            <div class="mb-3.5 flex items-start gap-3">
                                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-sky-100 text-sky-700 text-xs"><i class="fa-solid fa-road"></i></span>
                                <div>
                                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-900">{{ $kat->nama_kategori }} per lane</h3>
                                    <p class="mt-0.5 text-[11px] text-slate-500">Perbarui berat setiap lane/mesin yang digunakan pada shift ini.</p>
                                </div>
                            </div>
                            @if($preservedTradingWaste > 0)
                                <input type="hidden" class="waste-input" value="{{ $preservedTradingWaste }}">
                            @endif
                            @if($legacyTradingWaste > 0)
                                <p class="mb-3 rounded-xl border border-amber-200 bg-amber-50 p-3 text-xs leading-5 text-amber-800">
                                    Laporan ini memiliki {{ number_format($legacyTradingWaste, 2, ',', '.') }} kg Trading lama yang belum dirinci per lane. Nilai historis tersebut tetap dipertahankan saat laporan diperbarui.
                                </p>
                            @endif
                            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-3">
                                @foreach($lanes as $lane)
                                    @php
                                        $laneVal = old('lane_waste.' . $kat->id . '.' . $lane->id, $laneWasteValues[$kat->id][$lane->id] ?? 0);
                                        $laneHasDetails = $laporan->detailWaste->contains(fn ($detail) => (int) $detail->lane_id === (int) $lane->id && (int) $detail->kategori_waste_id === (int) $kat->id);
                                    @endphp
                                    @if($lane->status_aktif)
                                        <label for="lane_waste_{{ $kat->id }}_{{ $lane->id }}" class="block rounded-xl border border-slate-200/80 bg-white p-3.5 transition hover:border-sky-300">
                                            <span class="mb-2 flex items-center justify-between">
                                                <span class="text-xs font-bold text-slate-800">{{ $lane->nama_lane }}</span>
                                                <span class="text-[9px] font-bold uppercase tracking-wider text-sky-600 bg-sky-50 px-1.5 py-0.5 rounded">Trading</span>
                                            </span>
                                            <span class="relative block">
                                                <input type="number" step="0.01" min="0" name="lane_waste[{{ $kat->id }}][{{ $lane->id }}]" id="lane_waste_{{ $kat->id }}_{{ $lane->id }}"
                                                       value="{{ $laneVal }}"
                                                       class="waste-input w-full rounded-lg border border-slate-200 bg-slate-50 py-2 pl-3 pr-10 text-xs sm:text-sm font-bold text-slate-800 outline-none transition focus:border-sky-500 focus:ring-1 focus:ring-sky-100"
                                                       placeholder="0.00">
                                                <span class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-xs font-bold text-slate-400">kg</span>
                                            </span>
                                        </label>
                                    @elseif($laneHasDetails)
                                        <div class="rounded-xl border border-slate-200 bg-slate-100/70 p-3.5">
                                            <span class="mb-2 flex items-center justify-between">
                                                <span class="text-xs font-bold text-slate-700">{{ $lane->nama_lane }}</span>
                                                <span class="text-[9px] font-semibold uppercase text-slate-400">Nonaktif · Riwayat</span>
                                            </span>
                                            <p class="text-xs sm:text-sm font-bold text-slate-700">{{ number_format($laneVal, 2, ',', '.') }} kg</p>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="space-y-2.5 rounded-xl border {{ $isProductReject ? 'border-amber-300 bg-amber-50/30 ring-1 ring-amber-200/70' : 'border-slate-200/80 bg-slate-50/50' }} p-4 transition hover:bg-slate-50">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-1.5 flex-wrap">
                                    <span class="text-xs font-bold uppercase tracking-wider text-slate-800">{{ $kat->nama_kategori }}</span>
                                    @if($isProductReject)
                                        <span class="text-[9px] font-bold uppercase tracking-wider text-amber-800 bg-amber-100 px-1.5 py-0.5 rounded border border-amber-200">
                                            Downgrade &lt; 5.5 Kg
                                        </span>
                                    @endif
                                </div>
                                <span class="h-2 w-2 rounded-full {{ $isProductReject ? 'bg-amber-500' : 'bg-emerald-500' }}"></span>
                            </div>
                            <p class="line-clamp-2 text-[11px] text-slate-500">
                                @if($isProductReject)
                                    Akumulasi bobot gulungan benang DTY yang di-downgrade di bawah batas Grade AX (5.5 kg/bobbin) menjadi Grade B/C.
                                @else
                                    {{ $kat->deskripsi ?: 'Limbah ' . $kat->nama_kategori }}
                                @endif
                            </p>
                            <div class="relative">
                                <input type="number" step="0.01" min="0" name="waste[{{ $kat->id }}]" id="waste_{{ $kat->id }}"
                                       value="{{ $curVal }}"
                                       data-is-reject="{{ $isProductReject ? 'true' : 'false' }}"
                                       class="waste-input {{ $isProductReject ? 'product-reject-input border-amber-300 focus:border-amber-500 focus:ring-amber-100' : 'border-slate-200 focus:border-sky-500 focus:ring-sky-100' }} w-full rounded-lg border bg-white py-2 pl-3 pr-10 text-xs sm:text-sm font-bold text-slate-800 outline-none transition"
                                       placeholder="0.00">
                                <span class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-xs font-bold text-slate-400">kg</span>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>

        <!-- Section 3: Live Interactive Calculator Preview -->
        <div class="bg-slate-900 text-white p-5 sm:p-6 rounded-2xl border border-slate-800 shadow-xs space-y-4">
            <div class="flex flex-col gap-1 border-b border-slate-800 pb-3 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex items-center gap-2">
                    <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-sky-500/20 text-sky-400 text-xs"><i class="fa-solid fa-calculator"></i></span>
                    <h3 class="text-xs font-bold uppercase tracking-wider text-sky-300">Kalkulasi Otomatis (Live Preview)</h3>
                </div>
                <span class="text-[10px] text-slate-400 font-mono">Rumus: (Total Waste &divide; Total Output) &times; 100%</span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-center">
                <div class="bg-slate-800/80 p-3.5 rounded-xl border border-slate-700/60">
                    <div class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Total Output Produksi</div>
                    <div class="text-base sm:text-lg font-bold text-white mt-1 tabular-nums" id="previewOutput">0.00 kg</div>
                </div>
                <div class="bg-slate-800/80 p-3.5 rounded-xl border border-slate-700/60">
                    <div class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Akumulasi Total Limbah</div>
                    <div class="text-base sm:text-lg font-bold text-amber-400 mt-1 tabular-nums" id="previewWaste">0.00 kg</div>
                </div>
                <div class="bg-slate-800/80 p-3.5 rounded-xl border border-slate-700/60">
                    <div class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Estimasi Reject Ratio</div>
                    <div class="text-lg sm:text-xl font-extrabold text-sky-400 mt-1 tabular-nums" id="previewRatio">0.00 %</div>
                </div>
            </div>

            <div id="logicWarning" class="hidden p-3 rounded-xl bg-rose-500/20 border border-rose-500/50 text-rose-300 text-xs flex items-center gap-2">
                <i class="fa-solid fa-triangle-exclamation text-rose-400"></i>
                <span><strong>Peringatan Logika:</strong> Total volume waste tidak boleh melebihi total output produksi!</span>
            </div>
        </div>

        <!-- Submit Buttons -->
        <div class="flex items-center justify-end gap-2.5 pt-1">
            <a href="{{ route('operator.laporan.index') }}" 
               class="px-4 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-100 font-semibold text-xs sm:text-sm transition">
                Batal
            </a>
            <button type="submit" id="btnSubmit"
                    class="px-5 py-2.5 rounded-xl bg-sky-600 hover:bg-sky-500 text-white font-semibold text-xs sm:text-sm shadow-xs transition active:scale-[0.98] flex items-center gap-2">
                <i class="fa-solid fa-check text-xs"></i>
                <span>Simpan Perubahan & Ajukan Ulang</span>
            </button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
    function toggleBobbinHelper() {
        const card = document.getElementById('bobbinHelperCard');
        const btnText = document.getElementById('btnHelperText');
        if (card.classList.contains('hidden')) {
            card.classList.remove('hidden');
            btnText.textContent = 'Tutup Bantu Hitung';
            document.getElementById('helperBobbinCount')?.focus();
        } else {
            card.classList.add('hidden');
            btnText.textContent = 'Bantu Hitung Bobbin';
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        const outputInput = document.getElementById('total_output_kg');
        const wasteInputs = document.querySelectorAll('.waste-input');
        const previewOutput = document.getElementById('previewOutput');
        const previewWaste = document.getElementById('previewWaste');
        const previewRatio = document.getElementById('previewRatio');
        const logicWarning = document.getElementById('logicWarning');
        const btnSubmit = document.getElementById('btnSubmit');

        // Helper Bobbin Elements
        const helperCount = document.getElementById('helperBobbinCount');
        const helperWeight = document.getElementById('helperBobbinWeight');
        const helperTotal = document.getElementById('helperTotalWeight');
        const btnApplyHelper = document.getElementById('btnApplyHelper');

        function updateBobbinHelper() {
            const count = parseInt(helperCount?.value, 10) || 0;
            const weight = parseFloat(helperWeight?.value) || 0;
            const total = count * weight;

            if (helperTotal) {
                helperTotal.textContent = total.toFixed(2) + ' kg';
            }

            if (btnApplyHelper) {
                btnApplyHelper.disabled = total <= 0;
            }
        }

        if (helperCount) helperCount.addEventListener('input', updateBobbinHelper);
        if (helperWeight) {
            helperWeight.addEventListener('input', function() {
                if (parseFloat(this.value) >= 5.5) {
                    alert('Catatan Standar Mutu: Bobot 5.5 Kg ke atas merupakan benang kualitas tertinggi (Grade AX), bukan Product Reject! Batas penurunan mutu (downgrade) adalah di bawah 5.5 Kg.');
                }
                updateBobbinHelper();
            });
        }

        if (btnApplyHelper) {
            btnApplyHelper.addEventListener('click', function () {
                const count = parseInt(helperCount?.value, 10) || 0;
                const weight = parseFloat(helperWeight?.value) || 0;
                const total = (count * weight).toFixed(2);

                const rejectInput = document.querySelector('.product-reject-input') || document.querySelector('[data-is-reject="true"]');
                if (rejectInput) {
                    rejectInput.value = total;
                    rejectInput.classList.add('ring-2', 'ring-amber-400');
                    setTimeout(() => rejectInput.classList.remove('ring-2', 'ring-amber-400'), 2000);
                    calculateLive();
                    toggleBobbinHelper();
                } else {
                    alert('Kategori Product Reject tidak ditemukan pada formulir. Pastikan kategori telah aktif.');
                }
            });
        }

        function calculateLive() {
            const output = parseFloat(outputInput.value) || 0;
            let totalWaste = 0;

            wasteInputs.forEach(input => {
                const val = parseFloat(input.value) || 0;
                totalWaste += val;
            });

            previewOutput.textContent = output.toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + ' kg';
            previewWaste.textContent = totalWaste.toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + ' kg';

            if (output > 0) {
                const ratio = (totalWaste / output) * 100;
                previewRatio.textContent = ratio.toFixed(2) + ' %';
            } else {
                previewRatio.textContent = '0.00 %';
            }

            if (output > 0 && totalWaste > output) {
                logicWarning.classList.remove('hidden');
                btnSubmit.disabled = true;
                btnSubmit.classList.add('opacity-50', 'cursor-not-allowed');
            } else {
                logicWarning.classList.add('hidden');
                btnSubmit.disabled = false;
                btnSubmit.classList.remove('opacity-50', 'cursor-not-allowed');
            }
        }

        outputInput.addEventListener('input', calculateLive);
        wasteInputs.forEach(input => input.addEventListener('input', calculateLive));

        calculateLive();
    });
</script>
@endsection
