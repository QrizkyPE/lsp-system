@extends('layouts.app')

@section('title', 'Penyesuaian Checklist')
@section('page-title', 'Penyesuaian Checklist')

@section('content')
<div class="container-fluid">
    <!-- Main Content -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Daftar Penyesuaian Checklist Yang Wajar dan Beralasan</h4>
                        @if(isset($pendingPenyesuaianCount) && $pendingPenyesuaianCount > 0)
                            <span class="badge bg-danger fs-6">
                                <i class="fas fa-exclamation-circle me-1"></i>{{ $pendingPenyesuaianCount }} Menunggu Tanda Tangan
                            </span>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($penyesuaianChecklists->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>No</th>
                                        <th>Judul Skema</th>
                                        <th>Nama Asesor</th>
                                        <th>TUK</th>
                                        <th>Tanggal</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($penyesuaianChecklists as $index => $penyesuaian)
                                        <tr>
                                            <td>{{ $penyesuaianChecklists->firstItem() + $index }}</td>
                                            <td>
                                                <strong>{{ $penyesuaian->judul }}</strong><br>
                                                <small class="text-muted">{{ $penyesuaian->nomor_skema }}</small>
                                            </td>
                                            <td>{{ $penyesuaian->nama_asesor }}</td>
                                            <td>
                                                @switch($penyesuaian->tuk)
                                                    @case('sewaktu')
                                                        <span class="badge bg-info">Sewaktu</span>
                                                        @break
                                                    @case('tempat_kerja')
                                                        <span class="badge bg-success">Tempat Kerja</span>
                                                        @break
                                                    @case('mandiri')
                                                        <span class="badge bg-warning">Mandiri</span>
                                                        @break
                                                @endswitch
                                            </td>
                                            <td>{{ $penyesuaian->tanggal->format('d F Y') }}</td>
                                            <td>
                                                @if($penyesuaian->mahasiswa_signature)
                                                    <span class="badge bg-success">
                                                        <i class="fas fa-check-circle me-1"></i>Sudah Ditandatangani
                                                    </span>
                                                @else
                                                    <span class="badge bg-warning">
                                                        <i class="fas fa-clock me-1"></i>Menunggu Tanda Tangan
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('mahasiswa.penyesuaian-checklist.show', $penyesuaian->id) }}" 
                                                   class="btn btn-sm btn-primary">
                                                    <i class="fas fa-eye me-1"></i>
                                                    @if($penyesuaian->mahasiswa_signature)
                                                        Lihat Detail
                                                    @else
                                                        Lihat & Tandatangani
                                                    @endif
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center">
                            {{ $penyesuaianChecklists->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-adjust fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Belum ada penyesuaian checklist</h5>
                            <p class="text-muted">Penyesuaian checklist akan muncul di sini setelah asesor membuatnya untuk Anda.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
