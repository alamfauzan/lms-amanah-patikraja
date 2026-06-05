<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\KelasMapelGuru;
use App\Models\User;
use Spatie\Permission\Models\Role;

class ClassAndSubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create Mata Pelajaran
        $matematika = MataPelajaran::create([
            'nama_mapel' => 'Matematika',
            'kode_mapel' => 'MTK-101',
        ]);

        $bInggris = MataPelajaran::create([
            'nama_mapel' => 'Bahasa Inggris',
            'kode_mapel' => 'ING-101',
        ]);

        $sejarah = MataPelajaran::create([
            'nama_mapel' => 'Sejarah Kebudayaan Islam',
            'kode_mapel' => 'SKI-101',
        ]);

        $fisika = MataPelajaran::create([
            'nama_mapel' => 'Fisika Dasar',
            'kode_mapel' => 'FIS-101',
        ]);

        // 2. Fetch or Create Users
        $guruRole = Role::whereName('guru')->first();
        $siswaRole = Role::whereName('siswa')->first();

        $adminUser = User::whereEmail('admin@lms.com')->first();
        $guruUser = User::whereEmail('guru@lms.com')->first();
        $siswaUser = User::whereEmail('siswa@lms.com')->first();

        // Create additional Guru
        $guruSiti = User::create([
            'name' => 'Guru Siti Aminah',
            'email' => 'siti@lms.com',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);
        $guruSiti->assignRole($guruRole);

        $guruBudi = User::create([
            'name' => 'Guru Budi Pratama',
            'email' => 'budi@lms.com',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);
        $guruBudi->assignRole($guruRole);

        // Create additional Siswa
        $siswaBudi = User::create([
            'name' => 'Budi Pratama',
            'email' => 'budi_siswa@lms.com',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);
        $siswaBudi->assignRole($siswaRole);

        $siswaRian = User::create([
            'name' => 'Rian Hidayat',
            'email' => 'rian@lms.com',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);
        $siswaRian->assignRole($siswaRole);

        // 3. Create Kelas
        $kelas7A = Kelas::create([
            'nama_kelas' => 'Kelas VII A',
            'deskripsi' => 'Kelas reguler tingkat VII A',
            'wali_kelas_id' => $guruUser ? $guruUser->id : null,
            'tahun_ajaran' => '2026/2027 Ganjil',
        ]);

        $kelas8B = Kelas::create([
            'nama_kelas' => 'Kelas VIII B',
            'deskripsi' => 'Kelas reguler tingkat VIII B',
            'wali_kelas_id' => null,
            'tahun_ajaran' => '2026/2027 Ganjil',
        ]);

        // 4. Enroll Students to Kelas VII A
        if ($siswaUser) {
            $kelas7A->siswa()->attach($siswaUser->id);
        }
        $kelas7A->siswa()->attach($siswaBudi->id);
        $kelas7A->siswa()->attach($siswaRian->id);

        // 5. Assign Teachers to Subjects in Class
        if ($guruUser) {
            KelasMapelGuru::create([
                'kelas_id' => $kelas7A->id,
                'mata_pelajaran_id' => $matematika->id,
                'guru_id' => $guruUser->id,
            ]);

            KelasMapelGuru::create([
                'kelas_id' => $kelas7A->id,
                'mata_pelajaran_id' => $fisika->id,
                'guru_id' => $guruUser->id,
            ]);
        }

        KelasMapelGuru::create([
            'kelas_id' => $kelas7A->id,
            'mata_pelajaran_id' => $bInggris->id,
            'guru_id' => $guruSiti->id,
        ]);

        KelasMapelGuru::create([
            'kelas_id' => $kelas7A->id,
            'mata_pelajaran_id' => $sejarah->id,
            'guru_id' => $guruBudi->id,
        ]);
    }
}
