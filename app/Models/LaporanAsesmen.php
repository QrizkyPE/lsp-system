<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LaporanAsesmen extends Model
{
    protected $table = 'laporan_asesmen';

    protected $fillable = [
        'jadwal_uji_id',
        'tuk_id',
        'tuk_type',
        'asesor_id',
        'tanggal',
        'hasil_asesi',
        'aspek_positif_negatif',
        'penolakan_hasil',
        'saran_perbaikan',
        'catatan',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'hasil_asesi' => 'array',
    ];

    public function jadwalUji(): BelongsTo
    {
        return $this->belongsTo(JadwalUji::class);
    }

    public function tuk(): BelongsTo
    {
        return $this->belongsTo(Tuk::class);
    }

    public function asesor(): BelongsTo
    {
        return $this->belongsTo(Asesor::class);
    }
}

