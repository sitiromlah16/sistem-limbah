@extends('layouts.app')

@section('title', 'Master Data Kategori Waste - Admin')

@section('content')
<div class="space-y-6">

    <!-- Header Section (Neat & Structured Banner) -->
    <div class="relative overflow-hidden flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-gradient-to-br from-slate-950 via-slate-900 to-emerald-950 p-6 sm:p-7 rounded-2xl shadow-sm text-white">
        <div class="pointer-events-none absolute -right-16 -top-24 h-56 w-56 rounded-full border border-emerald-200/10 bg-emerald-400/10"></div>
        <div class="relative z-10">
            <div class="mb-2 flex items-center gap-2">
                <span class="inline-flex items-center gap-1.5 text-[10px] px-2.5 py-0.5 rounded-full font-bold uppercase tracking-wider bg-emerald-300/10 text-emerald-100 border border-emerald-200/20">
                    <i class="fa-solid fa-tags text-[9px]" aria-hidden="true"></i>
                    Modul F-02 · Master Data
                </span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">Master kategori limbah</h1>
            <p class="mt-1 max-w-2xl text-xs sm:text-sm text-slate-300">Kelola kategori waste dan reject yang digunakan pada laporan shift.</p>
        </div>
        <div class="relative z-10 shrink-0">
            <button type="button" onclick="openAddModal()"
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white hover:bg-emerald-50 text-slate-900 font-semibold text-xs sm:text-sm shadow-sm transition active:scale-[0.98]">
                <i class="fa-solid fa-plus-circle text-xs text-emerald-600"></i>
                <span>Tambah Kategori Baru</span>
            </button>
        </div>
    </div>

    <!-- Standar Mutu Komoditas & Parameter Reject Section -->
    <div class="rounded-2xl border border-emerald-200/90 bg-gradient-to-r from-emerald-50/70 via-white to-sky-50/50 p-5 shadow-xs">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            <div class="flex items-start gap-3.5">
                <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-800 flex items-center justify-center text-lg shrink-0">
                    <i class="fa-solid fa-award"></i>
                </div>
                <div class="space-y-1">
                    <div class="flex items-center gap-2 flex-wrap">
                        <h2 class="text-xs font-bold uppercase tracking-wider text-slate-900">Standar Mutu Komoditas &amp; Parameter Reject Pabrik</h2>
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800 border border-emerald-200">
                            Grade AX = 5.5 Kg / Bobbin
                        </span>
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-amber-100 text-amber-800 border border-amber-200">
                            Downgrade Otomatis &lt; 5.5 Kg
                        </span>
                    </div>
                    <p class="text-xs text-slate-600 leading-relaxed max-w-3xl">
                        Penentuan volume produk cacat (reject) yang diinput ke dalam sistem mengacu pada ketentuan baku klasifikasi komoditas pabrik:
                        Mutu produk benang <strong>DTY</strong> kualitas tertinggi (<strong>Grade AX</strong>) memiliki standardisasi berat mutlak di angka <strong>5.5 Kg per gulungan (bobbin)</strong>. 
                        Aturan bisnis menetapkan bahwa <strong>penurunan mutu (downgrade) diberlakukan otomatis</strong> jika bobot produk akhir berada di bawah batas minimum AX tersebut. Gulungan benang yang mengalami downgrade ini diklasifikasikan sebagai <strong>Product Reject (seperti Grade B atau C)</strong>, yang kemudian total akumulasi beratnya (Kg) diinput oleh Operator ke dalam sistem untuk dikalkulasikan sebagai komponen penambah persentase <strong>Reject Ratio</strong>.
                    </p>
                </div>
            </div>
            <button type="button" onclick="openStandarMutuModal()" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs font-semibold text-emerald-800 bg-white border border-emerald-200 hover:bg-emerald-50 transition shadow-xs shrink-0 self-start lg:self-auto">
                <i class="fa-solid fa-book-open text-emerald-600"></i>
                <span>Panduan Baku Mutu</span>
            </button>
        </div>
    </div>

    <!-- Lane Configuration Grid -->
    <section class="grid grid-cols-1 gap-5 xl:grid-cols-[.85fr_1.15fr]">
        <!-- Form Tambah Lane -->
        <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-xs">
            <div class="mb-4 flex items-center gap-2.5">
                <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-sky-50 text-sky-600 text-xs"><i class="fa-solid fa-road"></i></span>
                <div>
                    <h2 class="text-xs font-bold uppercase tracking-wider text-slate-800">Tambah lane Trading</h2>
                    <p class="text-[11px] text-slate-400">Lane baru langsung tersedia pada laporan operator</p>
                </div>
            </div>
            <form action="{{ route('admin.lanes.store') }}" method="POST" class="flex flex-col gap-2.5 sm:flex-row">
                @csrf
                <label class="sr-only" for="nama_lane">Nama lane</label>
                <input type="text" name="nama_lane" id="nama_lane" required maxlength="100" placeholder="Contoh: Lane D"
                       class="min-w-0 flex-1 rounded-xl border border-slate-200 bg-slate-50/50 px-3.5 py-2 text-xs sm:text-sm text-slate-900 outline-none transition focus:border-sky-500 focus:ring-1 focus:ring-sky-100">
                <button type="submit" class="inline-flex items-center justify-center gap-1.5 rounded-xl bg-sky-600 px-4 py-2 text-xs sm:text-sm font-semibold text-white transition hover:bg-sky-500 active:scale-[0.98]">
                    <i class="fa-solid fa-plus text-xs"></i><span>Tambah lane</span>
                </button>
            </form>
        </div>

        <!-- Daftar Lane -->
        <div class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-xs">
            <div class="border-b border-slate-100 px-5 py-3.5 flex items-center justify-between">
                <div>
                    <h2 class="text-xs font-bold uppercase tracking-wider text-slate-800">Pengaturan lane Trading</h2>
                    <p class="text-[11px] text-slate-400">Lane dengan riwayat laporan dinonaktifkan, bukan dihapus</p>
                </div>
                <span class="text-xs font-medium text-slate-400">{{ count($lanes) }} lane terdaftar</span>
            </div>
            <div class="divide-y divide-slate-100 max-h-56 overflow-y-auto">
                @forelse($lanes as $lane)
                    <div class="flex flex-col gap-2.5 p-3.5 sm:flex-row sm:items-center sm:justify-between text-xs">
                        <form action="{{ route('admin.lanes.update', $lane->id) }}" method="POST" class="flex min-w-0 flex-1 flex-col gap-2 sm:flex-row sm:items-center">
                            @csrf
                            @method('PUT')
                            <label class="sr-only" for="lane_{{ $lane->id }}">Nama {{ $lane->nama_lane }}</label>
                            <input type="text" name="nama_lane" id="lane_{{ $lane->id }}" value="{{ $lane->nama_lane }}" required maxlength="100"
                                   class="min-w-0 flex-1 rounded-lg border border-slate-200 bg-slate-50/50 px-2.5 py-1.5 text-xs text-slate-900 outline-none focus:border-sky-500">
                            <label class="inline-flex items-center gap-1.5 whitespace-nowrap text-xs font-medium text-slate-600">
                                <input type="checkbox" name="status_aktif" value="1" {{ $lane->status_aktif ? 'checked' : '' }}
                                       class="h-3.5 w-3.5 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                                <span>Aktif</span>
                            </label>
                            <button type="submit" class="rounded-lg border border-slate-200 px-2.5 py-1 text-[11px] font-semibold text-slate-600 hover:bg-slate-100 transition">Simpan</button>
                        </form>
                        <div class="flex items-center justify-between gap-2 sm:justify-end shrink-0">
                            <span class="text-[10px] text-slate-400 font-medium">{{ $lane->detail_waste_count }} transaksi</span>
                            <form action="{{ route('admin.lanes.destroy', $lane->id) }}" method="POST"
                                  onsubmit="return confirm('Hapus atau nonaktifkan {{ $lane->nama_lane }}? Data laporan lama tetap terjaga.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" {{ !$lane->status_aktif && $lane->detail_waste_count > 0 ? 'disabled' : '' }}
                                        class="rounded-lg border border-rose-100 px-2.5 py-1 text-[11px] font-semibold text-rose-600 hover:bg-rose-50 transition disabled:cursor-not-allowed disabled:opacity-40">
                                    {{ $lane->detail_waste_count > 0 ? ($lane->status_aktif ? 'Nonaktifkan' : 'Aman') : 'Hapus' }}
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <p class="p-4 text-xs text-slate-400 text-center">Belum ada lane. Tambahkan lane Trading untuk memulai pencatatan.</p>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Table Card Kategori (Neat & Clean) -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="flex flex-col gap-1 border-b border-slate-100 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-sm font-bold text-slate-900">Daftar kategori waste</h2>
                <p class="text-xs text-slate-400 mt-0.5">Kelola nama, deskripsi, dan mode pencatatan per kategori</p>
            </div>
            <span class="text-xs font-medium text-slate-400">{{ $kategoris->total() }} kategori terdaftar</span>
        </div>
        <div class="overflow-x-auto">
            <table class="workspace-table w-full min-w-[850px] text-left text-xs sm:text-sm text-slate-600">
                <thead class="bg-slate-50/70 text-[11px] uppercase font-bold text-slate-400 border-b border-slate-200/70">
                    <tr>
                        <th class="py-3 px-4 w-12 text-center">#</th>
                        <th class="py-3 px-4">Nama Kategori</th>
                        <th class="py-3 px-4">Deskripsi / Keterangan</th>
                        <th class="py-3 px-4 text-center">Pencatatan</th>
                        <th class="py-3 px-4 text-center">Jumlah Laporan</th>
                        <th class="py-3 px-4 text-center">Status</th>
                        <th class="py-3 px-4 text-center w-28">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($kategoris as $index => $kat)
                        <tr class="hover:bg-emerald-50/20 transition">
                            <td class="py-3.5 px-4 text-center font-semibold text-slate-400 text-xs">
                                {{ $kategoris->firstItem() + $index }}
                            </td>
                            <td class="py-3.5 px-4 font-bold text-slate-900 whitespace-nowrap">
                                <i class="fa-solid fa-tag text-emerald-500 mr-1.5 text-xs"></i>
                                {{ $kat->nama_kategori }}
                                @if(\Illuminate\Support\Str::contains(\Illuminate\Support\Str::lower($kat->nama_kategori), 'reject'))
                                    <span class="inline-flex items-center gap-1 rounded-full border border-amber-200 bg-amber-50 px-2 py-0.5 text-[9px] font-bold text-amber-800 ml-1.5">
                                        Downgrade &lt; 5.5 Kg
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-slate-600 text-xs">
                                {{ $kat->deskripsi ?: 'Limbah jenis ' . $kat->nama_kategori }}
                            </td>
                            <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                @if($kat->rincian_per_lane)
                                    <span class="inline-flex items-center gap-1 rounded-full border border-sky-200 bg-sky-50 px-2.5 py-0.5 text-[10px] font-semibold text-sky-700">
                                        <i class="fa-solid fa-road text-[9px]"></i> Per lane
                                    </span>
                                @else
                                    <span class="text-xs text-slate-400">Total kategori</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-center font-semibold text-slate-700 whitespace-nowrap">
                                <span class="px-2 py-0.5 rounded-full text-xs bg-slate-100 border border-slate-200/80">
                                    {{ $kat->detail_waste_count }} transaksi
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                @if($kat->status_aktif)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-150">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span>
                                        <span>Aktif</span>
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-500 border border-slate-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                        <span>Non-Aktif</span>
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                <div class="flex items-center justify-center gap-1.5">
                                    <!-- Edit Button -->
                                    <button type="button" 
                                            onclick="openEditModal({{ $kat->id }}, '{{ addslashes($kat->nama_kategori) }}', '{{ addslashes($kat->deskripsi) }}', {{ $kat->status_aktif ? 'true' : 'false' }}, {{ $kat->rincian_per_lane ? 'true' : 'false' }})"
                                            class="p-1.5 rounded-lg text-sky-600 hover:bg-sky-50 border border-transparent hover:border-sky-200 transition"
                                            title="Edit Kategori">
                                        <i class="fa-solid fa-pen-to-square text-xs"></i>
                                    </button>

                                    <!-- Delete Button -->
                                    <form action="{{ route('admin.kategori.destroy', $kat->id) }}" method="POST" class="inline"
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus / menonaktifkan kategori ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="p-1.5 rounded-lg text-rose-600 hover:bg-rose-50 border border-transparent hover:border-rose-200 transition"
                                                title="Hapus / Nonaktifkan">
                                            <i class="fa-solid fa-trash-can text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-10 text-center">
                                <span class="mx-auto mb-2 flex h-10 w-10 items-center justify-center rounded-xl bg-slate-100 text-slate-400"><i class="fa-solid fa-tags text-base"></i></span>
                                <p class="text-sm font-medium text-slate-600">Belum ada kategori terdaftar.</p>
                                <p class="mt-0.5 text-xs text-slate-400">Tambahkan kategori untuk digunakan pada laporan.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($kategoris->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $kategoris->links() }}
            </div>
        @endif
    </div>

