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
                $hasil = HasilKuis::where('kuis_id', $kuis->id)
                    ->where('siswa_id', $siswa->id)
                    ->where('is_submitted', true)
                    ->orderByDesc('attempt')
                    ->first();
                $nilai = $hasil?->nilai_akhir;
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
     * Halaman nilai untuk Siswa (melihat nilai sendiri di semua kelas)
     */
    public function indexSiswa()
    {
        $user = auth()->user();
        if (!$user->hasRole('siswa')) abort(403);

        // Ambil semua kelas yang diikuti siswa
        $kelasList = $user->kelasDiikuti()->with(['tugas', 'kuis'])->get();

        $rekapKelas = $kelasList->map(function ($kelas) use ($user) {
            // Tugas
            $tugasData = $kelas->tugas->map(function ($tugas) use ($user) {
                $pengumpulan = PengumpulanTugas::where('tugas_id', $tugas->id)
                    ->where('siswa_id', $user->id)
                    ->first();
                return [
                    'tugas'  => $tugas,
                    'nilai'  => $pengumpulan?->nilai,
                    'status' => $pengumpulan?->status,
                ];
            });

            // Kuis
            $kuisData = $kelas->kuis->map(function ($kuis) use ($user) {
                $hasil = HasilKuis::where('kuis_id', $kuis->id)
                    ->where('siswa_id', $user->id)
                    ->where('is_submitted', true)
                    ->orderByDesc('attempt')
                    ->first();
                return [
                    'kuis'  => $kuis,
                    'nilai' => $hasil?->nilai_akhir,
                ];
            });

            $nilaiTugas = $tugasData->pluck('nilai')->filter()->values();
            $nilaiKuis  = $kuisData->pluck('nilai')->filter()->values();
            $semua = $nilaiTugas->concat($nilaiKuis)->filter();
            $rataRata = $semua->count() > 0 ? round($semua->average(), 1) : null;

            return [
                'kelas'     => $kelas,
                'tugas'     => $tugasData,
                'kuis'      => $kuisData,
                'rata_rata' => $rataRata,
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
                    $h = HasilKuis::where(['kuis_id' => $kuis->id, 'siswa_id' => $siswa->id, 'is_submitted' => true])
                        ->orderByDesc('attempt')->first();
                    $nilaiKuis[] = $h?->nilai_akhir ?? '-';
                    $row[] = $h?->nilai_akhir ?? '-';
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
