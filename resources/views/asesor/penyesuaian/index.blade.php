@extends('layouts.app')

@section('title', 'Ceklis Penyesuaian Yang Wajar dan Beralasan')
@section('page-title', 'Ceklis Penyesuaian Yang Wajar dan Beralasan')

@section('content')
<div class="container-fluid">
    <!-- Main Content -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Daftar Ceklis Penyesuaian Yang Wajar dan Beralasan</h4>
                    <a href="{{ route('asesor.penyesuaian.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i>Tambah Ceklis Penyesuaian
                    </a>
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
                                        <th>Nama Asesi</th>
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
                                            <td>{{ $penyesuaian->nama_asesi }}</td>
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
                                                        <i class="fas fa-check-circle me-1"></i>Ditandatangani Mahasiswa
                                                    </span>
                                                @elseif($penyesuaian->asesor_signature)
                                                    <span class="badge bg-info">
                                                        <i class="fas fa-clock me-1"></i>Menunggu Tanda Tangan Mahasiswa
                                                    </span>
                                                @else
                                                    <span class="badge bg-warning">
                                                        <i class="fas fa-edit me-1"></i>Draft
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('asesor.penyesuaian.show', $penyesuaian->id) }}" 
                                                       class="btn btn-sm btn-info" title="Lihat Detail">
                                                        <i class="fas fa-eye me-1"></i>Detail
                                                    </a>
                                                    @if(!$penyesuaian->mahasiswa_signature)
                                                        <form action="{{ route('asesor.penyesuaian.destroy', $penyesuaian->id) }}" 
                                                              method="POST" class="d-inline"
                                                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus ceklis penyesuaian ini?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                                                <i class="fas fa-trash me-1"></i>Hapus
                                                            </button>
                                                        </form>
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
                            {{ $penyesuaianChecklists->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Belum ada ceklis penyesuaian</h5>
                            <p class="text-muted">Klik tombol "Tambah Ceklis Penyesuaian" untuk membuat ceklis penyesuaian baru.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
