@extends('layouts.app')

@section('title', 'TUK (Tempat Uji Kompetensi)')
@section('page-title', 'TUK')

@section('content')
<!-- Main Content -->
<div class="container-fluid">
    <!-- TUK Section -->
    <div class="mb-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4>Daftar TUK (Tempat Uji Kompetensi)</h4>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTukModal">
                <i class="fas fa-plus me-2"></i>Tambah TUK
            </button>
        </div>

        <!-- Search Box -->
        <div class="row mb-3">
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                    <input type="text" class="form-control" id="searchTuk" placeholder="Cari berdasarkan nama TUK, kota, atau provinsi...">
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card shadow">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="tukTable">
                        <thead class="table-dark">
                            <tr>
                                <th>No</th>
                                <th>Nama TUK</th>
                                <th>Alamat</th>
                                <th>Kota</th>
                                <th>Provinsi</th>
                                <th>Telepon</th>
                                <th>Email</th>
                                <th>Penanggung Jawab</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($tuks as $index => $tuk)
                            <tr>
                                <td>{{ $tuks->firstItem() + $index }}</td>
                                <td>{{ $tuk->nama_tuk }}</td>
                                <td>{{ Str::limit($tuk->alamat, 50) }}</td>
                                <td>{{ $tuk->kota }}</td>
                                <td>{{ $tuk->provinsi }}</td>
                                <td>{{ $tuk->telepon }}</td>
                                <td>{{ $tuk->email }}</td>
                                <td>{{ $tuk->penanggung_jawab }}</td>
                                <td>
                                    @if($tuk->status)
                                        <span class="badge bg-success">Aktif</span>
                                    @else
                                        <span class="badge bg-danger">Tidak Aktif</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-info btn-sm" 
                                                onclick="viewTuk({{ $tuk->id }}, '{{ $tuk->nama_tuk }}', '{{ $tuk->alamat }}', '{{ $tuk->kota }}', '{{ $tuk->provinsi }}', '{{ $tuk->kode_pos }}', '{{ $tuk->telepon }}', '{{ $tuk->email }}', '{{ $tuk->penanggung_jawab }}', {{ $tuk->status ? 'true' : 'false' }}, {{ json_encode($tuk->skema_kompetensi) }})">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button type="button" class="btn btn-warning btn-sm" 
                                                onclick="editTuk({{ $tuk->id }}, '{{ $tuk->nama_tuk }}', '{{ $tuk->alamat }}', '{{ $tuk->kota }}', '{{ $tuk->provinsi }}', '{{ $tuk->kode_pos }}', '{{ $tuk->telepon }}', '{{ $tuk->email }}', '{{ $tuk->penanggung_jawab }}', {{ $tuk->status ? 'true' : 'false' }}, {{ json_encode($tuk->skema_kompetensi) }})">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <form action="{{ route('admin.tuk') }}/{{ $tuk->id }}" method="POST" 
                                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus TUK ini?')" 
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
                                <td colspan="10" class="text-center">Tidak ada data TUK</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div class="d-flex justify-content-center">
                    {{ $tuks->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add TUK Modal -->
<div class="modal fade" id="addTukModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah TUK (Tempat Uji Kompetensi)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.tuk') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nama_tuk" class="form-label">Nama TUK <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nama_tuk') is-invalid @enderror" 
                                   id="nama_tuk" name="nama_tuk" value="{{ old('nama_tuk') }}" required>
                            @error('nama_tuk')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="penanggung_jawab" class="form-label">Penanggung Jawab <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('penanggung_jawab') is-invalid @enderror" 
                                   id="penanggung_jawab" name="penanggung_jawab" value="{{ old('penanggung_jawab') }}" required>
                            @error('penanggung_jawab')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="alamat" class="form-label">Alamat <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('alamat') is-invalid @enderror" 
                                  id="alamat" name="alamat" rows="3" required>{{ old('alamat') }}</textarea>
                        @error('alamat')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="kota" class="form-label">Kota <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('kota') is-invalid @enderror" 
                                   id="kota" name="kota" value="{{ old('kota') }}" required>
                            @error('kota')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="provinsi" class="form-label">Provinsi <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('provinsi') is-invalid @enderror" 
                                   id="provinsi" name="provinsi" value="{{ old('provinsi') }}" required>
                            @error('provinsi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="kode_pos" class="form-label">Kode Pos <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('kode_pos') is-invalid @enderror" 
                                   id="kode_pos" name="kode_pos" value="{{ old('kode_pos') }}" required>
                            @error('kode_pos')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="telepon" class="form-label">Telepon <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('telepon') is-invalid @enderror" 
                                   id="telepon" name="telepon" value="{{ old('telepon') }}" required>
                            @error('telepon')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email') }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Skema Kompetensi <span class="text-danger">*</span></label>
                        <div class="row">
                            @foreach($skemas as $skema)
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="skema_kompetensi[]" 
                                           value="{{ $skema->id }}" id="skema_{{ $skema->id }}"
                                           {{ in_array($skema->id, old('skema_kompetensi', [])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="skema_{{ $skema->id }}">
                                        {{ $skema->nama_skema }}
                                    </label>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @error('skema_kompetensi')
                            <div class="text-danger small">{{ $message }}</div>
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

<!-- View TUK Modal -->
<div class="modal fade" id="viewTukModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail TUK</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <strong>Nama TUK:</strong>
                        <p id="view_nama_tuk"></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Penanggung Jawab:</strong>
                        <p id="view_penanggung_jawab"></p>
                    </div>
                </div>
                <div class="mb-3">
                    <strong>Alamat:</strong>
                    <p id="view_alamat"></p>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <strong>Kota:</strong>
                        <p id="view_kota"></p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <strong>Provinsi:</strong>
                        <p id="view_provinsi"></p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <strong>Kode Pos:</strong>
                        <p id="view_kode_pos"></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <strong>Telepon:</strong>
                        <p id="view_telepon"></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Email:</strong>
                        <p id="view_email"></p>
                    </div>
                </div>
                <div class="mb-3">
                    <strong>Status:</strong>
                    <p id="view_status"></p>
                </div>
                <div class="mb-3">
                    <strong>Skema Kompetensi:</strong>
                    <ul id="view_skema_kompetensi"></ul>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit TUK Modal -->
<div class="modal fade" id="editTukModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit TUK (Tempat Uji Kompetensi)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editTukForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="edit_nama_tuk" class="form-label">Nama TUK <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit_nama_tuk" name="nama_tuk" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="edit_penanggung_jawab" class="form-label">Penanggung Jawab <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit_penanggung_jawab" name="penanggung_jawab" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="edit_alamat" class="form-label">Alamat <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="edit_alamat" name="alamat" rows="3" required></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="edit_kota" class="form-label">Kota <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit_kota" name="kota" required>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="edit_provinsi" class="form-label">Provinsi <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit_provinsi" name="provinsi" required>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="edit_kode_pos" class="form-label">Kode Pos <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit_kode_pos" name="kode_pos" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="edit_telepon" class="form-label">Telepon <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit_telepon" name="telepon" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="edit_email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="edit_email" name="email" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Skema Kompetensi <span class="text-danger">*</span></label>
                        <div class="row" id="edit_skema_container">
                            @foreach($skemas as $skema)
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="skema_kompetensi[]" 
                                           value="{{ $skema->id }}" id="edit_skema_{{ $skema->id }}">
                                    <label class="form-check-label" for="edit_skema_{{ $skema->id }}">
                                        {{ $skema->nama_skema }}
                                    </label>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="edit_status" class="form-label">Status</label>
                        <select class="form-select" id="edit_status" name="status">
                            <option value="1">Aktif</option>
                            <option value="0">Tidak Aktif</option>
                        </select>
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

<script>
// Search functionality
document.getElementById('searchTuk').addEventListener('keyup', function() {
    const searchTerm = this.value.toLowerCase();
    const table = document.getElementById('tukTable');
    const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
    
    for (let i = 0; i < rows.length; i++) {
        const row = rows[i];
        const cells = row.getElementsByTagName('td');
        let found = false;
        
        // Search in nama TUK (index 1), kota (index 3), and provinsi (index 4)
        for (let j = 1; j <= 4; j++) {
            if (cells[j] && cells[j].textContent.toLowerCase().includes(searchTerm)) {
                found = true;
                break;
            }
        }
        
        row.style.display = found ? '' : 'none';
    }
});

// View function
function viewTuk(id, namaTuk, alamat, kota, provinsi, kodePos, telepon, email, penanggungJawab, status, skemaKompetensi) {
    document.getElementById('view_nama_tuk').textContent = namaTuk;
    document.getElementById('view_penanggung_jawab').textContent = penanggungJawab;
    document.getElementById('view_alamat').textContent = alamat;
    document.getElementById('view_kota').textContent = kota;
    document.getElementById('view_provinsi').textContent = provinsi;
    document.getElementById('view_kode_pos').textContent = kodePos;
    document.getElementById('view_telepon').textContent = telepon;
    document.getElementById('view_email').textContent = email;
    document.getElementById('view_status').textContent = status ? 'Aktif' : 'Tidak Aktif';
    
    // Clear and populate skema kompetensi
    const skemaList = document.getElementById('view_skema_kompetensi');
    skemaList.innerHTML = '';
    
    if (skemaKompetensi && skemaKompetensi.length > 0) {
        skemaKompetensi.forEach(function(skemaId) {
            const li = document.createElement('li');
            // Find skema name from the checkboxes in add modal
            const skemaCheckbox = document.getElementById('skema_' + skemaId);
            if (skemaCheckbox) {
                li.textContent = skemaCheckbox.nextElementSibling.textContent;
                skemaList.appendChild(li);
            }
        });
    } else {
        const li = document.createElement('li');
        li.textContent = 'Tidak ada skema kompetensi';
        skemaList.appendChild(li);
    }
    
    new bootstrap.Modal(document.getElementById('viewTukModal')).show();
}

// Edit function
function editTuk(id, namaTuk, alamat, kota, provinsi, kodePos, telepon, email, penanggungJawab, status, skemaKompetensi) {
    document.getElementById('editTukForm').action = "{{ route('admin.tuk') }}/" + id;
    document.getElementById('edit_nama_tuk').value = namaTuk;
    document.getElementById('edit_penanggung_jawab').value = penanggungJawab;
    document.getElementById('edit_alamat').value = alamat;
    document.getElementById('edit_kota').value = kota;
    document.getElementById('edit_provinsi').value = provinsi;
    document.getElementById('edit_kode_pos').value = kodePos;
    document.getElementById('edit_telepon').value = telepon;
    document.getElementById('edit_email').value = email;
    document.getElementById('edit_status').value = status ? '1' : '0';
    
    // Clear all checkboxes first
    const checkboxes = document.querySelectorAll('#edit_skema_container input[type="checkbox"]');
    checkboxes.forEach(checkbox => checkbox.checked = false);
    
    // Check the selected skema kompetensi
    if (skemaKompetensi && skemaKompetensi.length > 0) {
        skemaKompetensi.forEach(function(skemaId) {
            const checkbox = document.getElementById('edit_skema_' + skemaId);
            if (checkbox) {
                checkbox.checked = true;
            }
        });
    }
    
    new bootstrap.Modal(document.getElementById('editTukModal')).show();
}
</script>
@endsection
