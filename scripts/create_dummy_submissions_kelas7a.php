<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Kelas;
use App\Models\PengumpulanTugas;
use App\Models\Tugas;
use Illuminate\Support\Carbon;

$kelas = Kelas::where('nama_kelas', 'Kelas 7A')->first();
if (! $kelas) {
    echo "CLASS_NOT_FOUND\n";
    exit(1);
}

$tugasList = Tugas::where('kelas_id', $kelas->id)->get();
if ($tugasList->isEmpty()) {
    echo "NO_TUGAS_IN_CLASS\n";
    exit(1);
}

$siswaList = $kelas->siswa()->get();
if ($siswaList->isEmpty()) {
    echo "NO_STUDENTS_IN_CLASS\n";
    exit(1);
}

foreach ($tugasList as $tugas) {
    foreach ($siswaList as $i => $siswa) {
        $exists = PengumpulanTugas::where(['tugas_id' => $tugas->id, 'siswa_id' => $siswa->id])->first();
        if ($exists) {
            echo "EXISTS: tugas={$tugas->id} siswa={$siswa->email}\n";
            continue;
        }

        PengumpulanTugas::create([
            'tugas_id' => $tugas->id,
            'siswa_id' => $siswa->id,
            'file_jawaban' => "dummy_jawaban_{$siswa->id}.pdf",
            'catatan' => 'Pengumpulan dummy untuk testing',
            'dikumpulkan_at' => Carbon::now(),
            'status' => 'terkumpul',
        ]);

        echo "CREATED_SUBMISSION: tugas={$tugas->id} siswa={$siswa->email}\n";
    }
}

echo "DONE\n";
