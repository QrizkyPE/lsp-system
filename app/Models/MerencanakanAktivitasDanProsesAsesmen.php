<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MerencanakanAktivitasDanProsesAsesmen extends Model
{
    protected $table = 'merencanakan_aktivitas_dan_proses_asesmen';
    
    protected $fillable = [
        'skema_sertifikasi_id',
        'asesor_id',
        'peserta',
        'tujuan_asesmen',
        'lingkungan',
        'peluang_bukti',
        'hubungan_standar_kompetensi',
        'pelaku_asesmen',
        'konfirmasi_orang_relevan_1',
        'konfirmasi_orang_relevan_1_lainnya',
        'tolok_ukur_asesmen',
        'rencana_asesmen',
        'karakteristik_kandidat',
        'kebutuhan_kontekstualisasi_tempat_kerja',
        'saran_paket_pelatihan',
        'penyesuaian_perangkat_asesmen',
        'peluang_kegiatan_terintegrasi',
        'kegiatan_terintegrasi_units',
        'konfirmasi_orang_relevan_2',
        'konfirmasi_orang_relevan_2_lainnya',
        'penyusun_nama',
        'penyusun_jabatan',
        'penyusun_tandatangan',
        'penyusun_tanggal',
        'validator_nama',
        'validator_jabatan',
        'validator_tandatangan',
        'validator_tanggal',
    ];

    protected $casts = [
        'hubungan_standar_kompetensi' => 'array',
        'rencana_asesmen' => 'array',
        'kegiatan_terintegrasi_units' => 'array',
        'penyusun_tanggal' => 'date',
        'validator_tanggal' => 'date',
    ];

    public function skemaSertifikasi(): BelongsTo
    {
        return $this->belongsTo(SkemaSertifikasi::class);
    }

    public function asesor(): BelongsTo
    {
        return $this->belongsTo(Asesor::class);
    }
}
