@extends('layouts.app')

@section('title', 'Elemen')
@section('page-title', 'Elemen')

@section('content')
<!-- Main Content -->
<div class="container-fluid">
    <!-- Elemen Section -->
    <div class="mb-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4>Daftar Elemen</h4>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addElemenJudulModal">
                <i class="fas fa-plus me-2"></i>Tambah Elemen
            </button>
        </div>

        <!-- Filter and Search Box -->
        <div class="row mb-3">
            <div class="col-md-4">
                <form method="GET" action="{{ route('admin.elemen') }}" id="filterForm">
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
                <label for="searchElemen" class="form-label"><strong>Pencarian:</strong></label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                    <input type="text" class="form-control" id="searchElemen" placeholder="Cari berdasarkan judul sertifikasi, kode unit, atau nama elemen...">
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
                    <table class="table table-bordered table-hover" id="elemenTable">
                        <thead class="table-dark">
                            <tr>
                                <th>No</th>
                                <th>Judul Sertifikasi</th>
                                <th>Kode Unit</th>
                                <th>Kode Elemen</th>
                                <th>Nama Elemen</th>
                                {{-- <th>Kriteria</th> --}}
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($elemenJudul as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $item->judul_sertifikasi }}</td>
                                <td>{{ $item->kode_unit }}</td>
                                <td>{{ $item->kode_elemen }}</td>
                                <td>{{ $item->nama_elemen }}</td>
                                {{-- <td>
                                    <span class="badge bg-info">{{ $item->kriteriaUnjukKerja->count() }} kriteria</span>
                                </td> --}}
                                <td>
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-warning btn-sm" 
                                                onclick="editElemenJudul({{ $item->id }}, '{{ $item->judul_sertifikasi }}', '{{ $item->kode_unit }}', '{{ $item->kode_elemen }}', '{{ $item->nama_elemen }}', '{{ $item->deskripsi }}')">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <form action="{{ route('admin.elemen-judul') }}/{{ $item->id }}" method="POST" 
                                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus elemen ini?')" 
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
                                <td colspan="7" class="text-center">Tidak ada data elemen</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
            </div>
        </div>
    </div>


</div>

