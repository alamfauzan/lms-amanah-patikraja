<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Materi extends Model
{
    use HasFactory;

    protected $table = 'materi';

    protected $fillable = ['pertemuan_id', 'kelas_id', 'guru_id', 'judul', 'konten', 'file_path', 'tipe'];

    public function pertemuan()
    {
        return $this->belongsTo(Pertemuan::class);
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function guru()
    {
        return $this->belongsTo(User::class, 'guru_id');
    }
}
