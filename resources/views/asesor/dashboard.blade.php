@extends('layouts.app')

@section('title', 'Asesor Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="row">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Total Penugasan</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalPenugasan ?? 0 }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-user-check fa-2x text-gray-300"></i>
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
                            Penugasan Diterima</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $acceptedPenugasan ?? 0 }}</div>
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
                            Dokumen Pending</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $pendingDokumen ?? 0 }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-file-alt fa-2x text-gray-300"></i>
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
                            Asesmen Selesai</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $completedAsesmen ?? 0 }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-clipboard-check fa-2x text-gray-300"></i>
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
                <h6 class="m-0 font-weight-bold text-primary">Penugasan Terbaru</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Jadwal Uji</th>
                                <th>Skema</th>
                                <th>Jenis Penugasan</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentPenugasan ?? [] as $penugasan)
                            <tr>
                                <td>{{ $penugasan->jadwalUji->nama_batch }}</td>
                                <td>{{ $penugasan->jadwalUji->skemaSertifikasi->nama_skema }}</td>
                                <td>{{ strtoupper($penugasan->jenis_penugasan) }}</td>
                                <td>
                                    <span class="badge badge-{{ $penugasan->status == 'accepted' ? 'success' : ($penugasan->status == 'assigned' ? 'warning' : 'danger') }}">
                                        {{ ucfirst($penugasan->status) }}
                                    </span>
                                </td>
                                <td>{{ $penugasan->created_at->format('d/m/Y') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">Belum ada penugasan</td>
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
                    <a href="{{ route('asesor.penugasan') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-user-check me-2"></i>Lihat Penugasan
                    </a>
                    <a href="{{ route('asesor.dokumen') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-file-alt me-2"></i>Review Dokumen
                    </a>
                    <a href="{{ route('asesor.asesmen') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-clipboard-check me-2"></i>Lakukan Asesmen
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
