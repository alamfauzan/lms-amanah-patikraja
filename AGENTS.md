# LMS Madrasah Al-Ilm — Project Context

## Stack Teknologi
- **Framework:** Laravel (PHP)
- **Database:** MySQL
- **Frontend:** Blade Laravel + Tailwind CSS
- **Auth:** Laravel Authentication + Role-Based Access Control (RBAC)

---

## Roles & Hak Akses

| Role | Deskripsi |
|------|-----------|
| `admin` | Mengelola seluruh sistem |
| `guru` | Mengelola pembelajaran di kelas yang diampu |
| `siswa` | Mengikuti kegiatan pembelajaran |

---

## Modul & Fitur

### 1. Autentikasi
- Login, Logout, Forgot Password, Ganti Password, Profil Pengguna
- Semua role

### 2. Dashboard
**Siswa:** jumlah tugas belum selesai, kelas aktif, nilai rata-rata, persentase kehadiran, jadwal hari ini, deadline tugas, daftar kelas

**Guru:** jumlah kelas diajar, total siswa, tugas aktif, kuis aktif, jadwal hari ini, log aktivitas siswa

**Admin:** total guru, siswa, kelas, mata pelajaran, statistik tugas/kuis, statistik penggunaan sistem

### 3. Kelas
- **Guru:** buat, edit, hapus kelas; tambah siswa ke kelas; tentukan wali kelas
- **Siswa:** lihat daftar kelas, masuk ke kelas, lihat detail kelas

### 4. Pertemuan
- **Guru:** buat pertemuan, atur urutan, tambah deskripsi & lampiran
- **Siswa:** lihat daftar pertemuan, buka materi pertemuan

### 5. Materi
- **Guru:** upload PDF, PPT, DOC, Video; tulis materi langsung di editor
- **Siswa:** baca materi, download materi, lihat riwayat pembelajaran

### 6. Tugas
- **Guru:** buat tugas, set deadline & nilai maksimum, lihat pengumpulan, beri nilai & feedback
- **Siswa:** lihat tugas & deadline, upload jawaban, edit sebelum deadline, lihat nilai & feedback

### 7. Kuis
- Jenis soal: **Pilihan Ganda, Benar/Salah, Isian Singkat**
- **Guru:** buat kuis, atur durasi/jumlah soal/batas pengerjaan/bobot nilai, koreksi isian
- **Siswa:** lihat kuis, mulai kuis, jawab soal (auto-save), submit, lihat hasil

### 8. Jadwal
- **Guru:** lihat jadwal mengajar, sinkronisasi jadwal kelas
- **Siswa:** lihat jadwal harian & mingguan

### 9. Nilai
- **Guru:** input nilai tugas & kuis, lihat rekap per kelas, ekspor (Excel/PDF)
- **Siswa:** lihat nilai tugas, kuis, dan rata-rata

### 10. Notifikasi
- **Guru:** tugas dikumpulkan, kuis selesai dikerjakan
- **Siswa:** tugas baru, deadline mendekat, materi baru, kuis baru, nilai tersedia

---

## Struktur Menu

**Admin:** Dashboard, Data Guru, Data Siswa, Kelas, Mata Pelajaran, Jadwal, Tahun Ajaran, Laporan, Pengaturan

**Guru:** Dashboard, Kelas Saya, Materi, Tugas, Kuis, Jadwal, Nilai, Profil

**Siswa:** Dashboard, Kelas, Tugas, Kuis, Nilai, Jadwal, Profil

---

## Responsive Design (Mobile First)

| Komponen | Mobile | Tablet | Desktop |
|----------|--------|--------|---------|
| Dashboard | Hamburger menu, card vertikal | Card 2 kolom | Sidebar kiri |
| Kelas | Grid 1 kolom | Grid 2 kolom | Grid 4 kolom |
| Materi | Full content | Full content | Sidebar + konten |
| Tugas | Card layout | Card layout | Tabel/list |
| Kuis | Soal full screen, navigasi sticky, tombol besar, auto-save | Sama seperti mobile | Panel soal + panel navigasi |

---

## Non-Functional Requirements

- **Performance:** loading < 3 detik, support 100+ user bersamaan
- **Security:** bcrypt hashing, CSRF protection, RBAC, validasi file upload
- **Browser:** Chrome, Firefox, Edge, Android Browser, Safari
- **Responsif:** mobile, tablet, desktop
