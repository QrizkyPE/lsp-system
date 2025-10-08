<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SkemaSertifikasi extends Model
{
    protected $table = 'skema_sertifikasi';
    
    protected $fillable = [
        'nama_skema',
        'deskripsi',
        'kode_skema',
        'level_kompetensi',
        'standar_kompetensi',
        'status'
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function unitKompetensi(): HasMany
    {
        return $this->hasMany(UnitKompetensi::class);
    }

    public function jadwalUji(): HasMany
    {
        return $this->hasMany(JadwalUji::class);
    }

    public function pendaftaran(): HasMany
    {
        return $this->hasMany(Pendaftaran::class);
    }
}
