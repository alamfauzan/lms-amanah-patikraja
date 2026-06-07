<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use App\Models\Pertemuan;
use App\Models\Materi;
use App\Models\Tugas;
use App\Models\PengumpulanTugas;
use App\Models\Kuis;
use App\Models\SoalKuis;
use App\Models\HasilKuis;
use Carbon\Carbon;

class LmsDummyDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed Tahun Ajaran
        TahunAjaran::updateOrCreate(
            ['tahun_ajaran' => '2025/2026 Genap'],
            ['is_aktif' => false]
        );
        TahunAjaran::updateOrCreate(
            ['tahun_ajaran' => '2026/2027 Ganjil'],
            ['is_aktif' => true]
        );

        // 2. Fetch required users
        $guruUser = User::whereEmail('guru@lms.com')->first();
        if (!$guruUser) {
            $guruUser = User::role('guru')->first();
        }
        
        $siswaUser = User::whereEmail('siswa@lms.com')->first();
        $siswaBudi = User::whereEmail('budi_siswa@lms.com')->first();
        $siswaRian = User::whereEmail('rian@lms.com')->first();

        // 3. Fetch kelas
        $kelas7A = Kelas::where('nama_kelas', 'Kelas VII A')->first();
        if (!$kelas7A) {
            return;
        }
        $matematika = \App\Models\MataPelajaran::where('nama_mapel', 'Matematika')->first();
        $matematikaId = $matematika ? $matematika->id : null;

        // 4. Create Pertemuan
        $pertemuan1 = Pertemuan::create([
            'kelas_id' => $kelas7A->id,
            'mata_pelajaran_id' => $matematikaId,
            'judul' => 'Pengenalan & Aljabar Dasar',
            'urutan' => 1,
            'deskripsi' => 'Pertemuan pertama membahas konsep aljabar dasar, variabel, dan konstanta.',
            'tanggal' => Carbon::now()->subDays(4),
        ]);

        $pertemuan2 = Pertemuan::create([
            'kelas_id' => $kelas7A->id,
            'mata_pelajaran_id' => $matematikaId,
            'judul' => 'Fungsi Kuadrat & Persamaan',
            'urutan' => 2,
            'deskripsi' => 'Pertemuan kedua membahas grafik fungsi kuadrat dan penyelesaian persamaan kuadrat.',
            'tanggal' => Carbon::now()->subDays(2),
        ]);

        $pertemuan3 = Pertemuan::create([
            'kelas_id' => $kelas7A->id,
            'mata_pelajaran_id' => $matematikaId,
            'judul' => 'Matriks & Geometri',
            'urutan' => 3,
            'deskripsi' => 'Pertemuan ketiga membahas materi dasar tentang matriks dan aplikasinya pada geometri.',
            'tanggal' => Carbon::now()->addDays(5),
        ]);

        // 5. Create Materi
        if ($guruUser) {
            Materi::create([
                'pertemuan_id' => $pertemuan1->id,
                'kelas_id' => $kelas7A->id,
                'guru_id' => $guruUser->id,
                'judul' => 'Pengantar Konsep Aljabar & Modul Pembelajaran',
                'tipe' => 'file',
                'file_path' => 'uploads/materi/aljabar_dasar.pdf',
                'konten' => "# Pengantar Konsep Aljabar\n\nVariabel adalah lambang pengganti suatu bilangan yang belum diketahui nilainya dengan jelas. Contoh:\n`2x + 5 = 15`\ndi mana `x` adalah variabel.\n\nSilakan unduh atau pelajari modul PDF di bawah untuk pembahasan yang lebih lengkap.",
            ]);
        }

        // 6. Create Tugas
        if ($guruUser) {
            $tugas = Tugas::create([
                'kelas_id' => $kelas7A->id,
                'mata_pelajaran_id' => $matematikaId,
                'guru_id' => $guruUser->id,
                'pertemuan_id' => $pertemuan1->id,
                'judul' => 'Tugas Mandiri 1: Aljabar Dasar',
                'deskripsi' => 'Kerjakan latihan soal halaman 15 di kertas folio, foto hasil pengerjaan lalu unggah di sini.',
                'deadline' => Carbon::now()->addDays(3),
                'nilai_maksimum' => 100,
            ]);

            // 7. Seed Pengumpulan Tugas (submissions)
            if ($siswaUser) {
                PengumpulanTugas::create([
                    'tugas_id' => $tugas->id,
                    'siswa_id' => $siswaUser->id,
                    'file_jawaban' => 'jawaban_siswa.pdf',
                    'catatan' => 'Tugas sudah selesai saya kerjakan pak, mohon koreksinya.',
                    'dikumpulkan_at' => Carbon::now()->subDays(1),
                    'nilai' => 85,
                    'feedback' => 'Bagus sekali, langkah pengerjaan sudah runtut dan benar.',
                    'status' => 'dinilai',
                ]);
            }

            if ($siswaBudi) {
                PengumpulanTugas::create([
                    'tugas_id' => $tugas->id,
                    'siswa_id' => $siswaBudi->id,
                    'file_jawaban' => 'jawaban_budi.pdf',
                    'catatan' => 'Mengumpulkan tugas matematika pak.',
                    'dikumpulkan_at' => Carbon::now()->subDays(2),
                    'nilai' => 95,
                    'feedback' => 'Sangat rapi dan semua jawaban benar.',
                    'status' => 'dinilai',
                ]);
            }

            if ($siswaRian) {
                PengumpulanTugas::create([
                    'tugas_id' => $tugas->id,
                    'siswa_id' => $siswaRian->id,
                    'file_jawaban' => 'jawaban_rian.pdf',
                    'catatan' => 'Tugas aljabar rian pak.',
                    'dikumpulkan_at' => Carbon::now()->subHours(2),
                    'nilai' => null,
                    'feedback' => null,
                    'status' => 'terkumpul',
                ]);
            }
        }

        // 8. Create Kuis
        if ($guruUser) {
            $kuis = Kuis::create([
                'kelas_id' => $kelas7A->id,
                'mata_pelajaran_id' => $matematikaId,
                'guru_id' => $guruUser->id,
                'pertemuan_id' => $pertemuan2->id,
                'judul' => 'Kuis Harian: Aljabar & Fungsi',
                'deskripsi' => 'Kuis singkat menguji pemahaman Anda mengenai aljabar dasar dan fungsi kuadrat.',
                'durasi_menit' => 15,
                'jumlah_soal' => 2,
                'batas_pengerjaan' => 1,
                'bobot_nilai' => 10,
                'mulai_at' => Carbon::now()->subDays(2),
                'selesai_at' => Carbon::now()->addDays(5),
                'is_aktif' => true,
            ]);

            // Create Soal Kuis
            $soal1 = SoalKuis::create([
                'kuis_id' => $kuis->id,
                'pertanyaan' => 'Jika 2x + 5 = 15, berapakah nilai x?',
                'tipe' => 'pilihan_ganda',
                'pilihan_jawaban' => ['3', '5', '7', '10'],
                'kunci_jawaban' => '5',
                'poin' => 50,
                'urutan' => 1,
            ]);

            $soal2 = SoalKuis::create([
                'kuis_id' => $kuis->id,
                'pertanyaan' => 'Kata Al-Jabar dalam matematika berasal dari bahasa Arab.',
                'tipe' => 'benar_salah',
                'pilihan_jawaban' => ['Benar', 'Salah'],
                'kunci_jawaban' => 'Benar',
                'poin' => 50,
                'urutan' => 2,
            ]);

            // 9. Seed Hasil Kuis (quiz results)
            if ($siswaUser) {
                HasilKuis::create([
                    'kuis_id' => $kuis->id,
                    'siswa_id' => $siswaUser->id,
                    'attempt' => 1,
                    'nilai_raw' => 100,
                    'nilai_akhir' => 100,
                    'benar' => 2,
                    'salah' => 0,
                    'mulai_at' => Carbon::now()->subDays(1)->subMinutes(12),
                    'selesai_at' => Carbon::now()->subDays(1),
                    'is_submitted' => true,
                ]);
            }

            if ($siswaBudi) {
                HasilKuis::create([
                    'kuis_id' => $kuis->id,
                    'siswa_id' => $siswaBudi->id,
                    'attempt' => 1,
                    'nilai_raw' => 50,
                    'nilai_akhir' => 50,
                    'benar' => 1,
                    'salah' => 1,
                    'mulai_at' => Carbon::now()->subDays(1)->subMinutes(15),
                    'selesai_at' => Carbon::now()->subDays(1),
                    'is_submitted' => true,
                ]);
            }
        }
    }
}
