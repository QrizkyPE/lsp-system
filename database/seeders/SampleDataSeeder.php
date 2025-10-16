<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SampleDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create sample skema sertifikasi
        $skema1 = \App\Models\SkemaSertifikasi::create([
            'nama_skema' => 'PENGEMBANG WEB (WEB DEVELOPER)',
            'deskripsi' => 'Sertifikasi untuk kompetensi pengembangan website',
            'kode_skema' => 'SK-WD-001',
            'level_kompetensi' => 'Level 3',
            'standar_kompetensi' => 'Standar kompetensi untuk web developer',
            'status' => true,
        ]);

        $skema2 = \App\Models\SkemaSertifikasi::create([
            'nama_skema' => 'Database Administrator',
            'deskripsi' => 'Sertifikasi untuk kompetensi administrasi database',
            'kode_skema' => 'SK-DBA-001',
            'level_kompetensi' => 'Level 4',
            'standar_kompetensi' => 'Standar kompetensi untuk database administrator',
            'status' => true,
        ]);

        // Create sample TUK
        $tuk = \App\Models\Tuk::create([
            'nama_tuk' => 'TUK Universitas Teknologi',
            'alamat' => 'Jl. Teknologi No. 123',
            'kota' => 'Jakarta',
            'provinsi' => 'DKI Jakarta',
            'kode_pos' => '12345',
            'telepon' => '021-1234567',
            'email' => 'tuk@univtech.ac.id',
            'penanggung_jawab' => 'Dr. John Doe',
            'skema_kompetensi' => [$skema1->id, $skema2->id],
            'status' => true,
        ]);

        // Create sample jadwal uji
        \App\Models\JadwalUji::create([
            'skema_sertifikasi_id' => $skema1->id,
            'tuk_id' => $tuk->id,
            'nama_batch' => 'Batch 1 - Web Developer',
            'tanggal_mulai' => now()->addDays(30),
            'tanggal_selesai' => now()->addDays(32),
            'jam_mulai' => '08:00',
            'jam_selesai' => '17:00',
            'kuota_maksimal' => 20,
            'kuota_terisi' => 0,
            'status' => 'open',
            'keterangan' => 'Jadwal uji kompetensi web developer',
        ]);

        // Create sample users
        $asesorUser = \App\Models\User::create([
            'name' => 'Dr. Budi',
            'email' => 'asesor@lsp.com',
            'password' => \Hash::make('password'),
            'role' => 'asesor',
            'nama_lengkap' => 'Dr. Budi Franky',
            'no_telepon' => '081234567890',
            'alamat' => 'Jl. Asesor No. 1',
        ]);

        $mahasiswaUser = \App\Models\User::create([
            'name' => 'Santoso',
            'email' => 'mahasiswa@lsp.com',
            'password' => \Hash::make('password'),
            'role' => 'mahasiswa',
            'nama_lengkap' => 'Santoso John',
            'nim' => '123456789',
            'program_studi' => 'Teknik Informatika',
            'fakultas' => 'Fakultas Teknik',
            'no_telepon' => '081234567891',
            'alamat' => 'Jl. Mahasiswa No. 1',
        ]);

        // Create asesor profile
        \App\Models\Asesor::create([
            'user_id' => $asesorUser->id,
            'nama_lengkap' => 'Dr. Budi Franky',
            'nip' => '1234567890',
            'jabatan' => 'Dosen',
            'instansi' => 'Universitas Teknologi',
            'no_sertifikat_asesor' => 'ASR-001-2024',
            'tanggal_sertifikat' => now()->subMonths(6),
            'tanggal_expired' => now()->addMonths(18),
            'skema_kompetensi' => [$skema1->id, $skema2->id],
            'status' => true,
        ]);
    }
}
