<?php
// Usage: php scripts/create_admin.php email@example.com password
// Creates role 'admin' (if missing) and creates or updates a user with given email, assigns 'admin' role.

require __DIR__ . '/../vendor/autoload.php';

// bootstrap Laravel
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

if ($argc < 3) {
    echo "Usage: php scripts/create_admin.php email@example.com password\n";
    exit(1);
}

$email = $argv[1];
$password = $argv[2];

use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;

// Create role if not exists
if (!Role::where('name', 'admin')->exists()) {
    Role::create(['name' => 'admin']);
    echo "Role 'admin' created.\n";
} else {
    echo "Role 'admin' already exists.\n";
}

// Create or update user
$user = User::where('email', $email)->first();
if (!$user) {
    $user = User::create([
        'name' => 'Admin',
        'email' => $email,
        'password' => bcrypt($password),
        'remember_token' => Str::random(10),
    ]);
    echo "User created: {$email}\n";
} else {
    $user->password = bcrypt($password);
    $user->save();
    echo "User updated: {$email} (password reset)\n";
}

// Assign role
if (!$user->hasRole('admin')) {
    $user->assignRole('admin');
    echo "Assigned 'admin' role to {$email}\n";
} else {
    echo "User already has 'admin' role.\n";
}

echo "Done. You can now login as admin with the provided credentials.\n";
