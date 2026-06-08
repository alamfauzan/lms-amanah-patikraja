<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function siswaKelas()
    {
        return $this->belongsToMany(Kelas::class, 'kelas_siswa', 'siswa_id', 'kelas_id')->withTimestamps();
    }

    // Alias untuk siswaKelas — digunakan di NilaiController
    public function kelasDiikuti()
    {
        return $this->belongsToMany(Kelas::class, 'kelas_siswa', 'siswa_id', 'kelas_id')->withTimestamps();
    }

    public function guruKelasMapel()
    {
        return $this->hasMany(KelasMapelGuru::class, 'guru_id');
    }

    public function tugasDibuat()
    {
        return $this->hasMany(Tugas::class, 'guru_id');
    }

    public function pengumpulanTugas()
    {
        return $this->hasMany(PengumpulanTugas::class, 'siswa_id');
    }

    public function kuisDibuat()
    {
        return $this->hasMany(Kuis::class, 'guru_id');
    }

    public function hasilKuis()
    {
        return $this->hasMany(HasilKuis::class, 'siswa_id');
    }

    public function jawabanKuis()
    {
        return $this->hasMany(JawabanSiswa::class, 'siswa_id');
    }

    public function notifikasi()
    {
        return $this->hasMany(Notifikasi::class)->latest();
    }

    public function notifikasiTidakDibaca()
    {
        return $this->notifikasi()->belumDibaca();
    }

    public function jadwal()
    {
        return $this->hasMany(Jadwal::class, 'guru_id');
    }
}
