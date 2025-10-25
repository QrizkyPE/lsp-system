@extends('layouts.app')

@section('title', 'Detail Ceklis Observasi Aktivitas')
@section('page-title', 'Detail Ceklis Observasi Aktivitas')

@section('content')
<div class="container-fluid">
    <!-- Main Content -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header">
                    <h4 class="mb-0">FR.IA.01. CEKLIS OBSERVASI AKTIVITAS DI TEMPAT KERJA ATAU TEMPAT KERJA SIMULASI</h4>
                </div>
                <div class="card-body">
                    <!-- Informasi Dasar -->
                    <div class="row mb-4">
                        <div class="col-12">
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
                                    <td>{{ $observasiChecklist->judul }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Nomor</strong></td>
                                    <td>{{ $observasiChecklist->nomor_skema }}</td>
                                </tr>
                                <tr>
                                    <td><strong>TUK</strong></td>
                                    <td>
                                        @switch($observasiChecklist->tuk)
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
                                    <td>{{ $observasiChecklist->nama_asesor }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Nama Asesi</strong></td>
                                    <td>{{ $observasiChecklist->nama_asesi }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Tanggal</strong></td>
                                    <td>{{ $observasiChecklist->tanggal->format('d F Y') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- Panduan Asesor -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5><strong>PANDUAN BAGI ASESOR</strong></h5>
                            <div class="alert alert-info">
                                <ul class="mb-0">
                                    <li>Lengkapi nama unit kompetensi, elemen, dan kriteria unjuk kerja sesuai kolom dalam tabel.</li>
                                    <li>Istilah Acuan Pembanding dengan SOP/spesifikasi produk dari industri/organisasi dari tempat kerja atau simulasi tempat kerja</li>
                                    <li>Beri tanda centang (√) pada kolom K jika Anda yakin asesi dapat melakukan/ mendemonstrasikan tugas sesuai KUK, atau centang (√) pada kolom BK bila sebaliknya.</li>
                                    <li>Penilaian Lanjut diisi bila hasil belum dapat disimpulkan, untuk itu gunakan metode lain sehingga keputusan dapat dibuat.</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Unit Kompetensi, Elemen dan Kriteria Unjuk Kerja -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5><strong>Unit Kompetensi, Elemen dan Kriteria Unjuk Kerja</strong></h5>
                            
                            @if($observasiChecklist->elemen_data)
                                @foreach($observasiChecklist->elemen_data as $unitIndex => $unit)
                                    <div class="card mb-4">
                                        <div class="card-header bg-primary text-white">
                                            <h5 class="mb-0">Unit Kompetensi {{ $unitIndex + 1 }}: {{ $unit['nama_unit_kompetensi'] }}</h5>
                                        </div>
                                        <div class="card-body">
                                            @foreach($unit['elemen'] as $elemenIndex => $elemen)
                                                <div class="card mb-3">
                                                    <div class="card-header bg-light">
                                                        <h6 class="mb-0">Elemen {{ $elemenIndex + 1 }}: {{ $elemen['nama_elemen'] }}</h6>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="table-responsive">
                                                            <table class="table table-bordered table-sm">
                                                                <thead class="table-dark">
                                                                    <tr>
                                                                        <th width="40%">Kriteria Unjuk Kerja</th>
                                                                        <th width="15%" class="text-center">K</th>
                                                                        <th width="15%" class="text-center">BK</th>
                                                                        <th width="30%">Benchmark (SOP/Spesifikasi Produk Industri)</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @foreach($elemen['kriteria'] as $kriteriaIndex => $kriteria)
                                                                        <tr>
                                                                            <td>
                                                                                <small>{{ $kriteria['nama_kriteria'] }}</small>
                                                                            </td>
                                                                            <td class="text-center">
                                                                                @if(isset($observasiChecklist->observasi_data[$unitIndex][$elemenIndex][$kriteriaIndex]))
                                                                                    @if($observasiChecklist->observasi_data[$unitIndex][$elemenIndex][$kriteriaIndex] === 'K')
                                                                                        <i class="fas fa-check text-success"></i>
                                                                                    @endif
                                                                                @endif
                                                                            </td>
                                                                            <td class="text-center">
                                                                                @if(isset($observasiChecklist->observasi_data[$unitIndex][$elemenIndex][$kriteriaIndex]))
                                                                                    @if($observasiChecklist->observasi_data[$unitIndex][$elemenIndex][$kriteriaIndex] === 'BK')
                                                                                        <i class="fas fa-check text-danger"></i>
                                                                                    @endif
                                                                                @endif
                                                                            </td>
                                                                            <td>
                                                                                @if(isset($observasiChecklist->benchmark[$unitIndex][$elemenIndex][$kriteriaIndex]))
                                                                                    @php
                                                                                        $benchmarkValue = $observasiChecklist->benchmark[$unitIndex][$elemenIndex][$kriteriaIndex];
                                                                                    @endphp
                                                                                    <small>{{ is_array($benchmarkValue) ? json_encode($benchmarkValue) : $benchmarkValue }}</small>
                                                                                @else
                                                                                    <small class="text-muted">-</small>
                                                                                @endif
                                                                            </td>
                                                                        </tr>
                                                                        @if(isset($observasiChecklist->penilaian_lanjut[$unitIndex][$elemenIndex][$kriteriaIndex]) && $observasiChecklist->penilaian_lanjut[$unitIndex][$elemenIndex][$kriteriaIndex])
                                                                            @php
                                                                                $penilaianValue = $observasiChecklist->penilaian_lanjut[$unitIndex][$elemenIndex][$kriteriaIndex];
                                                                            @endphp
                                                                            <tr>
                                                                                <td colspan="4">
                                                                                    <div class="alert alert-warning mb-0">
                                                                                        <strong>Penilaian Lanjut:</strong>
                                                                                        <p class="mb-0">{{ is_array($penilaianValue) ? json_encode($penilaianValue) : $penilaianValue }}</p>
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                        @endif
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    Belum ada data unit kompetensi, elemen dan kriteria unjuk kerja.
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Istilah Acuan -->
                    @if($observasiChecklist->istilah_acuan)
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5><strong>Istilah Acuan Pembanding</strong></h5>
                                <div class="card">
                                    <div class="card-body">
                                        @php
                                            $istilahAcuan = $observasiChecklist->istilah_acuan;
                                            $istilahAcuanText = is_array($istilahAcuan) ? json_encode($istilahAcuan) : $istilahAcuan;
                                        @endphp
                                        <p class="mb-0">{{ $istilahAcuanText }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Penilaian Lanjut Global -->
                    @if($observasiChecklist->penilaian_lanjut && !is_array($observasiChecklist->penilaian_lanjut))
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5><strong>Penilaian Lanjut</strong></h5>
                                <div class="card">
                                    <div class="card-body">
                                        <p class="mb-0">{{ $observasiChecklist->penilaian_lanjut }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Status -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5><strong>Status Observasi</strong></h5>
                            @if($observasiChecklist->observasi_data)
                                <span class="badge bg-success fs-6">
                                    <i class="fas fa-check me-1"></i>Observasi Selesai
                                </span>
                            @else
                                <span class="badge bg-warning fs-6">
                                    <i class="fas fa-clock me-1"></i>Draft - Belum Diobservasi
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Umpan Balik Untuk Asesi -->
                    @if($observasiChecklist->umpan_balik)
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5><strong>Umpan Balik Untuk Asesi</strong></h5>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr>
                                            <td class="bg-light" width="20%">
                                                <strong>Umpan Balik:</strong>
                                            </td>
                                            <td>
                                                <div class="p-3">
                                                    @php
                                                        $umpanBalik = $observasiChecklist->umpan_balik;
                                                        $umpanBalikText = is_array($umpanBalik) ? json_encode($umpanBalik) : $umpanBalik;
                                                    @endphp
                                                    {!! nl2br(e($umpanBalikText)) !!}
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    @endif

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
                                                <span>{{ $observasiChecklist->nama_asesi }}</span>
                                            </td>
                                            <td>
                                                <div class="text-center">
                                                    @if($observasiChecklist->mahasiswa_signature)
                                                        <img src="{{ $observasiChecklist->mahasiswa_signature }}" 
                                                             alt="Tanda Tangan Mahasiswa" 
                                                             style="max-width: 200px; border: 1px solid #ddd; border-radius: 4px; background: white;">
                                                    @else
                                                        <p class="text-muted mb-2">
                                                            <i class="fas fa-info-circle me-1"></i>
                                                            Tanda tangan asesi akan diisi oleh mahasiswa setelah ceklis observasi dikirim
                                                        </p>
                                                        <div class="border rounded p-3 bg-light">
                                                            <i class="fas fa-signature fa-2x text-muted"></i>
                                                            <p class="mb-0 mt-2 text-muted">Tanda Tangan Asesi</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <div class="text-center">
                                                    @if($observasiChecklist->tanggal_mahasiswa)
                                                        <div class="border rounded p-3 bg-light">
                                                            <i class="fas fa-calendar fa-2x text-success"></i>
                                                            <p class="mb-0 mt-2 text-success">
                                                                {{ \Carbon\Carbon::parse($observasiChecklist->tanggal_mahasiswa)->format('d F Y') }}
                                                            </p>
                                                        </div>
                                                    @else
                                                        <p class="text-muted mb-2">
                                                            <i class="fas fa-info-circle me-1"></i>
                                                            Tanggal akan diisi otomatis saat mahasiswa menandatangani
                                                        </p>
                                                        <div class="border rounded p-3 bg-light">
                                                            <i class="fas fa-calendar fa-2x text-muted"></i>
                                                            <p class="mb-0 mt-2 text-muted">Tanggal Asesi</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="bg-light">
                                                <strong>Asesor:</strong><br>
                                                <span>{{ $observasiChecklist->nama_asesor }}</span>
                                            </td>
                                            <td>
                                                <div class="text-center">
                                                    @if($observasiChecklist->asesor_signature)
                                                        <img src="{{ $observasiChecklist->asesor_signature }}" 
                                                             alt="Tanda Tangan Asesor" 
                                                             style="max-width: 200px; border: 1px solid #ddd; border-radius: 4px; background: white;">
                                                    @else
                                                        <div class="border rounded p-3 bg-light">
                                                            <i class="fas fa-signature fa-2x text-muted"></i>
                                                            <p class="mb-0 mt-2 text-muted">Belum Ditandatangani</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <div class="text-center">
                                                    @if($observasiChecklist->tanggal_asesor)
                                                        <div class="border rounded p-3 bg-light">
                                                            <i class="fas fa-calendar fa-2x text-success"></i>
                                                            <p class="mb-0 mt-2 text-success">
                                                                {{ \Carbon\Carbon::parse($observasiChecklist->tanggal_asesor)->format('d F Y') }}
                                                            </p>
                                                        </div>
                                                    @else
                                                        <div class="border rounded p-3 bg-light">
                                                            <i class="fas fa-calendar fa-2x text-muted"></i>
                                                            <p class="mb-0 mt-2 text-muted">Belum Ditandatangani</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="row">
                        <div class="col-12">
                            <div class="d-flex justify-content-start">
                                <a href="{{ route('asesor.observasi.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left me-1"></i>Kembali
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
