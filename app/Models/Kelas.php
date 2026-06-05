<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;

    protected $table = 'kelas';

    protected $fillable = [
        'nama_kelas',
        'deskripsi',
        'wali_kelas_id',
        'tahun_ajaran',
    ];

    public function waliKelas()
    {
        return $this->belongsTo(User::class, 'wali_kelas_id');
    }

    public function siswa()
    {
        return $this->belongsToMany(User::class, 'kelas_siswa', 'kelas_id', 'siswa_id')->withTimestamps();
    }

    public function kelasMapelGuru()
    {
        return $this->hasMany(KelasMapelGuru::class, 'kelas_id');
    }

    public function pertemuan()
    {
        return $this->hasMany(Pertemuan::class)->orderBy('urutan');
    }

    public function tugas()
    {
        return $this->hasMany(Tugas::class);
    }

    public function kuis()
    {
        return $this->hasMany(Kuis::class);
    }
}
