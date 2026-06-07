<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Kelas;
use App\Models\Pertemuan;
use App\Models\Kuis;
use App\Models\SoalKuis;
use Carbon\Carbon;

class KuisUjiCobaSeeder extends Seeder
{
    public function run(): void
    {
        $guruUser = User::role('guru')->first();
        $kelas7A = Kelas::where('nama_kelas', 'Kelas VII A')->first();
        
        if (!$kelas7A || !$guruUser) {
            return;
        }

        $pertemuan2 = Pertemuan::where('kelas_id', $kelas7A->id)->where('urutan', 2)->first();
        $pertemuanId = $pertemuan2 ? $pertemuan2->id : null;

        $matematika = \App\Models\MataPelajaran::where('nama_mapel', 'Matematika')->first();
        $matematikaId = $matematika ? $matematika->id : null;

        // Create the new Quiz with all 3 question types (untaken by students)
        $kuis = Kuis::create([
            'kelas_id' => $kelas7A->id,
            'mata_pelajaran_id' => $matematikaId,
            'guru_id' => $guruUser->id,
            'pertemuan_id' => $pertemuanId,
            'judul' => 'Kuis Uji Coba: Tiga Tipe Soal',
            'deskripsi' => 'Kuis simulasi ini berisi tiga tipe soal sekaligus: Pilihan Ganda, Benar/Salah, dan Isian Singkat.',
            'durasi_menit' => 30,
            'jumlah_soal' => 3,
            'batas_pengerjaan' => 1,
            'bobot_nilai' => 10,
            'mulai_at' => Carbon::now()->subDays(1),
            'selesai_at' => Carbon::now()->addDays(5),
            'is_aktif' => true,
        ]);

        // 1. Soal Pilihan Ganda (Multiple Choice - abc)
        SoalKuis::create([
            'kuis_id' => $kuis->id,
            'pertanyaan' => 'Jika x + 3 = 10, berapakah nilai x?',
            'tipe' => 'pilihan_ganda',
            'pilihan_jawaban' => [
                'a' => '5',
                'b' => '7',
                'c' => '9',
                'd' => '12'
            ],
            'kunci_jawaban' => 'b',
            'poin' => 30,
            'urutan' => 1,
        ]);

        // 2. Soal Benar / Salah (True / False)
        SoalKuis::create([
            'kuis_id' => $kuis->id,
            'pertanyaan' => 'Persamaan linear satu variabel selalu memiliki tepat satu solusi real.',
            'tipe' => 'benar_salah',
            'pilihan_jawaban' => [
                'benar' => 'Benar',
                'salah' => 'Salah'
            ],
            'kunci_jawaban' => 'benar',
            'poin' => 30,
            'urutan' => 2,
        ]);

        // 3. Soal Isian Singkat (Short Answer / Tanya Jawab)
        SoalKuis::create([
            'kuis_id' => $kuis->id,
            'pertanyaan' => 'Siapakah tokoh ilmuwan muslim yang dikenal sebagai penemu aljabar? (Tulis nama terkenalnya)',
            'tipe' => 'isian_singkat',
            'pilihan_jawaban' => null,
            'kunci_jawaban' => 'al-khwarizmi',
            'poin' => 40,
            'urutan' => 3,
        ]);
    }
}
