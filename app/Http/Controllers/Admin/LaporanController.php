<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\Tugas;
use App\Models\Kuis;
use App\Models\PengumpulanTugas;
use App\Models\HasilKuis;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LaporanController extends Controller
{
    public function index()
    {
        $totalTeachers = User::role('guru')->count();
        $totalStudents = User::role('siswa')->count();
        $totalClasses = Kelas::count();
        $totalSubjects = MataPelajaran::count();
        $totalAssignments = Tugas::count();
        $totalQuizzes = Kuis::count();

        $classesData = Kelas::with(['waliKelas', 'tugas', 'kuis'])
            ->withCount('siswa')
            ->get()
            ->map(function ($kelas) {
                // Get Average Tugas
                $tugasIds = $kelas->tugas->pluck('id');
                $avgTugas = $tugasIds->isNotEmpty() 
                    ? PengumpulanTugas::whereIn('tugas_id', $tugasIds)->where('status', 'dinilai')->avg('nilai') 
                    : null;

                // Get Average Kuis
                $kuisIds = $kelas->kuis->pluck('id');
                $avgKuis = $kuisIds->isNotEmpty() 
                    ? HasilKuis::whereIn('kuis_id', $kuisIds)->where('is_submitted', true)->avg('nilai_akhir') 
                    : null;

                return [
                    'id' => $kelas->id,
                    'nama_kelas' => $kelas->nama_kelas,
                    'tahun_ajaran' => $kelas->tahun_ajaran,
                    'wali_kelas' => $kelas->waliKelas ? $kelas->waliKelas->name : '-',
                    'siswa_count' => $kelas->siswa_count,
                    'avg_tugas' => !is_null($avgTugas) ? round($avgTugas, 1) : null,
                    'avg_kuis' => !is_null($avgKuis) ? round($avgKuis, 1) : null,
                ];
            });

        return view('admin.laporan.index', compact(
            'totalTeachers', 'totalStudents', 'totalClasses', 'totalSubjects', 'totalAssignments', 'totalQuizzes', 'classesData'
        ));
    }

    public function exportCsv()
    {
        $response = new StreamedResponse(function () {
            $handle = fopen('php://output', 'w');
            
            // Write UTF-8 BOM for Excel compatibility
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            // Write CSV headers
            fputcsv($handle, [
                'Nama Kelas',
                'Tahun Ajaran',
                'Wali Kelas',
                'Jumlah Siswa',
                'Rata-rata Tugas',
                'Rata-rata Kuis'
            ], ';');

            $classes = Kelas::with(['waliKelas', 'tugas', 'kuis'])->get();

            foreach ($classes as $kelas) {
                $tugasIds = $kelas->tugas->pluck('id');
                $avgTugas = $tugasIds->isNotEmpty() 
                    ? PengumpulanTugas::whereIn('tugas_id', $tugasIds)->where('status', 'dinilai')->avg('nilai') 
                    : null;

                $kuisIds = $kelas->kuis->pluck('id');
                $avgKuis = $kuisIds->isNotEmpty() 
                    ? HasilKuis::whereIn('kuis_id', $kuisIds)->where('is_submitted', true)->avg('nilai_akhir') 
                    : null;

                fputcsv($handle, [
                    $kelas->nama_kelas,
                    $kelas->tahun_ajaran,
                    $kelas->waliKelas ? $kelas->waliKelas->name : '-',
                    $kelas->siswa()->count(),
                    !is_null($avgTugas) ? round($avgTugas, 1) : '-',
                    !is_null($avgKuis) ? round($avgKuis, 1) : '-'
                ], ';');
            }

            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv; charset=utf-8');
        $response->headers->set('Content-Disposition', 'attachment; filename="laporan-lms-' . date('Y-m-d') . '.csv"');

        return $response;
    }
}
