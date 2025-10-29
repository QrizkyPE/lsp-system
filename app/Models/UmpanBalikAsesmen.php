<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UmpanBalikAsesmen extends Model
{
    protected $table = 'umpan_balik_asesmen';

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
        'umpan_balik_data',
        'catatan_lainnya',
        'mahasiswa_id',
        'rekaman_asesmen_id'
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'umpan_balik_data' => 'array'
    ];

    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(User::class, 'mahasiswa_id');
    }

    public function rekamanAsesmen(): BelongsTo
    {
        return $this->belongsTo(RekamanAsesmenKompetensi::class);
    }
}
