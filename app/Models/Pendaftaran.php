<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        'nama_lengkap', 'no_ktp', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin',
        'kebangsaan', 'alamat_rumah', 'kode_pos', 'rumah', 'kantor', 'no_telp', 'email',
        'kualifikasi_pendidikan', 'pekerjaan', 'nama_institusi', 'jabatan',
        'alamat_lembaga', 'kode_pos_lembaga', 'no_telp_lembaga', 'no_fax_lembaga', 'email_lembaga',
        'sumber_anggaran', 'pemberi_anggaran'
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
}
