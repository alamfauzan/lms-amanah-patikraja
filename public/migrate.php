<?php
// Hapus file ini setelah dijalankan!
define('LARAVEL_START', microtime(true));
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

echo "<pre>";
$kernel->call('migrate', ['--force' => true]);
echo $kernel->output();
echo "\n✅ Migration selesai!\n";

$kernel->call('db:seed', ['--force' => true]);
echo $kernel->output();
echo "\n✅ Seeding selesai!\n";
echo "</pre>";
