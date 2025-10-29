@extends('layouts.app')

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-eye me-2"></i>
                        Detail Umpan Balik dan Catatan Asesmen
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('mahasiswa.umpan-balik.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i>
                            Kembali
                        </a>
                    </div>
                </div>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Informasi:</strong> Umpan balik ini telah dikirim dan tidak dapat diedit atau dihapus.
                </div>
                <div class="card-body">
                    <!-- Header -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h4 class="text-center"><strong>FR.AK.03. UMPAN BALIK DAN CATATAN ASESMEN</strong></h4>
                        </div>
                    </div>

                    <!-- Informasi Dasar -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5><strong>Informasi Dasar</strong></h5>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <tr>
                                        <td width="20%"><strong>Skema Sertifikasi (KKNI/Okupasi/Klaster):</strong></td>
                                        <td>{{ $umpanBalik->judul }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Judul:</strong></td>
                                        <td>{{ $umpanBalik->judul }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Nomor:</strong></td>
                                        <td>{{ $umpanBalik->nomor_skema }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>TUK:</strong></td>
                                        <td>
                                            @switch($umpanBalik->tuk)
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
                                        <td>{{ $umpanBalik->nama_asesor }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Nama Asesi:</strong></td>
                                        <td>{{ $umpanBalik->nama_asesi }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Tanggal Asesmen:</strong></td>
                                        <td>
                                            <strong>Mulai:</strong> {{ $umpanBalik->tanggal_mulai->format('d F Y') }} {{ $umpanBalik->waktu_mulai }}<br>
                                            <strong>Selesai:</strong> {{ $umpanBalik->tanggal_selesai->format('d F Y') }} {{ $umpanBalik->waktu_selesai }}
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Umpan Balik dari Asesi -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5><strong>Umpan balik dari Asesi (diisi oleh Asesi setelah pengambilan keputusan):</strong></h5>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="table-dark">
                                        <tr>
                                            <th width="50%">KOMPONEN</th>
                                            <th width="10%" class="text-center">Hasil</th>
                                            <th width="10%" class="text-center">Ya</th>
                                            <th width="10%" class="text-center">Tidak</th>
                                            <th width="20%">Catatan/Komentar Asesi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $komponen = [
                                                'Saya mendapatkan penjelasan yang cukup memadai mengenai proses asesmen/uji kompetensi',
                                                'Saya diberikan kesempatan untuk mempelajari standar kompetensi yang akan diujikan dan menilai diri sendiri terhadap pencapaiannya',
                                                'Asesor memberikan kesempatan untuk mendiskusikan/menegosiasikan metoda, instrumen dan sumber asesmen serta jadwal asesmen',
                                                'Asesor berusaha menggali seluruh bukti pendukung yang sesuai dengan latar belakang pelatihan dan pengalaman yang saya miliki',
                                                'Saya sepenuhnya diberikan kesempatan untuk mendemonstrasikan kompetensi yang saya miliki selama asesmen',
                                                'Saya mendapatkan penjelasan yang memadai mengenai keputusan asesmen',
                                                'Asesor memberikan umpan balik yang mendukung setelah asesmen serta tindak lanjutnya',
                                                'Asesor bersama saya mempelajari semua dokumen asesmen serta menandatanganinya',
                                                'Saya mendapatkan jaminan kerahasiaan hasil asesmen serta penjelasan penanganan dokumen asesmen',
                                                'Asesor menggunakan keterampilan komunikasi yang efektif selama asesmen'
                                            ];
                                        @endphp
                                        @foreach($komponen as $index => $komponenText)
                                            <tr>
                                                <td>{{ $komponenText }}</td>
                                                <td class="text-center">
                                                    @if(isset($umpanBalik->umpan_balik_data[$index]))
                                                        @if(isset($umpanBalik->umpan_balik_data[$index]['ya']) && $umpanBalik->umpan_balik_data[$index]['ya'])
                                                            <span class="badge bg-success">Ya</span>
                                                        @elseif(isset($umpanBalik->umpan_balik_data[$index]['tidak']) && $umpanBalik->umpan_balik_data[$index]['tidak'])
                                                            <span class="badge bg-danger">Tidak</span>
                                                        @else
                                                            <span class="badge bg-secondary">-</span>
                                                        @endif
                                                    @else
                                                        <span class="badge bg-secondary">-</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    @if(isset($umpanBalik->umpan_balik_data[$index]['ya']) && $umpanBalik->umpan_balik_data[$index]['ya'])
                                                        <i class="fas fa-check text-success"></i>
                                                    @else
                                                        <i class="fas fa-times text-muted"></i>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    @if(isset($umpanBalik->umpan_balik_data[$index]['tidak']) && $umpanBalik->umpan_balik_data[$index]['tidak'])
                                                        <i class="fas fa-check text-success"></i>
                                                    @else
                                                        <i class="fas fa-times text-muted"></i>
                                                    @endif
                                                </td>
                                                <td>{{ $umpanBalik->umpan_balik_data[$index]['catatan'] ?? '-' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Catatan Lainnya -->
                    @if($umpanBalik->catatan_lainnya)
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5><strong>Catatan/komentar lainnya (apabila ada):</strong></h5>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <tr>
                                            <td>{{ $umpanBalik->catatan_lainnya }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
