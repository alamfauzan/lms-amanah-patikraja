<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Pertemuan;
use App\Models\Tugas;
use App\Models\PengumpulanTugas;
use App\Models\Notifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TugasController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * List all tugas for a class (guru/admin) or just enrolled siswa's tugas
     */
    public function index(Request $request, $kelasId = null)
    {
        $user = auth()->user();
        $mapelId = $request->query('mapel_id');
        $filter = $request->query('filter', 'semua');
        $mapel = null;

        if ($kelasId) {
            $kelas = Kelas::findOrFail($kelasId);
            $query = Tugas::where('kelas_id', $kelasId);
            if ($mapelId) {
                $mapel = \App\Models\MataPelajaran::findOrFail($mapelId);
                $query->where('mata_pelajaran_id', $mapelId);
            }
            $allTugas = $query->with(['guru', 'pertemuan', 'mataPelajaran'])->latest()->get();

            $statusMap = [];
            if ($user->hasRole('siswa')) {
                foreach ($allTugas as $t) {
                    $statusMap[$t->id] = $t->pengumpulanSiswa($user->id) ? 'selesai' : (now()->gt($t->deadline) ? 'overdue' : 'belum');
                }
                // Apply filter
                if ($filter !== 'semua') {
                    $tugas = $allTugas->filter(fn($t) => ($statusMap[$t->id] ?? 'belum') === $filter)->values();
                } else {
                    $tugas = $allTugas;
                }
            } else {
                $tugas = $allTugas;
            }

            return view('tugas.index', compact('tugas', 'kelas', 'mapel', 'statusMap'));
        }

        // Global listing per role
        if ($user->hasRole('guru')) {
            $tugas = Tugas::where('guru_id', $user->id)->with(['kelas', 'pertemuan'])->latest()->get();
        } elseif ($user->hasRole('siswa')) {
            $kelasIds = $user->siswaKelas()->pluck('kelas.id');
            $tugasQuery = Tugas::whereIn('kelas_id', $kelasIds)->with(['kelas', 'guru']);
            
            $allTugas = $tugasQuery->get();
            $statusMap = [];
            foreach ($allTugas as $t) {
                $statusMap[$t->id] = $t->pengumpulanSiswa($user->id) ? 'selesai' : (now()->gt($t->deadline) ? 'overdue' : 'belum');
            }

            if ($filter !== 'semua') {
                $tugas = $allTugas->filter(function ($t) use ($statusMap, $filter) {
                    return $statusMap[$t->id] === $filter;
                });
            } else {
                $tugas = $allTugas;
            }
            return view('tugas.index', compact('tugas', 'statusMap'));
        } else {
            $tugas = Tugas::with(['kelas', 'guru'])->latest()->get();
        }

        return view('tugas.index', compact('tugas'));
    }

    public function create(Request $request, $kelasId)
    {
        if (!auth()->user()->hasAnyRole(['admin', 'guru'])) abort(403);
        $kelas = Kelas::with('pertemuan')->findOrFail($kelasId);

        $user = auth()->user();
        if ($user->hasRole('admin')) {
            $mapels = $kelas->kelasMapelGuru()->with('mataPelajaran')->get()->map->mataPelajaran->unique();
        } else {
            $mapels = $kelas->kelasMapelGuru()->where('guru_id', $user->id)->with('mataPelajaran')->get()->map->mataPelajaran->unique();
        }

        $preselectedMapelId = $request->query('mapel_id');
        $preselectedPertemuanId = $request->query('pertemuan_id');

        $pertemuan = $kelas->pertemuan;
        if ($preselectedMapelId) {
            $pertemuan = $pertemuan->where('mata_pelajaran_id', $preselectedMapelId);
        }

        return view('tugas.create', compact('kelas', 'mapels', 'preselectedMapelId', 'preselectedPertemuanId', 'pertemuan'));
    }

    public function store(Request $request, $kelasId)
    {
        if (!auth()->user()->hasAnyRole(['admin', 'guru'])) abort(403);
        $kelas = Kelas::findOrFail($kelasId);

        $validated = $request->validate([
            'mata_pelajaran_id' => 'required|exists:mata_pelajaran,id',
            'judul'          => 'required|string|max:255',
            'deskripsi'      => 'nullable|string',
            'deadline'       => 'required|date',
            'nilai_maksimum' => 'required|integer|min:1|max:100',
            'pertemuan_id'   => 'nullable|exists:pertemuan,id',
            'file'           => 'nullable|file|max:10240',
        ]);

        $user = auth()->user();
        if (!$user->hasRole('admin')) {
            $isAssigned = $kelas->kelasMapelGuru()
                ->where('guru_id', $user->id)
                ->where('mata_pelajaran_id', $validated['mata_pelajaran_id'])
                ->exists();
            if (!$isAssigned) abort(403, 'Anda tidak mengampu mata pelajaran ini di kelas ini.');
        }

        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('tugas/attachments', 'public');
        }

        $tugas = Tugas::create([
            'kelas_id'          => $kelasId,
            'mata_pelajaran_id' => $validated['mata_pelajaran_id'],
            'guru_id'           => auth()->id(),
            'pertemuan_id'      => $validated['pertemuan_id'] ?? null,
            'judul'             => $validated['judul'],
            'deskripsi'         => $validated['deskripsi'] ?? null,
            'deadline'          => $validated['deadline'],
            'nilai_maksimum'    => $validated['nilai_maksimum'],
            'file_path'         => $filePath,
        ]);

        // Notify all siswa in class
        $kelas = Kelas::with('siswa')->findOrFail($kelasId);
        foreach ($kelas->siswa as $siswa) {
            Notifikasi::create([
                'user_id' => $siswa->id,
                'judul'   => 'Tugas Baru: ' . $tugas->judul,
                'pesan'   => 'Ada tugas baru di kelas ' . $kelas->nama_kelas . '. Deadline: ' . $tugas->deadline->format('d M Y H:i'),
                'tipe'    => 'tugas_baru',
                'link'    => route('tugas.show', [$kelasId, $tugas->id]),
            ]);
        }

        return redirect()->route('tugas.show', [$kelasId, $tugas->id])
            ->with('success', 'Tugas berhasil dibuat!');
    }

    public function show($kelasId, $id)
    {
        $kelas = Kelas::findOrFail($kelasId);
        $tugas = Tugas::with(['guru', 'pertemuan.mataPelajaran', 'pengumpulan.siswa', 'mataPelajaran'])->findOrFail($id);
        $user  = auth()->user();

        $pengumpulan = null;
        if ($user->hasRole('siswa')) {
            $pengumpulan = $tugas->pengumpulanSiswa($user->id);
        }

        return view('tugas.show', compact('kelas', 'tugas', 'pengumpulan'));
    }

    public function edit($kelasId, $id)
    {
        if (!auth()->user()->hasAnyRole(['admin', 'guru'])) abort(403);
        $kelas = Kelas::with('pertemuan')->findOrFail($kelasId);
        $tugas = Tugas::findOrFail($id);

        $user = auth()->user();
        if ($user->hasRole('admin')) {
            $mapels = $kelas->kelasMapelGuru()->with('mataPelajaran')->get()->map->mataPelajaran->unique();
        } else {
            $mapels = $kelas->kelasMapelGuru()->where('guru_id', $user->id)->with('mataPelajaran')->get()->map->mataPelajaran->unique();
        }

        $pertemuan = $kelas->pertemuan->where('mata_pelajaran_id', $tugas->mata_pelajaran_id);

        return view('tugas.edit', compact('kelas', 'tugas', 'mapels', 'pertemuan'));
    }

    public function update(Request $request, $kelasId, $id)
    {
        if (!auth()->user()->hasAnyRole(['admin', 'guru'])) abort(403);
        $kelas = Kelas::findOrFail($kelasId);
        $tugas = Tugas::findOrFail($id);

        $validated = $request->validate([
            'mata_pelajaran_id' => 'required|exists:mata_pelajaran,id',
            'judul'          => 'required|string|max:255',
            'deskripsi'      => 'nullable|string',
            'deadline'       => 'required|date',
            'nilai_maksimum' => 'required|integer|min:1|max:100',
            'pertemuan_id'   => 'nullable|exists:pertemuan,id',
            'file'           => 'nullable|file|max:10240',
            'hapus_berkas'   => 'nullable|boolean',
        ]);

        $user = auth()->user();
        if (!$user->hasRole('admin')) {
            $isAssigned = $kelas->kelasMapelGuru()
                ->where('guru_id', $user->id)
                ->where('mata_pelajaran_id', $validated['mata_pelajaran_id'])
                ->exists();
            if (!$isAssigned) abort(403, 'Anda tidak mengampu mata pelajaran ini di kelas ini.');
        }

        $filePath = $tugas->file_path;

        if ($request->hapus_berkas && $filePath) {
            Storage::disk('public')->delete($filePath);
            $filePath = null;
        }

        if ($request->hasFile('file')) {
            if ($tugas->file_path) {
                Storage::disk('public')->delete($tugas->file_path);
            }
            $filePath = $request->file('file')->store('tugas/attachments', 'public');
        }

        $tugas->update([
            'mata_pelajaran_id' => $validated['mata_pelajaran_id'],
            'judul'             => $validated['judul'],
            'deskripsi'         => $validated['deskripsi'],
            'deadline'          => $validated['deadline'],
            'nilai_maksimum'    => $validated['nilai_maksimum'],
            'pertemuan_id'      => $validated['pertemuan_id'] ?? null,
            'file_path'         => $filePath,
        ]);

        return redirect()->route('tugas.show', [$kelasId, $id])
            ->with('success', 'Tugas berhasil diperbarui!');
    }

    public function destroy($kelasId, $id)
    {
        if (!auth()->user()->hasAnyRole(['admin', 'guru'])) abort(403);
        $tugas = Tugas::findOrFail($id);
        $mapelId = $tugas->mata_pelajaran_id;
        if ($tugas->file_path) {
            Storage::disk('public')->delete($tugas->file_path);
        }
        $tugas->delete();

        return redirect()->route('kelas.pertemuan.index', [$kelasId, 'mapel_id' => $mapelId])
            ->with('success', 'Tugas berhasil dihapus!');
    }

    /**
     * Siswa upload/submit jawaban
     */
    public function submit(Request $request, $kelasId, $id)
    {
        $user  = auth()->user();
        if (!$user->hasRole('siswa')) abort(403);

        $tugas = Tugas::findOrFail($id);
        if (now()->gt($tugas->deadline)) {
            return back()->with('error', 'Deadline telah lewat, tidak dapat mengumpulkan tugas.');
        }

        $request->validate([
            'file_jawaban' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,zip|max:10240',
            'catatan'      => 'nullable|string',
        ]);

        $pengumpulan = PengumpulanTugas::where(['tugas_id' => $id, 'siswa_id' => $user->id])->first();
        $filePath = $pengumpulan ? $pengumpulan->file_jawaban : null;

        if ($request->hasFile('file_jawaban')) {
            if ($pengumpulan && $pengumpulan->file_jawaban) {
                Storage::disk('public')->delete($pengumpulan->file_jawaban);
            }
            $filePath = $request->file('file_jawaban')->store('tugas/' . $id, 'public');
        } elseif ($request->input('hapus_file_jawaban') == '1') {
            if ($pengumpulan && $pengumpulan->file_jawaban) {
                Storage::disk('public')->delete($pengumpulan->file_jawaban);
            }
            $filePath = null;
        }

        $isDraft = $request->input('action') === 'draft';
        $status = ($filePath && !$isDraft) ? 'terkumpul' : 'belum';

        $pengumpulan = PengumpulanTugas::updateOrCreate(
            ['tugas_id' => $id, 'siswa_id' => $user->id],
            [
                'file_jawaban'    => $filePath,
                'catatan'         => $request->catatan,
                'dikumpulkan_at'  => now(),
                'status'          => $status,
            ]
        );

        if ($status === 'terkumpul') {
            // Notify guru
            Notifikasi::create([
                'user_id' => $tugas->guru_id,
                'judul'   => 'Tugas Dikumpulkan',
                'pesan'   => $user->name . ' mengumpulkan tugas "' . $tugas->judul . '".',
                'tipe'    => 'pengumpulan',
                'link'    => route('tugas.show', [$kelasId, $id]),
            ]);

            return back()->with('success', 'Tugas berhasil dikumpulkan!');
        }

        return back()->with('success', 'Draft jawaban berhasil disimpan!');
    }

    /**
     * Guru beri nilai & feedback
     */
    public function grade(Request $request, $kelasId, $id, $pengumpulanId)
    {
        if (!auth()->user()->hasAnyRole(['admin', 'guru'])) abort(403);

        $request->validate([
            'nilai'    => 'required|numeric|min:0|max:100',
            'feedback' => 'nullable|string',
        ]);

        $pengumpulan = PengumpulanTugas::findOrFail($pengumpulanId);
        $pengumpulan->update([
            'nilai'    => $request->nilai,
            'feedback' => $request->feedback,
            'status'   => 'dinilai',
        ]);

        // Notify siswa
        Notifikasi::create([
            'user_id' => $pengumpulan->siswa_id,
            'judul'   => 'Nilai Tersedia',
            'pesan'   => 'Tugas "' . $pengumpulan->tugas->judul . '" telah dinilai. Nilai: ' . $request->nilai,
            'tipe'    => 'nilai_tersedia',
            'link'    => route('tugas.show', [$kelasId, $id]),
        ]);

        return back()->with('success', 'Nilai berhasil diberikan!');
    }
}
