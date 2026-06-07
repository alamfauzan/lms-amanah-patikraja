<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TahunAjaran extends Model
{
    use HasFactory;

    protected $table = 'tahun_ajaran';

    protected $fillable = [
        'tahun_ajaran',
        'is_aktif',
    ];

    protected $casts = [
        'is_aktif' => 'boolean',
    ];

    public static function getActiveYear()
    {
        return self::where('is_aktif', true)->first();
    }
}
