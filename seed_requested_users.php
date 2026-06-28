<?php
// seed_requested_users.php
// Script untuk menginput data Kepala & Guru baru ke database

echo "Menginisialisasi framework Laravel...\n";
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

// Tentukan password seragam untuk semua akun baru
$password = 'Amanah2026';
$hashedPassword = Hash::make($password);

// Daftar user yang akan di-input
$usersData = [
    [
        'name' => 'Uli Nuha, S.KM',
        'email' => 'ulinuha@lms.com',
        'role' => 'admin'
    ],
    [
        'name' => 'Nurul Latifah, S.Pd',
        'email' => 'nurullatifah@lms.com',
        'role' => 'guru'
    ],
    [
        'name' => 'Mei Purnamawati, S.Pd',
        'email' => 'meipurnamawati@lms.com',
        'role' => 'guru'
    ],
    [
        'name' => 'Rosidah Rachmawati, S.Sos',
        'email' => 'rosidah@lms.com',
        'role' => 'guru'
    ],
    [
        'name' => 'Suhirman, S.H.',
        'email' => 'suhirman@lms.com',
        'role' => 'guru'
    ],
    [
        'name' => 'Helar Grinda Daniela, S.Pd.',
        'email' => 'helargrinda@lms.com',
        'role' => 'guru'
    ],
    [
        'name' => 'Nur Hamdawati, S.Pd',
        'email' => 'nurhamdawati@lms.com',
        'role' => 'guru'
    ],
    [
        'name' => 'Ainur Susma Anisa F., S.Pd',
        'email' => 'ainursusma@lms.com',
        'role' => 'guru'
    ],
    [
        'name' => 'Asniar Fajrianti, S.Sos., M.Pd',
        'email' => 'asniar@lms.com',
        'role' => 'guru'
    ],
    [
        'name' => 'Muhammad Haidar, S.Pd',
        'email' => 'mhaidar@lms.com',
        'role' => 'guru'
    ],
    [
        'name' => 'Siti Fatimah, S.Pd',
        'email' => 'sitifatimah@lms.com',
        'role' => 'guru'
    ]
];

echo "Memulai input user ke database...\n";

try {
    foreach ($usersData as $data) {
        // Cek apakah email sudah terdaftar
        $user = User::where('email', $data['email'])->first();
        
        if (!$user) {
            // Buat user baru
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $hashedPassword,
            ]);
            
            // Assign role
            $user->assignRole($data['role']);
            
            echo "Sukses membuat: {$data['name']} ({$data['email']}) -> Role: {$data['role']}\n";
        } else {
            echo "Skip: {$data['email']} sudah terdaftar di database.\n";
        }
    }
    
    echo "=========================================\n";
    echo " INPUT USER SELESAI DENGAN SUKSES! 🎉\n";
    echo "=========================================\n";
} catch (\Exception $e) {
    echo "=========================================\n";
    echo " ERROR: Gagal menginput user!\n";
    echo " Detail: " . $e->getMessage() . "\n";
    echo "=========================================\n";
}
