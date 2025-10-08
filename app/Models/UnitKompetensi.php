<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UnitKompetensi extends Model
{
    protected $table = 'unit_kompetensi';
    
    protected $fillable = [
        'skema_sertifikasi_id',
        'kode_unit',
        'nama_unit',
        'deskripsi',
        'kriteria_penilaian'
    ];

    public function skemaSertifikasi(): BelongsTo
    {
        return $this->belongsTo(SkemaSertifikasi::class);
    }

    public function elemen(): HasMany
    {
        return $this->hasMany(Elemen::class);
    }
}
