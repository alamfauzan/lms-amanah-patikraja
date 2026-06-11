<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Kelas;
use App\Models\User;
use App\Models\MataPelajaran;
use App\Models\KelasMapelGuru;
use App\Models\TahunAjaran;
use App\Models\Jadwal;

class KelasController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        $classes = collect();

        if ($user->hasRole('admin')) {
            $classes = Kelas::with('waliKelas')->withCount('siswa')->get();
        } elseif ($user->hasRole('guru')) {
            $classes = Kelas::where('wali_kelas_id', $user->id)
                ->orWhereHas('kelasMapelGuru', function ($query) use ($user) {
                    $query->where('guru_id', $user->id);
                })
                ->with('waliKelas')
                ->withCount('siswa')
                ->get();
        } else { // siswa
            $classes = $user->siswaKelas()->with('waliKelas')->withCount('siswa')->get();
        }

        return view('kelas.index', compact('classes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'Hanya Admin yang dapat membuat kelas.');
        }

        $teachers = User::role('guru')->get();
        $years = TahunAjaran::all();
        return view('kelas.create', compact('teachers', 'years'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!auth()->user()->hasRole('admin')) {
            abort(403);
        }

        $validated = $request->validate([
            'nama_kelas' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'wali_kelas_id' => 'nullable|exists:users,id',
            'tahun_ajaran' => 'required|string|max:255',
        ]);

        Kelas::create($validated);

        return redirect()->route('kelas.index')->with('success', 'Kelas berhasil dibuat!');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $kelas = Kelas::with(['waliKelas', 'siswa', 'kelasMapelGuru.mataPelajaran', 'kelasMapelGuru.guru'])->findOrFail($id);
        $user = auth()->user();

        // Check permission to view this specific class
        if ($user->hasRole('admin')) {
            // Admin can see everything
        } elseif ($user->hasRole('guru')) {
            $teachesInClass = $kelas->kelasMapelGuru()->where('guru_id', $user->id)->exists();
            $isWaliKelas = $kelas->wali_kelas_id == $user->id;
            if (!$teachesInClass && !$isWaliKelas) {
                abort(403, 'Anda tidak mengampu kelas ini.');
            }
        } else { // siswa
            $isEnrolled = $kelas->siswa()->where('siswa_id', $user->id)->exists();
            if (!$isEnrolled) {
                abort(403, 'Anda tidak terdaftar di kelas ini.');
            }
        }

        // Fetch teachers & students for admin assignments
        $allTeachers = collect();
        $allStudents = collect();
        $allSubjects = collect();
        if ($user->hasRole('admin')) {
            $allTeachers = User::role('guru')->get();
            $allStudents = User::role('siswa')->whereDoesntHave('siswaKelas')->get();
            $allSubjects = MataPelajaran::all();
        }

        // Jadwal kelas ini — group by mata_pelajaran_id
        $jadwalKelas = Jadwal::where('kelas_id', $id)
            ->orderBy('hari')->orderBy('jam_mulai')
            ->get()
            ->groupBy('mata_pelajaran_id');

        return view('kelas.show', compact('kelas', 'allTeachers', 'allStudents', 'allSubjects', 'jadwalKelas'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Kelas $kela)
    {
        if (!auth()->user()->hasRole('admin')) {
            abort(403);
        }

        $teachers = User::role('guru')->get();
        $years = TahunAjaran::all();
        $kelas = $kela; // Laravel binds it to $kela because singular of 'kelas' is parsed as 'kela' by route binding
        return view('kelas.edit', compact('kelas', 'teachers', 'years'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        if (!auth()->user()->hasRole('admin')) {
            abort(403);
        }

        $validated = $request->validate([
            'nama_kelas' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'wali_kelas_id' => 'nullable|exists:users,id',
            'tahun_ajaran' => 'required|string|max:255',
        ]);

        $kelas = Kelas::findOrFail($id);
        $kelas->update($validated);

        return redirect()->route('kelas.index')->with('success', 'Kelas berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        if (!auth()->user()->hasRole('admin')) {
            abort(403);
        }

        $kelas = Kelas::findOrFail($id);
        $kelas->delete();

        return redirect()->route('kelas.index')->with('success', 'Kelas berhasil dihapus!');
    }

    /**
     * Add student to Class (Admin action)
     */
    public function addStudent(Request $request, $kelasId)
    {
        if (!auth()->user()->hasRole('admin')) {
            abort(403);
        }

        $request->validate([
            'siswa_id' => 'required|exists:users,id',
        ]);

        $siswa = User::findOrFail($request->siswa_id);
        $existingClass = $siswa->siswaKelas()->first();
        if ($existingClass) {
            return back()->withErrors(['siswa_id' => 'Siswa sudah terdaftar di kelas ' . $existingClass->nama_kelas]);
        }

        $kelas = Kelas::findOrFail($kelasId);
        $kelas->siswa()->syncWithoutDetaching([$request->siswa_id]);

        return redirect()->route('kelas.show', $kelasId)->with('success', 'Siswa berhasil ditambahkan ke kelas!');
    }

    /**
     * Remove student from Class (Admin action)
     */
    public function removeStudent($kelasId, $siswaId)
    {
        if (!auth()->user()->hasRole('admin')) {
            abort(403);
        }

        $kelas = Kelas::findOrFail($kelasId);
        $kelas->siswa()->detach($siswaId);

        return redirect()->route('kelas.show', $kelasId)->with('success', 'Siswa berhasil dikeluarkan dari kelas!');
    }

    /**
     * Show form to assign a subject & teacher to Class (Admin action)
     */
    public function createSubject($kelasId)
    {
        if (!auth()->user()->hasRole('admin')) {
            abort(403);
        }

        $kelas = Kelas::findOrFail($kelasId);
        $allSubjects = MataPelajaran::all();
        $allTeachers = User::role('guru')->get();

        return view('kelas.assign-mapel', compact('kelas', 'allSubjects', 'allTeachers'));
    }

    /**
     * Assign Subject & Teacher to Class (Admin action)
     */
    public function assignSubject(Request $request, $kelasId)
    {
        if (!auth()->user()->hasRole('admin')) {
            abort(403);
        }

        $validated = $request->validate([
            'mata_pelajaran_id' => 'required|exists:mata_pelajaran,id',
            'guru_id' => 'required|exists:users,id',
            'hari' => 'nullable|integer|min:1|max:7',
            'jam_mulai' => 'nullable',
            'jam_selesai' => 'nullable',
            'ruangan' => 'nullable|string|max:100',
        ]);

        KelasMapelGuru::updateOrCreate(
            ['kelas_id' => $kelasId, 'mata_pelajaran_id' => $request->mata_pelajaran_id],
            ['guru_id' => $request->guru_id]
        );

        // Update existing schedules for this class and subject to match the new teacher
        Jadwal::where('kelas_id', $kelasId)
            ->where('mata_pelajaran_id', $request->mata_pelajaran_id)
            ->update(['guru_id' => $request->guru_id]);

        if ($request->filled('hari') && $request->filled('jam_mulai') && $request->filled('jam_selesai')) {
            Jadwal::create([
                'kelas_id' => $kelasId,
                'mata_pelajaran_id' => $request->mata_pelajaran_id,
                'guru_id' => $request->guru_id,
                'hari' => $request->hari,
                'jam_mulai' => $request->jam_mulai,
                'jam_selesai' => $request->jam_selesai,
                'ruangan' => $request->ruangan,
            ]);
        }

        return redirect()->route('kelas.show', $kelasId)->with('success', 'Mata Pelajaran & Guru pengampu berhasil ditugaskan!');
    }

    /**
     * Show form to edit subject pengampu in Class (Admin action)
     */
    public function editSubject($kelasId, $id)
    {
        if (!auth()->user()->hasRole('admin')) {
            abort(403);
        }

        $kelas = Kelas::findOrFail($kelasId);
        $link = KelasMapelGuru::with('mataPelajaran')->findOrFail($id);
        $allTeachers = User::role('guru')->get();

        return view('kelas.edit-mapel', compact('kelas', 'link', 'allTeachers'));
    }

    /**
     * Update subject pengampu in Class (Admin action)
     */
    public function updateSubject(Request $request, $kelasId, $id)
    {
        if (!auth()->user()->hasRole('admin')) {
            abort(403);
        }

        $request->validate([
            'guru_id' => 'required|exists:users,id',
        ]);

        $link = KelasMapelGuru::findOrFail($id);
        $link->update(['guru_id' => $request->guru_id]);

        // Update existing schedules for this class and subject to match the new teacher
        Jadwal::where('kelas_id', $kelasId)
            ->where('mata_pelajaran_id', $link->mata_pelajaran_id)
            ->update(['guru_id' => $request->guru_id]);

        return redirect()->route('kelas.show', $kelasId)->with('success', 'Pengampu mata pelajaran berhasil diupdate!');
    }

    /**
     * Remove assigned subject from Class (Admin action)
     */
    public function removeSubject($kelasId, $id)
    {
        if (!auth()->user()->hasRole('admin')) {
            abort(403);
        }

        $link = KelasMapelGuru::findOrFail($id);
        $link->delete();

        return redirect()->route('kelas.show', $kelasId)->with('success', 'Mata Pelajaran berhasil dihapus dari kelas!');
    }
}
