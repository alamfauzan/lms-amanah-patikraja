<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Jadwal;

$email = 'alam@lms.com';
$u = User::where('email', $email)->first();
if (! $u) { echo "USER_NOT_FOUND\n"; exit(1); }

$jadwals = Jadwal::where('guru_id', $u->id)->get();
if ($jadwals->isEmpty()) {
    echo "NO_JADWAL_FOR_USER\n";
    exit(0);
}

foreach ($jadwals as $j) {
    echo "ID: {$j->id} | Kelas: {$j->kelas_id} | Mapel: {$j->mata_pelajaran_id} | Hari: {$j->hari} | Jam: {$j->jam_mulai} - {$j->jam_selesai} | Ruangan: {$j->ruangan}" . PHP_EOL;
}
