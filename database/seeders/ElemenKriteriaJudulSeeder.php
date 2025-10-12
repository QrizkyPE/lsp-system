<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ElemenJudul;
use App\Models\KriteriaUnjukKerjaJudul;

class ElemenKriteriaJudulSeeder extends Seeder
{
    public function run(): void
    {
        // Data untuk PENGEMBANG WEB (WEB DEVELOPER) - Unit J.620100.041.01
        $this->seedPengembangWeb();
    }

    private function seedPengembangWeb()
    {
        $judulSertifikasi = 'PENGEMBANG WEB (WEB DEVELOPER)';
        $kodeUnit = 'J.620100.041.01';

        // Elemen 1: Merancang rencana cutover aplikasi
        ElemenJudul::create([
            'judul_sertifikasi' => $judulSertifikasi,
            'kode_unit' => $kodeUnit,
            'kode_elemen' => '1',
            'nama_elemen' => 'Merancang rencana cutover aplikasi',
            'deskripsi' => 'Elemen ini mencakup perancangan rencana cutover aplikasi yang komprehensif'
        ]);

        // Kriteria Unjuk Kerja untuk Elemen 1
        KriteriaUnjukKerjaJudul::create([
            'judul_sertifikasi' => $judulSertifikasi,
            'kode_unit' => $kodeUnit,
            'kode_elemen' => '1',
            'nomor_kriteria' => '1.1',
            'deskripsi_kriteria' => 'Aktivitas-aktivitas cutover beserta karakteristiknya diidentifikasi',
            'jenis_bukti' => 'Dokumentasi',
            'metode_asesmen' => 'Observasi',
            'perangkat_asesmen' => 'Checklist'
        ]);

        KriteriaUnjukKerjaJudul::create([
            'judul_sertifikasi' => $judulSertifikasi,
            'kode_unit' => $kodeUnit,
            'kode_elemen' => '1',
            'nomor_kriteria' => '1.2',
            'deskripsi_kriteria' => 'Metode cutover yang cocok dengan situasi dan kondisi ditentukan',
            'jenis_bukti' => 'Dokumentasi',
            'metode_asesmen' => 'Observasi',
            'perangkat_asesmen' => 'Checklist'
        ]);

        KriteriaUnjukKerjaJudul::create([
            'judul_sertifikasi' => $judulSertifikasi,
            'kode_unit' => $kodeUnit,
            'kode_elemen' => '1',
            'nomor_kriteria' => '1.3',
            'deskripsi_kriteria' => 'Rollback strategy ditentukan',
            'jenis_bukti' => 'Dokumentasi',
            'metode_asesmen' => 'Observasi',
            'perangkat_asesmen' => 'Checklist'
        ]);

        // Elemen 2: Melaksanakan cutover aplikasi
        ElemenJudul::create([
            'judul_sertifikasi' => $judulSertifikasi,
            'kode_unit' => $kodeUnit,
            'kode_elemen' => '2',
            'nama_elemen' => 'Melaksanakan cutover aplikasi',
            'deskripsi' => 'Elemen ini mencakup pelaksanaan cutover aplikasi sesuai dengan rencana yang telah dibuat'
        ]);

        // Kriteria Unjuk Kerja untuk Elemen 2
        KriteriaUnjukKerjaJudul::create([
            'judul_sertifikasi' => $judulSertifikasi,
            'kode_unit' => $kodeUnit,
            'kode_elemen' => '2',
            'nomor_kriteria' => '2.1',
            'deskripsi_kriteria' => 'Data-data yang dibutuhkan aplikasi setelah cutover dimigrasikan',
            'jenis_bukti' => 'Dokumentasi',
            'metode_asesmen' => 'Observasi',
            'perangkat_asesmen' => 'Checklist'
        ]);

        KriteriaUnjukKerjaJudul::create([
            'judul_sertifikasi' => $judulSertifikasi,
            'kode_unit' => $kodeUnit,
            'kode_elemen' => '2',
            'nomor_kriteria' => '2.2',
            'deskripsi_kriteria' => 'Aktivitas-aktivitas yang tidak mengganggu kegiatan bisnis didahulukan',
            'jenis_bukti' => 'Dokumentasi',
            'metode_asesmen' => 'Observasi',
            'perangkat_asesmen' => 'Checklist'
        ]);

        KriteriaUnjukKerjaJudul::create([
            'judul_sertifikasi' => $judulSertifikasi,
            'kode_unit' => $kodeUnit,
            'kode_elemen' => '2',
            'nomor_kriteria' => '2.3',
            'deskripsi_kriteria' => 'Aktivitas yang mengganggu kegiatan bisnis dilakukan pada waktu yang paling efektif dan efisien',
            'jenis_bukti' => 'Dokumentasi',
            'metode_asesmen' => 'Observasi',
            'perangkat_asesmen' => 'Checklist'
        ]);

        KriteriaUnjukKerjaJudul::create([
            'judul_sertifikasi' => $judulSertifikasi,
            'kode_unit' => $kodeUnit,
            'kode_elemen' => '2',
            'nomor_kriteria' => '2.4',
            'deskripsi_kriteria' => 'Rollback strategy dijalankan jika ditemukan permasalahan',
            'jenis_bukti' => 'Dokumentasi',
            'metode_asesmen' => 'Observasi',
            'perangkat_asesmen' => 'Checklist'
        ]);
    }
}