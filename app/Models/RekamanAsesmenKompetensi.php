<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RekamanAsesmenKompetensi extends Model
{
    protected $table = 'rekaman_asesmen_kompetensi';

    protected $fillable = [
        'judul',
        'nomor_skema',
        'tuk',
        'nama_asesor',
        'nama_asesi',
        'tanggal_mulai',
        'waktu_mulai',
        'tanggal_selesai',
        'waktu_selesai',
        'unit_kompetensi_data',
        'rekomendasi_hasil',
        'tindak_lanjut',
        'komentar_observasi',
        'no_reg_asesor',
        'asesor_signature',
        'tanggal_asesor',
        'mahasiswa_signature',
        'tanggal_mahasiswa',
        'asesor_id',
        'pendaftaran_id'
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'tanggal_asesor' => 'date',
        'tanggal_mahasiswa' => 'date',
        'unit_kompetensi_data' => 'array'
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
