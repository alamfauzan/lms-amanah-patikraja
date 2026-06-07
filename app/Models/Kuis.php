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
        'durasi_menit', 'jumlah_soal', 'batas_pengerjaan', 'bobot_nilai',
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
}
