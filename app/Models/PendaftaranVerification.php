<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PendaftaranVerification extends Model
{
    protected $fillable = [
        'pendaftaran_id',
        'verifier_id',
        'type',
        'status',
        'signature_data',
        'verifier_name',
        'verifier_position',
        'verification_date',
        'notes',
    ];

    protected $casts = [
        'verification_date' => 'datetime',
    ];

    public function pendaftaran(): BelongsTo
    {
        return $this->belongsTo(Pendaftaran::class);
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verifier_id');
    }
}