</div>

<!-- Modal Tambah Kategori -->
<div id="addModal" class="fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-xs hidden items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl border border-slate-100">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3 mb-4">
            <h3 class="font-bold text-sm text-slate-900">Tambah Kategori Waste</h3>
            <button type="button" onclick="closeAddModal()" class="text-slate-400 hover:text-slate-600 p-1">
                <i class="fa-solid fa-xmark text-base"></i>
            </button>
        </div>

        <form action="{{ route('admin.kategori.store') }}" method="POST" class="space-y-4 text-xs">
            @csrf
            <div>
                <label for="nama_kategori" class="block font-semibold text-slate-700 uppercase tracking-wider mb-1">
                    Nama Kategori <span class="text-rose-500">*</span>
                </label>
                <input type="text" name="nama_kategori" id="nama_kategori" required
                       placeholder="Misal: Waste Trading, Bobbin Reject"
                       class="w-full px-3 py-2 text-xs sm:text-sm rounded-xl border border-slate-200 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-100 outline-none bg-slate-50/50">
            </div>

            <div>
                <label for="deskripsi" class="block font-semibold text-slate-700 uppercase tracking-wider mb-1">
                    Deskripsi / Keterangan
                </label>
                <textarea name="deskripsi" id="deskripsi" rows="3"
                          placeholder="Penjelasan penyebab limbah atau spesifikasi jenis benang..."
                          class="w-full px-3 py-2 text-xs sm:text-sm rounded-xl border border-slate-200 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-100 outline-none bg-slate-50/50"></textarea>
            </div>

            <div class="flex items-center gap-2 pt-1">
                <input type="checkbox" name="status_aktif" id="status_aktif" value="1" checked
                       class="w-4 h-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                <label for="status_aktif" class="text-slate-700 font-medium">Status Aktif (Bisa diisi oleh Operator)</label>
            </div>
            <div class="flex items-start gap-2 rounded-xl border border-sky-100 bg-sky-50/70 p-3">
                <input type="checkbox" name="rincian_per_lane" id="rincian_per_lane" value="1"
                       class="mt-0.5 h-4 w-4 rounded border-slate-300 text-sky-600 focus:ring-sky-500">
                <label for="rincian_per_lane" class="text-slate-700">
                    <span class="block font-semibold">Catat volume per lane</span>
                    <span class="mt-0.5 block text-slate-500 text-[11px]">Gunakan untuk Trading agar operator mengisi berat setiap lane.</span>
                </label>
            </div>

            <div class="flex justify-end gap-2 pt-3 border-t border-slate-100">
                <button type="button" onclick="closeAddModal()" 
                        class="px-3.5 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-100 rounded-xl transition">
                    Batal
                </button>
                <button type="submit" 
                        class="px-4 py-2 text-xs font-semibold text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl shadow-xs transition">
                    Simpan Kategori
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Kategori -->
<div id="editModal" class="fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-xs hidden items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl border border-slate-100">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3 mb-4">
            <h3 class="font-bold text-sm text-slate-900">Edit Kategori Waste</h3>
            <button type="button" onclick="closeEditModal()" class="text-slate-400 hover:text-slate-600 p-1">
                <i class="fa-solid fa-xmark text-base"></i>
            </button>
        </div>

        <form id="editForm" method="POST" class="space-y-4 text-xs">
            @csrf
            @method('PUT')
            <div>
                <label for="edit_nama" class="block font-semibold text-slate-700 uppercase tracking-wider mb-1">
                    Nama Kategori <span class="text-rose-500">*</span>
                </label>
                <input type="text" name="nama_kategori" id="edit_nama" required
                       class="w-full px-3 py-2 text-xs sm:text-sm rounded-xl border border-slate-200 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-100 outline-none bg-slate-50/50">
            </div>

            <div>
                <label for="edit_deskripsi" class="block font-semibold text-slate-700 uppercase tracking-wider mb-1">
                    Deskripsi / Keterangan
                </label>
                <textarea name="deskripsi" id="edit_deskripsi" rows="3"
                          class="w-full px-3 py-2 text-xs sm:text-sm rounded-xl border border-slate-200 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-100 outline-none bg-slate-50/50"></textarea>
            </div>

            <div class="flex items-center gap-2 pt-1">
                <input type="checkbox" name="status_aktif" id="edit_status" value="1"
                       class="w-4 h-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                <label for="edit_status" class="text-slate-700 font-medium">Status Aktif (Tampilkan di form operator)</label>
            </div>
            <div class="flex items-start gap-2 rounded-xl border border-sky-100 bg-sky-50/70 p-3">
                <input type="checkbox" name="rincian_per_lane" id="edit_rincian_per_lane" value="1"
                       class="mt-0.5 h-4 w-4 rounded border-slate-300 text-sky-600 focus:ring-sky-500">
                <label for="edit_rincian_per_lane" class="text-slate-700">
                    <span class="block font-semibold">Catat volume per lane</span>
                    <span class="mt-0.5 block text-slate-500 text-[11px]">Aktifkan untuk Trading atau kategori yang memiliki lane/mesin.</span>
                </label>
            </div>

            <div class="flex justify-end gap-2 pt-3 border-t border-slate-100">
                <button type="button" onclick="closeEditModal()" 
                        class="px-3.5 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-100 rounded-xl transition">
                    Batal
                </button>
                <button type="submit" 
                        class="px-4 py-2 text-xs font-semibold text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl shadow-xs transition">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@section('scripts')
<script>
    function openAddModal() {
        const modal = document.getElementById('addModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
    function closeAddModal() {
        const modal = document.getElementById('addModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
    function openEditModal(id, nama, deskripsi, aktif, rincianPerLane) {
        const modal = document.getElementById('editModal');
        const form = document.getElementById('editForm');
        form.action = `/admin/kategori/${id}`;
        document.getElementById('edit_nama').value = nama;
        document.getElementById('edit_deskripsi').value = deskripsi;
        document.getElementById('edit_status').checked = aktif;
        document.getElementById('edit_rincian_per_lane').checked = rincianPerLane;
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
    function closeEditModal() {
        const modal = document.getElementById('editModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
</script>
@endsection
