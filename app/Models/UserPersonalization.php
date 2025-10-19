<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserPersonalization extends Model
{
    protected $fillable = [
        'user_id',
        'signature_data',
        'signature_filename',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
