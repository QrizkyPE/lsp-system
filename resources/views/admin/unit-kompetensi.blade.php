@extends('layouts.app')

@section('title', 'Unit Kompetensi')
@section('page-title', 'Unit Kompetensi')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4>Daftar Unit Kompetensi</h4>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUnitModal">
        <i class="fas fa-plus me-2"></i>Tambah Unit
    </button>
</div>

<div class="card shadow">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Kode Unit</th>
                        <th>Nama Unit</th>
                        <th>Skema Sertifikasi</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($units as $index => $unit)
                    <tr>
                        <td>{{ $units->firstItem() + $index }}</td>
                        <td>{{ $unit->kode_unit }}</td>
                        <td>{{ $unit->nama_unit }}</td>
                        <td>{{ $unit->skemaSertifikasi->nama_skema }}</td>
                        <td>
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-warning btn-sm" 
                                        onclick="editUnit({{ $unit->id }}, '{{ $unit->kode_unit }}', '{{ $unit->nama_unit }}', '{{ $unit->deskripsi }}', '{{ $unit->kriteria_penilaian }}', {{ $unit->skema_sertifikasi_id }})">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('admin.unit-kompetensi') }}/{{ $unit->id }}" method="POST" 
                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus unit ini?')" 
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
                        <td colspan="5" class="text-center">Tidak ada data unit kompetensi</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="d-flex justify-content-center">
            {{ $units->links() }}
        </div>
    </div>
</div>

<!-- Add Unit Modal -->
<div class="modal fade" id="addUnitModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Unit Kompetensi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.unit-kompetensi') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="skema_sertifikasi_id" class="form-label">Skema Sertifikasi <span class="text-danger">*</span></label>
                        <select class="form-select @error('skema_sertifikasi_id') is-invalid @enderror" 
                                id="skema_sertifikasi_id" name="skema_sertifikasi_id" required>
                            <option value="">Pilih Skema Sertifikasi</option>
                            @foreach($skemas as $skema)
                                <option value="{{ $skema->id }}">{{ $skema->nama_skema }}</option>
                            @endforeach
                        </select>
                        @error('skema_sertifikasi_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="kode_unit" class="form-label">Kode Unit <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('kode_unit') is-invalid @enderror" 
                                   id="kode_unit" name="kode_unit" value="{{ old('kode_unit') }}" required>
                            @error('kode_unit')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="nama_unit" class="form-label">Nama Unit <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nama_unit') is-invalid @enderror" 
                                   id="nama_unit" name="nama_unit" value="{{ old('nama_unit') }}" required>
                            @error('nama_unit')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">Deskripsi <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('deskripsi') is-invalid @enderror" 
                                  id="deskripsi" name="deskripsi" rows="3" required>{{ old('deskripsi') }}</textarea>
                        @error('deskripsi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="kriteria_penilaian" class="form-label">Kriteria Penilaian <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('kriteria_penilaian') is-invalid @enderror" 
                                  id="kriteria_penilaian" name="kriteria_penilaian" rows="3" required>{{ old('kriteria_penilaian') }}</textarea>
                        @error('kriteria_penilaian')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Unit Modal -->
<div class="modal fade" id="editUnitModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Unit Kompetensi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editUnitForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_skema_sertifikasi_id" class="form-label">Skema Sertifikasi <span class="text-danger">*</span></label>
                        <select class="form-select" id="edit_skema_sertifikasi_id" name="skema_sertifikasi_id" required>
                            <option value="">Pilih Skema Sertifikasi</option>
                            @foreach($skemas as $skema)
                                <option value="{{ $skema->id }}">{{ $skema->nama_skema }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="edit_kode_unit" class="form-label">Kode Unit <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit_kode_unit" name="kode_unit" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="edit_nama_unit" class="form-label">Nama Unit <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit_nama_unit" name="nama_unit" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="edit_deskripsi" class="form-label">Deskripsi <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="edit_deskripsi" name="deskripsi" rows="3" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="edit_kriteria_penilaian" class="form-label">Kriteria Penilaian <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="edit_kriteria_penilaian" name="kriteria_penilaian" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function editUnit(id, kode, nama, deskripsi, kriteria, skemaId) {
    document.getElementById('editUnitForm').action = '{{ route("admin.unit-kompetensi") }}/' + id;
    document.getElementById('edit_kode_unit').value = kode;
    document.getElementById('edit_nama_unit').value = nama;
    document.getElementById('edit_deskripsi').value = deskripsi;
    document.getElementById('edit_kriteria_penilaian').value = kriteria;
    document.getElementById('edit_skema_sertifikasi_id').value = skemaId;
    
    new bootstrap.Modal(document.getElementById('editUnitModal')).show();
}
</script>
@endsection
