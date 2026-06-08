<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

$roleName = 'guru';
$email = 'alam@lms.com';
$password = 'alam123';

try {
    $role = Role::firstOrCreate(['name' => $roleName]);
    echo "ROLE_OK: {$role->name}\n";
} catch (Exception $e) {
    echo "ROLE_ERR: " . $e->getMessage() . PHP_EOL;
}

$user = User::where('email', $email)->first();
if ($user) {
    echo "USER_EXISTS: {$user->email}\n";
    $user->password = Hash::make($password);
    $user->save();
    echo "PASSWORD_RESET\n";
} else {
    $user = User::create([
        'name' => 'Alam',
        'email' => $email,
        'password' => Hash::make($password),
    ]);
    echo "USER_CREATED: {$user->email}\n";
}

try {
    $user->assignRole($roleName);
    echo "ROLE_ASSIGNED\n";
} catch (Exception $e) {
    echo "ASSIGN_ERR: " . $e->getMessage() . PHP_EOL;
}

echo "DONE\n";
