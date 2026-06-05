<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Jadwal;
use App\Models\MataPelajaran;
use App\Models\User;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = auth()->user();
        $hariList = \App\Models\Jadwal::HARI;

        if ($user->hasRole('admin')) {
            $jadwal = Jadwal::with(['kelas', 'mataPelajaran', 'guru'])->orderBy('hari')->orderBy('jam_mulai')->get();
        } elseif ($user->hasRole('guru')) {
            $jadwal = Jadwal::where('guru_id', $user->id)->with(['kelas', 'mataPelajaran'])->orderBy('hari')->orderBy('jam_mulai')->get();
        } else {
            $kelasIds = $user->siswaKelas()->pluck('kelas.id');
            $jadwal = Jadwal::whereIn('kelas_id', $kelasIds)->with(['kelas', 'mataPelajaran', 'guru'])->orderBy('hari')->orderBy('jam_mulai')->get();
        }

        // Group by hari
        $jadwalByHari = $jadwal->groupBy('hari');

        return view('jadwal.index', compact('jadwalByHari', 'hariList'));
    }

    public function create()
    {
        if (!auth()->user()->hasRole('admin')) abort(403);
        $kelas       = Kelas::all();
        $mapel       = MataPelajaran::all();
        $guru        = User::role('guru')->get();
        $hariList    = \App\Models\Jadwal::HARI;
        return view('jadwal.create', compact('kelas', 'mapel', 'guru', 'hariList'));
    }

    public function store(Request $request)
    {
        if (!auth()->user()->hasRole('admin')) abort(403);

        $validated = $request->validate([
            'kelas_id'          => 'required|exists:kelas,id',
            'mata_pelajaran_id' => 'required|exists:mata_pelajaran,id',
            'guru_id'           => 'required|exists:users,id',
            'hari'              => 'required|integer|min:1|max:7',
            'jam_mulai'         => 'required',
            'jam_selesai'       => 'required',
            'ruangan'           => 'nullable|string|max:100',
        ]);

        Jadwal::create($validated);

        return redirect()->route('jadwal.index')->with('success', 'Jadwal berhasil ditambahkan!');
    }

    public function destroy($id)
    {
        if (!auth()->user()->hasRole('admin')) abort(403);
        Jadwal::findOrFail($id)->delete();
        return back()->with('success', 'Jadwal berhasil dihapus!');
    }
}
