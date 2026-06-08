<?php
// Usage: php scripts/seed_users.php
// Creates sample teachers and students with roles 'guru' and 'siswa'.

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;

$roles = ['guru', 'siswa'];
foreach ($roles as $r) {
    if (!Role::where('name', $r)->exists()) {
        Role::create(['name' => $r]);
        echo "Role '$r' created.\n";
    }
}

// Create 3 teachers
for ($i = 1; $i <= 3; $i++) {
    $email = "guru{$i}@lms.com";
    $user = User::firstOrNew(['email' => $email]);
    $user->name = "Guru {$i}";
    $user->password = bcrypt('password');
    $user->remember_token = Str::random(10);
    $user->save();
    $user->assignRole('guru');
    echo "Created/updated teacher: {$email}\n";
}

// Create 12 students
for ($i = 1; $i <= 12; $i++) {
    $email = "siswa{$i}@lms.com";
    $user = User::firstOrNew(['email' => $email]);
    $user->name = "Siswa {$i}";
    $user->password = bcrypt('password');
    $user->remember_token = Str::random(10);
    $user->save();
    $user->assignRole('siswa');
    echo "Created/updated student: {$email}\n";
}

echo "Done. Teachers and students seeded. Default password: password\n";
