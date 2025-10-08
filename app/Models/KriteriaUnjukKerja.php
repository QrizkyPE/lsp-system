<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KriteriaUnjukKerja extends Model
{
    protected $table = 'kriteria_unjuk_kerja';
    
    protected $fillable = [
        'elemen_id',
        'kode_kriteria',
        'deskripsi_kriteria',
        'jenis_bukti',
        'metode_asesmen',
        'perangkat_asesmen'
    ];

    public function elemen(): BelongsTo
    {
        return $this->belongsTo(Elemen::class);
    }
}
