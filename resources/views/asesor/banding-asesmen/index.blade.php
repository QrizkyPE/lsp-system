@extends('layouts.app')

@section('title', 'Banding Asesmen')
@section('page-title', 'Banding Asesmen')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="fas fa-gavel me-2"></i>Daftar Banding Asesmen</h4>
                </div>
                <div class="card-body">
                    @if($bandingAsesmen->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Asesi</th>
                                        <th>Skema Sertifikasi</th>
                                        <th>Tanggal Asesmen</th>
                                        <th>Tanggal Banding</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($bandingAsesmen as $index => $banding)
                                        <tr>
                                            <td>{{ $bandingAsesmen->firstItem() + $index }}</td>
                                            <td>{{ $banding->nama_asesi }}</td>
                                            <td>{{ $banding->skema_sertifikasi }}</td>
                                            <td>{{ $banding->tanggal_asesmen->format('d/m/Y') }}</td>
                                            <td>{{ $banding->tanggal_banding->format('d/m/Y') }}</td>
                                            <td>
                                                <a href="{{ route('asesor.banding-asesmen.show', $banding->id) }}" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye me-1"></i>Lihat Detail
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-center mt-3">
                            {{ $bandingAsesmen->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-gavel fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Belum ada banding asesmen</h5>
                            <p class="text-muted">Tidak ada banding asesmen yang diajukan oleh mahasiswa.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

