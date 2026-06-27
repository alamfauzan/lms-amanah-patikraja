<?php
// import_db.php
// Script untuk impor database lokal ke Railway secara internal

echo "Menginisialisasi framework Laravel...\n";
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$file = __DIR__ . '/lms_database.sql';

if (!file_exists($file)) {
    die("Error: File lms_database.sql tidak ditemukan di root directory!\n");
}

echo "Membaca file database (82 KB)...\n";
$sql = file_get_contents($file);

echo "Memulai proses impor ke database Railway...\n";

try {
    // Nonaktifkan foreign key checks sementara agar tidak error urutan table
    DB::statement('SET FOREIGN_KEY_CHECKS=0;');
    
    // Jalankan seluruh query SQL
    DB::unprepared($sql);
    
    DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    
    echo "=========================================\n";
    echo " KONEKSI & IMPOR DATABASE SUKSES! 🎉\n";
    echo "=========================================\n";
} catch (\Exception $e) {
    echo "=========================================\n";
    echo " ERROR: Gagal mengimpor database!\n";
    echo " Detail: " . $e->getMessage() . "\n";
    echo "=========================================\n";
}
