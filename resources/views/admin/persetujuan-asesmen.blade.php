@extends('layouts.app')

@section('title', 'Persetujuan Asesmen')
@section('page-title', 'Persetujuan Asesmen')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header">
                    <h4 class="mb-0">Daftar Persetujuan Asesmen</h4>
                </div>
                <div class="card-body">
                    @if($pendaftaran->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>No. Pendaftaran</th>
                                        <th>Nama Mahasiswa</th>
                                        <th>Skema Sertifikasi</th>
                                        <th>Tanggal Verifikasi</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pendaftaran as $p)
                                    <tr>
                                        <td>{{ $p->no_pendaftaran }}</td>
                                        <td>{{ $p->user->nama_lengkap }}</td>
                                        <td>{{ $p->skemaSertifikasi->nama_skema }}</td>
                                        <td>{{ $p->tanggal_asesmen ? \Carbon\Carbon::parse($p->tanggal_asesmen)->format('d/m/Y H:i') : '-' }}</td>
                                        <td>
                                            <span class="badge bg-info">Menunggu Persetujuan</span>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.persetujuan-asesmen.detail', $p->id) }}" 
                                               class="btn btn-sm btn-primary">
                                                <i class="fas fa-eye me-1"></i>Detail
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-4">
                            {{ $pendaftaran->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-clipboard-check fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Belum ada persetujuan asesmen</h5>
                            <p class="text-muted">Tidak ada asesmen yang menunggu persetujuan admin</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
