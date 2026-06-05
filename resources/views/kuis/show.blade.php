<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between w-full">
            <div class="flex items-center gap-3">
                <a href="{{ route('kelas.kuis.index', $kelas->id) }}" class="p-1.5 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                </a>
                <div>
                    <h2 class="font-bold text-lg text-slate-800 dark:text-slate-100 truncate max-w-sm sm:max-w-md">{{ $kuis->judul }}</h2>
                    <p class="text-xs text-slate-400 leading-none mt-0.5">{{ $kelas->nama_kelas }}</p>
                </div>
            </div>

            @if(auth()->user()->hasAnyRole(['admin','guru']))
                <div class="flex items-center gap-2">
                    <a href="{{ route('kelas.kuis.edit', [$kelas->id, $kuis->id]) }}"
                       class="px-3.5 py-2 bg-slate-100 dark:bg-slate-800 hover:bg-indigo-50 dark:hover:bg-indigo-950/20 text-slate-650 dark:text-slate-350 hover:text-indigo-600 dark:hover:text-indigo-400 text-xs font-semibold rounded-xl transition duration-200">
                        Edit
                    </a>
                    <form action="{{ route('kelas.kuis.destroy', [$kelas->id, $kuis->id]) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kuis ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="px-3.5 py-2 bg-red-50 hover:bg-red-100 dark:bg-red-950/20 dark:hover:bg-red-950/40 text-red-650 dark:text-red-400 text-xs font-semibold rounded-xl transition duration-200">
                            Hapus
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </x-slot>

    <div class="max-w-5xl mx-auto space-y-6">
        @if(session('error'))
            <div class="p-4 bg-red-500/10 border border-red-500/20 text-red-600 dark:text-red-450 rounded-xl text-sm font-semibold">
                {{ session('error') }}
            </div>
        @endif
        @if(session('success'))
            <div class="p-4 bg-emerald-500/10 border border-emerald-500/20 text-emerald-600 dark:text-emerald-450 rounded-xl text-sm font-semibold">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Info Panel -->
            <div class="lg:col-span-1 space-y-4">
                <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl p-6 shadow-sm">
                    <h3 class="font-bold text-slate-850 dark:text-slate-100 text-base mb-4">Detail Kuis</h3>
                    <dl class="space-y-4 text-xs">
                        <div>
                            <dt class="font-semibold text-slate-400 uppercase tracking-wider">Durasi Pengerjaan</dt>
                            <dd class="text-slate-800 dark:text-slate-200 text-sm font-bold mt-1">⏱️ {{ $kuis->durasi_menit }} Menit</dd>
                        </div>
                        <div>
                            <dt class="font-semibold text-slate-400 uppercase tracking-wider">Jumlah Pertanyaan</dt>
                            <dd class="text-slate-800 dark:text-slate-200 text-sm font-bold mt-1">📝 {{ $kuis->jumlah_soal }} Soal</dd>
                        </div>
                        <div>
                            <dt class="font-semibold text-slate-400 uppercase tracking-wider">Batas Percobaan</dt>
                            <dd class="text-slate-800 dark:text-slate-200 text-sm font-bold mt-1">🔄 {{ $kuis->batas_pengerjaan }} Kali</dd>
                        </div>
                        <div>
                            <dt class="font-semibold text-slate-400 uppercase tracking-wider">Bobot Nilai Kelas</dt>
                            <dd class="text-slate-800 dark:text-slate-200 text-sm font-bold mt-1">⚖️ {{ $kuis->bobot_nilai }}%</dd>
                        </div>
                        <div>
                            <dt class="font-semibold text-slate-400 uppercase tracking-wider">Status Kuis</dt>
                            <dd class="mt-1">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold
                                    {{ $kuis->is_aktif ? 'bg-emerald-500/10 text-emerald-600' : 'bg-red-500/10 text-red-550' }}">
                                    {{ $kuis->is_aktif ? 'Aktif' : 'Draft' }}
                                </span>
                            </dd>
                        </div>
                        @if($kuis->mulai_at || $kuis->selesai_at)
                            <div class="pt-2 border-t border-slate-100 dark:border-slate-800/50 space-y-2">
                                @if($kuis->mulai_at)
                                    <div>
                                        <dt class="font-semibold text-slate-400 uppercase tracking-wider">Dibuka</dt>
                                        <dd class="text-slate-700 dark:text-slate-350 font-medium mt-0.5">{{ $kuis->mulai_at->format('d M Y, H:i') }}</dd>
                                    </div>
                                @endif
                                @if($kuis->selesai_at)
                                    <div>
                                        <dt class="font-semibold text-slate-400 uppercase tracking-wider">Ditutup</dt>
                                        <dd class="text-slate-700 dark:text-slate-350 font-medium mt-0.5">{{ $kuis->selesai_at->format('d M Y, H:i') }}</dd>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </dl>
                </div>
            </div>

            <!-- Right Workspace Panel -->
            <div class="lg:col-span-2 space-y-6">
                <!-- For Student -->
                @if(auth()->user()->hasRole('siswa'))
                    <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl shadow-sm overflow-hidden">
                        <div class="p-6 border-b border-slate-100 dark:border-slate-800/50">
                            <h3 class="font-bold text-slate-800 dark:text-slate-100 text-base">Panduan & Status Pengerjaan</h3>
                            <p class="text-xs text-slate-400 mt-1">Harap baca petunjuk sebelum memulai kuis.</p>
                        </div>

                        <div class="p-6 space-y-6">
                            @if($kuis->deskripsi)
                                <div class="p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-850 rounded-xl">
                                    <h4 class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Instruksi Guru:</h4>
                                    <p class="text-xs text-slate-655 dark:text-slate-350 leading-relaxed">{!! nl2br(e($kuis->deskripsi)) !!}</p>
                                </div>
                            @endif

                            <div class="border border-slate-150 dark:border-slate-850 rounded-2xl overflow-hidden divide-y divide-slate-150 dark:divide-slate-850">
                                <div class="p-4 flex items-center justify-between text-xs">
                                    <span class="text-slate-500 font-medium">Batas pengerjaan</span>
                                    <span class="text-slate-800 dark:text-slate-200 font-bold">{{ $kuis->batas_pengerjaan }} kali percobaan</span>
                                </div>
                                @php
                                    $attemptsCount = \App\Models\HasilKuis::where(['kuis_id' => $kuis->id, 'siswa_id' => auth()->id()])->count();
                                @endphp
                                <div class="p-4 flex items-center justify-between text-xs">
                                    <span class="text-slate-500 font-medium">Percobaan yang sudah dilakukan</span>
                                    <span class="text-slate-800 dark:text-slate-200 font-bold">{{ $attemptsCount }} dari {{ $kuis->batas_pengerjaan }}</span>
                                </div>
                            </div>

                            @if($hasilSiswa)
                                <div class="p-5 rounded-2xl bg-indigo-50/50 dark:bg-slate-950 border border-indigo-100/80 dark:border-slate-800/80 flex items-center justify-between gap-4">
                                    <div>
                                        <p class="text-xs text-indigo-555 dark:text-indigo-400 font-bold uppercase tracking-wider">Hasil Terakhir Anda (Percobaan #{{ $hasilSiswa->attempt }})</p>
                                        @if($hasilSiswa->is_submitted)
                                            <p class="text-[11px] text-slate-400 mt-1">Selesai dikerjakan pada {{ $hasilSiswa->selesai_at->format('d M Y, H:i') }}</p>
                                        @else
                                            <p class="text-[11px] text-amber-600 dark:text-amber-400 mt-1">Sedang dikerjakan (Belum disubmit)</p>
                                        @endif
                                    </div>
                                    <div class="text-right">
                                        @if($hasilSiswa->is_submitted)
                                            <span class="text-2xl font-black text-indigo-650 dark:text-indigo-400">{{ $hasilSiswa->nilai_akhir }}</span>
                                            <p class="text-[10px] text-slate-400">Skor Akhir</p>
                                        @else
                                            <span class="text-xs font-bold text-amber-500">Berlangsung</span>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            <div class="pt-4 border-t border-slate-100 dark:border-slate-800/50 flex flex-wrap items-center justify-end gap-3">
                                @if($hasilSiswa && !$hasilSiswa->is_submitted)
                                    <a href="{{ route('kuis.kerjakan', [$kelas->id, $kuis->id]) }}"
                                       class="px-5 py-2.5 bg-amber-500 hover:bg-amber-600 text-white text-xs font-bold rounded-xl shadow-md transition duration-200">
                                        Lanjutkan Percobaan #{{ $hasilSiswa->attempt }}
                                    </a>
                                @else
                                    @if($attemptsCount < $kuis->batas_pengerjaan)
                                        <a href="{{ route('kuis.kerjakan', [$kelas->id, $kuis->id]) }}"
                                           class="px-5 py-2.5 bg-indigo-650 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl shadow-md shadow-indigo-650/10 transition duration-200">
                                            {{ $attemptsCount > 0 ? 'Mulai Percobaan Baru' : 'Mulai Kuis' }}
                                        </a>
                                    @else
                                        <button disabled class="px-5 py-2.5 bg-slate-150 text-slate-400 text-xs font-bold rounded-xl cursor-not-allowed">
                                            Batas Percobaan Habis
                                        </button>
                                    @endif
                                @endif

                                @if($hasilSiswa && $hasilSiswa->is_submitted)
                                    <a href="{{ route('kuis.hasil', [$kelas->id, $kuis->id]) }}"
                                       class="px-4 py-2.5 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-750 text-slate-700 dark:text-slate-300 text-xs font-bold rounded-xl transition duration-200">
                                        Lihat Review Jawaban
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

                <!-- For Teacher / Admin: Submissions & Results -->
                @if(auth()->user()->hasAnyRole(['admin','guru']))
                    <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl shadow-sm overflow-hidden">
                        <div class="p-6 border-b border-slate-100 dark:border-slate-800/50">
                            <h3 class="font-bold text-slate-800 dark:text-slate-100 text-base">📊 Hasil Percobaan Siswa</h3>
                            <p class="text-xs text-slate-400 mt-1">Daftar pengerjaan kuis oleh siswa kelas ini.</p>
                        </div>

                        <div class="overflow-x-auto">
                            @if($semuaHasil->isEmpty())
                                <div class="p-8 text-center text-xs text-slate-400 dark:text-slate-500">Belum ada siswa yang mengerjakan kuis ini.</div>
                            @else
                                <table class="w-full text-left border-collapse">
                                    <thead>
                                        <tr class="bg-slate-50/80 dark:bg-slate-905 border-b border-slate-100 dark:border-slate-800 text-[10px] font-bold text-slate-400 uppercase tracking-wider">
                                            <th class="px-6 py-3.5">Nama Siswa</th>
                                            <th class="px-6 py-3.5 text-center">Percobaan Ke</th>
                                            <th class="px-6 py-3.5">Mulai Pada</th>
                                            <th class="px-6 py-3.5">Selesai Pada</th>
                                            <th class="px-6 py-3.5 text-right">Nilai Akhir</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800/50 text-xs">
                                        @foreach($semuaHasil as $hasil)
                                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-850/20 transition duration-150">
                                                <td class="px-6 py-4 font-semibold text-slate-750 dark:text-slate-200">{{ $hasil->siswa->name }}</td>
                                                <td class="px-6 py-4 text-center text-slate-500 font-medium">Percobaan #{{ $hasil->attempt }}</td>
                                                <td class="px-6 py-4 text-slate-400">{{ $hasil->mulai_at ? $hasil->mulai_at->format('d M Y, H:i') : '-' }}</td>
                                                <td class="px-6 py-4 text-slate-400">
                                                    @if($hasil->is_submitted)
                                                        {{ $hasil->selesai_at ? $hasil->selesai_at->format('d M Y, H:i') : '-' }}
                                                    @else
                                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[9px] font-bold bg-amber-500/10 text-amber-500 uppercase">Belum dikirim</span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 text-right">
                                                    @if($hasil->is_submitted)
                                                        <span class="font-bold text-sm text-indigo-650 dark:text-indigo-400">{{ $hasil->nilai_akhir }}</span>
                                                    @else
                                                        <span class="text-slate-400 font-semibold italic">Dalam proses</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
