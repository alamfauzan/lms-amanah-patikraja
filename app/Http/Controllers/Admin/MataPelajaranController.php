<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MataPelajaran;
use Illuminate\Http\Request;

class MataPelajaranController extends Controller
{
    public function index()
    {
        $mapels = MataPelajaran::latest()->paginate(10);
        return view('admin.mapel.index', compact('mapels'));
    }

    public function create()
    {
        return view('admin.mapel.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_mapel' => ['required', 'string', 'max:255'],
            'kode_mapel' => ['required', 'string', 'max:255', 'unique:mata_pelajaran'],
        ]);

        MataPelajaran::create([
            'nama_mapel' => $request->nama_mapel,
            'kode_mapel' => $request->kode_mapel,
        ]);

        return redirect()->route('admin.mapel.index')
            ->with('success', 'Mata Pelajaran berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $mapel = MataPelajaran::findOrFail($id);
        return view('admin.mapel.edit', compact('mapel'));
    }

    public function update(Request $request, $id)
    {
        $mapel = MataPelajaran::findOrFail($id);

        $request->validate([
            'nama_mapel' => ['required', 'string', 'max:255'],
            'kode_mapel' => ['required', 'string', 'max:255', 'unique:mata_pelajaran,kode_mapel,' . $mapel->id],
        ]);

        $mapel->update([
            'nama_mapel' => $request->nama_mapel,
            'kode_mapel' => $request->kode_mapel,
        ]);

        return redirect()->route('admin.mapel.index')
            ->with('success', 'Mata Pelajaran berhasil diubah.');
    }

    public function destroy($id)
    {
        $mapel = MataPelajaran::findOrFail($id);
        $mapel->delete();

        return redirect()->route('admin.mapel.index')
            ->with('success', 'Mata Pelajaran berhasil dihapus.');
    }
}
