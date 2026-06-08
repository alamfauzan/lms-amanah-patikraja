<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\KelasMapelGuru;
use App\Models\Pertemuan;
use App\Models\Materi;
use App\Models\Tugas;
use Illuminate\Support\Facades\DB;
use Exception;

$email = 'alam@lms.com';
$u = User::where('email', $email)->first();
if (! $u) {
    echo "USER_NOT_FOUND\n";
    exit(1);
}

DB::beginTransaction();
try {
    // Create or get Mata Pelajaran
    $mapel = MataPelajaran::firstOrCreate(['kode_mapel' => 'MAT01'], ['nama_mapel' => 'Matematika']);

    // Create or get Kelas
    $kelas = Kelas::firstOrCreate(['nama_kelas' => 'Kelas 7A'], ['deskripsi' => 'Kelas 7A IPA', 'wali_kelas_id' => $u->id, 'tahun_ajaran' => '2025/2026']);

    // Link kelas-mapel-guru
    $kmg = KelasMapelGuru::firstOrCreate([
        'kelas_id' => $kelas->id,
        'mata_pelajaran_id' => $mapel->id,
        'guru_id' => $u->id,
    ]);

    // Create Pertemuan
    $pertemuan = Pertemuan::create([
        'kelas_id' => $kelas->id,
        'mata_pelajaran_id' => $mapel->id,
        'judul' => 'Pertemuan 1 - Pengenalan Aljabar',
        'urutan' => 1,
        'deskripsi' => 'Pengenalan konsep aljabar dasar',
        'tanggal' => now()->toDateString(),
    ]);

    // Create Materi
    $materi = Materi::create([
        'pertemuan_id' => $pertemuan->id,
        'kelas_id' => $kelas->id,
        'guru_id' => $u->id,
        'judul' => 'Materi Aljabar Dasar',
        'konten' => '<p>Ini adalah materi aljabar dasar. Contoh: x + 2 = 5</p>',
        'file_path' => null,
        'tipe' => 'html',
    ]);

    // Create Tugas
    $tugas = Tugas::create([
        'kelas_id' => $kelas->id,
        'mata_pelajaran_id' => $mapel->id,
        'guru_id' => $u->id,
        'pertemuan_id' => $pertemuan->id,
        'judul' => 'Tugas 1 - Soal Aljabar',
        'deskripsi' => 'Kerjakan soal aljabar pada halaman 12-13',
        'deadline' => now()->addDays(7),
        'nilai_maksimum' => 100,
        'file_path' => null,
    ]);

    DB::commit();
    echo "CREATED: kelas={$kelas->id}, mapel={$mapel->id}, pertemuan={$pertemuan->id}, materi={$materi->id}, tugas={$tugas->id}\n";
} catch (Exception $e) {
    DB::rollBack();
    echo "ERR: " . $e->getMessage() . PHP_EOL;
}