<!-- Add Elemen Modal -->
<div class="modal fade" id="addElemenJudulModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Elemen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.elemen-judul') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="skema_sertifikasi" class="form-label">Skema Sertifikasi <span class="text-danger">*</span></label>
                        <select class="form-select @error('skema_sertifikasi') is-invalid @enderror" 
                                id="skema_sertifikasi" name="skema_sertifikasi" required>
                            <option value="">Pilih Skema Sertifikasi</option>
                            @foreach($skemas as $skema)
                                <option value="{{ $skema->nama_skema }}">{{ $skema->nama_skema }}</option>
                            @endforeach
                        </select>
                        @error('skema_sertifikasi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="judul_sertifikasi" class="form-label">Judul Sertifikasi <span class="text-danger">*</span></label>
                        <select class="form-select @error('judul_sertifikasi') is-invalid @enderror" 
                                id="judul_sertifikasi" name="judul_sertifikasi" required disabled>
                            <option value="">Pilih Skema Sertifikasi terlebih dahulu</option>
                        </select>
                        @error('judul_sertifikasi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="kode_unit" class="form-label">Kode Unit <span class="text-danger">*</span></label>
                        <div class="position-relative">
                            <input type="text" class="form-control @error('kode_unit') is-invalid @enderror" 
                                   id="kode_unit" name="kode_unit" value="{{ old('kode_unit') }}" 
                                   placeholder="Ketik kode unit..." required autocomplete="off">
                            <div id="kode_unit_suggestions" class="position-absolute w-100 bg-white border border-top-0 rounded-bottom shadow" 
                                 style="z-index: 1000; display: none; max-height: 200px; overflow-y: auto;">
                            </div>
                        </div>
                        @error('kode_unit')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="kode_elemen_judul" class="form-label">Kode Elemen <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('kode_elemen') is-invalid @enderror" 
                                   id="kode_elemen_judul" name="kode_elemen" value="{{ old('kode_elemen') }}" required>
                            @error('kode_elemen')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="nama_elemen_judul" class="form-label">Nama Elemen <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nama_elemen') is-invalid @enderror" 
                                   id="nama_elemen_judul" name="nama_elemen" value="{{ old('nama_elemen') }}" required>
                            @error('nama_elemen')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="deskripsi_judul" class="form-label">Deskripsi</label>
                        <textarea class="form-control @error('deskripsi') is-invalid @enderror" 
                                  id="deskripsi_judul" name="deskripsi" rows="3">{{ old('deskripsi') }}</textarea>
                        @error('deskripsi')
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

<!-- Edit Elemen Modal (Original) -->
<div class="modal fade" id="editElemenModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Elemen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editElemenForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_unit_kompetensi_id" class="form-label">Unit Kompetensi <span class="text-danger">*</span></label>
                        <select class="form-select" id="edit_unit_kompetensi_id" name="unit_kompetensi_id" required>
                            @foreach($units as $unit)
                                <option value="{{ $unit->id }}">{{ $unit->nama_unit }} - {{ $unit->skemaSertifikasi->nama_skema }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="edit_kode_elemen" class="form-label">Kode Elemen <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit_kode_elemen" name="kode_elemen" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="edit_nama_elemen" class="form-label">Nama Elemen <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit_nama_elemen" name="nama_elemen" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="edit_deskripsi" class="form-label">Deskripsi <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="edit_deskripsi" name="deskripsi" rows="3" required></textarea>
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

<!-- Edit Elemen Judul Modal -->
<div class="modal fade" id="editElemenJudulModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Elemen per Judul</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editElemenJudulForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_skema_sertifikasi" class="form-label">Skema Sertifikasi <span class="text-danger">*</span></label>
                        <select class="form-select" id="edit_skema_sertifikasi" name="skema_sertifikasi" required>
                            <option value="">Pilih Skema Sertifikasi</option>
                            @foreach($skemas as $skema)
                                <option value="{{ $skema->nama_skema }}">{{ $skema->nama_skema }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="edit_judul_sertifikasi" class="form-label">Judul Sertifikasi <span class="text-danger">*</span></label>
                        <select class="form-select" id="edit_judul_sertifikasi" name="judul_sertifikasi" required disabled>
                            <option value="">Pilih Skema Sertifikasi terlebih dahulu</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="edit_kode_unit" class="form-label">Kode Unit <span class="text-danger">*</span></label>
                        <div class="position-relative">
                            <input type="text" class="form-control" id="edit_kode_unit" name="kode_unit" 
                                   placeholder="Ketik kode unit..." required autocomplete="off">
                            <div id="edit_kode_unit_suggestions" class="position-absolute w-100 bg-white border border-top-0 rounded-bottom shadow" 
                                 style="z-index: 1000; display: none; max-height: 200px; overflow-y: auto;">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="edit_kode_elemen_judul" class="form-label">Kode Elemen <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit_kode_elemen_judul" name="kode_elemen" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="edit_nama_elemen_judul" class="form-label">Nama Elemen <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit_nama_elemen_judul" name="nama_elemen" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="edit_deskripsi_judul" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="edit_deskripsi_judul" name="deskripsi" rows="3"></textarea>
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
function editElemen(id, kode, nama, deskripsi, unitId) {
    document.getElementById('editElemenForm').action = '{{ route("admin.elemen") }}/' + id;
    document.getElementById('edit_kode_elemen').value = kode;
    document.getElementById('edit_nama_elemen').value = nama;
    document.getElementById('edit_deskripsi').value = deskripsi;
    document.getElementById('edit_unit_kompetensi_id').value = unitId;
    
    new bootstrap.Modal(document.getElementById('editElemenModal')).show();
}

function editElemenJudul(id, judul, kodeUnit, kodeElemen, nama, deskripsi) {
    document.getElementById('editElemenJudulForm').action = '{{ route("admin.elemen-judul") }}/' + id;
    
    // Set skema sertifikasi (judul sertifikasi = nama skema)
    const editSkemaSelect = document.getElementById('edit_skema_sertifikasi');
    const editJudulSelect = document.getElementById('edit_judul_sertifikasi');
    
    if (editSkemaSelect && editJudulSelect) {
        editSkemaSelect.value = judul;
        // Trigger change event to populate judul sertifikasi
        editSkemaSelect.dispatchEvent(new Event('change'));
    }
    
    document.getElementById('edit_kode_unit').value = kodeUnit;
    document.getElementById('edit_kode_elemen_judul').value = kodeElemen;
    document.getElementById('edit_nama_elemen_judul').value = nama;
    document.getElementById('edit_deskripsi_judul').value = deskripsi;
    
    new bootstrap.Modal(document.getElementById('editElemenJudulModal')).show();
}

// Auto-fill judul sertifikasi when skema sertifikasi is selected
document.addEventListener('DOMContentLoaded', function() {
    // For add form
    const skemaSelect = document.getElementById('skema_sertifikasi');
    const judulSertifikasiSelect = document.getElementById('judul_sertifikasi');
    
    if (skemaSelect && judulSertifikasiSelect) {
        skemaSelect.addEventListener('change', function() {
            const selectedSkema = this.value;
            if (selectedSkema) {
                // Set judul sertifikasi sama dengan nama skema
                judulSertifikasiSelect.innerHTML = '<option value="' + selectedSkema + '">' + selectedSkema + '</option>';
                judulSertifikasiSelect.value = selectedSkema;
                judulSertifikasiSelect.disabled = false;
            } else {
                judulSertifikasiSelect.innerHTML = '<option value="">Pilih Skema Sertifikasi terlebih dahulu</option>';
                judulSertifikasiSelect.disabled = true;
            }
        });
    }
    
    // For edit form
    const editSkemaSelect = document.getElementById('edit_skema_sertifikasi');
    const editJudulSertifikasiSelect = document.getElementById('edit_judul_sertifikasi');
    
    if (editSkemaSelect && editJudulSertifikasiSelect) {
        editSkemaSelect.addEventListener('change', function() {
            const selectedSkema = this.value;
            if (selectedSkema) {
                // Set judul sertifikasi sama dengan nama skema
                editJudulSertifikasiSelect.innerHTML = '<option value="' + selectedSkema + '">' + selectedSkema + '</option>';
                editJudulSertifikasiSelect.value = selectedSkema;
                editJudulSertifikasiSelect.disabled = false;
            } else {
                editJudulSertifikasiSelect.innerHTML = '<option value="">Pilih Skema Sertifikasi terlebih dahulu</option>';
                editJudulSertifikasiSelect.disabled = true;
            }
        });
    }
});

// Search functionality
document.getElementById('searchElemen').addEventListener('keyup', function() {
    const searchTerm = this.value.toLowerCase();
    const table = document.getElementById('elemenTable');
    const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
    
    for (let i = 0; i < rows.length; i++) {
        const row = rows[i];
        const cells = row.getElementsByTagName('td');
        let found = false;
        
        // Search in judul sertifikasi (index 1), kode unit (index 2), and nama elemen (index 4)
        for (let j = 1; j <= 4; j++) {
            if (cells[j] && cells[j].textContent.toLowerCase().includes(searchTerm)) {
                found = true;
                break;
            }
        }
        
        row.style.display = found ? '' : 'none';
    }
});

// Autocomplete functionality for kode unit
const unitData = @json($unitKompetensiJudul);

function setupAutocomplete(inputId, suggestionsId) {
    const input = document.getElementById(inputId);
    const suggestions = document.getElementById(suggestionsId);
    
    if (!input || !suggestions) return;
    
    input.addEventListener('input', function() {
        const value = this.value.toLowerCase();
        suggestions.innerHTML = '';
        
        if (value.length < 2) {
            suggestions.style.display = 'none';
            return;
        }
        
        const filtered = unitData.filter(unit => 
            unit.kode_unit.toLowerCase().includes(value) || 
            unit.judul_unit.toLowerCase().includes(value)
        );
        
        if (filtered.length > 0) {
            filtered.forEach(unit => {
                const div = document.createElement('div');
                div.className = 'p-2 border-bottom cursor-pointer';
                div.style.cursor = 'pointer';
                div.innerHTML = `<strong>${unit.kode_unit}</strong> - ${unit.judul_unit}`;
                div.addEventListener('click', function() {
                    input.value = unit.kode_unit;
                    suggestions.style.display = 'none';
                });
                suggestions.appendChild(div);
            });
            suggestions.style.display = 'block';
        } else {
            suggestions.style.display = 'none';
        }
    });
    
    // Hide suggestions when clicking outside
    document.addEventListener('click', function(e) {
        if (!input.contains(e.target) && !suggestions.contains(e.target)) {
            suggestions.style.display = 'none';
        }
    });
}

// Setup autocomplete for both create and edit modals
setupAutocomplete('kode_unit', 'kode_unit_suggestions');
setupAutocomplete('edit_kode_unit', 'edit_kode_unit_suggestions');
</script>
@endsection
