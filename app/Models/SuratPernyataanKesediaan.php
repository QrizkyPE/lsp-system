<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SuratPernyataanKesediaan extends Model
{
    protected $table = 'surat_pernyataan_kesediaan';
    
    protected $fillable = [
        'no_dokumen',
        'edisi_revisi',
        'tanggal_berlaku',
        'asesor_id',
        'alamat',
        'no_met_sertifikat',
        'tuk_id',
        'status',
        'signature_data',
        'tanggal_tanda_tangan',
        'created_by',
        'sent_at',
    ];

    protected $casts = [
        'tanggal_berlaku' => 'date',
        'tanggal_tanda_tangan' => 'date',
        'sent_at' => 'datetime',
    ];

    public function asesor(): BelongsTo
    {
        return $this->belongsTo(Asesor::class);
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
