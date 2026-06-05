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
    public function index($kelasId = null)
    {
        $user = auth()->user();

        if ($kelasId) {
            $kelas = Kelas::findOrFail($kelasId);
            $tugas = Tugas::where('kelas_id', $kelasId)
                ->with(['guru', 'pertemuan'])
                ->latest()
                ->get();
            return view('tugas.index', compact('tugas', 'kelas'));
        }

        // Global listing per role
        if ($user->hasRole('guru')) {
            $tugas = Tugas::where('guru_id', $user->id)->with(['kelas', 'pertemuan'])->latest()->get();
        } elseif ($user->hasRole('siswa')) {
            $kelasIds = $user->siswaKelas()->pluck('kelas.id');
            $tugas = Tugas::whereIn('kelas_id', $kelasIds)->with(['kelas', 'guru'])->latest()->get();
        } else {
            $tugas = Tugas::with(['kelas', 'guru'])->latest()->get();
        }

        return view('tugas.index', compact('tugas'));
    }

    public function create($kelasId)
    {
        if (!auth()->user()->hasAnyRole(['admin', 'guru'])) abort(403);
        $kelas = Kelas::with('pertemuan')->findOrFail($kelasId);
        return view('tugas.create', compact('kelas'));
    }

    public function store(Request $request, $kelasId)
    {
        if (!auth()->user()->hasAnyRole(['admin', 'guru'])) abort(403);

        $validated = $request->validate([
            'judul'          => 'required|string|max:255',
            'deskripsi'      => 'nullable|string',
            'deadline'       => 'required|date',
            'nilai_maksimum' => 'required|integer|min:1|max:100',
            'pertemuan_id'   => 'nullable|exists:pertemuan,id',
        ]);

        $validated['kelas_id'] = $kelasId;
        $validated['guru_id']  = auth()->id();

        $tugas = Tugas::create($validated);

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

        return redirect()->route('kelas.tugas.index', $kelasId)
            ->with('success', 'Tugas berhasil dibuat!');
    }

    public function show($kelasId, $id)
    {
        $kelas = Kelas::findOrFail($kelasId);
        $tugas = Tugas::with(['guru', 'pertemuan', 'pengumpulan.siswa'])->findOrFail($id);
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
        return view('tugas.edit', compact('kelas', 'tugas'));
    }

    public function update(Request $request, $kelasId, $id)
    {
        if (!auth()->user()->hasAnyRole(['admin', 'guru'])) abort(403);

        $validated = $request->validate([
            'judul'          => 'required|string|max:255',
            'deskripsi'      => 'nullable|string',
            'deadline'       => 'required|date',
            'nilai_maksimum' => 'required|integer|min:1|max:100',
            'pertemuan_id'   => 'nullable|exists:pertemuan,id',
        ]);

        Tugas::findOrFail($id)->update($validated);

        return redirect()->route('kelas.tugas.index', $kelasId)
            ->with('success', 'Tugas berhasil diupdate!');
    }

    public function destroy($kelasId, $id)
    {
        if (!auth()->user()->hasAnyRole(['admin', 'guru'])) abort(403);
        Tugas::findOrFail($id)->delete();
        return redirect()->route('kelas.tugas.index', $kelasId)
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

        $filePath = null;
        if ($request->hasFile('file_jawaban')) {
            $filePath = $request->file('file_jawaban')->store('tugas/' . $id, 'public');
        }

        $pengumpulan = PengumpulanTugas::updateOrCreate(
            ['tugas_id' => $id, 'siswa_id' => $user->id],
            [
                'file_jawaban'    => $filePath ?? optional(PengumpulanTugas::where(['tugas_id' => $id, 'siswa_id' => $user->id])->first())->file_jawaban,
                'catatan'         => $request->catatan,
                'dikumpulkan_at'  => now(),
                'status'          => 'terkumpul',
            ]
        );

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
