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
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class RoleAccessControlTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $supervisor;
    private User $worker;
    private Khs $khs;
    private Kr $kr;
    private TahapPenagihan $tahap;
    private Pk $pk;
    private FotoDokumentasi $foto;

    protected function setUp(): void
    {
        parent::setUp();

        app(PermissionRegistrar::class)->forgetCachedPermissions();
        $this->seed(RoleAndPermissionSeeder::class);
        $this->seed(UserSeeder::class);

        $this->admin = User::where('email', 'admin@diancahayaprima.com')->firstOrFail();
        $this->supervisor = User::where('email', 'spv@diancahayaprima.com')->firstOrFail();
        $this->worker = User::where('email', 'worker@diancahayaprima.com')->firstOrFail();

        $this->khs = Khs::create([
            'nomor_khs' => 'KHS-TEST',
            'judul_kontrak' => 'Kontrak Test',
            'tanggal_mulai' => '2026-01-01',
            'tanggal_selesai' => '2026-12-31',
            'status_kontrak' => 'AKTIF',
        ]);
        $this->kr = Kr::create([
            'khs_id' => $this->khs->id,
            'nomor_kr' => 'KR-TEST',
        ]);
        $this->tahap = TahapPenagihan::create([
            'kr_id' => $this->kr->id,
            'nomor_tahap' => 'Tahap Test',
            'status_penagihan' => 'DRAFT',
        ]);
        $this->pk = Pk::create([
            'tahap_id' => $this->tahap->id,
            'nomor_pk' => 'PK-TEST',
            'nama_pekerjaan' => 'Pekerjaan Test',
            'nilai_pekerjaan' => 1000,
            'status_konstruksi' => 'PROSES',
        ]);
        $this->foto = FotoDokumentasi::create([
            'pk_id' => $this->pk->id,
            'uploader_id' => $this->worker->id,
            'file_path_url' => 'foto_dokumentasi/existing.jpg',
            'status_verifikasi' => 'PENDING',
        ]);
    }

    public function test_seeded_admin_can_log_in_with_the_user_seeder_credentials(): void
    {
        $this->post(route('login'), [
            'email' => 'admin@diancahayaprima.com',
            'password' => 'password123',
        ])->assertRedirect(route('dashboard'));

        $this->assertAuthenticatedAs($this->admin);
    }

    public function test_admin_can_access_every_module_and_all_permissions(): void
    {
        $this->actingAs($this->admin)
            ->get(route('users.index'))
            ->assertOk();

        Storage::fake('public');
        Storage::disk('public')->put($this->foto->file_path_url, 'image content');

        foreach ([
            'manage users', 'manage profile', 'view khs', 'manage khs', 'manage kr',
            'view tahap', 'manage tahap', 'create pk', 'edit pk', 'view pk',
            'view pk details', 'upload foto', 'delete foto', 'export zip',
        ] as $permission) {
            $this->assertTrue($this->admin->can($permission), "Admin lacks {$permission}.");
        }

        $this->get(route('pk.show', $this->pk))
            ->assertOk()
            ->assertSee('Upload Foto Dokumentasi Lapangan')
            ->assertSee('href="'.route('foto.view', $this->foto).'"', false);

        foreach ([
            route('profile.edit'),
            route('users.create'),
            route('khs.create'),
            route('khs.show', $this->khs),
            route('kr.create'),
            route('kr.edit', $this->kr),
            route('tahap.create'),
            route('tahap.edit', $this->tahap),
            route('tahap.show', $this->tahap),
            route('pk.create'),
            route('pk.edit', $this->pk),
            route('pk.show', $this->pk),
        ] as $url) {
            $this->get($url)->assertOk();
        }

        $this->get(route('foto.view', $this->foto))
            ->assertOk()
            ->assertHeader('Content-Disposition', 'inline; filename=existing.jpg');

        $this->post(route('khs.store'), [])->assertSessionHasErrors();
        $this->put(route('khs.update', $this->khs), [])->assertSessionHasErrors();
        $this->post(route('kr.store'), [])->assertSessionHasErrors();
        $this->put(route('kr.update', $this->kr), [])->assertSessionHasErrors();
        $this->post(route('tahap.store'), [])->assertSessionHasErrors();
        $this->put(route('tahap.update', $this->tahap), [])->assertSessionHasErrors();
        $this->post(route('pk.store'), [])->assertSessionHasErrors();
        $this->put(route('pk.update', $this->pk), [])->assertSessionHasErrors();
        $this->put(route('users.update', $this->worker), [])->assertSessionHasErrors();
        $this->delete(route('foto.destroy', $this->foto))->assertRedirect();
        $this->assertDatabaseMissing('foto_dokumentasis', ['id' => $this->foto->id]);
        $this->delete(route('users.destroy', $this->worker))->assertRedirect();
        $this->delete(route('khs.destroy', $this->khs))->assertRedirect();
    }

    public function test_supervisor_can_modify_kr_and_pk_but_cannot_modify_khs(): void
    {
        $this->actingAs($this->supervisor);

        $this->get(route('khs.index'))->assertOk();
        $this->get(route('khs.show', $this->khs))->assertOk();
        $this->get(route('kr.create'))->assertOk();
        $this->get(route('kr.edit', $this->kr))->assertOk();
        $this->get(route('pk.index'))->assertOk();
        $this->get(route('pk.create'))->assertOk();
        $this->get(route('pk.edit', $this->pk))->assertOk();
        $this->get(route('pk.show', $this->pk))->assertOk();

        $this->post(route('kr.store'), [
            'khs_id' => $this->khs->id,
            'nomor_kr' => 'KR-NEW',
        ])->assertRedirect();
        $newKr = Kr::where('nomor_kr', 'KR-NEW')->firstOrFail();
        $this->put(route('kr.update', $newKr), [
            'khs_id' => $this->khs->id,
            'nomor_kr' => 'KR-UPDATED',
        ])->assertRedirect();
        $this->delete(route('kr.destroy', $newKr))->assertRedirect();

        Storage::fake('public');
        $upload = UploadedFile::fake()->createWithContent(
            'supervisor.png',
            base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+/p2sAAAAASUVORK5CYII=')
        );
        $this->post(route('foto.store', $this->pk), ['foto' => $upload])->assertRedirect();
        $uploadedFoto = FotoDokumentasi::where('uploader_id', $this->supervisor->id)->firstOrFail();
        $this->delete(route('foto.destroy', $uploadedFoto))->assertRedirect();

        $this->post(route('pk.store'), [
            'tahap_id' => $this->tahap->id,
            'nomor_pk' => 'PK-NEW',
            'nama_pekerjaan' => 'Pekerjaan Baru',
            'nilai_pekerjaan' => 2000,
            'status_konstruksi' => 'PROSES',
        ])->assertRedirect();
        $newPk = Pk::where('nomor_pk', 'PK-NEW')->firstOrFail();
        $this->put(route('pk.update', $newPk), [
            'tahap_id' => $this->tahap->id,
            'nomor_pk' => 'PK-UPDATED',
            'nama_pekerjaan' => 'Pekerjaan Diperbarui',
            'nilai_pekerjaan' => 3000,
            'status_konstruksi' => 'SELESAI_100',
        ])->assertRedirect();
        $this->delete(route('pk.destroy', $newPk))->assertRedirect();

        $this->post(route('khs.store'), [])->assertForbidden();
        $this->put(route('khs.update', $this->khs), [])->assertForbidden();
        $this->delete(route('khs.destroy', $this->khs))->assertForbidden();
        $this->get(route('khs.edit', $this->khs))->assertForbidden();
        $this->get(route('users.index'))->assertForbidden();
        $this->get(route('tahap.create'))->assertForbidden();
        $this->get(route('tahap.edit', $this->tahap))->assertForbidden();
        $this->post(route('tahap.store'), [])->assertForbidden();
        $this->put(route('tahap.update', $this->tahap), [])->assertForbidden();
        $this->delete(route('tahap.destroy', $this->tahap))->assertForbidden();
        $this->get(route('users.create'))->assertForbidden();
        $this->post(route('users.store'), [])->assertForbidden();
        $this->delete(route('users.destroy', $this->worker))->assertForbidden();
    }

    public function test_worker_can_upload_and_review_photos_without_modifying_other_features(): void
    {
        Storage::fake('public');
        $this->actingAs($this->worker);

        $this->get(route('pk.index'))
            ->assertOk()
            ->assertSee('Unggah Foto')
            ->assertSee('href="' . route('pk.show', $this->pk) . '"', false);

        $upload = UploadedFile::fake()->createWithContent(
            'lapangan.png',
            base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+/p2sAAAAASUVORK5CYII=')
        );
        $this->post(route('foto.store', $this->pk), ['foto' => $upload])->assertRedirect();
        $uploadedFoto = FotoDokumentasi::where('uploader_id', $this->worker->id)->latest('id')->firstOrFail();
        $this->assertDatabaseHas('foto_dokumentasis', [
            'pk_id' => $this->pk->id,
            'uploader_id' => $this->worker->id,
        ]);

        $this->get(route('pk.show', $this->pk))
            ->assertOk()
            ->assertSee('href="'.route('foto.view', $uploadedFoto).'"', false);

        $secondUpload = UploadedFile::fake()->createWithContent(
            'lapangan-2.png',
            base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+/p2sAAAAASUVORK5CYII=')
        );
        $this->from(route('pk.show', $this->pk))
            ->post(route('foto.store', $this->pk), ['foto' => $secondUpload])
            ->assertRedirect(route('pk.show', $this->pk));

        $secondFoto = FotoDokumentasi::where('uploader_id', $this->worker->id)->latest('id')->firstOrFail();
        $this->get(route('foto.view', $secondFoto))->assertOk();
        $this->get(route('pk.show', $this->pk))
            ->assertOk()
            ->assertSee('href="'.route('foto.view', $secondFoto).'"', false)
            ->assertDontSee(route('pk.edit', $this->pk), false)
            ->assertSee('action="'.route('foto.destroy', $secondFoto).'"', false);

        $otherWorker = User::factory()->create();
        $otherFoto = FotoDokumentasi::create([
            'pk_id' => $this->pk->id,
            'uploader_id' => $otherWorker->id,
            'file_path_url' => 'foto_dokumentasi/other-worker.png',
            'status_verifikasi' => 'PENDING',
        ]);

        $this->get(route('pk.show', $this->pk))
            ->assertOk()
            ->assertDontSee('action="'.route('foto.destroy', $otherFoto).'"', false);
        $this->delete(route('foto.destroy', $otherFoto))->assertForbidden();
        $this->assertDatabaseHas('foto_dokumentasis', ['id' => $otherFoto->id]);
        $this->delete(route('foto.destroy', $secondFoto))->assertRedirect();
        $this->assertDatabaseMissing('foto_dokumentasis', ['id' => $secondFoto->id]);

        foreach ([
            route('users.index'),
            route('profile.edit'),
            route('khs.index'),
            route('khs.show', $this->khs),
            route('khs.create'),
            route('khs.edit', $this->khs),
            route('kr.create'),
            route('tahap.index'),
            route('tahap.create'),
            route('tahap.show', $this->tahap),
            route('tahap.edit', $this->tahap),
            route('pk.create'),
            route('pk.edit', $this->pk),
        ] as $url) {
            $this->get($url)->assertForbidden();
        }

        $this->post(route('pk.store'), [])->assertForbidden();
        $this->put(route('pk.update', $this->pk), [])->assertForbidden();
        $this->delete(route('pk.destroy', $this->pk))->assertForbidden();
        $this->post(route('khs.store'), [])->assertForbidden();
        $this->put(route('khs.update', $this->khs), [])->assertForbidden();
        $this->delete(route('khs.destroy', $this->khs))->assertForbidden();
        $this->post(route('kr.store'), [])->assertForbidden();
        $this->put(route('kr.update', $this->kr), [])->assertForbidden();
        $this->delete(route('kr.destroy', $this->kr))->assertForbidden();
        $this->post(route('tahap.store'), [])->assertForbidden();
        $this->put(route('tahap.update', $this->tahap), [])->assertForbidden();
        $this->delete(route('tahap.destroy', $this->tahap))->assertForbidden();
        $this->post(route('users.store'), [])->assertForbidden();
        $this->put(route('users.update', $this->worker), [])->assertForbidden();
        $this->delete(route('users.destroy', $this->worker))->assertForbidden();
        $this->patch(route('profile.update'), [])->assertForbidden();
        $this->delete(route('profile.destroy'), [])->assertForbidden();
        $this->put(route('password.update'), [])->assertForbidden();
        $this->delete(route('foto.destroy', $this->foto))->assertRedirect();
        $this->get(route('tahap.export', $this->tahap))->assertForbidden();
    }
}
