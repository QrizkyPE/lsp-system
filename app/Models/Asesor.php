<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Asesor extends Model
{
    protected $table = 'asesor';
    
    protected $fillable = [
        'user_id',
        'nama_lengkap',
        'nip',
        'jabatan',
        'instansi',
        'no_reg',
        'no_sertifikat_asesor',
        'tanggal_sertifikat',
        'tanggal_expired',
        'skema_kompetensi',
        'status'
    ];

    protected $casts = [
        'tanggal_sertifikat' => 'date',
        'tanggal_expired' => 'date',
        'skema_kompetensi' => 'array',
        'status' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function penugasan(): HasMany
    {
        return $this->hasMany(Penugasan::class);
    }
}
