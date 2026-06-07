<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;

class TahunAjaranController extends Controller
{
    public function index()
    {
        $years = TahunAjaran::latest()->paginate(10);
        return view('admin.tahun-ajaran.index', compact('years'));
    }

    public function create()
    {
        return view('admin.tahun-ajaran.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'tahun_ajaran' => ['required', 'string', 'max:255', 'unique:tahun_ajaran'],
        ]);

        TahunAjaran::create([
            'tahun_ajaran' => $request->tahun_ajaran,
            'is_aktif' => false,
        ]);

        return redirect()->route('admin.tahun-ajaran.index')
            ->with('success', 'Tahun Ajaran berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $year = TahunAjaran::findOrFail($id);
        return view('admin.tahun-ajaran.edit', compact('year'));
    }

    public function update(Request $request, $id)
    {
        $year = TahunAjaran::findOrFail($id);

        $request->validate([
            'tahun_ajaran' => ['required', 'string', 'max:255', 'unique:tahun_ajaran,tahun_ajaran,' . $year->id],
        ]);

        $year->update([
            'tahun_ajaran' => $request->tahun_ajaran,
        ]);

        return redirect()->route('admin.tahun-ajaran.index')
            ->with('success', 'Tahun Ajaran berhasil diubah.');
    }

    public function destroy($id)
    {
        $year = TahunAjaran::findOrFail($id);
        $year->delete();

        return redirect()->route('admin.tahun-ajaran.index')
            ->with('success', 'Tahun Ajaran berhasil dihapus.');
    }

    public function aktifkan($id)
    {
        $year = TahunAjaran::findOrFail($id);

        // Deactivate all years
        TahunAjaran::query()->update(['is_aktif' => false]);

        // Activate the selected one
        $year->update(['is_aktif' => true]);

        return redirect()->route('admin.tahun-ajaran.index')
            ->with('success', 'Tahun Ajaran "' . $year->tahun_ajaran . '" berhasil diaktifkan.');
    }
}
