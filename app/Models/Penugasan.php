<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Penugasan extends Model
{
    protected $table = 'penugasan';
    
    protected $fillable = [
        'jadwal_uji_id',
        'asesor_id',
        'jenis_penugasan',
        'status',
        'keterangan',
        'tanggal_penugasan',
        'tanggal_konfirmasi'
    ];

    protected $casts = [
        'tanggal_penugasan' => 'datetime',
        'tanggal_konfirmasi' => 'datetime',
    ];

    public function jadwalUji(): BelongsTo
    {
        return $this->belongsTo(JadwalUji::class);
    }

    public function asesor(): BelongsTo
    {
        return $this->belongsTo(Asesor::class);
    }
}
