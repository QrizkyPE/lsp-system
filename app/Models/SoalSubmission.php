<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SoalSubmission extends Model
{
    protected $table = 'soal_submissions';
    
    protected $fillable = [
        'soal_upload_id',
        'pendaftaran_id',
        'file_path',
        'original_filename',
        'file_type',
        'file_size',
        'submitted_at'
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
    ];

    public function soalUpload(): BelongsTo
    {
        return $this->belongsTo(SoalUpload::class);
    }

    public function pendaftaran(): BelongsTo
    {
        return $this->belongsTo(Pendaftaran::class);
    }
}

