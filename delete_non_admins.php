<?php
// delete_non_admins.php
// Script untuk membersihkan semua akun dan menyisakan hanya akun Admin

echo "Menginisialisasi framework Laravel...\n";
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\DB;

echo "Memulai proses pembersihan akun...\n";

try {
    // 1. Cari user Admin utama (admin@lms.com)
    $admin = User::where('email', 'admin@lms.com')->first();
    
    if (!$admin) {
        die("Error: Akun admin@lms.com tidak ditemukan di database!\n");
    }
    
    echo "Akun Admin utama ditemukan: ID {$admin->id} ({$admin->name})\n";
    
    // 2. Nonaktifkan foreign key checks sementara agar proses delete tidak terhambat constraint
    DB::statement('SET FOREIGN_KEY_CHECKS=0;');
    
    // 3. Ambil semua ID user non-admin
    $nonAdminIds = User::where('id', '!=', $admin->id)->pluck('id')->toArray();
    echo "Jumlah akun non-admin yang akan dihapus: " . count($nonAdminIds) . " akun.\n";
    
    if (count($nonAdminIds) > 0) {
        // Hapus role & permission Spatie
        DB::table('model_has_roles')->whereIn('model_id', $nonAdminIds)->delete();
        DB::table('model_has_permissions')->whereIn('model_id', $nonAdminIds)->delete();
        
        // Hapus pendaftaran kelas & jadwal
        DB::table('kelas_siswa')->whereIn('siswa_id', $nonAdminIds)->delete();
        DB::table('kelas_mapel_guru')->whereIn('guru_id', $nonAdminIds)->delete();
        
        // Hapus submission tugas, jawaban kuis, dan notifikasi
        DB::table('pengumpulan_tugas')->whereIn('siswa_id', $nonAdminIds)->delete();
        DB::table('jawaban_siswa')->whereIn('siswa_id', $nonAdminIds)->delete();
        DB::table('hasil_kuis')->whereIn('siswa_id', $nonAdminIds)->delete();
        DB::table('notifikasi')->whereIn('user_id', $nonAdminIds)->delete();
        
        // Set null wali kelas pada tabel kelas (agar kelasnya tidak terhapus)
        DB::table('kelas')->whereIn('wali_kelas_id', $nonAdminIds)->update(['wali_kelas_id' => null]);
        
        // Hapus kuis, materi, tugas yang dibuat oleh guru non-admin
        DB::table('materi')->whereIn('guru_id', $nonAdminIds)->delete();
        DB::table('tugas')->whereIn('guru_id', $nonAdminIds)->delete();
        DB::table('kuis')->whereIn('guru_id', $nonAdminIds)->delete();
        DB::table('jadwal')->whereIn('guru_id', $nonAdminIds)->delete();
        
        // Hapus akun itu sendiri
        DB::table('users')->where('id', '!=', $admin->id)->delete();
        
        echo "Seluruh data relasi dan akun non-admin berhasil dibersihkan!\n";
    } else {
        echo "Tidak ada akun non-admin untuk dihapus.\n";
    }
    
    DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    
    echo "=========================================\n";
    echo " PROSES CLEANUP SUKSES! 🎉\n";
    echo "=========================================\n";
} catch (\Exception $e) {
    DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    echo "=========================================\n";
    echo " ERROR CLEANUP: " . $e->getMessage() . "\n";
    echo "=========================================\n";
}
