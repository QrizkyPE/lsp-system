<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SoalUpload extends Model
{
    protected $table = 'soal_uploads';
    
    protected $fillable = [
        'jadwal_uji_id',
        'asesor_id',
        'jenis_instrumen',
        'file_path',
        'original_filename',
        'file_type',
        'file_size'
    ];

    public function jadwalUji(): BelongsTo
    {
        return $this->belongsTo(JadwalUji::class);
    }

    public function asesor(): BelongsTo
    {
        return $this->belongsTo(Asesor::class);
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(SoalSubmission::class);
    }
}

