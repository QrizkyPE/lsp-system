<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Pendaftaran extends Model
{
    protected $table = 'pendaftaran';
    
    protected $fillable = [
        'user_id',
        'skema_sertifikasi_id',
        'jadwal_uji_id',
        'no_pendaftaran',
        'status',
        'alasan_penolakan',
        'tanggal_pendaftaran',
        'tanggal_verifikasi',
        'tanggal_asesmen',
        'tanggal_selesai',
        'hasil_asesmen',
        'catatan_asesmen',
        'profil_data', 'sertifikasi_data', 'asesmen_data', 'persetujuan_data'
    ];

    protected $casts = [
        'tanggal_pendaftaran' => 'datetime',
        'tanggal_verifikasi' => 'datetime',
        'tanggal_asesmen' => 'datetime',
        'tanggal_selesai' => 'datetime',
        'tanggal_lahir' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function skemaSertifikasi(): BelongsTo
    {
        return $this->belongsTo(SkemaSertifikasi::class);
    }

    public function jadwalUji(): BelongsTo
    {
        return $this->belongsTo(JadwalUji::class);
    }

    public function dokumen(): HasMany
    {
        return $this->hasMany(Dokumen::class);
    }

    public function verifications(): HasMany
    {
        return $this->hasMany(PendaftaranVerification::class);
    }

    public function penugasan(): BelongsToMany
    {
        return $this->belongsToMany(Penugasan::class, 'penugasan_pendaftaran');
    }

    public function observasiChecklists(): HasMany
    {
        return $this->hasMany(ObservasiChecklist::class);
    }

    // Accessor untuk memastikan asesmen_data di-decode dengan benar
    public function getAsesmenDataAttribute($value)
    {
        if (is_string($value)) {
            return json_decode($value, true);
        }
        return $value;
    }

    // Accessor untuk memastikan profil_data di-decode dengan benar
    public function getProfilDataAttribute($value)
    {
        if (is_string($value)) {
            $decoded = json_decode($value, true);
            return $decoded ?: [];
        }
        return $value ?: [];
    }

    // Accessor untuk memastikan sertifikasi_data di-decode dengan benar
    public function getSertifikasiDataAttribute($value)
    {
        if (is_string($value)) {
            $decoded = json_decode($value, true);
            return $decoded ?: [];
        }
        return $value ?: [];
    }

    // Mutator untuk memastikan data disimpan sebagai JSON
    public function setProfilDataAttribute($value)
    {
        $this->attributes['profil_data'] = is_array($value) ? json_encode($value) : $value;
    }

    public function setSertifikasiDataAttribute($value)
    {
        $this->attributes['sertifikasi_data'] = is_array($value) ? json_encode($value) : $value;
    }

    public function setAsesmenDataAttribute($value)
    {
        $this->attributes['asesmen_data'] = is_array($value) ? json_encode($value) : $value;
    }
}
