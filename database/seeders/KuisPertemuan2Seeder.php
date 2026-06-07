<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kuis;
use App\Models\SoalKuis;
use App\Models\Kelas;
use App\Models\Pertemuan;

class KuisPertemuan2Seeder extends Seeder
{
    public function run(): void
    {
        // Kelas VII A (ID: 1) — Pertemuan 2: Fungsi Kuadrat & Persamaan (ID: 2)
        // Kuis di sini sudah ada, jadi kita buat untuk Pertemuan 1 & 3 yang masih kosong

        $this->buatKuisPertemuan1();
        $this->buatKuisPertemuan3();
    }

    private function buatKuisPertemuan1(): void
    {
        $pertemuan = Pertemuan::find(1); // Pengenalan & Aljabar Dasar
        if (!$pertemuan) { $this->command->error('Pertemuan 1 tidak ditemukan'); return; }

        $existing = Kuis::where('pertemuan_id', $pertemuan->id)->first();
        if ($existing) { $this->command->warn('Kuis Pertemuan 1 sudah ada: ' . $existing->judul); return; }

        $kuis = Kuis::create([
            'pertemuan_id'      => $pertemuan->id,
            'kelas_id'          => 1,
            'mata_pelajaran_id' => 1, // Matematika
            'guru_id'           => 2,
            'judul'             => 'Kuis Pertemuan 1: Aljabar Dasar',
            'deskripsi'         => 'Kuis pengenalan untuk mengukur pemahaman dasar aljabar. Kerjakan setiap soal dengan teliti!',
            'durasi_menit'      => 15,
            'jumlah_soal'       => 3,
            'batas_pengerjaan'  => 2,
            'bobot_nilai'       => 15,
            'mulai_at'          => now()->subDays(2),
            'selesai_at'        => now()->addDays(5),
            'is_aktif'          => true,
        ]);

        $this->command->info('Kuis Pertemuan 1 dibuat: ' . $kuis->judul);

        SoalKuis::create([
            'kuis_id'         => $kuis->id,
            'tipe'            => 'pilihan_ganda',
            'pertanyaan'      => 'Bentuk sederhana dari 3a + 2b + 5a - b adalah...',
            'pilihan_jawaban' => ['a' => '8a + b', 'b' => '8a - b', 'c' => '8a + 3b', 'd' => '2a + b'],
            'kunci_jawaban'   => 'a',
            'poin'            => 40,
            'urutan'          => 1,
        ]);

        SoalKuis::create([
            'kuis_id'         => $kuis->id,
            'tipe'            => 'benar_salah',
            'pertanyaan'      => 'Variabel dalam aljabar dapat mewakili sembarang bilangan.',
            'pilihan_jawaban' => [],
            'kunci_jawaban'   => 'benar',
            'poin'            => 30,
            'urutan'          => 2,
        ]);

        SoalKuis::create([
            'kuis_id'         => $kuis->id,
            'tipe'            => 'isian_singkat',
            'pertanyaan'      => 'Jika a = 3 dan b = 2, maka nilai 2a + 3b adalah...',
            'pilihan_jawaban' => [],
            'kunci_jawaban'   => '12',
            'poin'            => 30,
            'urutan'          => 3,
        ]);

        $this->command->info('3 soal Pertemuan 1 berhasil dibuat!');
    }

    private function buatKuisPertemuan3(): void
    {
        $pertemuan = Pertemuan::find(3); // Matriks & Geometri
        if (!$pertemuan) { $this->command->error('Pertemuan 3 tidak ditemukan'); return; }

        $existing = Kuis::where('pertemuan_id', $pertemuan->id)->first();
        if ($existing) { $this->command->warn('Kuis Pertemuan 3 sudah ada: ' . $existing->judul); return; }

        $kuis = Kuis::create([
            'pertemuan_id'      => $pertemuan->id,
            'kelas_id'          => 1,
            'mata_pelajaran_id' => 1, // Matematika
            'guru_id'           => 2,
            'judul'             => 'Kuis Pertemuan 3: Matriks & Geometri',
            'deskripsi'         => 'Kuis untuk mengukur pemahaman siswa tentang konsep dasar matriks dan geometri bidang.',
            'durasi_menit'      => 20,
            'jumlah_soal'       => 3,
            'batas_pengerjaan'  => 2,
            'bobot_nilai'       => 20,
            'mulai_at'          => now()->addDays(3),
            'selesai_at'        => now()->addDays(10),
            'is_aktif'          => true,
        ]);

        $this->command->info('Kuis Pertemuan 3 dibuat: ' . $kuis->judul);

        SoalKuis::create([
            'kuis_id'         => $kuis->id,
            'tipe'            => 'pilihan_ganda',
            'pertanyaan'      => 'Ordo matriks yang memiliki 2 baris dan 3 kolom adalah...',
            'pilihan_jawaban' => ['a' => '2x2', 'b' => '3x2', 'c' => '2x3', 'd' => '3x3'],
            'kunci_jawaban'   => 'c',
            'poin'            => 40,
            'urutan'          => 1,
        ]);

        SoalKuis::create([
            'kuis_id'         => $kuis->id,
            'tipe'            => 'benar_salah',
            'pertanyaan'      => 'Jumlah sudut dalam segitiga selalu 180 derajat.',
            'pilihan_jawaban' => [],
            'kunci_jawaban'   => 'benar',
            'poin'            => 30,
            'urutan'          => 2,
        ]);

        SoalKuis::create([
            'kuis_id'         => $kuis->id,
            'tipe'            => 'isian_singkat',
            'pertanyaan'      => 'Keliling lingkaran dengan jari-jari 7 cm (π = 22/7) adalah... cm',
            'pilihan_jawaban' => [],
            'kunci_jawaban'   => '44',
            'poin'            => 30,
            'urutan'          => 3,
        ]);

        $this->command->info('3 soal Pertemuan 3 berhasil dibuat!');
    }
}
