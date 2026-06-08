<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;

$users = User::take(20)->get();
if ($users->isEmpty()) { echo "NO_USERS\n"; exit; }
foreach ($users as $u) {
    echo $u->id.' | '.$u->name.' | '.$u->email.PHP_EOL;
}
