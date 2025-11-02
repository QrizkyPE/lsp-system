<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BandingAsesmen extends Model
{
    protected $table = 'banding_asesmen';

    protected $fillable = [
        'nama_asesi',
        'nama_asesor',
        'tanggal_asesmen',
        'proses_banding_dijelaskan',
        'mendiskusikan_banding',
        'melibatkan_orang_lain',
        'skema_sertifikasi',
        'nomor_skema',
        'alasan_banding',
        'mahasiswa_signature',
        'tanggal_banding',
        'rekaman_asesmen_id',
        'pendaftaran_id',
        'user_id'
    ];

    protected $casts = [
        'tanggal_asesmen' => 'date',
        'tanggal_banding' => 'date',
    ];

    public function rekamanAsesmen(): BelongsTo
    {
        return $this->belongsTo(RekamanAsesmenKompetensi::class, 'rekaman_asesmen_id');
    }

    public function pendaftaran(): BelongsTo
    {
        return $this->belongsTo(Pendaftaran::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
