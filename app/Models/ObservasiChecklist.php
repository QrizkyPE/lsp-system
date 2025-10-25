<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ObservasiChecklist extends Model
{
    protected $fillable = [
        'judul',
        'nomor_skema',
        'tuk',
        'nama_asesor',
        'nama_asesi',
        'tanggal',
        'elemen_data',
        'observasi_data',
        'benchmark',
        'penilaian_lanjut',
        'umpan_balik',
        'asesor_signature',
        'tanggal_asesor',
        'mahasiswa_signature',
        'tanggal_mahasiswa',
        'asesor_id',
        'pendaftaran_id'
    ];

    protected $casts = [
        'tanggal' => 'date',
        'elemen_data' => 'array',
        'observasi_data' => 'array',
        'benchmark' => 'array',
        'penilaian_lanjut' => 'array'
    ];

    public function asesor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'asesor_id');
    }

    public function pendaftaran(): BelongsTo
    {
        return $this->belongsTo(Pendaftaran::class);
    }
}
