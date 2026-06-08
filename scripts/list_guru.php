<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;

$gurus = User::role('guru')->take(10)->get();
if ($gurus->isEmpty()) {
    echo "NO_GURU_USERS\n";
    exit(0);
}
foreach ($gurus as $u) {
    echo $u->id . ' | ' . $u->email . PHP_EOL;
}
