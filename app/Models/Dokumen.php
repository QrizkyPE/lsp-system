<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Dokumen extends Model
{
    protected $table = 'dokumen';
    
    protected $fillable = [
        'pendaftaran_id',
        'jenis_dokumen',
        'nama_dokumen',
        'file_path',
        'file_name',
        'file_size',
        'mime_type',
        'status',
        'catatan',
        'data_dokumen',
        'tanda_tangan',
        'tanggal_submit',
        'tanggal_approve'
    ];

    protected $casts = [
        'data_dokumen' => 'array',
        'tanda_tangan' => 'array',
        'tanggal_submit' => 'datetime',
        'tanggal_approve' => 'datetime',
    ];

    public function pendaftaran(): BelongsTo
    {
        return $this->belongsTo(Pendaftaran::class);
    }
}
