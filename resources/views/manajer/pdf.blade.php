<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Rekapitulasi Limbah &amp; Reject DTY</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 24mm 12mm 18mm;
        }

        body {
            color: #172033;
            font-family: "DejaVu Sans", sans-serif;
            font-size: 8pt;
        }

        .document-header {
            border-bottom: 2px solid #0f172a;
            margin-bottom: 14px;
            padding-bottom: 9px;
        }

        .institution {
            color: #475569;
            font-size: 8pt;
            font-weight: bold;
            letter-spacing: 1.4px;
        }

        h1 {
            font-size: 13pt;
            margin: 4px 0;
        }

        .subtitle,
        .period {
            color: #475569;
            font-size: 8pt;
        }

        .title {
            margin: 14px 0 12px;
            text-align: center;
        }

        .title h2 {
            font-size: 12pt;
            margin: 0 0 5px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th,
        td {
            border: 1px solid #cbd5e1;
            padding: 5px 4px;
        }

        thead {
            display: table-header-group;
        }

        th {
            background: #e2e8f0;
            color: #0f172a;
            font-size: 7pt;
            text-align: left;
        }

        td {
            font-size: 7pt;
        }

        .number {
            text-align: right;
            white-space: nowrap;
        }

        .center {
            text-align: center;
        }

        tbody tr {
            page-break-inside: avoid;
        }

        tfoot {
            display: table-row-group;
        }

        tfoot td {
            background: #e2e8f0;
            border-top: 2px solid #0f172a;
            font-weight: bold;
        }

        .empty {
            color: #64748b;
            font-style: italic;
            padding: 18px;
            text-align: center;
        }

        .summary {
            background: #f1f5f9;
            border: 1px solid #cbd5e1;
            margin-top: 14px;
            padding: 9px;
            width: 100%;
        }

        .summary td {
            border: 0;
            font-size: 8pt;
            padding: 3px 8px;
        }

        .summary-label {
            color: #64748b;
        }

        .signature {
            margin-top: 28px;
            width: 100%;
        }

        .signature td {
            border: 0;
            padding: 5px;
            text-align: center;
            width: 50%;
        }

        .signature-space {
            height: 48px;
        }

        .muted {
            color: #64748b;
        }
    </style>
</head>
<body>
    <header class="document-header">
        <div class="institution">POLITEKNIK ENJINERING INDORAMA</div>
        <h1>PROGRAM STUDI TEKNOLOGI REKAYASA PERANGKAT LUNAK</h1>
        <div class="subtitle">Proyek Manufaktur Tekstil - Aplikasi Pengelolaan Limbah dan Product Reject (Studi Kasus: <strong>DTY</strong> (<em>Draw Textured Yarn</em>))</div>
    </header>

    <div class="title">
        <h2>LAPORAN REKAPITULASI LIMBAH &amp; PRODUCT REJECT</h2>
        <div class="period">
            Periode:
            <strong>{{ $startDate ? \Carbon\Carbon::parse($startDate)->format('d/m/Y') : 'Awal' }}</strong>
            s/d
            <strong>{{ $endDate ? \Carbon\Carbon::parse($endDate)->format('d/m/Y') : 'Sekarang' }}</strong>
            @if($shift)
                &bull; Shift: <strong>{{ $shift }}</strong>
            @endif
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th class="center">No</th>
                <th>Tanggal</th>
                <th class="center">Shift</th>
                <th>Operator</th>
                <th class="number">Output (kg)</th>
                <th>Rincian per waste</th>
                <th class="number">Total Waste (kg)</th>
                <th class="center">Reject Ratio (%)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($laporans as $index => $laporan)
                <tr>
                    <td class="center">{{ $index + 1 }}</td>
                    <td>{{ $laporan->tanggal->format('d/m/Y') }}</td>
                    <td class="center">Shift {{ $laporan->shift }}</td>
                    <td>{{ $laporan->user->name ?? '-' }}</td>
                    <td class="number">{{ number_format($laporan->total_output_kg, 2, ',', '.') }}</td>
                    <td>
                        @forelse($laporan->detailWaste->groupBy('kategori_waste_id') as $details)
                            @php
                                $category = $details->first()->kategoriWaste;
                                $categoryTotal = $details->sum('volume_kg');
                            @endphp
                            <strong>{{ $category->nama_kategori ?? 'Limbah' }}: {{ number_format($categoryTotal, 2, ',', '.') }} kg</strong>
                            @if($category?->rincian_per_lane)
                                @foreach($details as $detail)
                                    <div class="muted">{{ $detail->lane->nama_lane ?? 'Belum dirinci' }}: {{ number_format($detail->volume_kg, 2, ',', '.') }} kg</div>
                                @endforeach
                            @endif
                            {{ !$loop->last ? '; ' : '' }}
                        @empty
                            <span class="muted">Belum ada rincian waste.</span>
                        @endforelse
                    </td>
                    <td class="number">{{ number_format($laporan->total_waste, 2, ',', '.') }}</td>
                    <td class="center">{{ number_format($laporan->reject_ratio, 2, ',', '.') }}%</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="empty">
                        Tidak ada data laporan yang memenuhi kriteria periode ini.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <h3>Rekap Waste per Shift</h3>
    <table>
        <thead>
            <tr>
                <th>Waste / lane</th>
                <th class="number">Shift 1 (kg)</th>
                <th class="number">Shift 2 (kg)</th>
                <th class="number">Shift 3 (kg)</th>
                <th class="number">Total (kg)</th>
            </tr>
        </thead>
        <tbody>
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
                <tr>
                    <td>{{ $kategori->nama_kategori }}</td>
                    @foreach([1, 2, 3] as $shiftNumber)
                        <td class="number">{{ number_format($categoryShiftVolumes[$shiftNumber], 2, ',', '.') }}</td>
                    @endforeach
                    <td class="number"><strong>{{ number_format($categoryTotal, 2, ',', '.') }}</strong></td>
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
                            <td class="muted">- {{ $laneName }}</td>
                            @foreach([1, 2, 3] as $shiftNumber)
                                <td class="number muted">{{ number_format($laneShiftVolumes[$shiftNumber], 2, ',', '.') }}</td>
                            @endforeach
                            <td class="number muted">{{ number_format(array_sum($laneShiftVolumes), 2, ',', '.') }}</td>
                        </tr>
                    @endforeach
                @endif
            @endforeach
        </tbody>
    </table>

    <h3>Analisis Trading per Lane</h3>
    <table>
        <thead>
            <tr>
                <th>Lane / Mesin</th>
                <th class="number">Total Trading (kg)</th>
                <th class="center">Jumlah Laporan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($laneAnalytics['lanes'] as $lane)
                <tr>
                    <td>{{ $lane['nama'] }}</td>
                    <td class="number">{{ number_format($lane['volume'], 2, ',', '.') }}</td>
                    <td class="center">{{ $lane['jumlah_laporan'] }}</td>
                </tr>
            @empty
                <tr><td colspan="3" class="empty">Belum ada lane Trading.</td></tr>
            @endforelse
            @if($laneAnalytics['unassigned_volume'] > 0)
                <tr>
                    <td>Trading belum dirinci (data historis)</td>
                    <td class="number">{{ number_format($laneAnalytics['unassigned_volume'], 2, ',', '.') }}</td>
                    <td class="center">-</td>
                </tr>
            @endif
        </tbody>
    </table>

    <table class="summary">
        <tr>
            <td>
                <span class="summary-label">Jumlah sesi:</span><br>
                <strong>{{ $laporans->count() }} Laporan Tervalidasi</strong>
            </td>
            <td>
                <span class="summary-label">Total output benang jadi:</span><br>
                <strong>{{ number_format($summaryOutput, 2, ',', '.') }} kg</strong>
            </td>
            <td>
                <span class="summary-label">Total limbah:</span><br>
                <strong>{{ number_format($summaryWaste, 2, ',', '.') }} kg</strong>
            </td>
            <td>
                <span class="summary-label">Reject ratio:</span><br>
                <strong>{{ number_format($summaryRatio, 2, ',', '.') }}%</strong>
            </td>
        </tr>
    </table>

    <div style="margin: 10px 0 14px; padding: 6px 8px; border: 1px solid #cbd5e1; background: #f8fafc; font-size: 6.5pt; line-height: 1.35; color: #334155;">
        <strong>Catatan Standar Mutu Komoditas &amp; Parameter Reject:</strong>
        Penentuan volume reject mengacu pada baku klasifikasi pabrik benang DTY. Mutu produk kualitas tertinggi (Grade AX) memiliki standardisasi bobot mutlak 5.5 Kg per gulungan (bobbin). Penurunan mutu (downgrade) diberlakukan otomatis jika bobot produk akhir di bawah 5.5 Kg/bobbin dan diklasifikasikan sebagai Product Reject (Grade B/C) yang diakumulasikan sebagai penambah persentase Reject Ratio.
    </div>

    <table class="signature">
        <tr>
            <td>
                <span class="muted">Diverifikasi oleh (Admin Produksi),</span>
                <div class="signature-space"></div>
                <strong><u>Siti Admin</u></strong><br>
                <span class="muted">NIP / ID: ADM-DTY-01</span>
            </td>
            <td>
                <span class="muted">Disetujui oleh (Manajer Produksi),</span>
                <div class="signature-space"></div>
                <strong><u>Andi Manajer</u></strong><br>
                <span class="muted">NIP / ID: MNJ-DTY-01</span>
            </td>
        </tr>
    </table>
</body>
</html>
