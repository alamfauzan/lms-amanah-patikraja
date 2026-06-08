<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Notifikasi;
use Illuminate\Support\Str;

$email = 'alam@lms.com';
$u = User::where('email', $email)->first();
if (! $u) {
    echo "USER_NOT_FOUND\n";
    exit(1);
}

$u->email_verified_at = now();
$u->remember_token = Str::random(60);
$u->save();

echo "UPDATED_USER: {$u->email}\n";

$notes = [
    ['judul' => 'Selamat datang', 'pesan' => 'Selamat bergabung, Alam! Akses dashboard guru sekarang.'],
    ['judul' => 'Tugas Baru', 'pesan' => 'Ada tugas baru untuk kelas yang Anda ampu.'],
    ['judul' => 'Pengumuman', 'pesan' => 'Jadwal rapat guru pada hari Rabu, jam 10:00.'],
];

foreach ($notes as $n) {
    Notifikasi::create([
        'user_id' => $u->id,
        'judul' => $n['judul'],
        'pesan' => $n['pesan'],
        'tipe' => 'info',
        'link' => null,
    ]);
}

echo "CREATED_NOTIFICATIONS\n";
echo "DONE\n";
