@extends('layouts.app')

@section('title', 'Kriteria Unjuk Kerja')
@section('page-title', 'Kriteria Unjuk Kerja')

@section('content')
<!-- Main Content -->
<div class="container-fluid">
    <!-- Kriteria Unjuk Kerja Section -->
    <div class="mb-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4>Daftar Kriteria Unjuk Kerja</h4>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addKriteriaJudulModal">
                <i class="fas fa-plus me-2"></i>Tambah Kriteria
            </button>
        </div>

        <!-- Filter and Search Box -->
        <div class="row mb-3">
            <div class="col-md-4">
                <form method="GET" action="{{ route('admin.kriteria-unjuk-kerja') }}" id="filterForm">
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
                <label for="searchKriteria" class="form-label"><strong>Pencarian:</strong></label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                    <input type="text" class="form-control" id="searchKriteria" placeholder="Cari berdasarkan judul sertifikasi, kode unit, atau deskripsi kriteria...">
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
                    <table class="table table-bordered table-hover" id="kriteriaTable">
                        <thead class="table-dark">
                            <tr>
                                <th>No</th>
                                <th>Judul Sertifikasi</th>
                                <th>Kode Unit</th>
                                <th>Kode Elemen</th>
                                <th>Nomor Kriteria</th>
                                <th>Deskripsi Kriteria</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($kriteriaJudul as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $item->judul_sertifikasi }}</td>
                                <td>{{ $item->kode_unit }}</td>
                                <td>{{ $item->kode_elemen }}</td>
                                <td>{{ $item->nomor_kriteria }}</td>
                                <td>{{ $item->deskripsi_kriteria }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-warning btn-sm" 
                                                onclick="editKriteriaJudul({{ $item->id }}, '{{ $item->judul_sertifikasi }}', '{{ $item->kode_unit }}', '{{ $item->kode_elemen }}', '{{ $item->nomor_kriteria }}', '{{ $item->deskripsi_kriteria }}', '{{ $item->jenis_bukti }}', '{{ $item->metode_asesmen }}', '{{ $item->perangkat_asesmen }}')">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <form action="{{ route('admin.kriteria-judul') }}/{{ $item->id }}" method="POST" 
                                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus kriteria ini?')" 
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
                                <td colspan="7" class="text-center">Tidak ada data kriteria unjuk kerja</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
            </div>
        </div>
    </div>


</div>

<!-- Add Kriteria Modal -->
<div class="modal fade" id="addKriteriaJudulModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Kriteria Unjuk Kerja</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.kriteria-judul') }}" method="POST">
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

                        <div class="col-md-6 mb-3">
                            <label for="kode_elemen" class="form-label">Kode Elemen <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('kode_elemen') is-invalid @enderror" 
                                   id="kode_elemen" name="kode_elemen" value="{{ old('kode_elemen') }}" required>
                            @error('kode_elemen')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nomor_kriteria_judul" class="form-label">Nomor Kriteria <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nomor_kriteria') is-invalid @enderror" 
                                   id="nomor_kriteria_judul" name="nomor_kriteria" value="{{ old('nomor_kriteria') }}" required>
                            @error('nomor_kriteria')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="jenis_bukti_judul" class="form-label">Jenis Bukti</label>
                            <input type="text" class="form-control @error('jenis_bukti') is-invalid @enderror" 
                                   id="jenis_bukti_judul" name="jenis_bukti" value="{{ old('jenis_bukti') }}">
                            @error('jenis_bukti')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="deskripsi_kriteria_judul" class="form-label">Deskripsi Kriteria <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('deskripsi_kriteria') is-invalid @enderror" 
                                  id="deskripsi_kriteria_judul" name="deskripsi_kriteria" rows="3" required>{{ old('deskripsi_kriteria') }}</textarea>
                        @error('deskripsi_kriteria')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="metode_asesmen_judul" class="form-label">Metode Asesmen</label>
                            <input type="text" class="form-control @error('metode_asesmen') is-invalid @enderror" 
                                   id="metode_asesmen_judul" name="metode_asesmen" value="{{ old('metode_asesmen') }}">
                            @error('metode_asesmen')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="perangkat_asesmen_judul" class="form-label">Perangkat Asesmen</label>
                            <input type="text" class="form-control @error('perangkat_asesmen') is-invalid @enderror" 
                                   id="perangkat_asesmen_judul" name="perangkat_asesmen" value="{{ old('perangkat_asesmen') }}">
                            @error('perangkat_asesmen')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
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

<!-- Edit Kriteria Modal (Original) -->
<div class="modal fade" id="editKriteriaModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Kriteria Unjuk Kerja</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editKriteriaForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_elemen_id" class="form-label">Elemen <span class="text-danger">*</span></label>
                        <select class="form-select" id="edit_elemen_id" name="elemen_id" required>
                            @foreach($elemen as $item)
                                <option value="{{ $item->id }}">{{ $item->nama_elemen }} - {{ $item->unitKompetensi->nama_unit }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="edit_nomor_kriteria" class="form-label">Nomor Kriteria <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit_nomor_kriteria" name="nomor_kriteria" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="edit_jenis_bukti" class="form-label">Jenis Bukti</label>
                            <input type="text" class="form-control" id="edit_jenis_bukti" name="jenis_bukti">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="edit_deskripsi_kriteria" class="form-label">Deskripsi Kriteria <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="edit_deskripsi_kriteria" name="deskripsi_kriteria" rows="3" required></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="edit_metode_asesmen" class="form-label">Metode Asesmen</label>
                            <input type="text" class="form-control" id="edit_metode_asesmen" name="metode_asesmen">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="edit_perangkat_asesmen" class="form-label">Perangkat Asesmen</label>
                            <input type="text" class="form-control" id="edit_perangkat_asesmen" name="perangkat_asesmen">
                        </div>
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

<!-- Edit Kriteria Judul Modal -->
<div class="modal fade" id="editKriteriaJudulModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Kriteria per Judul</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editKriteriaJudulForm" method="POST">
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
                            <label for="edit_kode_unit" class="form-label">Kode Unit <span class="text-danger">*</span></label>
                            <div class="position-relative">
                                <input type="text" class="form-control" id="edit_kode_unit" name="kode_unit" 
                                       placeholder="Ketik kode unit..." required autocomplete="off">
                                <div id="edit_kode_unit_suggestions" class="position-absolute w-100 bg-white border border-top-0 rounded-bottom shadow" 
                                     style="z-index: 1000; display: none; max-height: 200px; overflow-y: auto;">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="edit_kode_elemen" class="form-label">Kode Elemen <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit_kode_elemen" name="kode_elemen" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="edit_nomor_kriteria_judul" class="form-label">Nomor Kriteria <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit_nomor_kriteria_judul" name="nomor_kriteria" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="edit_jenis_bukti_judul" class="form-label">Jenis Bukti</label>
                            <input type="text" class="form-control" id="edit_jenis_bukti_judul" name="jenis_bukti">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="edit_deskripsi_kriteria_judul" class="form-label">Deskripsi Kriteria <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="edit_deskripsi_kriteria_judul" name="deskripsi_kriteria" rows="3" required></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="edit_metode_asesmen_judul" class="form-label">Metode Asesmen</label>
                            <input type="text" class="form-control" id="edit_metode_asesmen_judul" name="metode_asesmen">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="edit_perangkat_asesmen_judul" class="form-label">Perangkat Asesmen</label>
                            <input type="text" class="form-control" id="edit_perangkat_asesmen_judul" name="perangkat_asesmen">
                        </div>
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
function editKriteria(id, kode, deskripsi, jenisBukti, metode, perangkat, elemenId) {
    document.getElementById('editKriteriaForm').action = '{{ route("admin.kriteria-unjuk-kerja") }}/' + id;
    document.getElementById('edit_nomor_kriteria').value = kode;
    document.getElementById('edit_deskripsi_kriteria').value = deskripsi;
    document.getElementById('edit_jenis_bukti').value = jenisBukti;
    document.getElementById('edit_metode_asesmen').value = metode;
    document.getElementById('edit_perangkat_asesmen').value = perangkat;
    document.getElementById('edit_elemen_id').value = elemenId;
    
    new bootstrap.Modal(document.getElementById('editKriteriaModal')).show();
}

function editKriteriaJudul(id, judul, kodeUnit, kodeElemen, kodeKriteria, deskripsi, jenisBukti, metode, perangkat) {
    document.getElementById('editKriteriaJudulForm').action = '{{ route("admin.kriteria-judul") }}/' + id;
    document.getElementById('edit_judul_sertifikasi').value = judul;
    document.getElementById('edit_kode_unit').value = kodeUnit;
    document.getElementById('edit_kode_elemen').value = kodeElemen;
    document.getElementById('edit_nomor_kriteria_judul').value = kodeKriteria;
    document.getElementById('edit_deskripsi_kriteria_judul').value = deskripsi;
    document.getElementById('edit_jenis_bukti_judul').value = jenisBukti;
    document.getElementById('edit_metode_asesmen_judul').value = metode;
    document.getElementById('edit_perangkat_asesmen_judul').value = perangkat;
    
    new bootstrap.Modal(document.getElementById('editKriteriaJudulModal')).show();
}

// Auto-fill judul sertifikasi when kode unit is selected
document.addEventListener('DOMContentLoaded', function() {
    // For add form
    const kodeUnitSelect = document.getElementById('kode_unit');
    const judulSertifikasiSelect = document.getElementById('judul_sertifikasi');
    
    if (kodeUnitSelect && judulSertifikasiSelect) {
        kodeUnitSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            if (selectedOption.value) {
                const judul = selectedOption.getAttribute('data-judul');
                judulSertifikasiSelect.value = judul;
            }
        });
    }
    
});

// Search functionality
document.getElementById('searchKriteria').addEventListener('keyup', function() {
    const searchTerm = this.value.toLowerCase();
    const table = document.getElementById('kriteriaTable');
    const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
    
    for (let i = 0; i < rows.length; i++) {
        const row = rows[i];
        const cells = row.getElementsByTagName('td');
        let found = false;
        
        // Search in judul sertifikasi (index 1), kode unit (index 2), and deskripsi kriteria (index 5)
        for (let j = 1; j <= 5; j++) {
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
