@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-eye me-2"></i>
                        Detail Rekaman Asesmen Kompetensi
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('asesor.rekaman-asesmen.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i>
                            Kembali
                        </a>
                        @if(!$rekamanAsesmen->mahasiswa_signature)
                            <a href="{{ route('asesor.rekaman-asesmen.edit', $rekamanAsesmen->id) }}" class="btn btn-warning">
                                <i class="fas fa-edit me-1"></i>
                                Edit
                            </a>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <!-- Header -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h4 class="text-center"><strong>FR.AK.02. REKAMAN ASESMEN KOMPETENSI</strong></h4>
                        </div>
                    </div>

                    <!-- Informasi Dasar -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5><strong>Informasi Dasar</strong></h5>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <tr>
                                        <td width="20%"><strong>Skema Sertifikasi (<s>KKNI</s>/Okupasi/<s>Klaster</s>):</strong></td></td>
                                        <td>{{ $rekamanAsesmen->judul }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Judul:</strong></td>
                                        <td>{{ $rekamanAsesmen->judul }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Nomor:</strong></td>
                                        <td>{{ $rekamanAsesmen->nomor_skema }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>TUK:</strong></td>
                                        <td>
                                            @switch($rekamanAsesmen->tuk)
                                                @case('sewaktu')
                                                    Sewaktu
                                                    @break
                                                @case('tempat_kerja')
                                                    Tempat Kerja
                                                    @break
                                                @case('mandiri')
                                                    Mandiri
                                                    @break
                                            @endswitch
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Nama Asesor:</strong></td>
                                        <td>{{ $rekamanAsesmen->nama_asesor }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Nama Asesi:</strong></td>
                                        <td>{{ $rekamanAsesmen->nama_asesi }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Tanggal Asesmen:</strong></td>
                                        <td>
                                            <strong>Mulai:</strong> {{ $rekamanAsesmen->tanggal_mulai->format('d F Y') }} {{ $rekamanAsesmen->waktu_mulai }}<br>
                                            <strong>Selesai:</strong> {{ $rekamanAsesmen->tanggal_selesai->format('d F Y') }} {{ $rekamanAsesmen->waktu_selesai }}
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Unit Kompetensi -->
                    @if($rekamanAsesmen->unit_kompetensi_data && count($rekamanAsesmen->unit_kompetensi_data) > 0)
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5><strong>Beri tanda centang (√) di kolom yang sesuai untuk mencerminkan bukti yang sesuai untuk setiap Unit Kompetensi.</strong></h5>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead class="table-dark">
                                            <tr>
                                                <th width="25%">Unit Kompetensi</th>
                                                <th width="10%" class="text-center">Observasi Demonstrasi</th>
                                                <th width="10%" class="text-center">Portofolio</th>
                                                <th width="10%" class="text-center">Pernyataan Pihak Ketiga</th>
                                                <th width="10%" class="text-center">Pertanyaan Wawancara</th>
                                                <th width="10%" class="text-center">Pertanyaan Lisan</th>
                                                <th width="10%" class="text-center">Pertanyaan Tertulis</th>
                                                <th width="10%" class="text-center">Proyek Kerja</th>
                                                <th width="10%" class="text-center">Lainnya</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($rekamanAsesmen->unit_kompetensi_data as $index => $unitData)
                                                <tr>
                                                    <td><strong>{{ $unitData['judul_unit'] ?? 'Unit ' . ($index + 1) }}</strong></td>
                                                    <td class="text-center">
                                                        @if(isset($unitData['observasi_demonstrasi']) && $unitData['observasi_demonstrasi'])
                                                            <i class="fas fa-check text-success"></i>
                                                        @else
                                                            <i class="fas fa-times text-muted"></i>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        @if(isset($unitData['portofolio']) && $unitData['portofolio'])
                                                            <i class="fas fa-check text-success"></i>
                                                        @else
                                                            <i class="fas fa-times text-muted"></i>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        @if(isset($unitData['pernyataan_pihak_ketiga']) && $unitData['pernyataan_pihak_ketiga'])
                                                            <i class="fas fa-check text-success"></i>
                                                        @else
                                                            <i class="fas fa-times text-muted"></i>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        @if(isset($unitData['pertanyaan_wawancara']) && $unitData['pertanyaan_wawancara'])
                                                            <i class="fas fa-check text-success"></i>
                                                        @else
                                                            <i class="fas fa-times text-muted"></i>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        @if(isset($unitData['pertanyaan_lisan']) && $unitData['pertanyaan_lisan'])
                                                            <i class="fas fa-check text-success"></i>
                                                        @else
                                                            <i class="fas fa-times text-muted"></i>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        @if(isset($unitData['pertanyaan_tertulis']) && $unitData['pertanyaan_tertulis'])
                                                            <i class="fas fa-check text-success"></i>
                                                        @else
                                                            <i class="fas fa-times text-muted"></i>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        @if(isset($unitData['proyek_kerja']) && $unitData['proyek_kerja'])
                                                            <i class="fas fa-check text-success"></i>
                                                        @else
                                                            <i class="fas fa-times text-muted"></i>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        @if(isset($unitData['lainnya']) && $unitData['lainnya'])
                                                            <i class="fas fa-check text-success"></i>
                                                        @else
                                                            <i class="fas fa-times text-muted"></i>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Rekomendasi Hasil -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5><strong>Rekomendasi hasil asesmen</strong></h5>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <tr>
                                        <td width="20%"><strong>Rekomendasi hasil asesmen:</strong></td>
                                        <td>
                                            @if($rekamanAsesmen->rekomendasi_hasil)
                                                @if($rekamanAsesmen->rekomendasi_hasil == 'kompeten')
                                                    <span class="badge bg-success">Kompeten</span>
                                                @else
                                                    <span class="badge bg-warning">Belum kompeten</span>
                                                @endif
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Tindak lanjut yang dibutuhkan:</strong></td>
                                        <td>{{ $rekamanAsesmen->tindak_lanjut ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Komentar/Observasi oleh asesor:</strong></td>
                                        <td>{{ $rekamanAsesmen->komentar_observasi ?? '-' }}</td>
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
                                                <strong>Asesi:</strong><br>
                                                <span>{{ $rekamanAsesmen->nama_asesi }}</span>
                                            </td>
                                            <td>
                                                <div class="text-center">
                                                    @if($rekamanAsesmen->mahasiswa_signature)
                                                        <img src="{{ $rekamanAsesmen->mahasiswa_signature }}" alt="Tanda Tangan Asesi" style="max-width: 100%; height: auto; border: 1px solid #ddd; border-radius: 4px;">
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
                                                    @if($rekamanAsesmen->tanggal_mahasiswa)
                                                        <p class="mb-0">{{ $rekamanAsesmen->tanggal_mahasiswa->format('d F Y') }}</p>
                                                    @else
                                                        <p class="mb-0 text-muted">-</p>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="bg-light">
                                                <strong>Asesor:</strong><br>
                                                <span>{{ $rekamanAsesmen->nama_asesor }}</span>
                                            </td>
                                            <td>
                                                <div class="text-center">
                                                    @if($rekamanAsesmen->asesor_signature)
                                                        <img src="{{ $rekamanAsesmen->asesor_signature }}" alt="Tanda Tangan Asesor" style="max-width: 100%; height: auto; border: 1px solid #ddd; border-radius: 4px;">
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
                                                    @if($rekamanAsesmen->tanggal_asesor)
                                                        <p class="mb-0">{{ $rekamanAsesmen->tanggal_asesor->format('d F Y') }}</p>
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

                    <!-- No Reg Asesor -->
                    @if($rekamanAsesmen->no_reg_asesor)
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <tr>
                                            <td width="20%"><strong>No. Reg Asesor:</strong></td>
                                            <td>{{ $rekamanAsesmen->no_reg_asesor }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Lampiran Dokumen -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5><strong>LAMPIRAN DOKUMEN:</strong></h5>
                            <ol>
                                <li>Dokumen APL 01 peserta</li>
                                <li>Dokumen APL 02 peserta</li>
                                <li>Bukti-bukti berkualitas peserta</li>
                                <li>Tinjauan proses asesmen</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
