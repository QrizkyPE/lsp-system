@extends('layouts.app')

@section('title', 'Observasi Checklist')
@section('page-title', 'Observasi Checklist')

@section('content')
<!-- Main Content -->
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h4>Daftar Observasi Checklist</h4>
                <div class="d-flex align-items-center gap-3">
                    @if(isset($pendingObservasiCount) && $pendingObservasiCount > 0)
                        <span class="badge bg-danger fs-6">
                            <i class="fas fa-exclamation-circle me-1"></i>{{ $pendingObservasiCount }} Belum Ditandatangani
                        </span>
                    @endif
                    <div class="text-muted">
                        <i class="fas fa-info-circle me-1"></i>
                        Observasi checklist yang dibuat oleh asesor untuk Anda
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Observasi Checklist List -->
    <div class="card shadow">
        <div class="card-body">
            @if($observasiChecklists->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>No</th>
                                <th>Judul Skema</th>
                                <th>Asesor</th>
                                <th>Tanggal Dibuat</th>
                                <th>Status Tanda Tangan</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($observasiChecklists as $index => $observasi)
                            <tr>
                                <td>{{ $observasiChecklists->firstItem() + $index }}</td>
                                <td>
                                    <strong>{{ $observasi->judul }}</strong><br>
                                    <small class="text-muted">{{ $observasi->nomor_skema }}</small>
                                </td>
                                <td>
                                    <i class="fas fa-user me-1"></i>
                                    {{ $observasi->nama_asesor }}
                                </td>
                                <td>
                                    <i class="fas fa-calendar me-1"></i>
                                    {{ \Carbon\Carbon::parse($observasi->tanggal)->format('d F Y') }}
                                </td>
                                <td>
                                    @if($observasi->mahasiswa_signature)
                                        <span class="badge bg-success">
                                            <i class="fas fa-check me-1"></i>Sudah Ditandatangani
                                        </span>
                                        <br>
                                        <small class="text-muted">
                                            {{ \Carbon\Carbon::parse($observasi->tanggal_mahasiswa)->format('d F Y') }}
                                        </small>
                                    @else
                                        <span class="badge bg-warning">
                                            <i class="fas fa-clock me-1"></i>Belum Ditandatangani
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('mahasiswa.observasi-checklist.show', $observasi->id) }}" 
                                           class="btn btn-primary btn-sm">
                                            <i class="fas fa-eye me-1"></i>Lihat
                                        </a>
                                        @if(!$observasi->mahasiswa_signature)
                                            <a href="{{ route('mahasiswa.observasi-checklist.show', $observasi->id) }}" 
                                               class="btn btn-success btn-sm">
                                                <i class="fas fa-signature me-1"></i>Tandatangani
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center">
                    {{ $observasiChecklists->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Belum Ada Observasi Checklist</h5>
                    <p class="text-muted">
                        Observasi checklist akan muncul di sini setelah asesor membuatnya untuk Anda.
                    </p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
