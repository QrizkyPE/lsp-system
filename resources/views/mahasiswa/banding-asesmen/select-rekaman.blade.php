@extends('layouts.app')

@section('title', 'Pilih Rekaman Asesmen untuk Banding')
@section('page-title', 'Pilih Rekaman Asesmen untuk Banding')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="fas fa-list me-2"></i>Pilih Rekaman Asesmen untuk Banding</h4>
                </div>
                <div class="card-body">
                    @if($availableRekaman->count() > 0)
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            Pilih rekaman asesmen yang ingin Anda ajukan banding. Hanya rekaman asesmen dengan rekomendasi hasil <strong>"Belum Kompeten"</strong> yang dapat diajukan banding.
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>No</th>
                                        <th>Skema Sertifikasi</th>
                                        <th>Nama Asesor</th>
                                        <th>Tanggal Asesmen</th>
                                        <th>Rekomendasi Hasil</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($availableRekaman as $index => $rekaman)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>
                                                <strong>{{ $rekaman->judul }}</strong><br>
                                                <small class="text-muted">No. Skema: {{ $rekaman->nomor_skema }}</small>
                                            </td>
                                            <td>{{ $rekaman->nama_asesor }}</td>
                                            <td>
                                                {{ $rekaman->tanggal_mulai->format('d/m/Y') }}<br>
                                                <small class="text-muted">{{ $rekaman->waktu_mulai }} - {{ $rekaman->waktu_selesai }}</small>
                                            </td>
                                            <td>
                                                @if($rekaman->rekomendasi_hasil)
                                                    @if($rekaman->rekomendasi_hasil == 'kompeten')
                                                        <span class="badge bg-success">Kompeten</span>
                                                    @else
                                                        <span class="badge bg-warning">Belum Kompeten</span>
                                                    @endif
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('mahasiswa.banding-asesmen.create', $rekaman->id) }}" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-gavel me-1"></i>Ajukan Banding
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Tidak ada rekaman asesmen yang tersedia</h5>
                            <p class="text-muted">
                                Tidak ada rekaman asesmen dengan rekomendasi hasil <strong>"Belum Kompeten"</strong> yang tersedia untuk diajukan banding. 
                                <br>Semua rekaman asesmen Anda sudah memiliki banding yang diajukan, atau rekaman asesmen Anda memiliki rekomendasi hasil "Kompeten".
                            </p>
                            <a href="{{ route('mahasiswa.rekaman-asesmen') }}" class="btn btn-primary mt-3">
                                <i class="fas fa-arrow-left me-1"></i>Kembali ke Rekaman Asesmen
                            </a>
                        </div>
                    @endif

                    <div class="mt-3">
                        <a href="{{ route('mahasiswa.banding-asesmen.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i>Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

