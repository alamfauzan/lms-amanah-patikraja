<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\Jadwal;

$email = 'alam@lms.com';
$u = User::where('email', $email)->first();
if (! $u) { echo "USER_NOT_FOUND\n"; exit(1); }

$kelas = Kelas::where('wali_kelas_id', $u->id)->orWhere('nama_kelas','Kelas 7A')->first();
if (! $kelas) { echo "CLASS_NOT_FOUND\n"; exit(1); }

$mapel = MataPelajaran::where('kode_mapel','MAT01')->first();
if (! $mapel) { $mapel = MataPelajaran::first(); if (! $mapel) { echo "NO_MAPEL\n"; exit(1); } }

$jadwal = Jadwal::create([
    'kelas_id' => $kelas->id,
    'mata_pelajaran_id' => $mapel->id,
    'guru_id' => $u->id,
    'hari' => 1,
    'jam_mulai' => '08:00:00',
    'jam_selesai' => '09:30:00',
    'ruangan' => 'Ruangan 101',
]);

echo "JADWAL_CREATED: {$jadwal->id}\n";
