<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ElemenJudul extends Model
{
    use HasFactory;

    protected $table = 'elemen_judul';

    protected $fillable = [
        'judul_sertifikasi',
        'kode_unit',
        'kode_elemen',
        'nama_elemen',
        'deskripsi',
    ];

    public function kriteriaUnjukKerja()
    {
        return $this->hasMany(KriteriaUnjukKerjaJudul::class, 'kode_elemen', 'kode_elemen')
            ->where('judul_sertifikasi', $this->judul_sertifikasi)
            ->where('kode_unit', $this->kode_unit);
    }
}