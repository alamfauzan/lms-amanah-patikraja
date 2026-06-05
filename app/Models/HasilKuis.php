<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HasilKuis extends Model
{
    use HasFactory;

    protected $table = 'hasil_kuis';

    protected $fillable = [
        'kuis_id', 'siswa_id', 'attempt', 'nilai_raw', 'nilai_akhir',
        'benar', 'salah', 'mulai_at', 'selesai_at', 'is_submitted',
    ];

    protected $casts = [
        'mulai_at'    => 'datetime',
        'selesai_at'  => 'datetime',
        'is_submitted' => 'boolean',
    ];

    public function kuis()
    {
        return $this->belongsTo(Kuis::class);
    }

    public function siswa()
    {
        return $this->belongsTo(User::class, 'siswa_id');
    }
}
