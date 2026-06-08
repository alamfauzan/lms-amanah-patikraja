<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Kuis;
use App\Models\SoalKuis;
use App\Models\HasilKuis;
use App\Models\JawabanSiswa;
use App\Models\Notifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KuisController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request, $kelasId = null)
    {
        if ($kelasId) {
            return redirect()->route('kelas.pertemuan.index', [$kelasId, 'mapel_id' => $request->query('mapel_id')]);
        }

        $user = auth()->user();
        $filter = $request->query('filter', 'semua');
        $statusMap = [];

        // Global listing
        if ($user->hasRole('guru')) {
            $kuis = Kuis::where('guru_id', $user->id)->with(['kelas', 'pertemuan', 'mataPelajaran'])->latest()->get();
        } elseif ($user->hasRole('siswa')) {
            $kelasIds = $user->siswaKelas()->pluck('kelas.id');
            $allKuis = Kuis::whereIn('kelas_id', $kelasIds)->with(['kelas', 'guru', 'pertemuan', 'mataPelajaran'])->latest()->get();
            foreach ($allKuis as $k) {
                $attempt = $k->hasilBySiswa($user->id);
                $isPast = $k->selesai_at ? now()->gt($k->selesai_at) : false;
                $statusMap[$k->id] = ($attempt && $attempt->is_submitted) ? 'selesai' : ($isPast ? 'overdue' : 'belum');
            }
            if ($filter !== 'semua') {
                $kuis = $allKuis->filter(fn($k) => ($statusMap[$k->id] ?? 'belum') === $filter)->values();
            } else {
                $kuis = $allKuis;
            }
            return view('kuis.index', compact('kuis', 'statusMap'));
        } else {
            $kuis = Kuis::with(['kelas', 'guru', 'pertemuan', 'mataPelajaran'])->latest()->get();
        }

        return view('kuis.index', compact('kuis', 'statusMap'));
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

        return view('kuis.create', compact('kelas', 'mapels', 'preselectedMapelId', 'preselectedPertemuanId', 'pertemuan'));
    }

    public function store(Request $request, $kelasId)
    {
        if (!auth()->user()->hasAnyRole(['admin', 'guru'])) abort(403);

        $validated = $request->validate([
            'mata_pelajaran_id' => 'required|exists:mata_pelajaran,id',
            'judul'           => 'required|string|max:255',
            'deskripsi'       => 'nullable|string',
            'durasi_menit'    => 'required|integer|min:1',
            'batas_pengerjaan'=> 'required|integer|min:1',
            'nilai_diambil_dari' => 'required|in:terakhir,tertinggi,rata_rata',
            'bobot_nilai'     => 'required|numeric|min:0|max:100',
            'mulai_at'        => 'nullable|date',
            'selesai_at'      => 'nullable|date',
            'pertemuan_id'    => 'nullable|exists:pertemuan,id',
            // Soal
            'soal'            => 'required|array|min:1',
            'soal.*.pertanyaan'      => 'required|string',
            'soal.*.tipe'            => 'required|in:pilihan_ganda,benar_salah,isian_singkat',
            'soal.*.kunci_jawaban'   => 'required|string',
            'soal.*.poin'            => 'required|integer|min:1',
            'soal.*.pilihan_jawaban' => 'nullable|array',
            'gambar.*'               => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:2048',
        ]);

        $user = auth()->user();
        $kelas = Kelas::findOrFail($kelasId);
        if (!$user->hasRole('admin')) {
            $isAssigned = $kelas->kelasMapelGuru()
                ->where('guru_id', $user->id)
                ->where('mata_pelajaran_id', $validated['mata_pelajaran_id'])
                ->exists();
            if (!$isAssigned) abort(403, 'Anda tidak mengampu mata pelajaran ini di kelas ini.');
        }

        DB::transaction(function () use ($validated, $kelasId, $request) {
            $kuis = Kuis::create([
                'kelas_id'         => $kelasId,
                'mata_pelajaran_id' => $validated['mata_pelajaran_id'],
                'guru_id'          => auth()->id(),
                'pertemuan_id'     => $validated['pertemuan_id'] ?? null,
                'judul'            => $validated['judul'],
                'deskripsi'        => $validated['deskripsi'] ?? null,
                'durasi_menit'     => $validated['durasi_menit'],
                'jumlah_soal'      => count($validated['soal']),
                'batas_pengerjaan' => $validated['batas_pengerjaan'],
                'nilai_diambil_dari' => $validated['nilai_diambil_dari'],
                'bobot_nilai'      => $validated['bobot_nilai'],
                'mulai_at'         => $validated['mulai_at'] ?? null,
                'selesai_at'       => $validated['selesai_at'] ?? null,
                'is_aktif'         => $request->boolean('is_aktif'),
            ]);

            foreach ($validated['soal'] as $i => $soalData) {
                $gambarPath = null;
                if ($request->hasFile("gambar.$i") && $request->file("gambar.$i")->isValid()) {
                    $gambarPath = $request->file("gambar.$i")->store('soal', 'public');
                }

                SoalKuis::create([
                    'kuis_id'          => $kuis->id,
                    'pertanyaan'       => $soalData['pertanyaan'],
                    'gambar'           => $gambarPath,
                    'tipe'             => $soalData['tipe'],
                    'pilihan_jawaban'  => $soalData['pilihan_jawaban'] ?? null,
                    'kunci_jawaban'    => $soalData['kunci_jawaban'],
                    'poin'             => $soalData['poin'],
                    'urutan'           => $i + 1,
                ]);
            }

            // Notify siswa
            $kelas = Kelas::with('siswa')->findOrFail($kelasId);
            foreach ($kelas->siswa as $siswa) {
                Notifikasi::create([
                    'user_id' => $siswa->id,
                    'judul'   => 'Kuis Baru: ' . $kuis->judul,
                    'pesan'   => 'Ada kuis baru di kelas ' . $kelas->nama_kelas . '. Durasi: ' . $kuis->durasi_menit . ' menit.',
                    'tipe'    => 'kuis_baru',
                    'link'    => route('kuis.show', [$kelasId, $kuis->id]),
                ]);
            }
        });

        return redirect()->route('kelas.pertemuan.index', [$kelasId, 'mapel_id' => $validated['mata_pelajaran_id']])->with('success', 'Kuis berhasil dibuat!');
    }

    public function show($kelasId, $id)
    {
        $kelas = Kelas::findOrFail($kelasId);
        $kuis  = Kuis::with(['soal', 'guru', 'mataPelajaran', 'pertemuan'])->findOrFail($id);
        $user  = auth()->user();

        $hasilSiswa = null;
        $semuaHasil = collect();
        if ($user->hasRole('siswa')) {
            $hasilSiswa = HasilKuis::where(['kuis_id' => $id, 'siswa_id' => $user->id])
                ->orderByDesc('attempt')->first();
        } elseif ($user->hasAnyRole(['guru', 'admin'])) {
            $semuaHasil = HasilKuis::where('kuis_id', $id)->with('siswa')->get();
        }

        return view('kuis.show', compact('kelas', 'kuis', 'hasilSiswa', 'semuaHasil'));
    }

    public function edit($kelasId, $id)
    {
        if (!auth()->user()->hasAnyRole(['admin', 'guru'])) abort(403);
        $kelas = Kelas::with('pertemuan')->findOrFail($kelasId);
        $kuis  = Kuis::with('soal')->findOrFail($id);

        $user = auth()->user();
        if ($user->hasRole('admin')) {
            $mapels = $kelas->kelasMapelGuru()->with('mataPelajaran')->get()->map->mataPelajaran->unique();
        } else {
            $mapels = $kelas->kelasMapelGuru()->where('guru_id', $user->id)->with('mataPelajaran')->get()->map->mataPelajaran->unique();
        }

        $pertemuan = $kelas->pertemuan->where('mata_pelajaran_id', $kuis->mata_pelajaran_id);

        return view('kuis.edit', compact('kelas', 'kuis', 'mapels', 'pertemuan'));
    }

    public function update(Request $request, $kelasId, $id)
    {
        if (!auth()->user()->hasAnyRole(['admin', 'guru'])) abort(403);

        $validated = $request->validate([
            'mata_pelajaran_id'  => 'required|exists:mata_pelajaran,id',
            'judul'              => 'required|string|max:255',
            'deskripsi'          => 'nullable|string',
            'durasi_menit'       => 'required|integer|min:1',
            'batas_pengerjaan'   => 'required|integer|min:1',
            'nilai_diambil_dari' => 'required|in:terakhir,tertinggi,rata_rata',
            'bobot_nilai'        => 'required|numeric|min:0|max:100',
            'mulai_at'           => 'nullable|date',
            'selesai_at'         => 'nullable|date',
            // Soal editing
            'soal'               => 'nullable|array',
            'soal.*.id'          => 'nullable|exists:soal_kuis,id',
            'soal.*.pertanyaan'  => 'required_with:soal|string',
            'soal.*.tipe'        => 'required_with:soal|in:pilihan_ganda,benar_salah,isian_singkat',
            'soal.*.kunci_jawaban' => 'required_with:soal|string',
            'soal.*.poin'        => 'required_with:soal|integer|min:1',
            'soal.*.pilihan_jawaban' => 'nullable|array',
            'gambar.*'           => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:2048',
        ]);

        $user  = auth()->user();
        $kelas = Kelas::findOrFail($kelasId);

        if (!$user->hasRole('admin')) {
            $isAssigned = $kelas->kelasMapelGuru()
                ->where('guru_id', $user->id)
                ->where('mata_pelajaran_id', $validated['mata_pelajaran_id'])
                ->exists();
            if (!$isAssigned) abort(403, 'Anda tidak mengampu mata pelajaran ini di kelas ini.');
        }

        DB::transaction(function () use ($validated, $request, $id) {
            $kuis = Kuis::findOrFail($id);
            $kuis->update([
                'mata_pelajaran_id'  => $validated['mata_pelajaran_id'],
                'judul'              => $validated['judul'],
                'deskripsi'          => $validated['deskripsi'] ?? null,
                'durasi_menit'       => $validated['durasi_menit'],
                'batas_pengerjaan'   => $validated['batas_pengerjaan'],
                'nilai_diambil_dari' => $validated['nilai_diambil_dari'],
                'bobot_nilai'        => $validated['bobot_nilai'],
                'mulai_at'           => $validated['mulai_at'] ?? null,
                'selesai_at'         => $validated['selesai_at'] ?? null,
                'is_aktif'           => $request->boolean('is_aktif'),
            ]);

            // Update soal if provided
            if (!empty($validated['soal'])) {
                foreach ($validated['soal'] as $i => $soalData) {
                    $gambarPath = null;
                    if ($request->hasFile("gambar.$i") && $request->file("gambar.$i")->isValid()) {
                        $gambarPath = $request->file("gambar.$i")->store('soal', 'public');
                    }

                    if (!empty($soalData['id'])) {
                        // Update existing soal
                        $soal = SoalKuis::find($soalData['id']);
                        if ($soal && $soal->kuis_id == $kuis->id) {
                            $soal->update([
                                'pertanyaan'      => $soalData['pertanyaan'],
                                'tipe'            => $soalData['tipe'],
                                'pilihan_jawaban' => $soalData['pilihan_jawaban'] ?? null,
                                'kunci_jawaban'   => $soalData['kunci_jawaban'],
                                'poin'            => $soalData['poin'],
                                'urutan'          => $i + 1,
                            ]);
                            if ($gambarPath) {
                                $soal->gambar = $gambarPath;
                                $soal->save();
                            }
                        }
                    } else {
                        // Create new soal
                        SoalKuis::create([
                            'kuis_id'         => $kuis->id,
                            'pertanyaan'      => $soalData['pertanyaan'],
                            'gambar'          => $gambarPath,
                            'tipe'            => $soalData['tipe'],
                            'pilihan_jawaban' => $soalData['pilihan_jawaban'] ?? null,
                            'kunci_jawaban'   => $soalData['kunci_jawaban'],
                            'poin'            => $soalData['poin'],
                            'urutan'          => $i + 1,
                        ]);
                    }
                }

                // Update jumlah_soal
                $kuis->jumlah_soal = $kuis->soal()->count();
                $kuis->save();
            }
        });

        return redirect()->route('kuis.show', [$kelasId, $id])->with('success', 'Kuis berhasil diupdate!');
    }

    public function destroy($kelasId, $id)
    {
        if (!auth()->user()->hasAnyRole(['admin', 'guru'])) abort(403);
        $kuis = Kuis::findOrFail($id);
        $mapelId = $kuis->mata_pelajaran_id;
        $kuis->delete();

        return redirect()->route('kelas.pertemuan.index', [$kelasId, 'mapel_id' => $mapelId])->with('success', 'Kuis berhasil dihapus!');
    }

    public function kerjakan($kelasId, $id)
    {
        $user = auth()->user();
        if (!$user->hasRole('siswa')) abort(403);

        $kelas = Kelas::findOrFail($kelasId);
        $kuis = Kuis::with(['soal', 'pertemuan'])->findOrFail($id);

        // Cek apakah ada sesi yang sedang berlangsung (belum di-submit)
        $ongoingAttempt = HasilKuis::where([
            'kuis_id'      => $id,
            'siswa_id'     => $user->id,
            'is_submitted' => false,
        ])->latest()->first();

        if ($ongoingAttempt) {
            // Lanjutkan sesi yang ada — jangan buat baru
            $attempt = $ongoingAttempt;
        } else {
            // Cek batas pengerjaan hanya untuk percobaan yang sudah submit
            $attemptCount = HasilKuis::where(['kuis_id' => $id, 'siswa_id' => $user->id])->count();
            if ($attemptCount >= $kuis->batas_pengerjaan) {
                return redirect()->route('kuis.show', [$kelasId, $id])
                    ->with('error', 'Anda sudah mencapai batas pengerjaan kuis.');
            }

            // Buat attempt baru
            $attempt = HasilKuis::create([
                'kuis_id'  => $id,
                'siswa_id' => $user->id,
                'attempt'  => $attemptCount + 1,
                'mulai_at' => now(),
            ]);
        }

        return view('kuis.kerjakan', compact('kelas', 'kuis', 'attempt'));
    }

    /** Auto-save jawaban (AJAX) */
    public function jawab(Request $request, $kelasId, $id)
    {
        $user = auth()->user();
        if (!$user->hasRole('siswa')) abort(403);

        $request->validate([
            'soal_id'  => 'required|exists:soal_kuis,id',
            'jawaban'  => 'nullable|string',
            'attempt'  => 'required|integer',
        ]);

        $soal = SoalKuis::findOrFail($request->soal_id);
        $kunci = strtolower(trim($soal->kunci_jawaban));
        $jawaban = strtolower(trim($request->jawaban ?? ''));

        $isBenar = $kunci === $jawaban;
        $poin    = $isBenar ? $soal->poin : 0;

        JawabanSiswa::updateOrCreate(
            ['soal_id' => $request->soal_id, 'siswa_id' => $user->id, 'attempt' => $request->attempt],
            ['kuis_id' => $id, 'jawaban' => $request->jawaban, 'is_benar' => $isBenar, 'poin_diperoleh' => $poin]
        );

        return response()->json(['saved' => true]);
    }

    /** Submit kuis */
    public function submit(Request $request, $kelasId, $id)
    {
        $user = auth()->user();
        if (!$user->hasRole('siswa')) abort(403);

        $attempt = $request->input('attempt', 1);
        $hasil   = HasilKuis::where(['kuis_id' => $id, 'siswa_id' => $user->id, 'attempt' => $attempt])->firstOrFail();
        $kuis    = Kuis::with('soal')->findOrFail($id);

        $jawaban = JawabanSiswa::where(['kuis_id' => $id, 'siswa_id' => $user->id, 'attempt' => $attempt])->get();
        $totalPoin = $jawaban->sum('poin_diperoleh');
        $totalSoal = $kuis->soal->sum('poin');
        $nilaiAkhir = $totalSoal > 0 ? round(($totalPoin / $totalSoal) * $kuis->bobot_nilai, 2) : 0;
        $benar = $jawaban->where('is_benar', true)->count();
        $salah = $jawaban->where('is_benar', false)->count();

        $hasil->update([
            'nilai_raw'    => $totalPoin,
            'nilai_akhir'  => $nilaiAkhir,
            'benar'        => $benar,
            'salah'        => $salah,
            'selesai_at'   => now(),
            'is_submitted' => true,
        ]);

        // Notify guru
        Notifikasi::create([
            'user_id' => $kuis->guru_id,
            'judul'   => 'Kuis Selesai Dikerjakan',
            'pesan'   => $user->name . ' menyelesaikan kuis "' . $kuis->judul . '" dengan nilai ' . $nilaiAkhir,
            'tipe'    => 'pengumpulan',
            'link'    => route('kuis.show', [$kelasId, $id]),
        ]);

        return redirect()->route('kuis.hasil', [$kelasId, $id])->with('hasil_id', $hasil->id);
    }

    /** Tampilkan hasil kuis */
    public function hasil($kelasId, $id)
    {
        $user   = auth()->user();
        $kelas  = Kelas::findOrFail($kelasId);
        $kuis   = Kuis::with('soal')->findOrFail($id);
        $hasil  = HasilKuis::where(['kuis_id' => $id, 'siswa_id' => $user->id])
            ->orderByDesc('attempt')->first();
        $nilaiDigunakan = $kuis->nilaiAkhirBySiswa($user->id);
        $hasilDigunakan = $kuis->hasilNilaiBySiswa($user->id);
        $jawaban = JawabanSiswa::where(['kuis_id' => $id, 'siswa_id' => $user->id, 'attempt' => optional($hasil)->attempt])
            ->with('soal')->get()->keyBy('soal_id');

        return view('kuis.hasil', compact('kelas', 'kuis', 'hasil', 'jawaban', 'nilaiDigunakan', 'hasilDigunakan'));
    }
}
