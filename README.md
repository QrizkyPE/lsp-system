# LSP (Lembaga Sertifikasi Profesi) System

Sistem LSP berbasis web yang dibangun menggunakan Laravel untuk mengelola proses sertifikasi kompetensi.

## Fitur Utama

### Admin LSP
- **CRUD Skema Sertifikasi**: Kelola skema sertifikasi dengan standar kompetensi
- **CRUD Unit Kompetensi**: Kelola unit kompetensi untuk setiap skema
- **CRUD Elemen**: Kelola elemen kompetensi
- **CRUD Kriteria Unjuk Kerja**: Kelola kriteria, jenis bukti, metode dan perangkat asesmen
- **CRUD Asesor**: Kelola data asesor dan sertifikat mereka
- **CRUD TUK**: Kelola Tempat Uji Kompetensi
- **Jadwal Uji**: Kelola jadwal uji kompetensi
- **Penugasan**: Penugasan Asesor, MAPA, MA, MKVA
- **Verifikasi Pendaftaran**: Verifikasi dan persetujuan pendaftaran mahasiswa
- **Laporan**: Generate laporan AK.05

### Asesor/Dosen
- **Lihat Penugasan**: Melihat penugasan yang diberikan
- **Review Dokumen**: Verifikasi dokumen APL.02
- **Asesmen**: Mengisi dan menandatangani dokumen AK.01, AK.02, IA.01
- **Upload Perangkat Asesmen**: Mengupload perangkat asesmen
- **Hasil Asesmen**: Memberi nilai dan hasil asesmen

### Mahasiswa/Peserta
- **Pendaftaran Sertifikasi**: Mengisi APL.01 dan APL.02
- **Lihat Jadwal**: Melihat jadwal dan status asesmen
- **Upload Dokumen**: Mengisi umpan balik (AK.03)
- **Lihat Hasil**: Melihat hasil (Kompeten/Belum Kompeten)
- **Banding**: Ajukan banding (AK.04) jika diperlukan

## Teknologi yang Digunakan

- **Laravel 12**: Framework PHP
- **MySQL**: Database
- **Bootstrap 5**: Frontend framework
- **Font Awesome**: Icons
- **PHP 8.1+**: Backend language

## Instalasi

1. Clone repository:
```bash
git clone <repository-url>
cd lsp-system
```

2. Install dependencies:
```bash
composer install
```

3. Copy environment file:
```bash
cp .env.example .env
```

4. Generate application key:
```bash
php artisan key:generate
```

5. Configure database in `.env` file:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=lsp_system
DB_USERNAME=root
DB_PASSWORD=
```

6. Run migrations:
```bash
php artisan migrate
```

7. Seed admin user:
```bash
php artisan db:seed --class=AdminUserSeeder
```

8. Start development server:
```bash
php artisan serve
```

## Default Login

### Admin
- Email: admin@lsp.com
- Password: password

## Struktur Database

### Tabel Utama
- `users`: Data pengguna (Admin, Asesor, Mahasiswa)
- `skema_sertifikasi`: Skema sertifikasi
- `unit_kompetensi`: Unit kompetensi
- `elemen`: Elemen kompetensi
- `kriteria_unjuk_kerja`: Kriteria unjuk kerja
- `asesor`: Data asesor
- `tuk`: Tempat Uji Kompetensi
- `jadwal_uji`: Jadwal uji kompetensi
- `penugasan`: Penugasan asesor
- `pendaftaran`: Pendaftaran mahasiswa
- `dokumen`: Dokumen asesmen

## Alur Kerja Sistem

### 1. Persiapan (Admin)
1. Admin mengisi daftar skema sertifikasi
2. Admin mengisi unit kompetensi, elemen, dan kriteria unjuk kerja
3. Admin mengisi daftar asesor dan TUK
4. Admin membuat jadwal uji
5. Admin melakukan penugasan asesor

### 2. Proses Pendaftaran (Mahasiswa)
1. Mahasiswa mengisi APL.01 (Permohonan Sertifikasi)
2. Mahasiswa mengisi APL.02 (Asesmen Mandiri)
3. Admin verifikasi dan menandatangani APL.01

### 3. Proses Pra Asesmen
1. Dosen verifikasi APL.02
2. Dosen dan mahasiswa menandatangani AK.01 (Persetujuan dan Kerahasiaan)

### 4. Proses Asesmen
1. Dosen mengisi IA.01 (Ceklis Observasi)
2. Dosen mengisi AK.02 (Rekaman Asesmen)
3. Mahasiswa mengisi AK.03 (Umpan Balik)

### 5. Proses Pasca Asesmen
1. Jika diperlukan, mahasiswa bisa mengajukan banding (AK.04)
2. Admin generate laporan AK.05

## Fitur Dokumen

Sistem mendukung berbagai jenis dokumen:
- **APL.01**: Permohonan Sertifikasi Kompetensi
- **APL.02**: Asesmen Mandiri
- **AK.01**: Persetujuan dan Kerahasiaan
- **AK.02**: Rekaman Asesmen Kompetensi
- **AK.03**: Umpan Balik dan Catatan Asesmen
- **AK.04**: Banding Asesmen
- **AK.05**: Laporan Asesmen
- **IA.01**: Ceklis Observasi Aktifitas

## Security Features

- Role-based access control
- CSRF protection
- Input validation
- File upload security
- Authentication middleware

## Contributing

1. Fork the repository
2. Create feature branch
3. Commit changes
4. Push to branch
5. Create Pull Request

## License

This project is licensed under the MIT License.