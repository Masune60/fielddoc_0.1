<?php

namespace Tests\Feature;

use App\Models\FotoDokumentasi;
use App\Models\Khs;
use App\Models\Kr;
use App\Models\Pk;
use App\Models\TahapPenagihan;
use App\Models\User;
use Database\Seeders\RoleAndPermissionSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $worker;

    protected function setUp(): void
    {
        parent::setUp();

        app(PermissionRegistrar::class)->forgetCachedPermissions();
        $this->seed(RoleAndPermissionSeeder::class);
        $this->seed(UserSeeder::class);

        $this->admin = User::where('email', 'admin@diancahayaprima.com')->firstOrFail();
        $this->worker = User::where('email', 'worker@diancahayaprima.com')->firstOrFail();

        $khs = Khs::create([
            'nomor_khs' => 'KHS-DASHBOARD',
            'judul_kontrak' => 'Kontrak dashboard',
            'tanggal_mulai' => '2026-01-01',
            'tanggal_selesai' => '2026-12-31',
            'status_kontrak' => 'AKTIF',
        ]);
        $kr = Kr::create(['khs_id' => $khs->id, 'nomor_kr' => 'KR-DASHBOARD']);
        $draft = TahapPenagihan::create([
            'kr_id' => $kr->id,
            'nomor_tahap' => 'Tahap Draft',
            'status_penagihan' => 'DRAFT',
        ]);
        $paid = TahapPenagihan::create([
            'kr_id' => $kr->id,
            'nomor_tahap' => 'Tahap Cair',
            'status_penagihan' => 'DICAIRKAN',
        ]);
        $inProgress = Pk::create([
            'tahap_id' => $draft->id,
            'nomor_pk' => 'PK-DASHBOARD-1',
            'nama_pekerjaan' => 'Pekerjaan berlangsung',
            'nilai_pekerjaan' => 15000000,
            'status_konstruksi' => 'PROSES',
        ]);
        Pk::create([
            'tahap_id' => $paid->id,
            'nomor_pk' => 'PK-DASHBOARD-2',
            'nama_pekerjaan' => 'Pekerjaan selesai',
            'nilai_pekerjaan' => 8500000,
            'status_konstruksi' => 'SELESAI_100',
        ]);
        FotoDokumentasi::create([
            'pk_id' => $inProgress->id,
            'uploader_id' => $this->worker->id,
            'file_path_url' => 'foto_dokumentasi/dashboard.jpg',
            'status_verifikasi' => 'PENDING',
        ]);
    }

    public function test_admin_sees_live_dashboard_totals_statuses_and_recent_activity(): void
    {
        $this->actingAs($this->admin)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Total PK / SPK terdaftar')
            ->assertSee('Pekerjaan dalam proses')
            ->assertSee('Pekerjaan selesai 100%')
            ->assertSee('PK-DASHBOARD-1')
            ->assertSee('Tahap Draft')
            ->assertSee('Status penagihan')
            ->assertSee('Menunggu verifikasi');
    }

    public function test_worker_dashboard_only_includes_modules_allowed_by_their_role(): void
    {
        $this->actingAs($this->worker)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('PK-DASHBOARD-1')
            ->assertSee('Status pekerjaan')
            ->assertDontSee('Tahap penagihan')
            ->assertDontSee('Tahap Draft');
    }
}
