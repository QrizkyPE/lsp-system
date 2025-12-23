<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DaftarHadirPeserta extends Model
{
    protected $table = 'daftar_hadir_peserta';
    
    protected $fillable = [
        'jadwal_uji_id',
        'no_dokumen',
        'edisi_revisi',
        'tanggal_berlaku',
        'skema',
        'hari_tanggal',
        'tuk_id',
        'penanggung_jawab_tuk',
        'kehadiran_peserta',
        'created_by',
    ];

    protected $casts = [
        'tanggal_berlaku' => 'date',
        'hari_tanggal' => 'date',
        'kehadiran_peserta' => 'array',
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
