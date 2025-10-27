@extends('layouts.app')

@section('title', 'Detail Ceklis Penyesuaian Yang Wajar dan Beralasan')
@section('page-title', 'Detail Ceklis Penyesuaian Yang Wajar dan Beralasan')

@section('content')
<div class="container-fluid">
    <!-- Main Content -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">FR.AK.07 CEKLIS PENYESUAIAN YANG WAJAR DAN BERALASAN</h4>
                    <div>
                        <a href="{{ route('asesor.penyesuaian.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i>Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Informasi Dasar -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5><strong>Informasi Dasar</strong></h5>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <tr>
                                        <td width="20%"><strong>Skema Sertifikasi</strong></td>
                                        <td>
                                            <span class="text-decoration-line-through">KKNI</span> / 
                                            <strong>Okupasi</strong> / 
                                            <span class="text-decoration-line-through">Klaster</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Judul</strong></td>
                                        <td>{{ $penyesuaianChecklist->judul }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Nomor</strong></td>
                                        <td>{{ $penyesuaianChecklist->nomor_skema }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>TUK</strong></td>
                                        <td>
                                            @switch($penyesuaianChecklist->tuk)
                                                @case('sewaktu')
                                                    ☑ Sewaktu ☐ Tempat Kerja ☐ Mandiri
                                                    @break
                                                @case('tempat_kerja')
                                                    ☐ Sewaktu ☑ Tempat Kerja ☐ Mandiri
                                                    @break
                                                @case('mandiri')
                                                    ☐ Sewaktu ☐ Tempat Kerja ☑ Mandiri
                                                    @break
                                            @endswitch
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Nama Asesor</strong></td>
                                        <td>{{ $penyesuaianChecklist->nama_asesor }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Nama Asesi</strong></td>
                                        <td>{{ $penyesuaianChecklist->nama_asesi }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Tanggal</strong></td>
                                        <td>{{ $penyesuaianChecklist->tanggal->format('d F Y') }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Panduan Asesor -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5><strong>PANDUAN BAGI ASESOR</strong></h5>
                            <div class="alert alert-info">
                                <p>Formulir ini dapat digunakan (sebelum pra asesmen, saat pelaksanaan pra asesmen, setelah pra asesmen)* jika ada asesi yang mempunyai keterbatasan sesuai karakteristik yang dimilikinya sehingga diperlukan penyesuaian yang wajar dan beralasan, jika rencana asesmen dan perangkat asesmen tidak sesuai dengan acuan pembanding, potensi asesi dan konteks asesi, jika asesi merasa keletihan, sakit, serta jika kondisi alam, listrik padam,……..</p>
                                <p><strong>Coretlah pada tanda * yang tidak sesuai.</strong></p>
                                <p><strong>Berilah tanda √ pada kotak '☐' pada kolom potensi asesi</strong></p>
                                <p><strong>Berilah tanda √ Ya atau Tidak pada tanda ** sesuai pilihan, jika jawaban Ya selanjutnya pada kolom keterangan berilah tanda √ di kotak '☐' yang tersedia, pilihan boleh lebih dari satu.</strong></p>
                            </div>
                        </div>
                    </div>

                    <!-- Potensi Asesi -->
                    @if($penyesuaianChecklist->potensi_asesi)
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5><strong>Potensi Asesi</strong></h5>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead class="table-dark">
                                            <tr>
                                                <th width="80%">Potensi Asesi</th>
                                                <th width="20%" class="text-center">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Hasil pelatihan dan / atau pendidikan, dimana Kurikulum dan fasilitas praktek mampu telusur terhadap standar kompetensi</td>
                                                <td class="text-center">
                                                    @if(in_array('pelatihan_telusur', $penyesuaianChecklist->potensi_asesi))
                                                        <i class="fas fa-check text-success"></i>
                                                    @else
                                                        <i class="fas fa-times text-muted"></i>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Hasil pelatihan dan / atau pendidikan, dimana kurikulum belum berbasis kompetensi.</td>
                                                <td class="text-center">
                                                    @if(in_array('pelatihan_belum_kompetensi', $penyesuaianChecklist->potensi_asesi))
                                                        <i class="fas fa-check text-success"></i>
                                                    @else
                                                        <i class="fas fa-times text-muted"></i>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Pekerja berpengalaman, dimana berasal dari industri/tempat kerja yang dalam operasionalnya mampu telusur dengan standar kompetensi</td>
                                                <td class="text-center">
                                                    @if(in_array('pekerja_telusur', $penyesuaianChecklist->potensi_asesi))
                                                        <i class="fas fa-check text-success"></i>
                                                    @else
                                                        <i class="fas fa-times text-muted"></i>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Pekerja berpengalaman, dimana berasal dari industri/tempat kerja yang dalam operasionalnya belum berbasis kompetensi.</td>
                                                <td class="text-center">
                                                    @if(in_array('pekerja_belum_kompetensi', $penyesuaianChecklist->potensi_asesi))
                                                        <i class="fas fa-check text-success"></i>
                                                    @else
                                                        <i class="fas fa-times text-muted"></i>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Pelatihan / belajar mandiri atau otodidak.</td>
                                                <td class="text-center">
                                                    @if(in_array('mandiri_otodidak', $penyesuaianChecklist->potensi_asesi))
                                                        <i class="fas fa-check text-success"></i>
                                                    @else
                                                        <i class="fas fa-times text-muted"></i>
                                                    @endif
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Modifikasi dan Kontekstualisasi -->
                    @if($penyesuaianChecklist->modifikasi_data)
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5><strong>Mengidentifikasi Persyaratan Modifikasi dan Kontekstualisasi (karakteristik asesi):</strong></h5>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead class="table-dark">
                                            <tr>
                                                <th width="5%">No</th>
                                                <th width="40%">Diperlukan penyesuaian</th>
                                                <th width="10%" class="text-center">Ya</th>
                                                <th width="10%" class="text-center">Tidak</th>
                                                <th width="35%">Keterangan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @for($i = 1; $i <= 8; $i++)
                                                <tr>
                                                    <td>{{ $i }}</td>
                                                    <td>
                                                        @switch($i)
                                                            @case(1)
                                                                Keterbatasan asesi terhadap persyaratan bahasa, literasi, numerasi.
                                                                @break
                                                            @case(2)
                                                                Penyediaan dukungan pembaca, penerjemah, pelayan, penulis.
                                                                @break
                                                            @case(3)
                                                                Penggunaan teknologi adaptif atau peralatan khusus. (Tidak dapat menggunakan teknologi adaptif (misal: mengoperasikan komputer dan printer, peralatan digital dsb).
                                                                @break
                                                            @case(4)
                                                                Pelaksanaan asesmen secara fleksibel karena alasan keletihan atau keperluan pengobatan.
                                                                @break
                                                            @case(5)
                                                                Penyediaan peralatan asesmen berupa braille, audio/video-tape.
                                                                @break
                                                            @case(6)
                                                                Penyesuaian tempat fisik/lingkungan asesmen
                                                                @break
                                                            @case(7)
                                                                Pertimbangan umur/usia lanjut/gender asesi. (Adanya perbedaan usia dengan asesor yang lebih muda).
                                                                @break
                                                            @case(8)
                                                                Pertimbangan budaya/tradisi/agama.
                                                                @break
                                                        @endswitch
                                                    </td>
                                                    <td class="text-center">
                                                        @if(isset($penyesuaianChecklist->modifikasi_data[$i]) && is_array($penyesuaianChecklist->modifikasi_data[$i]))
                                                            <i class="fas fa-check text-success"></i>
                                                        @else
                                                            <i class="fas fa-times text-muted"></i>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        @if(!isset($penyesuaianChecklist->modifikasi_data[$i]) || !is_array($penyesuaianChecklist->modifikasi_data[$i]))
                                                            <i class="fas fa-check text-success"></i>
                                                        @else
                                                            <i class="fas fa-times text-muted"></i>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if(isset($penyesuaianChecklist->modifikasi_data[$i]) && is_array($penyesuaianChecklist->modifikasi_data[$i]))
                                                            <ul class="list-unstyled mb-0">
                                                                @foreach($penyesuaianChecklist->modifikasi_data[$i] as $keterangan)
                                                                    <li><i class="fas fa-check text-success me-1"></i>{{ $keterangan }}</li>
                                                                @endforeach
                                                            </ul>
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endfor
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Hasil Penyesuaian -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5><strong>Hasil Penyesuaian yang wajar dan beralasan disepakati menggunakan :</strong></h5>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <tr>
                                        <td width="20%"><strong>Acuan Pembanding Asesmen:</strong></td>
                                        <td>{{ $penyesuaianChecklist->acuan_pembanding ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Metode Asesmen:</strong></td>
                                        <td>{{ $penyesuaianChecklist->metode_asesmen ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Instrumen Asesmen:</strong></td>
                                        <td>{{ $penyesuaianChecklist->instrumen_asesmen ?? '-' }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Tanda Tangan dan Tanggal -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5><strong>Tanda Tangan dan Tanggal</strong></h5>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="table-dark">
                                        <tr>
                                            <th width="20%">Nama</th>
                                            <th width="40%">Tanda Tangan</th>
                                            <th width="40%">Tanggal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="bg-light">
                                                <strong>Asesor:</strong><br>
                                                <span>{{ $penyesuaianChecklist->nama_asesor }}</span>
                                            </td>
                                            <td>
                                                <div class="text-center">
                                                    @if($penyesuaianChecklist->asesor_signature)
                                                        <img src="{{ $penyesuaianChecklist->asesor_signature }}" alt="Tanda Tangan Asesor" style="max-width: 100%; height: auto; border: 1px solid #ddd; border-radius: 4px;">
                                                    @else
                                                        <div class="border rounded p-3 bg-light">
                                                            <i class="fas fa-signature fa-2x text-muted"></i>
                                                            <p class="mb-0 mt-2 text-muted">Belum ada tanda tangan</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <div class="text-center">
                                                    @if($penyesuaianChecklist->tanggal_asesor)
                                                        <p class="mb-0">{{ $penyesuaianChecklist->tanggal_asesor->format('d F Y') }}</p>
                                                    @else
                                                        <p class="mb-0 text-muted">-</p>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="bg-light">
                                                <strong>Asesi:</strong><br>
                                                <span>{{ $penyesuaianChecklist->nama_asesi }}</span>
                                            </td>
                                            <td>
                                                <div class="text-center">
                                                    @if($penyesuaianChecklist->mahasiswa_signature)
                                                        <img src="{{ $penyesuaianChecklist->mahasiswa_signature }}" alt="Tanda Tangan Asesi" style="max-width: 100%; height: auto; border: 1px solid #ddd; border-radius: 4px;">
                                                    @else
                                                        <div class="border rounded p-3 bg-light">
                                                            <i class="fas fa-signature fa-2x text-muted"></i>
                                                            <p class="mb-0 mt-2 text-muted">Belum ditandatangani</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <div class="text-center">
                                                    @if($penyesuaianChecklist->tanggal_mahasiswa)
                                                        <p class="mb-0">{{ $penyesuaianChecklist->tanggal_mahasiswa->format('d F Y') }}</p>
                                                    @else
                                                        <p class="mb-0 text-muted">-</p>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
