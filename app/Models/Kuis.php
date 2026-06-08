<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kuis extends Model
{
    use HasFactory;

    protected $table = 'kuis';

    protected $fillable = [
        'kelas_id', 'mata_pelajaran_id', 'guru_id', 'pertemuan_id', 'judul', 'deskripsi',
        'durasi_menit', 'jumlah_soal', 'batas_pengerjaan', 'nilai_diambil_dari', 'bobot_nilai',
        'mulai_at', 'selesai_at', 'is_aktif',
    ];

    protected $casts = [
        'mulai_at'  => 'datetime',
        'selesai_at' => 'datetime',
        'is_aktif'  => 'boolean',
    ];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function mataPelajaran()
    {
        return $this->belongsTo(MataPelajaran::class);
    }

    public function guru()
    {
        return $this->belongsTo(User::class, 'guru_id');
    }

    public function pertemuan()
    {
        return $this->belongsTo(Pertemuan::class);
    }

    public function soal()
    {
        return $this->hasMany(SoalKuis::class)->orderBy('urutan');
    }

    public function hasilSiswa()
    {
        return $this->hasMany(HasilKuis::class);
    }

    public function hasilBySiswa($siswaId)
    {
        return $this->hasilSiswa()->where('siswa_id', $siswaId)->latest('attempt')->first();
    }

    public function hasilNilaiBySiswa($siswaId)
    {
        $query = $this->hasilSiswa()
            ->where('siswa_id', $siswaId)
            ->where('is_submitted', true);

        return match ($this->nilai_diambil_dari ?? 'terakhir') {
            'tertinggi' => $query->orderByDesc('nilai_akhir')->orderByDesc('attempt')->first(),
            default => $query->orderByDesc('attempt')->first(),
        };
    }

    public function nilaiAkhirBySiswa($siswaId): ?float
    {
        $query = $this->hasilSiswa()
            ->where('siswa_id', $siswaId)
            ->where('is_submitted', true);

        if (($this->nilai_diambil_dari ?? 'terakhir') === 'rata_rata') {
            $avg = $query->avg('nilai_akhir');
            return is_null($avg) ? null : round($avg, 2);
        }

        return $this->hasilNilaiBySiswa($siswaId)?->nilai_akhir;
    }

    public function labelNilaiDiambilDari(): string
    {
        return match ($this->nilai_diambil_dari ?? 'terakhir') {
            'tertinggi' => 'Nilai tertinggi',
            'rata_rata' => 'Rata-rata semua percobaan',
            default => 'Percobaan terakhir',
        };
    }
}
