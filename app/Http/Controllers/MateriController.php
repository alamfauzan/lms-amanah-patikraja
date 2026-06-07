<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Materi;
use App\Models\Pertemuan;
use App\Models\Notifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MateriController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index($kelasId)
    {
        $kelas  = Kelas::findOrFail($kelasId);
        $materi = Materi::where('kelas_id', $kelasId)
            ->with(['pertemuan', 'guru'])
            ->latest()
            ->get();
        return view('materi.index', compact('kelas', 'materi'));
    }

    public function create($kelasId, $pertemuanId)
    {
        if (!auth()->user()->hasAnyRole(['admin', 'guru'])) abort(403);
        $kelas     = Kelas::findOrFail($kelasId);
        $pertemuan = Pertemuan::with('mataPelajaran')->findOrFail($pertemuanId);
        return view('materi.create', compact('kelas', 'pertemuan'));
    }

    public function store(Request $request, $kelasId, $pertemuanId)
    {
        if (!auth()->user()->hasAnyRole(['admin', 'guru'])) abort(403);

        $validated = $request->validate([
            'judul'  => 'required|string|max:255',
            'konten' => 'nullable|string',
            'file'   => 'nullable|file|max:102400', // max 100MB
        ]);

        $filePath = null;
        $tipe = 'teks';

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $extension = strtolower($file->getClientOriginalExtension());
            $videoExtensions = ['mp4', 'avi', 'mkv', 'webm', 'mov'];
            
            if (in_array($extension, $videoExtensions)) {
                $filePath = $file->store('materi/videos', 'public');
                $tipe = 'video';
            } else {
                $filePath = $file->store('materi/files', 'public');
                $tipe = 'file';
            }
        }

        $materi = Materi::create([
            'pertemuan_id' => $pertemuanId,
            'kelas_id'     => $kelasId,
            'guru_id'      => auth()->id(),
            'judul'        => $validated['judul'],
            'tipe'         => $tipe,
            'konten'       => $validated['konten'],
            'file_path'    => $filePath,
        ]);

        // Notify all siswa
        $kelas = Kelas::with('siswa')->findOrFail($kelasId);
        foreach ($kelas->siswa as $siswa) {
            Notifikasi::create([
                'user_id' => $siswa->id,
                'judul'   => 'Materi Baru: ' . $materi->judul,
                'pesan'   => 'Materi baru telah ditambahkan di kelas ' . $kelas->nama_kelas,
                'tipe'    => 'materi_baru',
                'link'    => route('materi.show', $materi->id),
            ]);
        }

        $pertemuan = Pertemuan::findOrFail($pertemuanId);
        return redirect()->route('kelas.pertemuan.index', [$kelasId, 'mapel_id' => $pertemuan->mata_pelajaran_id])
            ->with('success', 'Materi berhasil ditambahkan!');
    }

    public function show($id)
    {
        $materi = Materi::with(['pertemuan.mataPelajaran', 'kelas', 'guru'])->findOrFail($id);
        return view('materi.show', compact('materi'));
    }

    public function edit($id)
    {
        if (!auth()->user()->hasAnyRole(['admin', 'guru'])) abort(403);
        $materi = Materi::with(['pertemuan.mataPelajaran', 'kelas'])->findOrFail($id);
        return view('materi.edit', compact('materi'));
    }

    public function update(Request $request, $id)
    {
        if (!auth()->user()->hasAnyRole(['admin', 'guru'])) abort(403);

        $materi = Materi::with('pertemuan')->findOrFail($id);
        $validated = $request->validate([
            'judul'  => 'required|string|max:255',
            'konten' => 'nullable|string',
            'file'   => 'nullable|file|max:102400',
            'hapus_berkas' => 'nullable|boolean',
        ]);

        $filePath = $materi->file_path;
        $tipe = $materi->tipe;

        if ($request->hapus_berkas && $filePath) {
            Storage::disk('public')->delete($filePath);
            $filePath = null;
            $tipe = 'teks';
        }

        if ($request->hasFile('file')) {
            if ($materi->file_path) {
                Storage::disk('public')->delete($materi->file_path);
            }

            $file = $request->file('file');
            $extension = strtolower($file->getClientOriginalExtension());
            $videoExtensions = ['mp4', 'avi', 'mkv', 'webm', 'mov'];
            
            if (in_array($extension, $videoExtensions)) {
                $filePath = $file->store('materi/videos', 'public');
                $tipe = 'video';
            } else {
                $filePath = $file->store('materi/files', 'public');
                $tipe = 'file';
            }
        } elseif ($filePath === null) {
            $tipe = 'teks';
        }

        $materi->update([
            'judul'     => $validated['judul'],
            'konten'    => $validated['konten'],
            'file_path' => $filePath,
            'tipe'      => $tipe,
        ]);

        return redirect()->route('materi.show', $id)->with('success', 'Materi berhasil diperbarui!');
    }

    public function destroy($id)
    {
        if (!auth()->user()->hasAnyRole(['admin', 'guru'])) abort(403);
        $materi = Materi::findOrFail($id);
        if ($materi->file_path) Storage::disk('public')->delete($materi->file_path);
        $kelasId     = $materi->kelas_id;
        $pertemuanId = $materi->pertemuan_id;
        $materi->delete();
        return redirect()->route('kelas.pertemuan.show', [$kelasId, $pertemuanId])
            ->with('success', 'Materi berhasil dihapus!');
    }
}
