<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Elemen extends Model
{
    protected $table = 'elemen';
    
    protected $fillable = [
        'unit_kompetensi_id',
        'kode_elemen',
        'nama_elemen',
        'deskripsi'
    ];

    public function unitKompetensi(): BelongsTo
    {
        return $this->belongsTo(UnitKompetensi::class);
    }

    public function kriteriaUnjukKerja(): HasMany
    {
        return $this->hasMany(KriteriaUnjukKerja::class);
    }
}
