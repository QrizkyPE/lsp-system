<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PenyesuaianChecklist extends Model
{
    protected $fillable = [
        'judul',
        'nomor_skema',
        'tuk',
        'nama_asesor',
        'nama_asesi',
        'tanggal',
        'potensi_asesi',
        'modifikasi_data',
        'acuan_pembanding',
        'metode_asesmen',
        'instrumen_asesmen',
        'asesor_signature',
        'tanggal_asesor',
        'mahasiswa_signature',
        'tanggal_mahasiswa',
        'asesor_id',
        'pendaftaran_id'
    ];

    protected $casts = [
        'tanggal' => 'date',
        'tanggal_asesor' => 'date',
        'tanggal_mahasiswa' => 'date',
        'potensi_asesi' => 'array',
        'modifikasi_data' => 'array'
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
