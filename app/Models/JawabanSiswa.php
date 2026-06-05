<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JawabanSiswa extends Model
{
    use HasFactory;

    protected $table = 'jawaban_siswa';

    protected $fillable = [
        'kuis_id', 'soal_id', 'siswa_id', 'attempt', 'jawaban', 'is_benar', 'poin_diperoleh',
    ];

    protected $casts = ['is_benar' => 'boolean'];

    public function soal()
    {
        return $this->belongsTo(SoalKuis::class, 'soal_id');
    }

    public function siswa()
    {
        return $this->belongsTo(User::class, 'siswa_id');
    }

    public function kuis()
    {
        return $this->belongsTo(Kuis::class);
    }
}
