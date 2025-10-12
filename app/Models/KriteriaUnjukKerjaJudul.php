<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KriteriaUnjukKerjaJudul extends Model
{
    use HasFactory;

    protected $table = 'kriteria_unjuk_kerja_judul';

    protected $fillable = [
        'judul_sertifikasi',
        'kode_unit',
        'kode_elemen',
        'nomor_kriteria',
        'deskripsi_kriteria',
        'jenis_bukti',
        'metode_asesmen',
        'perangkat_asesmen',
    ];

    public function elemen()
    {
        return $this->belongsTo(ElemenJudul::class, 'kode_elemen', 'kode_elemen')
            ->where('judul_sertifikasi', $this->judul_sertifikasi)
            ->where('kode_unit', $this->kode_unit);
    }
}