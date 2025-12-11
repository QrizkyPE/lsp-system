@extends('layouts.app')

@section('title', 'Admin Dashboard')
@section('page-title', 'Dashboard')

@section('content')
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Skema Sertifikasi</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalSkema ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-certificate fa-2x text-gray-300"></i>
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
                                Total Asesor</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalAsesor ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-tie fa-2x text-gray-300"></i>
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
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Pending Approval</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $pendingApproval ?? 0 }}</div>
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
                    <h6 class="m-0 font-weight-bold text-primary">Recent Pendaftaran</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>No Pendaftaran</th>
                                    <th>Nama</th>
                                    <th>Skema</th>
                                    <!-- <th>Status</th> -->
                                    <th>Tanggal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentPendaftaran ?? [] as $pendaftaran)
                                    <tr>
                                        <td>{{ $pendaftaran->no_pendaftaran }}</td>
                                        <td>{{ $pendaftaran->user->name }}</td>
                                        <td>{{ $pendaftaran->skemaSertifikasi->nama_skema }}</td>
                                        <!-- <td>
                                            <span class="badge badge-{{ $pendaftaran->status == 'pending' ? 'warning' : ($pendaftaran->status == 'approved' ? 'success' : 'danger') }}">
                                                {{ ucfirst($pendaftaran->status) }}
                                            </span>
                                        </td> -->
                                        <td>{{ $pendaftaran->created_at->format('d/m/Y') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">No data available</td>
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
                        <a href="{{ route('admin.skema.index') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-plus me-2"></i>Add New Skema
                        </a>
                        <a href="{{ route('admin.users.index') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-users me-2"></i>Kelola Akun Pengguna
                        </a>
                        <a href="{{ route('admin.asesor') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-user-plus me-2"></i>Add New Asesor
                        </a>
                        <a href="{{ route('admin.jadwal-uji') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-calendar-plus me-2"></i>Create Jadwal Uji
                        </a>
                        <a href="{{ route('admin.penugasan') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-user-check me-2"></i>Assign Penugasan
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection