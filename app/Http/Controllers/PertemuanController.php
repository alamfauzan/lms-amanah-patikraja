<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Pertemuan;
use Illuminate\Http\Request;

class PertemuanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index($kelasId)
    {
        $kelas = Kelas::findOrFail($kelasId);
        $this->authorizeKelasAccess($kelas);

        $pertemuan = $kelas->pertemuan()->withCount(['materi', 'tugas', 'kuis'])->orderBy('urutan')->get();

        return view('pertemuan.index', compact('kelas', 'pertemuan'));
    }

    public function create($kelasId)
    {
        $kelas = Kelas::findOrFail($kelasId);
        if (!auth()->user()->hasAnyRole(['admin', 'guru'])) abort(403);

        return view('pertemuan.create', compact('kelas'));
    }

    public function store(Request $request, $kelasId)
    {
        $kelas = Kelas::findOrFail($kelasId);
        if (!auth()->user()->hasAnyRole(['admin', 'guru'])) abort(403);

        $validated = $request->validate([
            'judul'    => 'required|string|max:255',
            'urutan'   => 'required|integer|min:1',
            'deskripsi' => 'nullable|string',
            'tanggal'  => 'nullable|date',
        ]);

        $kelas->pertemuan()->create($validated);

        return redirect()->route('kelas.pertemuan.index', $kelasId)
            ->with('success', 'Pertemuan berhasil dibuat!');
    }

    public function show($kelasId, $id)
    {
        $kelas = Kelas::findOrFail($kelasId);
        $this->authorizeKelasAccess($kelas);

        $pertemuan = Pertemuan::with(['materi', 'tugas', 'kuis'])->findOrFail($id);

        return view('pertemuan.show', compact('kelas', 'pertemuan'));
    }

    public function edit($kelasId, $id)
    {
        $kelas = Kelas::findOrFail($kelasId);
        if (!auth()->user()->hasAnyRole(['admin', 'guru'])) abort(403);

        $pertemuan = Pertemuan::findOrFail($id);

        return view('pertemuan.edit', compact('kelas', 'pertemuan'));
    }

    public function update(Request $request, $kelasId, $id)
    {
        $kelas = Kelas::findOrFail($kelasId);
        if (!auth()->user()->hasAnyRole(['admin', 'guru'])) abort(403);

        $validated = $request->validate([
            'judul'    => 'required|string|max:255',
            'urutan'   => 'required|integer|min:1',
            'deskripsi' => 'nullable|string',
            'tanggal'  => 'nullable|date',
        ]);

        $pertemuan = Pertemuan::findOrFail($id);
        $pertemuan->update($validated);

        return redirect()->route('kelas.pertemuan.index', $kelasId)
            ->with('success', 'Pertemuan berhasil diupdate!');
    }

    public function destroy($kelasId, $id)
    {
        if (!auth()->user()->hasAnyRole(['admin', 'guru'])) abort(403);

        Pertemuan::findOrFail($id)->delete();

        return redirect()->route('kelas.pertemuan.index', $kelasId)
            ->with('success', 'Pertemuan berhasil dihapus!');
    }

    protected function authorizeKelasAccess(Kelas $kelas)
    {
        $user = auth()->user();
        if ($user->hasRole('admin')) return;
        if ($user->hasRole('guru')) {
            $ok = $kelas->wali_kelas_id == $user->id
                || $kelas->kelasMapelGuru()->where('guru_id', $user->id)->exists();
            if (!$ok) abort(403);
        } else {
            if (!$kelas->siswa()->where('siswa_id', $user->id)->exists()) abort(403);
        }
    }
}
