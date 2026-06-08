<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Kelas;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Exception;

$kelas = Kelas::where('nama_kelas','Kelas 7A')->first();
if (! $kelas) { echo "CLASS_NOT_FOUND\n"; exit(1); }

for ($i = 1; $i <= 5; $i++) {
    $email = "siswa{$i}@example.com";
    $user = User::where('email', $email)->first();
    if (! $user) {
        $user = User::create([
            'name' => 'Siswa ' . $i,
            'email' => $email,
            'password' => Hash::make('password'),
        ]);
        // assign role if role exists
        try { $user->assignRole('siswa'); } catch (Exception $e) {}
        echo "CREATED_USER: {$email}\n";
    } else {
        echo "USER_EXISTS: {$email}\n";
    }

    // attach to kelas if not attached
    if (! DB::table('kelas_siswa')->where(['kelas_id' => $kelas->id, 'siswa_id' => $user->id])->exists()) {
        $kelas->siswa()->attach($user->id);
        echo "ATTACHED: {$email} -> kelas {$kelas->nama_kelas}\n";
    } else {
        echo "ALREADY_ATTACHED: {$email}\n";
    }
}

echo "DONE\n";
