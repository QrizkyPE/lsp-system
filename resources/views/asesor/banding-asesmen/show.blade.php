@extends('layouts.app')

@section('title', 'Detail Banding Asesmen')
@section('page-title', 'Detail Banding Asesmen')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="fas fa-gavel me-2"></i>FR.AK.04. BANDING ASESMEN</h4>
                </div>
                <div class="card-body">
                    <!-- Informasi Dasar -->
                    <div class="table-responsive mb-4">
                        <table class="table table-bordered">
                            <tr>
                                <td width="25%"><strong>Nama Asesi:</strong></td>
                                <td>{{ $bandingAsesmen->nama_asesi }}</td>
                            </tr>
                            <tr>
                                <td><strong>Nama Asesor:</strong></td>
                                <td>{{ $bandingAsesmen->nama_asesor }}</td>
                            </tr>
                            <tr>
                                <td><strong>Tanggal Asesmen:</strong></td>
                                <td>{{ $bandingAsesmen->tanggal_asesmen->format('d F Y') }}</td>
                            </tr>
                        </table>
                    </div>

                    <!-- Pertanyaan Ya/Tidak -->
                    <div class="mb-4">
                        <h6><strong>Jawablah dengan Ya atau Tidak pertanyaan-pertanyaan berikut ini :</strong></h6>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th style="width: 70%;">Pertanyaan</th>
                                        <th class="text-center" style="width: 15%;">YA</th>
                                        <th class="text-center" style="width: 15%;">TIDAK</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Apakah Proses Banding telah dijelaskan kepada Anda?</td>
                                        <td class="text-center">
                                            @if($bandingAsesmen->proses_banding_dijelaskan == 'ya')
                                                <i class="fas fa-check-circle text-success"></i>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($bandingAsesmen->proses_banding_dijelaskan == 'tidak')
                                                <i class="fas fa-check-circle text-success"></i>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Apakah Anda telah mendiskusikan Banding dengan Asesor?</td>
                                        <td class="text-center">
                                            @if($bandingAsesmen->mendiskusikan_banding == 'ya')
                                                <i class="fas fa-check-circle text-success"></i>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($bandingAsesmen->mendiskusikan_banding == 'tidak')
                                                <i class="fas fa-check-circle text-success"></i>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Apakah Anda mau melibatkan "orang lain" membantu Anda dalam Proses Banding?</td>
                                        <td class="text-center">
                                            @if($bandingAsesmen->melibatkan_orang_lain == 'ya')
                                                <i class="fas fa-check-circle text-success"></i>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($bandingAsesmen->melibatkan_orang_lain == 'tidak')
                                                <i class="fas fa-check-circle text-success"></i>
                                            @endif
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Informasi Skema -->
                    <div class="mb-4">
                        <p>Banding ini diajukan atas Keputusan Asesmen yang dibuat terhadap Skema Sertifikasi (<s>Kualifikasi</s>/<s>Klaster</s>/Okupasi) berikut :</p>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tr>
                                    <td width="30%"><strong>Skema Sertifikasi :</strong></td>
                                    <td>{{ $bandingAsesmen->skema_sertifikasi }}</td>
                                </tr>
                                <tr>
                                    <td><strong>No. Skema Sertifikasi :</strong></td>
                                    <td>{{ $bandingAsesmen->nomor_skema }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- Alasan Banding -->
                    <div class="mb-4">
                        <label class="form-label"><strong>Banding ini diajukan atas alasan sebagai berikut :</strong></label>
                        <div class="p-3 bg-light border rounded">
                            {{ $bandingAsesmen->alasan_banding }}
                        </div>
                    </div>

                    <!-- Tanda Tangan -->
                    <div class="mb-4">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tr>
                                    <td width="30%"><strong>Tanda tangan Asesi :</strong></td>
                                    <td>
                                        @if($bandingAsesmen->mahasiswa_signature)
                                            <div class="text-center">
                                                <img src="{{ $bandingAsesmen->mahasiswa_signature }}" alt="Tanda Tangan" style="max-width: 400px; max-height: 200px; border: 1px solid #ddd; border-radius: 4px;">
                                            </div>
                                        @else
                                            <span class="text-muted">Tidak ada tanda tangan</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Tanggal :</strong></td>
                                    <td>{{ $bandingAsesmen->tanggal_banding->format('d F Y') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- Link ke Rekaman Asesmen -->
                    @if($bandingAsesmen->rekamanAsesmen)
                        <div class="mb-4">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Rekaman Asesmen Terkait:</strong>
                                <a href="{{ route('asesor.rekaman-asesmen.show', $bandingAsesmen->rekamanAsesmen->id) }}" class="alert-link">
                                    Lihat Rekaman Asesmen
                                </a>
                            </div>
                        </div>
                    @endif

                    <!-- Back Button -->
                    <div class="row">
                        <div class="col-12 text-center">
                            <a href="{{ route('asesor.banding-asesmen.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i>Kembali
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

