<?php

namespace Tests\Feature;

use App\Models\MasterKategoriWaste;
use App\Models\MasterTradingLane;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminKategoriTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Seed users and categories
        $this->seed();
    }

    /** @test */
    public function admin_can_create_category()
    {
        $admin = User::where('role', 'admin')->first();
        $this->actingAs($admin);

        $response = $this->post(route('admin.kategori.store'), [
            'nama_kategori' => 'New Category',
            'deskripsi' => 'Description for new category',
        ]);

        $response->assertRedirect(route('admin.kategori.index'));
        $this->assertDatabaseHas('master_kategori_wastes', [
            'nama_kategori' => 'New Category',
        ]);
    }

    /** @test */
    public function admin_can_update_category()
    {
        $admin = User::where('role', 'admin')->first();
        $this->actingAs($admin);
        $kategori = MasterKategoriWaste::factory()->create();

        $response = $this->put(route('admin.kategori.update', $kategori->id), [
            'nama_kategori' => 'Updated Name',
            'deskripsi' => $kategori->deskripsi,
        ]);

        $response->assertRedirect(route('admin.kategori.index'));
        $this->assertDatabaseHas('master_kategori_wastes', [
            'id' => $kategori->id,
            'nama_kategori' => 'Updated Name',
        ]);
    }

    /** @test */
    public function admin_can_delete_category()
    {
        $admin = User::where('role', 'admin')->first();
        $this->actingAs($admin);
        $kategori = MasterKategoriWaste::factory()->create();

        $response = $this->delete(route('admin.kategori.destroy', $kategori->id));
        $response->assertRedirect(route('admin.kategori.index'));
        $this->assertDatabaseMissing('master_kategori_wastes', [
            'id' => $kategori->id,
        ]);
    }

    /** @test */
    public function category_validation_errors()
    {
        $admin = User::where('role', 'admin')->first();
        $this->actingAs($admin);

        // Missing required field 'nama_kategori'
        $response = $this->post(route('admin.kategori.store'), [
            'deskripsi' => 'No name provided',
        ]);
        $response->assertSessionHasErrors(['nama_kategori']);
    }

    public function test_admin_can_manage_trading_lanes_dynamically(): void
    {
        $this->actingAs(User::where('role', 'admin')->first());

        $this->post(route('admin.lanes.store'), ['nama_lane' => 'Lane D'])
            ->assertRedirect(route('admin.kategori.index'));

        $lane = MasterTradingLane::where('nama_lane', 'Lane D')->firstOrFail();
        $this->assertTrue($lane->status_aktif);

        $this->put(route('admin.lanes.update', $lane->id), [
            'nama_lane' => 'Lane D Utara',
            'status_aktif' => 1,
        ])->assertRedirect(route('admin.kategori.index'));

        $this->assertDatabaseHas('master_trading_lanes', [
            'id' => $lane->id,
            'nama_lane' => 'Lane D Utara',
            'status_aktif' => true,
        ]);

        $this->delete(route('admin.lanes.destroy', $lane->id))
            ->assertRedirect(route('admin.kategori.index'));

        $this->assertDatabaseMissing('master_trading_lanes', ['id' => $lane->id]);
    }

    public function test_lane_with_report_history_is_deactivated_instead_of_deleted(): void
    {
        $this->actingAs(User::where('role', 'admin')->first());
        $lane = MasterTradingLane::where('nama_lane', 'Lane A')->firstOrFail();
        $detailCount = $lane->detailWaste()->count();
        $this->assertGreaterThan(0, $detailCount);

        $this->delete(route('admin.lanes.destroy', $lane->id))
            ->assertRedirect(route('admin.kategori.index'));

        $this->assertDatabaseHas('master_trading_lanes', [
            'id' => $lane->id,
            'status_aktif' => false,
        ]);
        $this->assertSame($detailCount, $lane->detailWaste()->count());
    }
}
