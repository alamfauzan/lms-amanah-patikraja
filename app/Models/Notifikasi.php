<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notifikasi extends Model
{
    use HasFactory;

    protected $table = 'notifikasi';

    protected $fillable = ['user_id', 'judul', 'pesan', 'tipe', 'link', 'dibaca_at'];

    protected $casts = ['dibaca_at' => 'datetime'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeBelumDibaca($query)
    {
        return $query->whereNull('dibaca_at');
    }

    public function markAsRead()
    {
        $this->update(['dibaca_at' => now()]);
    }
}
