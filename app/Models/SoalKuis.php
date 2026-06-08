<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SoalKuis extends Model
{
    use HasFactory;

    protected $table = 'soal_kuis';

    protected $fillable = [
        'kuis_id', 'pertanyaan', 'gambar', 'tipe', 'pilihan_jawaban', 'kunci_jawaban', 'poin', 'urutan',
    ];

    protected $casts = ['pilihan_jawaban' => 'array'];

    public function kuis()
    {
        return $this->belongsTo(Kuis::class);
    }

    public function jawaban()
    {
        return $this->hasMany(JawabanSiswa::class, 'soal_id');
    }
}
