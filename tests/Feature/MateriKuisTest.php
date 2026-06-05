<?php

namespace Tests\Feature;

use App\Models\HasilKuis;
use App\Models\JawabanSiswa;
use App\Models\Kelas;
use App\Models\KelasMapelGuru;
use App\Models\Kuis;
use App\Models\MataPelajaran;
use App\Models\Materi;
use App\Models\Pertemuan;
use App\Models\SoalKuis;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class MateriKuisTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $guru;
    protected $siswa;
    protected $kelas;
    protected $mapel;
    protected $pertemuan;

    protected function setUp(): void
    {
        parent::setUp();

        // 1. Create Roles
        $adminRole = Role::create(['name' => 'admin']);
        $guruRole = Role::create(['name' => 'guru']);
        $siswaRole = Role::create(['name' => 'siswa']);

        // 2. Create Users
        $this->admin = User::factory()->create(['name' => 'Admin Test']);
        $this->admin->assignRole($adminRole);

        $this->guru = User::factory()->create(['name' => 'Guru Test']);
        $this->guru->assignRole($guruRole);

        $this->siswa = User::factory()->create(['name' => 'Siswa Test']);
        $this->siswa->assignRole($siswaRole);

        // 3. Create Kelas, Mapel, and Pivot
        $this->kelas = Kelas::create([
            'nama_kelas' => 'Kelas VII Test',
            'deskripsi' => 'Deskripsi Kelas Test',
            'wali_kelas_id' => $this->guru->id,
            'tahun_ajaran' => '2026/2027',
        ]);
        $this->kelas->siswa()->attach($this->siswa->id);

        $this->mapel = MataPelajaran::create([
            'nama_mapel' => 'Matematika Test',
            'kode_mapel' => 'MTK-TEST',
        ]);

        KelasMapelGuru::create([
            'kelas_id' => $this->kelas->id,
            'mata_pelajaran_id' => $this->mapel->id,
            'guru_id' => $this->guru->id,
        ]);

        // 4. Create Pertemuan
        $this->pertemuan = Pertemuan::create([
            'kelas_id' => $this->kelas->id,
            'judul' => 'Pertemuan 1 Aljabar',
            'urutan' => 1,
            'deskripsi' => 'Belajar aljabar dasar',
            'tanggal' => now(),
        ]);
    }

    public function test_materi_workflow()
    {
        // 1. Create Materi as Guru
        $response = $this->actingAs($this->guru)
            ->post(route('kelas.pertemuan.materi.store', [$this->kelas->id, $this->pertemuan->id]), [
                'judul' => 'Pengenalan Aljabar',
                'tipe' => 'teks',
                'konten' => 'Aljabar adalah ilmu matematika tentang huruf dan simbol.',
            ]);

        $response->assertRedirect(route('kelas.pertemuan.show', [$this->kelas->id, $this->pertemuan->id]));
        $this->assertDatabaseHas('materi', [
            'judul' => 'Pengenalan Aljabar',
            'tipe' => 'teks',
        ]);

        $materi = Materi::first();

        // 2. View Materi as Siswa
        $response = $this->actingAs($this->siswa)
            ->get(route('materi.show', $materi->id));

        $response->assertStatus(200);
        $response->assertSee('Pengenalan Aljabar');
        $response->assertSee('Aljabar adalah ilmu matematika');
    }

    public function test_kuis_workflow()
    {
        // 1. Create Kuis as Guru
        $response = $this->actingAs($this->guru)
            ->post(route('kelas.kuis.store', $this->kelas->id), [
                'judul' => 'Kuis Aljabar Dasar',
                'deskripsi' => 'Kerjakan dengan teliti',
                'durasi_menit' => 10,
                'batas_pengerjaan' => 2,
                'bobot_nilai' => 100,
                'pertemuan_id' => $this->pertemuan->id,
                'is_aktif' => 1,
                'soal' => [
                    [
                        'pertanyaan' => '2 + 3 = ?',
                        'tipe' => 'pilihan_ganda',
                        'pilihan_jawaban' => [
                            'a' => '4',
                            'b' => '5',
                            'c' => '6',
                            'd' => '7',
                        ],
                        'kunci_jawaban' => 'b',
                        'poin' => 10,
                    ],
                    [
                        'pertanyaan' => 'Matematika itu menyenangkan.',
                        'tipe' => 'benar_salah',
                        'kunci_jawaban' => 'benar',
                        'poin' => 10,
                    ],
                    [
                        'pertanyaan' => 'Ibukota Indonesia adalah...',
                        'tipe' => 'isian_singkat',
                        'kunci_jawaban' => 'Jakarta',
                        'poin' => 10,
                    ]
                ]
            ]);

        $response->assertRedirect(route('kelas.kuis.index', $this->kelas->id));
        $this->assertDatabaseHas('kuis', [
            'judul' => 'Kuis Aljabar Dasar',
            'jumlah_soal' => 3,
        ]);

        $kuis = Kuis::first();
        $this->assertEquals(3, SoalKuis::where('kuis_id', $kuis->id)->count());

        // 2. View Kuis detail as Siswa
        $response = $this->actingAs($this->siswa)
            ->get(route('kuis.show', [$this->kelas->id, $kuis->id]));
        $response->assertStatus(200);
        $response->assertSee('Mulai Kuis');

        // 3. Start Kuis (Kerjakan) as Siswa
        $response = $this->actingAs($this->siswa)
            ->get(route('kuis.kerjakan', [$this->kelas->id, $kuis->id]));
        $response->assertStatus(200);
        $response->assertSee('Selesaikan Kuis');

        $this->assertDatabaseHas('hasil_kuis', [
            'kuis_id' => $kuis->id,
            'siswa_id' => $this->siswa->id,
            'attempt' => 1,
            'is_submitted' => false,
        ]);

        $attempt = HasilKuis::first();

        // 4. Answer questions (AJAX) as Siswa
        $soalPG = SoalKuis::where('tipe', 'pilihan_ganda')->first();
        $soalBS = SoalKuis::where('tipe', 'benar_salah')->first();
        $soalIS = SoalKuis::where('tipe', 'isian_singkat')->first();

        // Answer Question 1 (PG) - Correct
        $response = $this->actingAs($this->siswa)
            ->json('POST', route('kuis.jawab', [$this->kelas->id, $kuis->id]), [
                'soal_id' => $soalPG->id,
                'jawaban' => 'b',
                'attempt' => $attempt->attempt,
            ]);
        $response->assertJson(['saved' => true]);

        // Answer Question 2 (BS) - Correct
        $response = $this->actingAs($this->siswa)
            ->json('POST', route('kuis.jawab', [$this->kelas->id, $kuis->id]), [
                'soal_id' => $soalBS->id,
                'jawaban' => 'benar',
                'attempt' => $attempt->attempt,
            ]);
        $response->assertJson(['saved' => true]);

        // Answer Question 3 (IS) - Correct (case-insensitive check handled in controller submit)
        $response = $this->actingAs($this->siswa)
            ->json('POST', route('kuis.jawab', [$this->kelas->id, $kuis->id]), [
                'soal_id' => $soalIS->id,
                'jawaban' => 'jakarta',
                'attempt' => $attempt->attempt,
            ]);
        $response->assertJson(['saved' => true]);

        // 5. Submit Kuis as Siswa
        $response = $this->actingAs($this->siswa)
            ->post(route('kuis.submit', [$this->kelas->id, $kuis->id]), [
                'attempt' => $attempt->attempt,
            ]);

        $response->assertRedirect(route('kuis.hasil', [$this->kelas->id, $kuis->id]));

        // Refresh and check attempt status
        $attempt->refresh();
        $this->assertTrue($attempt->is_submitted);
        
        // Short Answer is scored on submit. Let's see:
        // total score = 30, correct = 3, score = 100.
        $this->assertEquals(100.0, (float)$attempt->nilai_akhir);
        $this->assertEquals(3, $attempt->benar);
        $this->assertEquals(0, $attempt->salah);

        // 6. View Results as Siswa (Review Scorecard)
        $response = $this->actingAs($this->siswa)
            ->get(route('kuis.hasil', [$this->kelas->id, $kuis->id]));
        $response->assertStatus(200);
        $response->assertSee('Review Hasil Kuis');
        $response->assertSee('Nilai Akhir');
        $response->assertSee('100');

        // 7. View Results as Guru
        $response = $this->actingAs($this->guru)
            ->get(route('kuis.show', [$this->kelas->id, $kuis->id]));
        $response->assertStatus(200);
        $response->assertSee('Hasil Percobaan Siswa');
        $response->assertSee('Siswa Test');
        $response->assertSee('100');
    }

    public function test_guru_dashboard()
    {
        $hariIni = now()->dayOfWeekIso;
        \App\Models\Jadwal::create([
            'kelas_id' => $this->kelas->id,
            'mata_pelajaran_id' => $this->mapel->id,
            'guru_id' => $this->guru->id,
            'hari' => $hariIni,
            'jam_mulai' => '08:00',
            'jam_selesai' => '09:30',
            'ruangan' => 'Kelas A',
        ]);

        $response = $this->actingAs($this->guru)
            ->get(route('dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Dashboard Pengajar');
        $response->assertSee('Kelas Diajar');
        $response->assertSee('Total Siswa');
        $response->assertSee('Tugas Aktif');
        $response->assertSee('Kuis Aktif');
        $response->assertSee('Jadwal Mengajar Hari Ini');
        $response->assertSee('Matematika Test');
    }
}
