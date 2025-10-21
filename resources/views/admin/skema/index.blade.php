@extends('layouts.app')

@section('title', 'Skema Sertifikasi')
@section('page-title', 'Skema Sertifikasi')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4>Daftar Skema Sertifikasi</h4>
    <a href="{{ route('admin.skema.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Tambah Skema
    </a>
</div>

<div class="card shadow">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Kode Skema</th>
                        <th>Nama Skema</th>
                        <th>Nomor Skema</th>
                        <th>Level Kompetensi</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($skemas as $index => $skema)
                    <tr>
                        <td>{{ $skemas->firstItem() + $index }}</td>
                        <td>{{ $skema->kode_skema }}</td>
                        <td>{{ $skema->nama_skema }}</td>
                        <td><span class="text-dark fw-normal">{{ $skema->nomor_skema }}</span></td>
                        <td>{{ $skema->level_kompetensi }}</td>
                        <td>
                            <span class="badge bg-{{ $skema->status ? 'success' : 'danger' }}">
                                {{ $skema->status ? 'Aktif' : 'Tidak Aktif' }}
                            </span>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.skema.show', $skema) }}" class="btn btn-info btn-sm">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.skema.edit', $skema) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.skema.destroy', $skema) }}" method="POST" 
                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus skema ini?')" 
                                      style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">Tidak ada data skema sertifikasi</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($skemas->hasPages())
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div class="text-muted">
                    <small>
                        Menampilkan {{ $skemas->firstItem() }} sampai {{ $skemas->lastItem() }} dari {{ $skemas->total() }} hasil
                        (Halaman {{ $skemas->currentPage() }} dari {{ $skemas->lastPage() }})
                    </small>
                </div>
                <div class="pagination-wrapper">
                    {{ $skemas->links('vendor.pagination.custom-bootstrap-5') }}
                </div>
            </div>
        @else
            {{-- <div class="d-flex justify-content-center mt-4">
                <div class="text-muted">
                    <small>Menampilkan {{ $skemas->count() }} hasil</small>
                </div>
            </div> --}}
        @endif
    </div>
</div>
@endsection

@section('styles')
<style>
.pagination-wrapper .pagination {
    margin: 0;
}

.pagination-wrapper .pagination .page-item .page-link {
    color: #0d6efd;
    border: 1px solid #dee2e6;
    padding: 0.375rem 0.75rem;
    margin: 0 2px;
    border-radius: 0.375rem;
}

.pagination-wrapper .pagination .page-item.active .page-link {
    background-color: #0d6efd;
    border-color: #0d6efd;
    color: white;
}

.pagination-wrapper .pagination .page-item.disabled .page-link {
    color: #6c757d;
    background-color: #fff;
    border-color: #dee2e6;
}

.pagination-wrapper .pagination .page-item:hover .page-link {
    color: #0a58ca;
    background-color: #e9ecef;
    border-color: #dee2e6;
}

.pagination-wrapper .pagination .page-item.active:hover .page-link {
    background-color: #0b5ed7;
    border-color: #0a58ca;
    color: white;
}
</style>
@endsection
