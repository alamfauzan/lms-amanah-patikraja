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

    public function index(Request $request, $kelasId)
    {
        $kelas = Kelas::findOrFail($kelasId);
        $this->authorizeKelasAccess($kelas);

        $mapelId = $request->query('mapel_id');
        $mapel = null;

        $query = $kelas->pertemuan();
        if ($mapelId) {
            $mapel = \App\Models\MataPelajaran::findOrFail($mapelId);
            $query->where('mata_pelajaran_id', $mapelId);
        }

        $pertemuan = $query->with(['materi', 'tugas', 'kuis'])->orderBy('urutan')->get();

        return view('pertemuan.index', compact('kelas', 'pertemuan', 'mapel'));
    }

    public function create(Request $request, $kelasId)
    {
        $kelas = Kelas::findOrFail($kelasId);
        if (!auth()->user()->hasAnyRole(['admin', 'guru'])) abort(403);

        $user = auth()->user();
        if ($user->hasRole('admin')) {
            $mapels = $kelas->kelasMapelGuru()->with('mataPelajaran')->get()->map->mataPelajaran->unique();
        } else {
            $mapels = $kelas->kelasMapelGuru()->where('guru_id', $user->id)->with('mataPelajaran')->get()->map->mataPelajaran->unique();
        }

        $preselectedMapelId = $request->query('mapel_id');

        return view('pertemuan.create', compact('kelas', 'mapels', 'preselectedMapelId'));
    }

    public function store(Request $request, $kelasId)
    {
        $kelas = Kelas::findOrFail($kelasId);
        if (!auth()->user()->hasAnyRole(['admin', 'guru'])) abort(403);

        $validated = $request->validate([
            'mata_pelajaran_id' => 'required|exists:mata_pelajaran,id',
            'judul'    => 'required|string|max:255',
            'urutan'   => 'required|integer|min:1',
            'deskripsi' => 'nullable|string',
            'tanggal'  => 'nullable|date',
        ]);

        $user = auth()->user();
        if (!$user->hasRole('admin')) {
            $isAssigned = $kelas->kelasMapelGuru()
                ->where('guru_id', $user->id)
                ->where('mata_pelajaran_id', $validated['mata_pelajaran_id'])
                ->exists();
            if (!$isAssigned) abort(403, 'Anda tidak mengampu mata pelajaran ini di kelas ini.');
        }

        $kelas->pertemuan()->create($validated);

        return redirect()->route('kelas.pertemuan.index', [$kelasId, 'mapel_id' => $validated['mata_pelajaran_id']])
            ->with('success', 'Pertemuan berhasil dibuat!');
    }

    public function show($kelasId, $id)
    {
        $kelas = Kelas::findOrFail($kelasId);
        $this->authorizeKelasAccess($kelas);

        $pertemuan = Pertemuan::with(['materi', 'tugas', 'kuis', 'mataPelajaran'])->findOrFail($id);

        return view('pertemuan.show', compact('kelas', 'pertemuan'));
    }

    public function edit($kelasId, $id)
    {
        $kelas = Kelas::findOrFail($kelasId);
        if (!auth()->user()->hasAnyRole(['admin', 'guru'])) abort(403);

        $pertemuan = Pertemuan::findOrFail($id);

        $user = auth()->user();
        if ($user->hasRole('admin')) {
            $mapels = $kelas->kelasMapelGuru()->with('mataPelajaran')->get()->map->mataPelajaran->unique();
        } else {
            $mapels = $kelas->kelasMapelGuru()->where('guru_id', $user->id)->with('mataPelajaran')->get()->map->mataPelajaran->unique();
        }

        return view('pertemuan.edit', compact('kelas', 'pertemuan', 'mapels'));
    }

    public function update(Request $request, $kelasId, $id)
    {
        $kelas = Kelas::findOrFail($kelasId);
        if (!auth()->user()->hasAnyRole(['admin', 'guru'])) abort(403);

        $validated = $request->validate([
            'mata_pelajaran_id' => 'required|exists:mata_pelajaran,id',
            'judul'    => 'required|string|max:255',
            'urutan'   => 'required|integer|min:1',
            'deskripsi' => 'nullable|string',
            'tanggal'  => 'nullable|date',
        ]);

        $user = auth()->user();
        if (!$user->hasRole('admin')) {
            $isAssigned = $kelas->kelasMapelGuru()
                ->where('guru_id', $user->id)
                ->where('mata_pelajaran_id', $validated['mata_pelajaran_id'])
                ->exists();
            if (!$isAssigned) abort(403, 'Anda tidak mengampu mata pelajaran ini di kelas ini.');
        }

        $pertemuan = Pertemuan::findOrFail($id);
        $pertemuan->update($validated);

        return redirect()->route('kelas.pertemuan.index', [$kelasId, 'mapel_id' => $validated['mata_pelajaran_id']])
            ->with('success', 'Pertemuan berhasil diupdate!');
    }

    public function destroy($kelasId, $id)
    {
        if (!auth()->user()->hasAnyRole(['admin', 'guru'])) abort(403);

        $pertemuan = Pertemuan::findOrFail($id);
        $mapelId = $pertemuan->mata_pelajaran_id;
        $pertemuan->delete();

        return redirect()->route('kelas.pertemuan.index', [$kelasId, 'mapel_id' => $mapelId])
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
