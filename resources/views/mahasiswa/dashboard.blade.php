@extends('layouts.app')

@section('title', 'Mahasiswa Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="row">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Total Pendaftaran</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalPendaftaran ?? 0 }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-file-alt fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Pendaftaran Disetujui</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $approvedPendaftaran ?? 0 }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Sertifikat</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $sertifikat ?? 0 }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-certificate fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Pending</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $pendingPendaftaran ?? 0 }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-clock fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Pendaftaran Terbaru</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No Pendaftaran</th>
                                <th>Skema</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentPendaftaran ?? [] as $pendaftaran)
                            <tr>
                                <td>{{ $pendaftaran->no_pendaftaran }}</td>
                                <td>{{ $pendaftaran->skemaSertifikasi->nama_skema }}</td>
                                <td>
                                    <span class="badge badge-{{ $pendaftaran->status == 'pending' ? 'warning' : ($pendaftaran->status == 'approved' ? 'success' : 'danger') }}">
                                        {{ ucfirst($pendaftaran->status) }}
                                    </span>
                                </td>
                                <td>{{ $pendaftaran->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <a href="{{ route('mahasiswa.pendaftaran.detail', $pendaftaran->id) }}" class="btn btn-sm btn-info me-1">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @php
                                        $isVerifiedByAsesor = $pendaftaran->verifications()
                                            ->where('type', 'asesor_verification')
                                            ->where('status', 'verified')
                                            ->exists();
                                        $hasPersetujuan = $pendaftaran->persetujuan_data;
                                    @endphp
                                    @if($isVerifiedByAsesor && !$hasPersetujuan)
                                        <a href="{{ route('mahasiswa.persetujuan', $pendaftaran->id) }}" class="btn btn-sm btn-success">
                                            <i class="fas fa-file-signature"></i> Persetujuan
                                        </a>
                                    @elseif($hasPersetujuan)
                                        <span class="badge badge-success">Persetujuan Dikirim</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">Belum ada pendaftaran</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
            </div>
            <div class="card-body">
                <div class="list-group">
                    <a href="{{ route('mahasiswa.pendaftaran.step1') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-user-plus me-2"></i>Daftar Sertifikasi Baru
                    </a>
                    <a href="{{ route('mahasiswa.pendaftaran') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-list me-2"></i>Riwayat Pendaftaran
                    </a>
                    <a href="{{ route('mahasiswa.jadwal') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-calendar me-2"></i>Lihat Jadwal
                    </a>
                    <a href="{{ route('mahasiswa.dokumen') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-file-alt me-2"></i>Upload Dokumen
                    </a>
                    <a href="{{ route('mahasiswa.hasil') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-chart-line me-2"></i>Lihat Hasil
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
