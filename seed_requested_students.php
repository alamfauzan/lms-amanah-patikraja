<?php
// seed_requested_students.php
// Script untuk menginput data Siswa baru dan mengelompokkannya ke kelas masing-masing

echo "Menginisialisasi framework Laravel...\n";
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

// Tentukan password seragam untuk semua siswa baru
$password = 'Amanah2026';
$hashedPassword = Hash::make($password);

// Hubungkan ke tahun ajaran aktif di database
$activeTahun = DB::table('tahun_ajaran')->where('is_aktif', 1)->value('tahun_ajaran') ?: '2025/2026';
echo "Tahun Ajaran Aktif: {$activeTahun}\n";

// Daftar siswa beserta kelasnya
$studentsData = [
    // --- KELAS XII ---
    ['name' => 'Alfino Nofensa Putra', 'class' => 'Kelas XII'],
    ['name' => 'Choirunisa Ni\'matur Rizqi', 'class' => 'Kelas XII'],
    ['name' => 'Dhimas Dwi Raditya', 'class' => 'Kelas XII'],
    ['name' => 'Dimas Bagus Irawan', 'class' => 'Kelas XII'],
    ['name' => 'Evan Dwi Arsendy', 'class' => 'Kelas XII'],
    ['name' => 'Lutfi Isnaeni', 'class' => 'Kelas XII'],
    ['name' => 'Mhd. Riduan', 'class' => 'Kelas XII'],
    ['name' => 'Muhammad Rifki', 'class' => 'Kelas XII'],
    ['name' => 'Nurul Indah Irawati', 'class' => 'Kelas XII'],
    ['name' => 'Rafly Ardiansyah', 'class' => 'Kelas XII'],
    ['name' => 'Rizki Maulana', 'class' => 'Kelas XII'],
    ['name' => 'Selly Fia Pitujuh Hasanah', 'class' => 'Kelas XII'],
    ['name' => 'Tri Yatini', 'class' => 'Kelas XII'],
    ['name' => 'Vina Nur Vala Nugraha', 'class' => 'Kelas XII'],
    ['name' => 'Yosita Agus Setya Ningrum', 'class' => 'Kelas XII'],
    ['name' => 'Zerlina Islami Ratnaduhita', 'class' => 'Kelas XII'],
    ['name' => 'Soleh Aprianto', 'class' => 'Kelas XII'],

    // --- KELAS XI ---
    ['name' => 'Nurrohim Maulana', 'class' => 'Kelas XI'],
    ['name' => 'Salsabila Maulida Purwanto', 'class' => 'Kelas XI'],
    ['name' => 'Yardan Abdussalam', 'class' => 'Kelas XI'],
    ['name' => 'Arya Nifal Arifin', 'class' => 'Kelas XI'],
    ['name' => 'Andita Oktafia', 'class' => 'Kelas XI'],
    ['name' => 'Anggun Mawarni', 'class' => 'Kelas XI'],
    ['name' => 'Anya Dwi Nuraisah', 'class' => 'Kelas XI'],
    ['name' => 'Ayu Setyaningsih', 'class' => 'Kelas XI'],
    ['name' => 'Dziban Tri Amanulloh', 'class' => 'Kelas XI'],
    ['name' => 'Fahri Aditya Saputra', 'class' => 'Kelas XI'],
    ['name' => 'Hayden Dafa Riwallen', 'class' => 'Kelas XI'],
    ['name' => 'Joni Triyanto', 'class' => 'Kelas XI'],
    ['name' => 'Kiki Amalia', 'class' => 'Kelas XI'],
    ['name' => 'Laila Thoyibatun', 'class' => 'Kelas XI'],
    ['name' => 'Liana Safitri', 'class' => 'Kelas XI'],
    ['name' => 'Nida Fahratul Maula', 'class' => 'Kelas XI'],
    ['name' => 'Novi Aulia Ramadani', 'class' => 'Kelas XI'],

    // --- KELAS X ---
    ['name' => 'Rani Safira', 'class' => 'Kelas X'],
    ['name' => 'Kulmanan', 'class' => 'Kelas X'],
    ['name' => 'Farel Widodo', 'class' => 'Kelas X'],
    ['name' => 'Keysa Larasesjani', 'class' => 'Kelas X'],
    ['name' => 'Nurfhanifah', 'class' => 'Kelas X'],
    ['name' => 'Nuril Fattihur Ramadhan', 'class' => 'Kelas X']
];

// Helper untuk generate email unik dan rapi dari nama
function cleanEmail($name) {
    // Hilangkan karakter selain huruf dan spasi, lalu lowercase
    $clean = strtolower(preg_replace('/[^a-zA-Z\s]/', '', $name));
    $parts = array_filter(explode(' ', $clean));
    
    // Ambil maksimal 2 kata pertama untuk nama email (misal: "alfino.nofensa")
    if (count($parts) >= 2) {
        $email = $parts[0] . $parts[1];
    } else {
        $email = reset($parts);
    }
    
    return $email . '@lms.com';
}

echo "Memverifikasi data kelas di database...\n";

try {
    // 1. Dapatkan atau buat kelas-kelas tersebut
    $classIds = [];
    $classes = ['Kelas X', 'Kelas XI', 'Kelas XII'];
    
    foreach ($classes as $className) {
        $classId = DB::table('kelas')->where('nama_kelas', $className)->value('id');
        if (!$classId) {
            $classId = DB::table('kelas')->insertGetId([
                'nama_kelas' => $className,
                'deskripsi' => 'Kelas ' . $className,
                'wali_kelas_id' => null,
                'tahun_ajaran' => $activeTahun,
                'created_at' => now(),
                'updated_at' => now()
            ]);
            echo "Membuat kelas baru: {$className}\n";
        } else {
            echo "Kelas {$className} sudah ada (ID: {$classId})\n";
        }
        $classIds[$className] = $classId;
    }
    
    echo "\nMemulai input siswa ke database...\n";
    
    foreach ($studentsData as $student) {
        $baseEmail = cleanEmail($student['name']);
        
        // Garansi email unik (tambah angka jika bentrok)
        $email = $baseEmail;
        $counter = 1;
        while (User::where('email', $email)->exists()) {
            $parts = explode('@', $baseEmail);
            $email = $parts[0] . $counter . '@' . $parts[1];
            $counter++;
        }
        
        // Buat user siswa baru
        $user = User::create([
            'name' => $student['name'],
            'email' => $email,
            'password' => $hashedPassword,
        ]);
        
        // Assign role siswa
        $user->assignRole('siswa');
        
        // Daftarkan siswa ke kelasnya
        DB::table('kelas_siswa')->updateOrInsert(
            ['kelas_id' => $classIds[$student['class']], 'siswa_id' => $user->id],
            ['created_at' => now(), 'updated_at' => now()]
        );
        
        echo "Sukses: {$student['name']} ({$email}) -> dimasukkan ke {$student['class']}\n";
    }
    
    echo "=========================================\n";
    echo " INPUT SISWA SELESAI DENGAN SUKSES! 🎉\n";
    echo "=========================================\n";
    
} catch (\Exception $e) {
    echo "=========================================\n";
    echo " ERROR: Gagal menginput siswa!\n";
    echo " Detail: " . $e->getMessage() . "\n";
    echo "=========================================\n";
}
