<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Rekapitulasi Limbah & Reject DTY - Cetak</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; padding: 0 !important; }
        }
    </style>
</head>
<body class="bg-slate-100 text-slate-800 p-4 sm:p-8">

    <!-- Action Bar (No Print) -->
    <div class="max-w-5xl mx-auto mb-6 flex items-center justify-between no-print bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
        <div class="flex items-center gap-2 text-sm text-slate-600">
            <span class="font-bold text-slate-900">Mode Pratinjau Dokumen Cetak</span>
            <span>&bull;</span>
            <span class="text-xs text-slate-500">Siap diekspor ke PDF / Printer</span>
        </div>
        <div class="flex items-center gap-2">
            <button onclick="window.print()" 
                    class="px-4 py-2 rounded-xl bg-purple-600 hover:bg-purple-500 text-white font-bold text-xs shadow-md shadow-purple-600/20 transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                <span>Cetak / Simpan PDF</span>
            </button>
            <button onclick="window.close()" 
                    class="px-3 py-2 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-100 text-xs font-semibold transition">
                Tutup
            </button>
        </div>
    </div>

    <!-- Printable Paper Sheet (A4 Styling) -->
    <div class="max-w-5xl mx-auto bg-white p-8 sm:p-12 rounded-2xl shadow-sm border border-slate-200 text-slate-900">

        <!-- Kop Surat Resmi -->
        <div class="border-b-2 border-slate-900 pb-4 mb-6">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <h2 class="text-xs font-extrabold uppercase tracking-widest text-slate-500">
                        POLITEKNIK ENJINERING INDORAMA
                    </h2>
                    <h1 class="text-lg font-black uppercase tracking-tight text-slate-900 mt-0.5">
                        PROGRAM STUDI TEKNOLOGI REKAYASA PERANGKAT LUNAK
                    </h1>
                    <p class="text-xs text-slate-600">
                        Proyek Manufaktur Tekstil • Aplikasi Pengelolaan Limbah dan Product Reject (Studi Kasus: <strong>DTY</strong> (<em>Draw Textured Yarn</em>))
                    </p>
                </div>
                <div class="text-right text-xs text-slate-500 hidden sm:block">
                    <p class="font-semibold text-slate-800">Dokumen Eksekutif</p>
                    <p>Purwakarta, Jawa Barat</p>
                </div>
            </div>
        </div>

        <!-- Judul Dokumen -->
        <div class="text-center mb-6">
            <h2 class="text-base font-extrabold uppercase tracking-wide text-slate-900">
                LAPORAN REKAPITULASI LIMBAH & PRODUCT REJECT
            </h2>
            <p class="text-xs text-slate-600 mt-1">
                Periode: 
                <strong>{{ $startDate ? \Carbon\Carbon::parse($startDate)->format('d/m/Y') : 'Awal' }}</strong> 
                s/d 
                <strong>{{ $endDate ? \Carbon\Carbon::parse($endDate)->format('d/m/Y') : 'Sekarang' }}</strong>
                @if($shift)
                    &bull; Shift: <strong>Shift {{ $shift }}</strong>
                @endif
            </p>
        </div>

        <!-- Tabel Rekapitulasi Data -->
        <table class="w-full text-left text-xs border-collapse border border-slate-300 mb-6">
            <thead>
                <tr class="bg-slate-100 text-slate-900 font-bold border-b border-slate-300">
                    <th class="border border-slate-300 p-2 text-center w-8">No</th>
                    <th class="border border-slate-300 p-2">Tanggal</th>
                    <th class="border border-slate-300 p-2 text-center">Shift</th>
                    <th class="border border-slate-300 p-2">Operator</th>
                    <th class="border border-slate-300 p-2 text-right">Output (kg)</th>
                    @foreach($kategoris as $k)
                        <th class="border border-slate-300 p-2 text-right">{{ $k->nama_kategori }} (kg)</th>
                    @endforeach
                    <th class="border border-slate-300 p-2 text-right">Total Waste (kg)</th>
                    <th class="border border-slate-300 p-2 text-center">Reject Ratio (%)</th>
                </tr>
            </thead>
            <tbody>
                @forelse($laporans as $idx => $item)
                    <tr class="border-b border-slate-200">
                        <td class="border border-slate-300 p-2 text-center">{{ $idx + 1 }}</td>
                        <td class="border border-slate-300 p-2">{{ $item->tanggal->format('d/m/Y') }}</td>
                        <td class="border border-slate-300 p-2 text-center font-semibold">Shift {{ $item->shift }}</td>
                        <td class="border border-slate-300 p-2">{{ $item->user->name ?? '-' }}</td>
                        <td class="border border-slate-300 p-2 text-right font-medium">
                            {{ number_format($item->total_output_kg, 2, ',', '.') }}
                        </td>
                        @foreach($kategoris as $k)
                            <td class="border border-slate-300 p-2 text-right">
                                {{ number_format($item->detailWaste->where('kategori_waste_id', $k->id)->sum('volume_kg'), 2, ',', '.') }}
                            </td>
                        @endforeach
                        <td class="border border-slate-300 p-2 text-right font-bold">
                            {{ number_format($item->total_waste, 2, ',', '.') }}
                        </td>
                        <td class="border border-slate-300 p-2 text-center font-bold">
                            {{ number_format($item->reject_ratio, 2, ',', '.') }}%
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ 6 + $kategoris->count() }}" class="border border-slate-300 p-6 text-center text-slate-400 italic">
                            Tidak ada data laporan yang memenuhi kriteria periode ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr class="bg-slate-100 font-bold border-t-2 border-slate-900">
                    <td colspan="4" class="border border-slate-300 p-2 text-center uppercase tracking-wider">
                        Akumulasi Total
                    </td>
                    <td class="border border-slate-300 p-2 text-right font-extrabold">
                        {{ number_format($summaryOutput, 2, ',', '.') }} kg
                    </td>
                    @foreach($kategoris as $k)
                        @php
                            $sumKat = $laporans->sum(function($item) use ($k) {
                                return $item->detailWaste->where('kategori_waste_id', $k->id)->sum('volume_kg');
                            });
                        @endphp
                        <td class="border border-slate-300 p-2 text-right font-semibold">
                            {{ number_format($sumKat, 2, ',', '.') }}
                        </td>
                    @endforeach
                    <td class="border border-slate-300 p-2 text-right font-extrabold text-slate-900">
                        {{ number_format($summaryWaste, 2, ',', '.') }} kg
                    </td>
                    <td class="border border-slate-300 p-2 text-center font-black text-slate-900">
                        {{ number_format($summaryRatio, 2, ',', '.') }}%
                    </td>
                </tr>
            </tfoot>
        </table>

        <div class="mb-6 overflow-hidden rounded-xl border border-slate-200">
            <div class="border-b border-slate-200 bg-slate-50 p-3">
                <h2 class="text-sm font-bold text-slate-900">Analisis Trading per Lane</h2>
                <p class="mt-1 text-xs text-slate-500">Akumulasi laporan tervalidasi sesuai filter periode.</p>
            </div>
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-100 text-slate-700">
                    <tr>
                        <th class="border border-slate-300 p-2">Lane / Mesin</th>
                        <th class="border border-slate-300 p-2 text-right">Total Trading (kg)</th>
                        <th class="border border-slate-300 p-2 text-center">Jumlah Laporan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($laneAnalytics['lanes'] as $lane)
                        <tr>
                            <td class="border border-slate-300 p-2">{{ $lane['nama'] }}</td>
                            <td class="border border-slate-300 p-2 text-right">{{ number_format($lane['volume'], 2, ',', '.') }}</td>
                            <td class="border border-slate-300 p-2 text-center">{{ $lane['jumlah_laporan'] }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="border border-slate-300 p-3 text-center text-slate-400">Belum ada lane Trading.</td></tr>
                    @endforelse
                    @if($laneAnalytics['unassigned_volume'] > 0)
                        <tr>
                            <td class="border border-slate-300 p-2">Trading belum dirinci (data historis)</td>
                            <td class="border border-slate-300 p-2 text-right">{{ number_format($laneAnalytics['unassigned_volume'], 2, ',', '.') }}</td>
                            <td class="border border-slate-300 p-2 text-center">-</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

        <!-- Ringkasan Statistik -->
        <div class="grid grid-cols-3 gap-4 p-4 rounded-xl bg-slate-50 border border-slate-200 mb-6 text-xs">
            <div>
                <span class="text-slate-500 block">Total Sesi Shift:</span>
                <span class="font-bold text-slate-900">{{ $laporans->count() }} Laporan Tervalidasi</span>
            </div>
            <div>
                <span class="text-slate-500 block">Total Output Benang Jadi:</span>
                <span class="font-bold text-slate-900">{{ number_format($summaryOutput, 2, ',', '.') }} kg</span>
            </div>
            <div>
                <span class="text-slate-500 block">Rata-rata Reject Ratio:</span>
                <span class="font-extrabold text-slate-900">{{ number_format($summaryRatio, 2, ',', '.') }} %</span>
            </div>
        </div>

        <!-- Catatan Standar Mutu Komoditas & Parameter Reject -->
        <div class="mb-8 p-3 rounded-xl border border-slate-300 bg-slate-50/70 text-[11px] leading-relaxed text-slate-700">
            <strong>Catatan Standar Mutu Komoditas &amp; Parameter Reject:</strong>
            Penentuan volume produk cacat (reject) mengacu pada standar baku klasifikasi komoditas pabrik benang DTY. Mutu produk benang DTY kualitas tertinggi (Grade AX) memiliki standardisasi berat mutlak di angka 5.5 Kg per gulungan (bobbin). Penurunan mutu (downgrade) diberlakukan otomatis jika bobot produk akhir berada di bawah batas minimum AX tersebut, diklasifikasikan sebagai Product Reject (Grade B/C), dan total akumulasi beratnya (Kg) dihitung sebagai penambah persentase Reject Ratio.
        </div>

        <!-- Kolom Tanda Tangan -->
        <div class="grid grid-cols-2 gap-8 text-center text-xs pt-4">
            <div>
                <p class="text-slate-500 mb-16">Diverifikasi oleh (Admin Produksi),</p>
                <p class="font-bold text-slate-900 underline uppercase">Siti Admin</p>
                <p class="text-slate-500 text-[11px]">NIP / ID: ADM-DTY-01</p>
            </div>
            <div>
                <p class="text-slate-500 mb-16">Disetujui oleh (Manajer Produksi),</p>
                <p class="font-bold text-slate-900 underline uppercase">Andi Manajer</p>
                <p class="text-slate-500 text-[11px]">NIP / ID: MNJ-DTY-01</p>
            </div>
        </div>

    </div>

</body>
</html>
