<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Tugas;
use App\Models\Kuis;
use App\Models\PengumpulanTugas;
use App\Models\HasilKuis;
use App\Models\KelasMapelGuru;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class NilaiController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Halaman rekap nilai untuk Guru — pilih kelas dulu
     */
    public function indexGuru()
    {
        $user = auth()->user();
        if (!$user->hasAnyRole(['admin', 'guru'])) abort(403);

        if ($user->hasRole('admin')) {
            $kelasList = Kelas::withCount('siswa')->latest()->get();
        } else {
            $kelasIds = KelasMapelGuru::where('guru_id', $user->id)->pluck('kelas_id')->unique();
            $kelasList = Kelas::whereIn('id', $kelasIds)->withCount('siswa')->get();
        }

        return view('nilai.index-guru', compact('kelasList'));
    }

    /**
     * Rekap nilai siswa dalam satu kelas (untuk Guru/Admin)
     */
    public function rekapKelas(Request $request, $kelasId)
    {
        $user = auth()->user();
        if (!$user->hasAnyRole(['admin', 'guru'])) abort(403);

        $kelas = Kelas::with('siswa')->findOrFail($kelasId);
        $siswas = $kelas->siswa;

        // Semua tugas di kelas ini
        $tugasList = Tugas::where('kelas_id', $kelasId)->orderBy('created_at')->get();

        // Semua kuis di kelas ini
        $kuisList = Kuis::where('kelas_id', $kelasId)->orderBy('created_at')->get();

        // Build rekap per siswa
        $rekap = $siswas->map(function ($siswa) use ($tugasList, $kuisList) {
            $nilaiTugas = [];
            $totalTugas = 0;
            $countTugas = 0;

            foreach ($tugasList as $tugas) {
                $pengumpulan = PengumpulanTugas::where('tugas_id', $tugas->id)
                    ->where('siswa_id', $siswa->id)
                    ->first();
                $nilai = $pengumpulan?->nilai;
                $nilaiTugas[$tugas->id] = [
                    'nilai'  => $nilai,
                    'status' => $pengumpulan ? $pengumpulan->status : null,
                ];
                if (!is_null($nilai)) {
                    $totalTugas += $nilai;
                    $countTugas++;
                }
            }

            $nilaiKuis = [];
            $totalKuis = 0;
            $countKuis = 0;

            foreach ($kuisList as $kuis) {
                $hasil = $kuis->hasilNilaiBySiswa($siswa->id);
                $nilai = $kuis->nilaiAkhirBySiswa($siswa->id);
                $nilaiKuis[$kuis->id] = [
                    'nilai'   => $nilai,
                    'attempt' => $hasil?->attempt,
                ];
                if (!is_null($nilai)) {
                    $totalKuis += $nilai;
                    $countKuis++;
                }
            }

            $rataRataTugas = $countTugas > 0 ? round($totalTugas / $countTugas, 1) : null;
            $rataRataKuis  = $countKuis > 0  ? round($totalKuis / $countKuis, 1)  : null;

            $components = collect([$rataRataTugas, $rataRataKuis])->filter(fn($v) => !is_null($v));
            $nilaiAkhir = $components->count() > 0 ? round($components->average(), 1) : null;

            return [
                'siswa'         => $siswa,
                'nilai_tugas'   => $nilaiTugas,
                'nilai_kuis'    => $nilaiKuis,
                'rata_tugas'    => $rataRataTugas,
                'rata_kuis'     => $rataRataKuis,
                'nilai_akhir'   => $nilaiAkhir,
            ];
        });

        return view('nilai.rekap-kelas', compact('kelas', 'tugasList', 'kuisList', 'rekap'));
    }

    /**
     * Terima update nilai tugas dari Guru (simple save)
     */
    public function updateKelas(Request $request, $kelasId)
    {
        $user = auth()->user();
        if (!$user->hasAnyRole(['admin', 'guru'])) abort(403);

        $payload = $request->input('nilai', []); // format: [tugasId => [siswaId => value]]
        $disimpan = 0;
        $dikosongkan = 0;

        foreach ($payload as $tugasId => $bySiswa) {
            foreach ($bySiswa as $siswaId => $val) {
                // If the input is empty, we should NOT create a new pengumpulan.
                // If a pengumpulan exists, clear the nilai and set status accordingly.
                if ($val === '' || is_null($val)) {
                    $existing = PengumpulanTugas::where(['tugas_id' => $tugasId, 'siswa_id' => $siswaId])->first();
                    if (! $existing) {
                        // nothing to clear
                        continue;
                    }

                    $existing->nilai = null;
                    // if student had uploaded a file, keep 'terkumpul', otherwise 'belum'
                    $existing->status = $existing->file_jawaban ? 'terkumpul' : 'belum';
                    if (Schema::hasColumn('pengumpulan_tugas', 'dikoreksi_oleh')) {
                        $existing->dikoreksi_oleh = null;
                    }
                    $existing->save();
                    $dikosongkan++;
                    continue;
                }

                // Non-empty input: create or update pengumpulan with nilai
                $nilai = is_numeric($val) ? floatval($val) : null;

                $pengumpulan = PengumpulanTugas::firstOrNew([
                    'tugas_id' => $tugasId,
                    'siswa_id' => $siswaId,
                ]);
                $pengumpulan->nilai = $nilai;
                $pengumpulan->status = 'dinilai';
                if (Schema::hasColumn('pengumpulan_tugas', 'dikoreksi_oleh')) {
                    $pengumpulan->dikoreksi_oleh = $user->id ?? null;
                }
                $pengumpulan->save();
                $disimpan++;
            }
        }

        $message = 'Nilai disimpan.';
        if ($disimpan > 0 || $dikosongkan > 0) {
            $parts = [];
            if ($disimpan > 0) $parts[] = $disimpan . ' nilai disimpan';
            if ($dikosongkan > 0) $parts[] = $dikosongkan . ' nilai dikosongkan';
            $message = 'Nilai berhasil diperbarui: ' . implode(', ', $parts) . '.';
        }

        return redirect()->route('nilai.rekap', $kelasId)->with('success', $message);
    }

    /**
     * Halaman nilai untuk Siswa (melihat nilai sendiri di semua kelas)
     */
    public function indexSiswa()
    {
        $user = auth()->user();
        if (!$user->hasRole('siswa')) abort(403);

        // Ambil semua kelas yang diikuti siswa
        $kelasList = $user->kelasDiikuti()->with(['kelasMapelGuru.mataPelajaran', 'kelasMapelGuru.guru'])->get();

        $rekapKelas = $kelasList->map(function ($kelas) use ($user) {
            // Group by subject in this class
            $mapelList = $kelas->kelasMapelGuru->map(function ($kmg) use ($kelas, $user) {
                $mapel = $kmg->mataPelajaran;
                $guru = $kmg->guru;

                if (!$mapel) return null;

                // Tasks for this mapel
                $tugasQuery = Tugas::where('kelas_id', $kelas->id)
                    ->where('mata_pelajaran_id', $mapel->id)
                    ->get();

                $tugasData = $tugasQuery->map(function ($tugas) use ($user) {
                    $pengumpulan = PengumpulanTugas::where('tugas_id', $tugas->id)
                        ->where('siswa_id', $user->id)
                        ->first();
                    return [
                        'tugas'  => $tugas,
                        'nilai'  => $pengumpulan?->nilai,
                        'status' => $pengumpulan?->status,
                    ];
                });

                // Quizzes for this mapel
                $kuisQuery = Kuis::where('kelas_id', $kelas->id)
                    ->where('mata_pelajaran_id', $mapel->id)
                    ->get();

                $kuisData = $kuisQuery->map(function ($kuis) use ($user) {
                    return [
                        'kuis'  => $kuis,
                        'nilai' => $kuis->nilaiAkhirBySiswa($user->id),
                    ];
                });

                $nilaiTugas = $tugasData->pluck('nilai')->filter(fn($v) => !is_null($v))->values();
                $nilaiKuis  = $kuisData->pluck('nilai')->filter(fn($v) => !is_null($v))->values();
                $semua = $nilaiTugas->concat($nilaiKuis);
                $rataRata = $semua->count() > 0 ? round($semua->average(), 1) : null;
                $rataTugas = $nilaiTugas->count() > 0 ? round($nilaiTugas->average(), 1) : null;
                $rataKuis   = $nilaiKuis->count() > 0 ? round($nilaiKuis->average(), 1) : null;
                $komponenAkhir = collect([$rataTugas, $rataKuis])->filter(fn ($v) => !is_null($v));
                $nilaiAkhir = $komponenAkhir->count() > 0 ? round($komponenAkhir->average(), 1) : null;

                return [
                    'mata_pelajaran' => $mapel,
                    'guru'           => $guru,
                    'tugas'          => $tugasData,
                    'kuis'           => $kuisData,
                    'rata_rata'      => $rataRata,
                    'nilai_akhir'    => $nilaiAkhir,
                ];
            })->filter()->values();

            return [
                'kelas'      => $kelas,
                'mapel_list' => $mapelList,
            ];
        });

        return view('nilai.index-siswa', compact('rekapKelas'));
    }

    /**
     * Export nilai kelas ke CSV (Guru/Admin)
     */
    public function exportCsv($kelasId)
    {
        $user = auth()->user();
        if (!$user->hasAnyRole(['admin', 'guru'])) abort(403);

        $kelas   = Kelas::with('siswa')->findOrFail($kelasId);
        $siswas  = $kelas->siswa;
        $tugasList = Tugas::where('kelas_id', $kelasId)->orderBy('created_at')->get();
        $kuisList  = Kuis::where('kelas_id', $kelasId)->orderBy('created_at')->get();

        $filename = 'nilai-' . str()->slug($kelas->nama_kelas) . '-' . now()->format('Ymd') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($siswas, $tugasList, $kuisList) {
            $handle = fopen('php://output', 'w');

            // Header row
            $row = ['No', 'Nama Siswa', 'Email'];
            foreach ($tugasList as $t) $row[] = 'Tugas: ' . $t->judul;
            foreach ($kuisList as $k)  $row[] = 'Kuis: ' . $k->judul;
            $row[] = 'Rata-rata Tugas';
            $row[] = 'Rata-rata Kuis';
            $row[] = 'Nilai Akhir';
            fputcsv($handle, $row);

            foreach ($siswas as $i => $siswa) {
                $row = [$i + 1, $siswa->name, $siswa->email];

                $nilaiTugas = [];
                foreach ($tugasList as $tugas) {
                    $p = PengumpulanTugas::where(['tugas_id' => $tugas->id, 'siswa_id' => $siswa->id])->first();
                    $nilaiTugas[] = $p?->nilai ?? '-';
                    $row[] = $p?->nilai ?? '-';
                }

                $nilaiKuis = [];
                foreach ($kuisList as $kuis) {
                    $nilai = $kuis->nilaiAkhirBySiswa($siswa->id);
                    $nilaiKuis[] = $nilai ?? '-';
                    $row[] = $nilai ?? '-';
                }

                $rtgs = collect($nilaiTugas)->filter(fn($v) => is_numeric($v));
                $rkuis = collect($nilaiKuis)->filter(fn($v) => is_numeric($v));
                $row[] = $rtgs->count() > 0 ? round($rtgs->average(), 1) : '-';
                $row[] = $rkuis->count() > 0 ? round($rkuis->average(), 1) : '-';

                $all = $rtgs->concat($rkuis);
                $row[] = $all->count() > 0 ? round($all->average(), 1) : '-';

                fputcsv($handle, $row);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}
