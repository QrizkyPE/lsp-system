@extends('layouts.app')

@section('title', 'Unit Kompetensi')
@section('page-title', 'Unit Kompetensi')

@section('content')
<!-- Main Content -->
<div class="container-fluid">
    <!-- Unit Kompetensi Section -->
    <div class="mb-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4>Daftar Unit Kompetensi</h4>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUnitJudulModal">
                <i class="fas fa-plus me-2"></i>Tambah Unit
            </button>
        </div>

        <!-- Filter and Search Box -->
        <div class="row mb-3">
            <div class="col-md-4">
                <form method="GET" action="{{ route('admin.unit-kompetensi') }}" id="filterForm">
                    <label for="filter_judul" class="form-label"><strong>Filter Judul Sertifikasi:</strong></label>
                    <select name="filter_judul" id="filter_judul" class="form-select" onchange="this.form.submit()">
                        <option value="">Semua Judul Sertifikasi</option>
                        @foreach($judulOptions as $judul)
                            <option value="{{ $judul }}" {{ $filterJudul == $judul ? 'selected' : '' }}>
                                {{ $judul }}
                            </option>
                        @endforeach
                    </select>
                </form>
            </div>
            <div class="col-md-8">
                <label for="searchUnit" class="form-label"><strong>Pencarian:</strong></label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                    <input type="text" class="form-control" id="searchUnit" placeholder="Cari berdasarkan judul sertifikasi, kode unit, atau nama unit...">
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card shadow">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="unitJudulTable" class="table table-bordered table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>No</th>
                                <th>Judul Sertifikasi</th>
                                <th>Kode Unit</th>
                                <th>Judul Unit</th>
                                <th>Standar Kompetensi Kerja</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($unitsJudul as $index => $unit)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $unit->judul_sertifikasi }}</td>
                                <td>{{ $unit->kode_unit }}</td>
                                <td>{{ $unit->judul_unit }}</td>
                                <td>{{ $unit->standar_kompetensi_kerja }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-warning btn-sm" 
                                                onclick="editUnitJudul({{ $unit->id }}, '{{ $unit->judul_sertifikasi }}', '{{ $unit->kode_unit }}', '{{ $unit->judul_unit }}', '{{ $unit->standar_kompetensi_kerja }}')">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-danger btn-sm" 
                                                onclick="deleteUnitJudul({{ $unit->id }}, '{{ $unit->judul_unit }}')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center">Tidak ada data unit kompetensi</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


</div>

<!-- Add Unit Modal -->
<div class="modal fade" id="addUnitJudulModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Unit Kompetensi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.unit-kompetensi-judul.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="judul_sertifikasi" class="form-label">Judul Sertifikasi <span class="text-danger">*</span></label>
                        <select class="form-select @error('judul_sertifikasi') is-invalid @enderror" 
                                id="judul_sertifikasi" name="judul_sertifikasi" required>
                            <option value="">Pilih Judul Sertifikasi</option>
                            @foreach($judulOptions as $judul)
                                <option value="{{ $judul }}">{{ $judul }}</option>
                            @endforeach
                        </select>
                        @error('judul_sertifikasi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="kode_unit_judul" class="form-label">Kode Unit <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('kode_unit') is-invalid @enderror" 
                                   id="kode_unit_judul" name="kode_unit" value="{{ old('kode_unit') }}" required>
                            @error('kode_unit')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="judul_unit_judul" class="form-label">Judul Unit <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('judul_unit') is-invalid @enderror" 
                                   id="judul_unit_judul" name="judul_unit" value="{{ old('judul_unit') }}" required>
                            @error('judul_unit')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="standar_kompetensi_kerja" class="form-label">Standar Kompetensi Kerja <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('standar_kompetensi_kerja') is-invalid @enderror" 
                               id="standar_kompetensi_kerja" name="standar_kompetensi_kerja" value="{{ old('standar_kompetensi_kerja') }}" required>
                        @error('standar_kompetensi_kerja')
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

<!-- Edit Unit Modal (Original) -->
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

<!-- Edit Unit Judul Modal -->
<div class="modal fade" id="editUnitJudulModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Unit Kompetensi per Judul</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editUnitJudulForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_judul_sertifikasi" class="form-label">Judul Sertifikasi <span class="text-danger">*</span></label>
                        <select class="form-select" id="edit_judul_sertifikasi" name="judul_sertifikasi" required>
                            @foreach($judulOptions as $judul)
                                <option value="{{ $judul }}">{{ $judul }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="edit_kode_unit_judul" class="form-label">Kode Unit <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit_kode_unit_judul" name="kode_unit" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="edit_judul_unit_judul" class="form-label">Judul Unit <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit_judul_unit_judul" name="judul_unit" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="edit_standar_kompetensi_kerja" class="form-label">Standar Kompetensi Kerja <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_standar_kompetensi_kerja" name="standar_kompetensi_kerja" required>
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

function editUnitJudul(id, judul, kode, nama, standar) {
    document.getElementById('editUnitJudulForm').action = '{{ url("admin/unit-kompetensi-judul") }}/' + id;
    document.getElementById('edit_judul_sertifikasi').value = judul;
    document.getElementById('edit_kode_unit_judul').value = kode;
    document.getElementById('edit_judul_unit_judul').value = nama;
    document.getElementById('edit_standar_kompetensi_kerja').value = standar;
    
    new bootstrap.Modal(document.getElementById('editUnitJudulModal')).show();
}

function deleteUnitJudul(id, judulUnit) {
    if (confirm('Apakah Anda yakin ingin menghapus unit "' + judulUnit + '"?')) {
        // Show loading state
        const deleteBtn = event.target.closest('button');
        const originalHTML = deleteBtn.innerHTML;
        deleteBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        deleteBtn.disabled = true;
        
        // Create form data
        const formData = new FormData();
        formData.append('_token', '{{ csrf_token() }}');
        formData.append('_method', 'DELETE');
        
        // Send AJAX request
        fetch('{{ url("admin/unit-kompetensi-judul") }}/' + id, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Remove row from table
                const row = deleteBtn.closest('tr');
                row.remove();
                
                // Show success message
                showAlert('success', data.message || 'Unit kompetensi berhasil dihapus');
                
                // Update row numbers
                updateRowNumbers();
            } else {
                showAlert('error', data.message || 'Gagal menghapus unit kompetensi');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('error', 'Terjadi kesalahan saat menghapus unit kompetensi');
        })
        .finally(() => {
            // Restore button state
            deleteBtn.innerHTML = originalHTML;
            deleteBtn.disabled = false;
        });
    }
}

function updateRowNumbers() {
    const rows = document.querySelectorAll('#unitJudulTable tbody tr');
    rows.forEach((row, index) => {
        const numberCell = row.querySelector('td:first-child');
        if (numberCell) {
            numberCell.textContent = index + 1;
        }
    });
}

function showAlert(type, message) {
    // Remove existing alerts
    const existingAlerts = document.querySelectorAll('.alert');
    existingAlerts.forEach(alert => alert.remove());
    
    // Create new alert
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show`;
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    // Insert alert at the top of the content
    const content = document.querySelector('.container-fluid');
    content.insertBefore(alertDiv, content.firstChild);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.remove();
        }
    }, 5000);
}

// Search functionality
document.getElementById('searchUnit').addEventListener('keyup', function() {
    const searchTerm = this.value.toLowerCase();
    const table = document.getElementById('unitJudulTable');
    const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
    
    for (let i = 0; i < rows.length; i++) {
        const row = rows[i];
        const cells = row.getElementsByTagName('td');
        let found = false;
        
        // Search in judul sertifikasi (index 1), kode unit (index 2), and judul unit (index 3)
        for (let j = 1; j <= 3; j++) {
            if (cells[j] && cells[j].textContent.toLowerCase().includes(searchTerm)) {
                found = true;
                break;
            }
        }
        
        row.style.display = found ? '' : 'none';
    }
});
</script>
@endsection