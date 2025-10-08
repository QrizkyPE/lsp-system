<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tuk extends Model
{
    protected $table = 'tuk';
    
    protected $fillable = [
        'nama_tuk',
        'alamat',
        'kota',
        'provinsi',
        'kode_pos',
        'telepon',
        'email',
        'penanggung_jawab',
        'skema_kompetensi',
        'status'
    ];

    protected $casts = [
        'skema_kompetensi' => 'array',
        'status' => 'boolean',
    ];

    public function jadwalUji(): HasMany
    {
        return $this->hasMany(JadwalUji::class);
    }
}
