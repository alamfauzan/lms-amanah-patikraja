<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\PertemuanController;
use App\Http\Controllers\TugasController;
use App\Http\Controllers\MateriController;
use App\Http\Controllers\KuisController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\NotifikasiController;
use App\Http\Controllers\NilaiController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/dashboard', function () {
    $user = auth()->user();
    if ($user->hasRole('admin')) {
        return view('dashboards.admin');
    } elseif ($user->hasRole('guru')) {
        $kelasIds = \App\Models\KelasMapelGuru::where('guru_id', $user->id)->pluck('kelas_id')->unique();
        $jumlahKelas = $kelasIds->count();
        
        $totalSiswa = \DB::table('kelas_siswa')->whereIn('kelas_id', $kelasIds)->distinct('siswa_id')->count('siswa_id');
        
        $tugasAktif = \App\Models\Tugas::whereIn('kelas_id', $kelasIds)->where('deadline', '>', now())->count();
        
        $kuisAktif = \App\Models\Kuis::whereIn('kelas_id', $kelasIds)->where('is_aktif', true)->count();
        
        $hariIni = now()->dayOfWeekIso;
        $jadwalHariIni = \App\Models\Jadwal::where('guru_id', $user->id)
            ->where('hari', $hariIni)
            ->with(['kelas', 'mataPelajaran'])
            ->orderBy('jam_mulai')
            ->get();
            
        // Log Aktivitas / Pengumpulan
        $pengumpulanTugas = \App\Models\PengumpulanTugas::whereIn('tugas_id', \App\Models\Tugas::whereIn('kelas_id', $kelasIds)->pluck('id'))
            ->with(['siswa', 'tugas'])
            ->latest('dikumpulkan_at')
            ->take(5)
            ->get()
            ->map(function($p) {
                return [
                    'type' => 'tugas',
                    'nama_siswa' => $p->siswa->name,
                    'initials' => strtoupper(substr($p->siswa->name, 0, 2)),
                    'deskripsi' => 'Mengumpulkan Tugas: ' . $p->tugas->judul,
                    'status' => $p->status === 'diserahkan' ? 'TERKIRIM' : 'DINILAI',
                    'timestamp' => $p->dikumpulkan_at,
                ];
            });

        $hasilKuis = \App\Models\HasilKuis::whereIn('kuis_id', \App\Models\Kuis::whereIn('kelas_id', $kelasIds)->pluck('id'))
            ->where('is_submitted', true)
            ->with(['siswa', 'kuis'])
            ->latest('selesai_at')
            ->take(5)
            ->get()
            ->map(function($h) {
                return [
                    'type' => 'kuis',
                    'nama_siswa' => $h->siswa->name,
                    'initials' => strtoupper(substr($h->siswa->name, 0, 2)),
                    'deskripsi' => 'Mengerjakan Kuis: ' . $h->kuis->judul,
                    'status' => 'Nilai: ' . $h->nilai_akhir,
                    'timestamp' => $h->selesai_at ?: $h->updated_at,
                ];
            });

        $aktivitasSiswa = $pengumpulanTugas->concat($hasilKuis)
            ->sortByDesc('timestamp')
            ->take(5);

        return view('dashboards.guru', compact(
            'jumlahKelas', 'totalSiswa', 'tugasAktif', 'kuisAktif', 'jadwalHariIni', 'aktivitasSiswa'
        ));
    } else {
        return view('dashboards.siswa');
    }
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ── KELAS ──────────────────────────────────────────────
    Route::resource('kelas', KelasController::class);
    Route::post('kelas/{kelas}/siswa',              [KelasController::class, 'addStudent'])->name('kelas.siswa.add');
    Route::delete('kelas/{kelas}/siswa/{siswa}',    [KelasController::class, 'removeStudent'])->name('kelas.siswa.remove');
    Route::post('kelas/{kelas}/mapel',              [KelasController::class, 'assignSubject'])->name('kelas.mapel.assign');
    Route::delete('kelas/{kelas}/mapel/{id}',       [KelasController::class, 'removeSubject'])->name('kelas.mapel.remove');

    // ── PERTEMUAN (nested under kelas) ─────────────────────
    Route::get('kelas/{kelas}/pertemuan',                   [PertemuanController::class, 'index'])->name('kelas.pertemuan.index');
    Route::get('kelas/{kelas}/pertemuan/create',            [PertemuanController::class, 'create'])->name('kelas.pertemuan.create');
    Route::post('kelas/{kelas}/pertemuan',                  [PertemuanController::class, 'store'])->name('kelas.pertemuan.store');
    Route::get('kelas/{kelas}/pertemuan/{pertemuan}',       [PertemuanController::class, 'show'])->name('kelas.pertemuan.show');
    Route::get('kelas/{kelas}/pertemuan/{pertemuan}/edit',  [PertemuanController::class, 'edit'])->name('kelas.pertemuan.edit');
    Route::put('kelas/{kelas}/pertemuan/{pertemuan}',       [PertemuanController::class, 'update'])->name('kelas.pertemuan.update');
    Route::delete('kelas/{kelas}/pertemuan/{pertemuan}',    [PertemuanController::class, 'destroy'])->name('kelas.pertemuan.destroy');

    // ── MATERI (nested under kelas/pertemuan) ──────────────
    Route::get('kelas/{kelas}/materi',                      [MateriController::class, 'index'])->name('kelas.materi.index');
    Route::get('kelas/{kelas}/pertemuan/{pertemuan}/materi/create', [MateriController::class, 'create'])->name('kelas.pertemuan.materi.create');
    Route::post('kelas/{kelas}/pertemuan/{pertemuan}/materi', [MateriController::class, 'store'])->name('kelas.pertemuan.materi.store');
    Route::get('materi/{materi}',                           [MateriController::class, 'show'])->name('materi.show');
    Route::get('materi/{materi}/edit',                      [MateriController::class, 'edit'])->name('materi.edit');
    Route::put('materi/{materi}',                           [MateriController::class, 'update'])->name('materi.update');
    Route::delete('materi/{materi}',                        [MateriController::class, 'destroy'])->name('materi.destroy');

    // ── TUGAS (nested under kelas) ─────────────────────────
    Route::get('kelas/{kelas}/tugas',                       [TugasController::class, 'index'])->name('kelas.tugas.index');
    Route::get('kelas/{kelas}/tugas/create',                [TugasController::class, 'create'])->name('kelas.tugas.create');
    Route::post('kelas/{kelas}/tugas',                      [TugasController::class, 'store'])->name('kelas.tugas.store');
    Route::get('kelas/{kelas}/tugas/{tugas}',               [TugasController::class, 'show'])->name('tugas.show');
    Route::get('kelas/{kelas}/tugas/{tugas}/edit',          [TugasController::class, 'edit'])->name('kelas.tugas.edit');
    Route::put('kelas/{kelas}/tugas/{tugas}',               [TugasController::class, 'update'])->name('kelas.tugas.update');
    Route::delete('kelas/{kelas}/tugas/{tugas}',            [TugasController::class, 'destroy'])->name('kelas.tugas.destroy');
    Route::post('kelas/{kelas}/tugas/{tugas}/submit',       [TugasController::class, 'submit'])->name('tugas.submit');
    Route::post('kelas/{kelas}/tugas/{tugas}/grade/{pengumpulan}', [TugasController::class, 'grade'])->name('tugas.grade');

    // ── KUIS (nested under kelas) ──────────────────────────
    Route::get('kelas/{kelas}/kuis',                        [KuisController::class, 'index'])->name('kelas.kuis.index');
    Route::get('kelas/{kelas}/kuis/create',                 [KuisController::class, 'create'])->name('kelas.kuis.create');
    Route::post('kelas/{kelas}/kuis',                       [KuisController::class, 'store'])->name('kelas.kuis.store');
    Route::get('kelas/{kelas}/kuis/{kuis}',                 [KuisController::class, 'show'])->name('kuis.show');
    Route::get('kelas/{kelas}/kuis/{kuis}/edit',            [KuisController::class, 'edit'])->name('kelas.kuis.edit');
    Route::put('kelas/{kelas}/kuis/{kuis}',                 [KuisController::class, 'update'])->name('kelas.kuis.update');
    Route::delete('kelas/{kelas}/kuis/{kuis}',              [KuisController::class, 'destroy'])->name('kelas.kuis.destroy');
    Route::get('kelas/{kelas}/kuis/{kuis}/kerjakan',        [KuisController::class, 'kerjakan'])->name('kuis.kerjakan');
    Route::post('kelas/{kelas}/kuis/{kuis}/jawab',          [KuisController::class, 'jawab'])->name('kuis.jawab');
    Route::post('kelas/{kelas}/kuis/{kuis}/submit',         [KuisController::class, 'submit'])->name('kuis.submit');
    Route::get('kelas/{kelas}/kuis/{kuis}/hasil',           [KuisController::class, 'hasil'])->name('kuis.hasil');

    // ── JADWAL ─────────────────────────────────────────────
    Route::get('jadwal',                    [JadwalController::class, 'index'])->name('jadwal.index');
    Route::get('jadwal/create',             [JadwalController::class, 'create'])->name('jadwal.create');
    Route::post('jadwal',                   [JadwalController::class, 'store'])->name('jadwal.store');
    Route::delete('jadwal/{jadwal}',        [JadwalController::class, 'destroy'])->name('jadwal.destroy');

    // ── NOTIFIKASI ─────────────────────────────────────────
    Route::get('notifikasi',                [NotifikasiController::class, 'index'])->name('notifikasi.index');
    Route::patch('notifikasi/{id}/read',    [NotifikasiController::class, 'markRead'])->name('notifikasi.read');
    Route::post('notifikasi/read-all',      [NotifikasiController::class, 'markAllRead'])->name('notifikasi.readAll');
    Route::delete('notifikasi/{id}',        [NotifikasiController::class, 'destroy'])->name('notifikasi.destroy');

    // ── NILAI ───────────────────────────────────────────────
    Route::get('nilai',                          [NilaiController::class, 'indexGuru'])->name('nilai.guru');
    Route::get('nilai/siswa',                    [NilaiController::class, 'indexSiswa'])->name('nilai.siswa');
    Route::get('nilai/kelas/{kelas}',            [NilaiController::class, 'rekapKelas'])->name('nilai.rekap');
    Route::get('nilai/kelas/{kelas}/export-csv', [NilaiController::class, 'exportCsv'])->name('nilai.export-csv');
});

require __DIR__.'/auth.php';
