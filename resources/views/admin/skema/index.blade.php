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
        
        <div class="d-flex justify-content-center">
            {{ $skemas->links() }}
        </div>
    </div>
</div>
@endsection
