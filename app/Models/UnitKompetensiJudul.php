<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnitKompetensiJudul extends Model
{
    use HasFactory;

    protected $table = 'unit_kompetensi_judul';

    protected $fillable = [
        'judul_sertifikasi',
        'kode_unit',
        'judul_unit',
        'standar_kompetensi_kerja',
        'status'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'status' => 'boolean',
    ];

    // Relationships
    public function elemenJudul()
    {
        return $this->hasMany(ElemenJudul::class, 'kode_unit', 'kode_unit');
    }

    public function kriteriaUnjukKerjaJudul()
    {
        return $this->hasMany(KriteriaUnjukKerjaJudul::class, 'kode_unit', 'kode_unit');
    }
}