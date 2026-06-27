<?php
// debug_db.php
// Script untuk debugging koneksi dan isi database di server Railway

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

try {
    // 1. Cek database aktif
    $dbNameQuery = DB::select('SELECT DATABASE() as db');
    $dbName = $dbNameQuery[0]->db;
    echo "=========================================\n";
    echo " DATABASE AKTIF DI CONTAINER: " . ($dbName ?: 'TIDAK TERHUBUNG') . "\n";
    echo "=========================================\n";
    
    // 2. Cek daftar tabel
    $tables = DB::select('SHOW TABLES');
    echo "Jumlah tabel: " . count($tables) . "\n";
    foreach ($tables as $table) {
        $arr = (array)$table;
        echo " - " . reset($arr) . "\n";
    }
    echo "=========================================\n";
} catch (\Exception $e) {
    echo "ERROR KONEKSI: " . $e->getMessage() . "\n";
}
