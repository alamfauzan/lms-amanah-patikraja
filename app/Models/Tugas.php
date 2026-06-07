<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tugas extends Model
{
    use HasFactory;

    protected $table = 'tugas';

    protected $fillable = ['kelas_id', 'mata_pelajaran_id', 'guru_id', 'pertemuan_id', 'judul', 'deskripsi', 'deadline', 'nilai_maksimum', 'file_path'];

    protected $casts = ['deadline' => 'datetime'];

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

    public function pengumpulan()
    {
        return $this->hasMany(PengumpulanTugas::class);
    }

    public function pengumpulanSiswa($siswaId)
    {
        return $this->pengumpulan()->where('siswa_id', $siswaId)->first();
    }
}
