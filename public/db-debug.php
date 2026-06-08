<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

header('Content-Type: text/plain; charset=utf-8');

// Show which DB we're connected to
try {
    echo "=== DATABASE INFO ===\n";
    echo "Host: " . config('database.connections.mysql.host') . "\n";
    echo "Port: " . config('database.connections.mysql.port') . "\n";
    echo "DB Name: " . config('database.connections.mysql.database') . "\n\n";
} catch (\Exception $e) {
    echo "Error getting DB config: " . $e->getMessage() . "\n";
}

// Show all users
try {
    echo "=== USERS IN DATABASE ===\n";
    $users = \DB::table('users')->get(['id', 'name', 'email']);
    if ($users->isEmpty()) {
        echo "No users found! Database might be empty.\n";
    }
    foreach ($users as $u) {
        $roles = \DB::table('model_has_roles')
            ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
            ->where('model_id', $u->id)
            ->pluck('name')->join(', ');
        echo "ID:{$u->id} | {$u->email} | Roles: {$roles}\n";
    }
} catch (\Exception $e) {
    echo "Error reading users: " . $e->getMessage() . "\n";
}

// Reset or create guru@lms.com
if (isset($_GET['fix'])) {
    echo "\n=== FIXING CREDENTIALS ===\n";
    try {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        
        // Ensure roles exist
        $roles = ['admin', 'guru', 'siswa'];
        foreach ($roles as $roleName) {
            \Spatie\Permission\Models\Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
        }

        $accounts = [
            ['name' => 'Admin User',    'email' => 'admin@lms.com',  'role' => 'admin'],
            ['name' => 'Guru User',     'email' => 'guru@lms.com',   'role' => 'guru'],
            ['name' => 'Siswa User',    'email' => 'siswa@lms.com',  'role' => 'siswa'],
        ];

        foreach ($accounts as $acc) {
            $user = \App\Models\User::updateOrCreate(
                ['email' => $acc['email']],
                [
                    'name'              => $acc['name'],
                    'password'          => \Illuminate\Support\Facades\Hash::make('password'),
                    'email_verified_at' => now(),
                ]
            );
            $user->syncRoles([$acc['role']]);
            echo "✓ {$acc['email']} (role: {$acc['role']}) → password reset to 'password'\n";
        }

        echo "\nDone! Try logging in again:\n";
        echo "  guru@lms.com / password\n";
        echo "  admin@lms.com / password\n";
        echo "  siswa@lms.com / password\n";
    } catch (\Exception $e) {
        echo "Error: " . $e->getMessage() . "\n";
        echo $e->getTraceAsString() . "\n";
    }
} else {
    echo "\n>>> Append ?fix=1 to this URL to reset all credentials.\n";
}
