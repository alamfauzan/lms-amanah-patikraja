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
use App\Http\Controllers\Admin\GuruController;
use App\Http\Controllers\Admin\SiswaController;
use App\Http\Controllers\Admin\MataPelajaranController;
use App\Http\Controllers\Admin\TahunAjaranController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\PengaturanController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/dashboard', function () {
    $user = auth()->user();
    if ($user->hasRole('admin')) {
        $totalGuru = \App\Models\User::role('guru')->count();
        $totalSiswa = \App\Models\User::role('siswa')->count();
        $totalKelas = \App\Models\Kelas::count();
        $totalMapel = \App\Models\MataPelajaran::count();

        $recentSubmissions = \App\Models\PengumpulanTugas::with(['siswa', 'tugas.kelas'])
            ->latest('dikumpulkan_at')
            ->take(5)
            ->get()
            ->map(function($p) {
                return [
                    'icon_bg' => 'bg-blue-500/10 text-blue-600',
                    'icon_svg' => '<svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2" /></svg>',
                    'text' => 'Siswa <strong>' . e($p->siswa->name) . '</strong> mengumpulkan tugas <span class="font-medium text-slate-800 dark:text-slate-200">"' . e($p->tugas->judul) . '"</span> di kelas ' . e($p->tugas->kelas->nama_kelas) . '.',
                    'time' => $p->dikumpulkan_at,
                ];
            });

        $recentQuizzes = \App\Models\HasilKuis::where('is_submitted', true)
            ->with(['siswa', 'kuis.kelas'])
            ->latest('selesai_at')
            ->take(5)
            ->get()
            ->map(function($h) {
                return [
                    'icon_bg' => 'bg-emerald-500/10 text-emerald-600',
                    'icon_svg' => '<svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>',
                    'text' => 'Siswa <strong>' . e($h->siswa->name) . '</strong> menyelesaikan kuis <span class="font-medium text-slate-805 dark:text-slate-200">"' . e($h->kuis->judul) . '"</span> dengan Nilai: ' . (int) round($h->nilai_akhir) . '.',
                    'time' => $h->selesai_at ?: $h->updated_at,
                ];
            });

        $systemLogs = $recentSubmissions->concat($recentQuizzes)
            ->sortByDesc('time')
            ->take(5);

        return view('dashboards.admin', compact('totalGuru', 'totalSiswa', 'totalKelas', 'totalMapel', 'systemLogs'));
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
                    'status' => 'Nilai: ' . (int) round($h->nilai_akhir),
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
        $classes = $user->siswaKelas()->withCount('siswa')->with('waliKelas')->get();
        $kelasAktif = $classes->count();

        // Tasks & submissions
        $kelasIds = $classes->pluck('id');
        $tugasAll = \App\Models\Tugas::whereIn('kelas_id', $kelasIds)->get();
        $submittedTugasIds = \App\Models\PengumpulanTugas::where('siswa_id', $user->id)->pluck('tugas_id')->toArray();
        
        $tugasBelumSelesai = $tugasAll->whereNotIn('id', $submittedTugasIds)->filter(function($t) {
            return now()->lt($t->deadline);
        });
        $jumlahTugasBelumSelesai = $tugasBelumSelesai->count();

        // GPA (Average grade)
        $nilaiTugas = \App\Models\PengumpulanTugas::where('siswa_id', $user->id)->where('status', 'dinilai')->pluck('nilai');
        $nilaiKuis = \App\Models\HasilKuis::where('siswa_id', $user->id)->where('is_submitted', true)->pluck('nilai_akhir');
        $allGrades = $nilaiTugas->concat($nilaiKuis);
        $nilaiRataRata = $allGrades->isEmpty() ? 0 : round($allGrades->average(), 1);

        // Schedule Today
        $hariIni = now()->dayOfWeekIso;
        $jadwalHariIni = \App\Models\Jadwal::whereIn('kelas_id', $kelasIds)
            ->where('hari', $hariIni)
            ->with(['kelas', 'mataPelajaran', 'guru'])
            ->orderBy('jam_mulai')
            ->get();

        // Upcoming task deadlines (take 5)
        $tugasMendatang = $tugasBelumSelesai->sortBy('deadline')
            ->take(5)
            ->map(function($t) {
                $diff = $t->deadline->diffForHumans();
                return [
                    'id' => $t->id,
                    'kelas_id' => $t->kelas_id,
                    'judul' => $t->judul,
                    'mapel' => $t->kelas->nama_kelas,
                    'diff' => $diff,
                    'is_urgent' => $t->deadline->diffInHours(now()) < 24,
                ];
            });

        // Calculate progress for each class (based on completed tasks/quizzes vs total)
        $activeClassesData = $classes->map(function($c) use ($user) {
            $totalTugas = \App\Models\Tugas::where('kelas_id', $c->id)->count();
            $totalKuis = \App\Models\Kuis::where('kelas_id', $c->id)->count();
            $totalActivity = $totalTugas + $totalKuis;

            if ($totalActivity === 0) {
                $progress = 100;
            } else {
                $completedTugas = \App\Models\PengumpulanTugas::where('siswa_id', $user->id)
                    ->whereIn('tugas_id', \App\Models\Tugas::where('kelas_id', $c->id)->pluck('id'))
                    ->count();
                $completedKuis = \App\Models\HasilKuis::where('siswa_id', $user->id)
                    ->where('is_submitted', true)
                    ->whereIn('kuis_id', \App\Models\Kuis::where('kelas_id', $c->id)->pluck('id'))
                    ->count();
                $completedActivity = $completedTugas + $completedKuis;
                $progress = round(($completedActivity / $totalActivity) * 100);
            }

            // Find primary teacher (first assigned to mapel in this class)
            $teacherName = optional($c->kelasMapelGuru->first())->guru->name ?? 'Belum ada pengampu';

            return [
                'id' => $c->id,
                'nama_kelas' => $c->nama_kelas,
                'wali_kelas' => $c->waliKelas->name ?? '-',
                'teacher' => $teacherName,
                'progress' => $progress,
                'siswa_count' => $c->siswa_count,
            ];
        });

        // Simulated attendance rate (ratio of completed activities vs total assigned up to now, e.g. 100% if all done)
        $totalAssigned = $tugasAll->count();
        $totalSubmitted = count($submittedTugasIds);
        $kehadiran = $totalAssigned === 0 ? 100 : round(($totalSubmitted / $totalAssigned) * 100, 1);
        if ($kehadiran < 75) $kehadiran = 75;
        if ($kehadiran > 100) $kehadiran = 100;

        return view('dashboards.siswa', compact(
            'jumlahTugasBelumSelesai',
            'kelasAktif',
            'nilaiRataRata',
            'kehadiran',
            'jadwalHariIni',
            'tugasMendatang',
            'activeClassesData'
        ));
    }
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Global Tasks & Quizzes
    Route::get('tugas', [TugasController::class, 'index'])->name('tugas.index');
    Route::get('kuis', [KuisController::class, 'index'])->name('kuis.index');

    // ── ADMIN RESOURCES ────────────────────────────────────
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::resource('guru', GuruController::class);
        Route::resource('siswa', SiswaController::class);
        Route::resource('mapel', MataPelajaranController::class);
        Route::resource('tahun-ajaran', TahunAjaranController::class);
        Route::post('tahun-ajaran/{id}/aktif', [TahunAjaranController::class, 'aktifkan'])->name('tahun-ajaran.aktifkan');
        Route::get('laporan', [LaporanController::class, 'index'])->name('laporan.index');
        Route::get('laporan/export', [LaporanController::class, 'exportCsv'])->name('laporan.export');
        Route::get('pengaturan', [PengaturanController::class, 'index'])->name('pengaturan.index');
        Route::post('pengaturan', [PengaturanController::class, 'update'])->name('pengaturan.update');
    });

    // ── KELAS ──────────────────────────────────────────────
    Route::resource('kelas', KelasController::class);
    Route::post('kelas/{kelas}/siswa',              [KelasController::class, 'addStudent'])->name('kelas.siswa.add');
    Route::delete('kelas/{kelas}/siswa/{siswa}',    [KelasController::class, 'removeStudent'])->name('kelas.siswa.remove');
    Route::get('kelas/{kelas}/mapel/create',        [KelasController::class, 'createSubject'])->name('kelas.mapel.create');
    Route::post('kelas/{kelas}/mapel',              [KelasController::class, 'assignSubject'])->name('kelas.mapel.assign');
    Route::get('kelas/{kelas}/mapel/{id}/edit',     [KelasController::class, 'editSubject'])->name('kelas.mapel.edit');
    Route::put('kelas/{kelas}/mapel/{id}',          [KelasController::class, 'updateSubject'])->name('kelas.mapel.update');
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
    Route::post('nilai/kelas/{kelas}/update',    [NilaiController::class, 'updateKelas'])->name('nilai.update');
    Route::get('nilai/kelas/{kelas}/export-csv', [NilaiController::class, 'exportCsv'])->name('nilai.export-csv');
});

require __DIR__.'/auth.php';
