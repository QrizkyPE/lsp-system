@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title mb-0">
                            <i class="fas fa-comments me-2"></i>
                            Umpan Balik dan Catatan Asesmen
                        </h3>
                        <div class="d-flex align-items-center gap-3">
                            @if(isset($pendingUmpanBalikCount) && $pendingUmpanBalikCount > 0)
                                <span class="badge bg-danger fs-6">
                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $pendingUmpanBalikCount }} Perlu Umpan Balik
                                </span>
                            @endif
                            <a href="{{ route('mahasiswa.umpan-balik.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-1"></i>
                                Buat Umpan Balik
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if(isset($pendingUmpanBalikCount) && $pendingUmpanBalikCount > 0)
                        <div class="alert alert-info alert-dismissible fade show" role="alert">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Informasi:</strong> Asesi diharapkan untuk membuat Umpan Balik Asesmen jika sudah menerima hasil Rekaman Asesmen. 
                            Saat ini Anda memiliki <strong>{{ $pendingUmpanBalikCount }}</strong> rekaman asesmen yang sudah ditandatangani dan memerlukan umpan balik.
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @else
                        <div class="alert alert-info alert-dismissible fade show" role="alert">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Informasi:</strong> Asesi diharapkan untuk membuat Umpan Balik Asesmen jika sudah menerima hasil Rekaman Asesmen.
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($umpanBalik->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th width="5%">No</th>
                                        <th width="25%">Skema Sertifikasi</th>
                                        <th width="15%">TUK</th>
                                        <th width="20%">Nama Asesor</th>
                                        <th width="15%">Tanggal Asesmen</th>
                                        <th width="20%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($umpanBalik as $index => $umpan)
                                        <tr>
                                            <td class="text-center">{{ $umpanBalik->firstItem() + $index }}</td>
                                            <td>
                                                {{ $umpan->judul }}
                                                <br><small class="text-muted">
                                                    <i class="fas fa-lock me-1"></i>Terkirim - Tidak dapat diedit
                                                </small>
                                            </td>
                                            <td>
                                                @switch($umpan->tuk)
                                                    @case('sewaktu')
                                                        <span class="badge bg-info">Sewaktu</span>
                                                        @break
                                                    @case('tempat_kerja')
                                                        <span class="badge bg-warning">Tempat Kerja</span>
                                                        @break
                                                    @case('mandiri')
                                                        <span class="badge bg-success">Mandiri</span>
                                                        @break
                                                @endswitch
                                            </td>
                                            <td>{{ $umpan->nama_asesor }}</td>
                                            <td>
                                                {{ $umpan->tanggal_mulai->format('d/m/Y') }} - {{ $umpan->tanggal_selesai->format('d/m/Y') }}
                                            </td>
                                            <td>
                                                <a href="{{ route('mahasiswa.umpan-balik.show', $umpan->id) }}" 
                                                   class="btn btn-info btn-sm" title="Detail">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-center">
                            {{ $umpanBalik->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-comments fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Belum ada umpan balik asesmen</h5>
                            <p class="text-muted">Klik tombol "Buat Umpan Balik" untuk membuat umpan balik asesmen baru.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
