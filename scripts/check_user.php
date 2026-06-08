<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

$email = 'guru@lms';
$u = User::where('email', $email)->first();
if (! $u) {
    echo "NOT_FOUND\n";
    exit(0);
}

echo "EMAIL:" . $u->email . PHP_EOL;
echo "HASH:" . $u->password . PHP_EOL;
echo "CHECK:" . (Hash::check('password', $u->password) ? 'OK' : 'FAIL') . PHP_EOL;
