<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Spatie\Permission\Models\Role;

$role = Role::firstOrCreate(['name' => 'siswa', 'guard_name' => 'web']);

$emails = [];
for ($i = 1; $i <= 5; $i++) $emails[] = "siswa{$i}@example.com";

foreach ($emails as $email) {
    $user = User::where('email', $email)->first();
    if (! $user) { echo "NOT_FOUND: {$email}\n"; continue; }
    $user->syncRoles(['siswa']);
    echo "SYNCED_ROLES: {$email} -> siswa\n";
}

echo "DONE\n";
