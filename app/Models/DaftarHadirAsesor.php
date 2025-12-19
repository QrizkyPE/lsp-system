<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DaftarHadirAsesor extends Model
{
    protected $table = 'daftar_hadir_asesor';
    
    protected $fillable = [
        'jadwal_uji_id',
        'no_dokumen',
        'edisi_revisi',
        'tanggal_berlaku',
        'halaman',
        'halaman_total',
        'skema',
        'pukul_mulai',
        'pukul_selesai',
        'hari_tanggal',
        'tuk_id',
        'penanggung_jawab_tuk',
        'asesor_signatures',
        'created_by',
    ];

    protected $casts = [
        'tanggal_berlaku' => 'date',
        'hari_tanggal' => 'date',
        'pukul_mulai' => 'datetime:H:i',
        'pukul_selesai' => 'datetime:H:i',
        'asesor_signatures' => 'array',
    ];

    public function jadwalUji(): BelongsTo
    {
        return $this->belongsTo(JadwalUji::class);
    }

    public function tuk(): BelongsTo
    {
        return $this->belongsTo(Tuk::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
