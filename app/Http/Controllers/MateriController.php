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
        $pertemuan = Pertemuan::findOrFail($pertemuanId);
        return view('materi.create', compact('kelas', 'pertemuan'));
    }

    public function store(Request $request, $kelasId, $pertemuanId)
    {
        if (!auth()->user()->hasAnyRole(['admin', 'guru'])) abort(403);

        $validated = $request->validate([
            'judul'  => 'required|string|max:255',
            'tipe'   => 'required|in:teks,file,video',
            'konten' => 'required_if:tipe,teks|nullable|string',
            'file'   => 'required_if:tipe,file|nullable|file|mimes:pdf,doc,docx,ppt,pptx|max:20480',
            'file_video' => 'required_if:tipe,video|nullable|file|mimes:mp4,avi,mkv|max:102400',
        ]);

        $filePath = null;
        if ($request->tipe === 'file' && $request->hasFile('file')) {
            $filePath = $request->file('file')->store('materi/files', 'public');
        } elseif ($request->tipe === 'video' && $request->hasFile('file_video')) {
            $filePath = $request->file('file_video')->store('materi/videos', 'public');
        }

        $materi = Materi::create([
            'pertemuan_id' => $pertemuanId,
            'kelas_id'     => $kelasId,
            'guru_id'      => auth()->id(),
            'judul'        => $validated['judul'],
            'tipe'         => $validated['tipe'],
            'konten'       => $validated['tipe'] === 'teks' ? $validated['konten'] : null,
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

        return redirect()->route('kelas.pertemuan.show', [$kelasId, $pertemuanId])
            ->with('success', 'Materi berhasil ditambahkan!');
    }

    public function show($id)
    {
        $materi = Materi::with(['pertemuan', 'kelas', 'guru'])->findOrFail($id);
        return view('materi.show', compact('materi'));
    }

    public function edit($id)
    {
        if (!auth()->user()->hasAnyRole(['admin', 'guru'])) abort(403);
        $materi = Materi::with(['pertemuan', 'kelas'])->findOrFail($id);
        return view('materi.edit', compact('materi'));
    }

    public function update(Request $request, $id)
    {
        if (!auth()->user()->hasAnyRole(['admin', 'guru'])) abort(403);

        $materi    = Materi::findOrFail($id);
        $validated = $request->validate([
            'judul'  => 'required|string|max:255',
            'konten' => 'nullable|string',
        ]);

        $materi->update($validated);
        return redirect()->route('materi.show', $id)->with('success', 'Materi berhasil diupdate!');
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
