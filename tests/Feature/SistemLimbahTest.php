<?php

namespace Tests\Feature;

use App\Models\MasterKategoriWaste;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SistemLimbahTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }
    public function test_login_page_renders_successfully(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('Sistem Pengelolaan Limbah', false);
    }

    public function test_operator_access_and_report_flow(): void
    {
        $operator = User::where('role', 'operator')->first();
        $this->actingAs($operator);

        // Operator dapat melihat riwayat laporan
        $response = $this->get(route('operator.laporan.index'));
        $response->assertStatus(200);
        $response->assertSee('id="themeToggle"', false);
        $response->assertSee('background: #000', false);
        $response->assertSee('workspace-enter', false);
        $response->assertSee('role-nav-track', false);
        $response->assertSee('Laporan produksi Anda', false);
        $response->assertSee('Total laporan', false);
        $response->assertSee('Menunggu validasi', false);
        $response->assertSee('Perlu diperbaiki', false);
        $response->assertSee('Laporan tervalidasi', false);

        // Operator dapat melihat form create
        $createRes = $this->get(route('operator.laporan.create'));
        $createRes->assertStatus(200);
        $createRes->assertSee('Catat hasil produksi shift', false);
        $createRes->assertSee('from-slate-950 via-slate-900 to-sky-950', false);

        // Operator submit laporan baru
        $kategori = MasterKategoriWaste::where('rincian_per_lane', false)->first();
        $trading = MasterKategoriWaste::where('rincian_per_lane', true)->firstOrFail();
        $lane = \App\Models\MasterTradingLane::where('nama_lane', 'Lane A')->firstOrFail();
        $postRes = $this->post(route('operator.laporan.store'), [
            'tanggal' => date('Y-m-d'),
            'shift' => 1,
            'total_output_kg' => 10000,
            'waste' => [
                $kategori->id => 120.5,
            ],
            'lane_waste' => [
                $trading->id => [
                    $lane->id => 35.25,
                ],
            ],
        ]);
        $postRes->assertRedirect(route('operator.laporan.index'));
        $this->assertDatabaseHas('detail_waste_shifts', [
            'kategori_waste_id' => $trading->id,
            'lane_id' => $lane->id,
            'volume_kg' => 35.25,
        ]);
        $submittedReport = \App\Models\LaporanProduksiShift::where('user_id', $operator->id)
            ->where('status_validasi', 'Pending')
            ->latest('id')
            ->firstOrFail();
        $this->get(route('operator.laporan.edit', $submittedReport->id))
            ->assertOk()
            ->assertSee('name="lane_waste[' . $trading->id . '][' . $lane->id . ']"', false)
            ->assertSee('value="35.25"', false);
        $this->get(route('operator.laporan.index'))
            ->assertOk()
            ->assertSee('Trading', false)
            ->assertSee('Lane A', false);

        // Operator dilarang membuka halaman Admin
        $forbiddenRes = $this->get(route('admin.dashboard'));
        $forbiddenRes->assertStatus(403);
    }

    public function test_admin_access_and_validation(): void
    {
        $admin = User::where('role', 'admin')->first();
        $this->actingAs($admin);

        // Admin dashboard
        $res1 = $this->get(route('admin.dashboard'));
        $res1->assertStatus(200);
        $res1->assertSee('id="themeToggle"', false);
        $res1->assertSee('Pusat validasi produksi', false);
        $res1->assertSee('admin-workspace', false);

        // Master Kategori
        $res2 = $this->get(route('admin.kategori.index'));
        $res2->assertStatus(200);
        $res2->assertSee('Master kategori limbah', false);
        $res2->assertSee('from-slate-950 via-slate-900 to-emerald-950', false);

        // Validasi Laporan
        $res3 = $this->get(route('admin.validasi.index'));
        $res3->assertStatus(200);
        $res3->assertSee('id="themeToggle"', false);
        $res3->assertSee('Antrean validasi laporan', false);

        $laporan = \App\Models\LaporanProduksiShift::where('status_validasi', 'Pending')->firstOrFail();
        $detailRes = $this->get(route('admin.validasi.show', $laporan->id));
        $detailRes->assertOk();
        $detailRes->assertSee('id="themeToggle"', false);
        $detailRes->assertSee('Tindakan Keputusan Validasi Admin', false);
        $detailRes->assertSee('Lane A', false);
    }

    public function test_manajer_access_and_analytics(): void
    {
        $manajer = User::where('role', 'manajer')->first();
        $this->actingAs($manajer);

        // Executive Dashboard
        $res1 = $this->get(route('manajer.dashboard'));
        $res1->assertStatus(200);
        $res1->assertSee('id="themeToggle"', false);
        $res1->assertSee('Analitik produksi & efisiensi', false);
        $res1->assertSee('manager-workspace', false);
        $res1->assertSee('<strong>DTY</strong> (<em>Draw Textured Yarn</em>)', false);

        // Laporan Periodik
        $res2 = $this->get(route('manajer.laporan'));
        $res2->assertStatus(200);
        $res2->assertSee('id="themeToggle"', false);
        $res2->assertSee('Laporan produksi periodik', false);
        $res2->assertSee('Analisis Trading per lane', false);
        $res2->assertSee('Rincian laporan per shift', false);
        $res2->assertSee('Rekap waste per shift', false);
        $res2->assertSee('Shift 1 (kg)', false);
        $res2->assertSee('Lane A', false);
        $res2->assertSee('Lane A:', false);

        // Cetak
        $res3 = $this->get(route('manajer.cetak'));
        $res3->assertStatus(200);
        $res3->assertHeader('content-type', 'application/pdf');
        $contentDisposition = strtolower($res3->headers->get('content-disposition'));
        $this->assertStringContainsString('attachment;', $contentDisposition);
        $this->assertStringContainsString('.pdf', $contentDisposition);
        $this->assertStringStartsWith('%PDF-', $res3->getContent());

        $filteredPdf = $this->get(route('manajer.cetak', [
            'start_date' => now()->toDateString(),
            'end_date' => now()->toDateString(),
            'shift' => 1,
        ]));
        $filteredPdf->assertOk()->assertHeader('content-type', 'application/pdf');
        $this->assertStringStartsWith('%PDF-', $filteredPdf->getContent());
    }
}
