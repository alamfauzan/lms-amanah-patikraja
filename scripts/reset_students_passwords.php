<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

for ($i = 1; $i <= 5; $i++) {
    $email = "siswa{$i}@example.com";
    $user = User::where('email', $email)->first();
    if ($user) {
        $user->password = Hash::make('password');
        $user->save();
        echo "RESET: {$email}\n";
    } else {
        echo "NOT_FOUND: {$email}\n";
    }
}

echo "DONE\n";
