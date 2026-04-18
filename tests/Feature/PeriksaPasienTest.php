<?php

namespace Tests\Feature;

use App\Events\AntrianUpdated;
use App\Models\DaftarPoli;
use App\Models\JadwalPeriksa;
use App\Models\Obat;
use App\Models\Poli;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class PeriksaPasienTest extends TestCase
{
    use RefreshDatabase;

    private function createDokter(Poli $poli): User
    {
        return User::create([
            'nama'     => 'Dokter Uji',
            'email'    => 'dokter@test.com',
            'password' => bcrypt('password'),
            'role'     => 'dokter',
            'id_poli'  => $poli->id,
            'no_ktp'   => '1234567890123456',
            'no_hp'    => '081234567890',
            'alamat'   => 'Jl. Test',
        ]);
    }

    private function createPasien(): User
    {
        return User::create([
            'nama'     => 'Pasien Uji',
            'email'    => 'pasien@test.com',
            'password' => bcrypt('password'),
            'role'     => 'pasien',
            'no_ktp'   => '9876543210987654',
            'no_hp'    => '089876543210',
            'alamat'   => 'Jl. Pasien',
        ]);
    }

    private function seedBaseData(): array
    {
        $poli    = Poli::create(['nama_poli' => 'Umum', 'keterangan' => 'Poli Umum']);
        $dokter  = $this->createDokter($poli);
        $pasien  = $this->createPasien();

        $jadwal  = JadwalPeriksa::create([
            'id_dokter'   => $dokter->id,
            'hari'        => 'Senin',
            'jam_mulai'   => '08:00:00',
            'jam_selesai' => '12:00:00',
        ]);

        $daftar  = DaftarPoli::create([
            'id_pasien'  => $pasien->id,
            'id_jadwal'  => $jadwal->id,
            'keluhan'    => 'Sakit kepala',
            'no_antrian' => 1,
        ]);

        $obat = Obat::create([
            'nama_obat' => 'Paracetamol',
            'kemasan'   => 'Tablet',
            'harga'     => 10000,
            'stok'      => 20,
        ]);

        return compact('poli', 'dokter', 'pasien', 'jadwal', 'daftar', 'obat');
    }

    // -------------------------------------------------------------------------
    // Pasien Dashboard
    // -------------------------------------------------------------------------

    public function test_pasien_dashboard_loads_for_authenticated_pasien(): void
    {
        ['pasien' => $pasien] = $this->seedBaseData();

        $response = $this->actingAs($pasien)->get(route('pasien.dashboard'));

        $response->assertOk();
        $response->assertSeeText('Selamat Datang');
    }

    public function test_pasien_dashboard_shows_active_registration(): void
    {
        ['pasien' => $pasien, 'daftar' => $daftar] = $this->seedBaseData();

        $response = $this->actingAs($pasien)->get(route('pasien.dashboard'));

        $response->assertOk();
        $response->assertSeeText('Antrian Aktif Anda');
        $response->assertSeeText((string) $daftar->no_antrian);
    }

    public function test_pasien_dashboard_redirects_guest(): void
    {
        $response = $this->get(route('pasien.dashboard'));
        $response->assertRedirect(route('login'));
    }

    // -------------------------------------------------------------------------
    // Periksa Pasien Store — broadcast
    // -------------------------------------------------------------------------

    public function test_periksa_pasien_store_broadcasts_antrian_updated_event(): void
    {
        Event::fake([AntrianUpdated::class]);

        ['dokter' => $dokter, 'daftar' => $daftar, 'obat' => $obat] = $this->seedBaseData();

        $response = $this->actingAs($dokter)
            ->post(route('dokter.periksa-pasien.store', $daftar->id), [
                'obat'    => [$obat->id],
                'catatan' => 'Pasien sudah membaik',
            ]);

        $response->assertRedirect(route('dokter.periksa-pasien.index'));
        $response->assertSessionHas('success');

        Event::assertDispatched(AntrianUpdated::class, function ($e) use ($daftar) {
            return $e->jadwalId    === (int) $daftar->id_jadwal
                && $e->nomorAntrian === (int) $daftar->no_antrian;
        });
    }

    public function test_periksa_pasien_store_decrements_obat_stok(): void
    {
        Event::fake([AntrianUpdated::class]);

        ['dokter' => $dokter, 'daftar' => $daftar, 'obat' => $obat] = $this->seedBaseData();
        $stokAwal = $obat->stok;

        $this->actingAs($dokter)
            ->post(route('dokter.periksa-pasien.store', $daftar->id), [
                'obat'    => [$obat->id],
                'catatan' => null,
            ]);

        $this->assertEquals($stokAwal - 1, $obat->fresh()->stok);
    }

    public function test_periksa_pasien_store_creates_periksa_and_pembayaran_records(): void
    {
        Event::fake([AntrianUpdated::class]);

        ['dokter' => $dokter, 'daftar' => $daftar, 'obat' => $obat] = $this->seedBaseData();

        $this->actingAs($dokter)
            ->post(route('dokter.periksa-pasien.store', $daftar->id), [
                'obat' => [$obat->id],
            ]);

        $this->assertDatabaseHas('periksa', ['id_daftar_poli' => $daftar->id]);
        $this->assertDatabaseHas('pembayaran', ['id_pasien' => $daftar->id_pasien, 'status' => 'belum_bayar']);
    }

    public function test_periksa_pasien_store_rejects_duplicate_examination(): void
    {
        Event::fake([AntrianUpdated::class]);

        ['dokter' => $dokter, 'daftar' => $daftar, 'obat' => $obat] = $this->seedBaseData();

        // First examination
        $this->actingAs($dokter)
            ->post(route('dokter.periksa-pasien.store', $daftar->id), [
                'obat' => [$obat->id],
            ]);

        // Second attempt — should be rejected
        $response = $this->actingAs($dokter)
            ->post(route('dokter.periksa-pasien.store', $daftar->id), [
                'obat' => [$obat->id],
            ]);

        $response->assertSessionHas('error');
        $this->assertDatabaseCount('periksa', 1);
    }

    public function test_periksa_pasien_store_rejects_obat_with_zero_stok(): void
    {
        Event::fake([AntrianUpdated::class]);

        ['dokter' => $dokter, 'daftar' => $daftar, 'obat' => $obat] = $this->seedBaseData();
        $obat->update(['stok' => 0]);

        $response = $this->actingAs($dokter)
            ->post(route('dokter.periksa-pasien.store', $daftar->id), [
                'obat' => [$obat->id],
            ]);

        $response->assertSessionHas('error');
        Event::assertNotDispatched(AntrianUpdated::class);
    }

    public function test_periksa_pasien_store_requires_at_least_one_obat(): void
    {
        ['dokter' => $dokter, 'daftar' => $daftar] = $this->seedBaseData();

        $response = $this->actingAs($dokter)
            ->post(route('dokter.periksa-pasien.store', $daftar->id), [
                'obat' => [],
            ]);

        $response->assertSessionHasErrors('obat');
    }

    // -------------------------------------------------------------------------
    // Periksa Pasien Index / Show
    // -------------------------------------------------------------------------

    public function test_dokter_can_view_periksa_pasien_index(): void
    {
        ['dokter' => $dokter] = $this->seedBaseData();

        $response = $this->actingAs($dokter)->get(route('dokter.periksa-pasien.index'));

        $response->assertOk();
    }

    public function test_dokter_can_view_periksa_pasien_show(): void
    {
        ['dokter' => $dokter, 'daftar' => $daftar] = $this->seedBaseData();

        $response = $this->actingAs($dokter)->get(route('dokter.periksa-pasien.show', $daftar->id));

        $response->assertOk();
        $response->assertSeeText('Sakit kepala');
    }

    public function test_pasien_cannot_access_dokter_periksa_route(): void
    {
        ['pasien' => $pasien, 'daftar' => $daftar] = $this->seedBaseData();

        $response = $this->actingAs($pasien)->get(route('dokter.periksa-pasien.index'));

        $response->assertForbidden();
    }
}
