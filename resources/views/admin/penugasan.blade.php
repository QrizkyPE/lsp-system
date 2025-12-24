@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Penugasan Asesor</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <div class="btn-group me-2">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPenugasanModal">
                            <i class="fas fa-plus"></i> Tambah Penugasan
                        </button>
                    </div>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Search and Filter -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                        <input type="text" class="form-control" id="searchInput" placeholder="Cari penugasan...">
                    </div>
                </div>
                <div class="col-md-3">
                    <select class="form-select" id="statusFilter">
                        <option value="">Semua Status</option>
                        <option value="pending">Pending</option>
                        <option value="accepted">Diterima</option>
                        <option value="rejected">Ditolak</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-select" id="jenisFilter">
                        <option value="">Semua Jenis</option>
                        <option value="asesor">MA (Master Asesor)</option>
                        <option value="mapa">MAPA</option>
                        <option value="ma">MA</option>
                        <option value="mkva">MKVA</option>
                    </select>
                </div>
            </div>

            <!-- Penugasan Table -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-user-check me-2"></i>Daftar Penugasan Asesor</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover" id="penugasanTable">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>Asesor</th>
                                    <th>Jadwal Uji</th>
                                    <th>Skema Sertifikasi</th>
                                    <th>Mahasiswa</th>
                                    <th>Jenis Penugasan</th>
                                    <th>Status</th>
                                    <th>Tanggal Penugasan</th>
                                    <th>Keterangan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($penugasan as $index => $p)
                                    <tr>
                                        <td>{{ $penugasan->firstItem() + $index }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm bg-primary rounded-circle d-flex align-items-center justify-content-center me-2">
                                                    <i class="fas fa-user text-white"></i>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0">{{ $p->asesor->user->nama_lengkap ?? $p->asesor->user->name }}</h6>
                                                    <small class="text-muted">{{ $p->asesor->user->email }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <strong>{{ $p->jadwalUji->nama_batch ?? '-' }}</strong>
                                                <br>
                                                <small class="text-muted">
                                                    {{ $p->jadwalUji->tanggal_mulai ? \Carbon\Carbon::parse($p->jadwalUji->tanggal_mulai)->format('d/m/Y') : '-' }}
                                                </small>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">
                                                {{ $p->jadwalUji->skemaSertifikasi->nama_skema ?? '-' }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($p->pendaftaran && $p->pendaftaran->count() > 0)
                                                <div class="d-flex flex-column gap-1">
                                                    @foreach($p->pendaftaran->take(3) as $pendaftaran)
                                                        <small class="badge bg-info">
                                                            {{ $pendaftaran->no_pendaftaran }} - {{ $pendaftaran->user->nama_lengkap ?? $pendaftaran->user->name }}
                                                        </small>
                                                    @endforeach
                                                    @if($p->pendaftaran->count() > 3)
                                                        <small class="text-muted">+{{ $p->pendaftaran->count() - 3 }} lainnya</small>
                                                    @endif
                                                </div>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @switch($p->jenis_penugasan)
                                                @case('asesor')
                                                    <span class="badge bg-primary">MA (Master Asesor)</span>
                                                    @break
                                                @case('mapa')
                                                    <span class="badge bg-success">MAPA</span>
                                                    @break
                                                @case('ma')
                                                    <span class="badge bg-warning">MA</span>
                                                    @break
                                                @case('mkva')
                                                    <span class="badge bg-danger">MKVA</span>
                                                    @break
                                                @default
                                                    <span class="badge bg-secondary">{{ ucfirst($p->jenis_penugasan) }}</span>
                                            @endswitch
                                        </td>
                                        <td>
                                            @switch($p->status)
                                                @case('pending')
                                                    <span class="badge bg-warning">Pending</span>
                                                    @break
                                                @case('accepted')
                                                    <span class="badge bg-success">Diterima</span>
                                                    @break
                                                @case('rejected')
                                                    <span class="badge bg-danger">Ditolak</span>
                                                    @break
                                                @default
                                                    <span class="badge bg-secondary">{{ ucfirst($p->status) }}</span>
                                            @endswitch
                                        </td>
                                        <td>
                                            {{ $p->tanggal_penugasan ? \Carbon\Carbon::parse($p->tanggal_penugasan)->format('d/m/Y H:i') : '-' }}
                                        </td>
                                        <td>
                                            <span class="text-truncate d-inline-block" style="max-width: 200px;" title="{{ $p->keterangan }}">
                                                {{ $p->keterangan ?? '-' }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="editPenugasan({{ $p->id }})" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="deletePenugasan({{ $p->id }})" title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="fas fa-inbox fa-2x mb-2"></i>
                                                <p>Tidak ada penugasan yang ditemukan</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($penugasan->hasPages())
                        <div class="d-flex justify-content-center mt-3">
                            {{ $penugasan->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Penugasan Modal -->
<div class="modal fade" id="addPenugasanModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Penugasan Asesor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.penugasan.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="jadwal_uji_id" class="form-label">Jadwal Uji <span class="text-danger">*</span></label>
                                <select class="form-select" name="jadwal_uji_id" id="jadwal_uji_id" required>
                                    <option value="">Pilih Jadwal Uji</option>
                                    @foreach($jadwals as $jadwal)
                                        <option value="{{ $jadwal->id }}">
                                            {{ $jadwal->nama_batch }} - {{ $jadwal->skemaSertifikasi->nama_skema ?? '-' }}
                                            ({{ $jadwal->tanggal_mulai ? \Carbon\Carbon::parse($jadwal->tanggal_mulai)->format('d/m/Y') : '-' }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="asesor_id" class="form-label">Asesor <span class="text-danger">*</span></label>
                                <select class="form-select" name="asesor_id" id="asesor_id" required>
                                    <option value="">Pilih Asesor</option>
                                    @foreach($asesor as $a)
                                        <option value="{{ $a->id }}">
                                            {{ $a->user->nama_lengkap ?? $a->user->name }} - {{ $a->user->email }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="jenis_penugasan" class="form-label">Jenis Penugasan <span class="text-danger">*</span></label>
                                <select class="form-select" name="jenis_penugasan" id="jenis_penugasan" required>
                                    <option value="">Pilih Jenis Penugasan</option>
                                    <option value="asesor">MA (Master Asesor)</option>
                                    <option value="mapa">MAPA (Master Asesor Penilaian Asesmen)</option>
                                    <option value="mkva">MKVA (Master Kompetensi Verifikasi Asesmen)</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="keterangan" class="form-label">Keterangan</label>
                                <textarea class="form-control" name="keterangan" id="keterangan" rows="3" placeholder="Keterangan penugasan (opsional)"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3" id="mahasiswaField" style="display: none;">
                        <label for="pendaftaran_id" class="form-label">Mahasiswa yang akan Diverifikasi <small class="text-muted">(Pilih asesi yang sudah disetujui)</small></label>
                        <select class="form-select" name="pendaftaran_id[]" id="pendaftaran_id" multiple size="5">
                            @foreach($mahasiswa as $m)
                                <option value="{{ $m->id }}">
                                    {{ $m->no_pendaftaran }} - {{ $m->user->nama_lengkap ?? $m->user->name }} ({{ $m->skemaSertifikasi->nama_skema ?? '-' }})
                                </option>
                            @endforeach
                        </select>
                        <small class="form-text text-muted">Gunakan Ctrl+Click (Windows) atau Cmd+Click (Mac) untuk memilih beberapa mahasiswa</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Penugasan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Penugasan Modal -->
<div class="modal fade" id="editPenugasanModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Penugasan Asesor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editPenugasanForm" method="POST" onsubmit="return handleEditPenugasanSubmit(event)">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_jadwal_uji_id" class="form-label">Jadwal Uji <span class="text-danger">*</span></label>
                                <select class="form-select" name="jadwal_uji_id" id="edit_jadwal_uji_id" required>
                                    <option value="">Pilih Jadwal Uji</option>
                                    @foreach($jadwals as $jadwal)
                                        <option value="{{ $jadwal->id }}">
                                            {{ $jadwal->nama_batch }} - {{ $jadwal->skemaSertifikasi->nama_skema ?? '-' }}
                                            ({{ $jadwal->tanggal_mulai ? \Carbon\Carbon::parse($jadwal->tanggal_mulai)->format('d/m/Y') : '-' }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_asesor_id" class="form-label">Asesor <span class="text-danger">*</span></label>
                                <select class="form-select" name="asesor_id" id="edit_asesor_id" required>
                                    <option value="">Pilih Asesor</option>
                                    @foreach($asesor as $a)
                                        <option value="{{ $a->id }}">
                                            {{ $a->user->nama_lengkap ?? $a->user->name }} - {{ $a->user->email }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_jenis_penugasan" class="form-label">Jenis Penugasan <span class="text-danger">*</span></label>
                                <select class="form-select" name="jenis_penugasan" id="edit_jenis_penugasan" required>
                                    <option value="">Pilih Jenis Penugasan</option>
                                    <option value="asesor">MA (Master Asesor)</option>
                                    <option value="mapa">MAPA (Master Asesor Penilaian Asesmen)</option>
                                    <option value="mkva">MKVA (Master Kompetensi Verifikasi Asesmen)</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_keterangan" class="form-label">Keterangan</label>
                                <textarea class="form-control" name="keterangan" id="edit_keterangan" rows="3" placeholder="Keterangan penugasan (opsional)"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3" id="editMahasiswaField" style="display: none;">
                        <label for="edit_pendaftaran_id" class="form-label">Mahasiswa yang akan Diverifikasi <small class="text-muted">(Pilih asesi yang sudah disetujui)</small></label>
                        <select class="form-select" name="pendaftaran_id[]" id="edit_pendaftaran_id" multiple size="5">
                            @foreach($mahasiswa as $m)
                                <option value="{{ $m->id }}">
                                    {{ $m->no_pendaftaran }} - {{ $m->user->nama_lengkap ?? $m->user->name }} ({{ $m->skemaSertifikasi->nama_skema ?? '-' }})
                                </option>
                            @endforeach
                        </select>
                        <small class="form-text text-muted">Gunakan Ctrl+Click (Windows) atau Cmd+Click (Mac) untuk memilih beberapa mahasiswa</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Update Penugasan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Search functionality
    const searchInput = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');
    const jenisFilter = document.getElementById('jenisFilter');
    const table = document.getElementById('penugasanTable');
    const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');

    function filterTable() {
        const searchTerm = searchInput.value.toLowerCase();
        const statusValue = statusFilter.value;
        const jenisValue = jenisFilter.value;

        for (let i = 0; i < rows.length; i++) {
            const row = rows[i];
            const text = row.textContent.toLowerCase();
            const statusCell = row.cells[5]; // Status column
            const jenisCell = row.cells[4]; // Jenis column

            const matchesSearch = text.includes(searchTerm);
            const matchesStatus = !statusValue || statusCell.textContent.toLowerCase().includes(statusValue);
            const matchesJenis = !jenisValue || jenisCell.textContent.toLowerCase().includes(jenisValue);

            if (matchesSearch && matchesStatus && matchesJenis) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        }
    }

    searchInput.addEventListener('input', filterTable);
    statusFilter.addEventListener('change', filterTable);
    jenisFilter.addEventListener('change', filterTable);

    // Toggle mahasiswa field based on jenis penugasan
    const jenisPenugasan = document.getElementById('jenis_penugasan');
    const mahasiswaField = document.getElementById('mahasiswaField');
    const pendaftaranSelect = document.getElementById('pendaftaran_id');

    function toggleMahasiswaField() {
        if (jenisPenugasan && mahasiswaField) {
            if (jenisPenugasan.value === 'asesor') {
                mahasiswaField.style.display = 'block';
            } else {
                mahasiswaField.style.display = 'none';
                // Clear selection when hidden
                if (pendaftaranSelect) {
                    Array.from(pendaftaranSelect.options).forEach(option => {
                        option.selected = false;
                    });
                }
            }
        }
    }

    if (jenisPenugasan) {
        jenisPenugasan.addEventListener('change', toggleMahasiswaField);
        // Check initial state
        toggleMahasiswaField();
    }

    // Reset form when modal is closed
    const addModal = document.getElementById('addPenugasanModal');
    if (addModal) {
        addModal.addEventListener('hidden.bs.modal', function() {
            const form = addModal.querySelector('form');
            if (form) {
                form.reset();
                if (mahasiswaField) {
                    mahasiswaField.style.display = 'none';
                }
            }
        });
    }

    // Reset edit form when modal is closed
    const editModal = document.getElementById('editPenugasanModal');
    if (editModal) {
        editModal.addEventListener('hidden.bs.modal', function() {
            const editMahasiswaField = document.getElementById('editMahasiswaField');
            if (editMahasiswaField) {
                editMahasiswaField.style.display = 'none';
            }
        });
    }
});

function editPenugasan(id) {
    // Fetch penugasan data and populate edit modal
    fetch(`/admin/penugasan/${id}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('edit_jadwal_uji_id').value = data.jadwal_uji_id;
            document.getElementById('edit_asesor_id').value = data.asesor_id;
            document.getElementById('edit_jenis_penugasan').value = data.jenis_penugasan;
            document.getElementById('edit_keterangan').value = data.keterangan || '';
            
            // Toggle mahasiswa field based on jenis penugasan
            const editJenisPenugasan = document.getElementById('edit_jenis_penugasan');
            const editMahasiswaField = document.getElementById('editMahasiswaField');
            const pendaftaranSelect = document.getElementById('edit_pendaftaran_id');
            
            function toggleEditMahasiswaField() {
                if (editJenisPenugasan && editMahasiswaField) {
                    if (editJenisPenugasan.value === 'asesor') {
                        editMahasiswaField.style.display = 'block';
                    } else {
                        editMahasiswaField.style.display = 'none';
                        // Clear selection when hidden
                        if (pendaftaranSelect) {
            Array.from(pendaftaranSelect.options).forEach(option => {
                option.selected = false;
            });
                        }
                    }
                }
            }
            
            // Set up event listener for jenis penugasan change
            const existingListener = editJenisPenugasan.getAttribute('data-listener-attached');
            if (!existingListener) {
                editJenisPenugasan.addEventListener('change', toggleEditMahasiswaField);
                editJenisPenugasan.setAttribute('data-listener-attached', 'true');
            }
            
            // Update available mahasiswa options
            if (pendaftaranSelect && data.available_mahasiswa) {
                pendaftaranSelect.innerHTML = '';
                data.available_mahasiswa.forEach(mahasiswa => {
                    const option = document.createElement('option');
                    option.value = mahasiswa.id;
                    option.textContent = mahasiswa.text;
                    pendaftaranSelect.appendChild(option);
                });
            }
            
            // Check initial state
            toggleEditMahasiswaField();
            
            // Set selected pendaftaran
            if (pendaftaranSelect && data.pendaftaran_ids && Array.isArray(data.pendaftaran_ids)) {
                data.pendaftaran_ids.forEach(pendaftaranId => {
                    const option = pendaftaranSelect.querySelector(`option[value="${pendaftaranId}"]`);
                    if (option) {
                        option.selected = true;
                    }
                });
            }
            
            document.getElementById('editPenugasanForm').action = `/admin/penugasan/${id}`;
            
            new bootstrap.Modal(document.getElementById('editPenugasanModal')).show();
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Gagal memuat data penugasan');
        });
}

// Handle edit penugasan form submit
function handleEditPenugasanSubmit(event) {
    const form = event.target;
    const pendaftaranSelect = document.getElementById('edit_pendaftaran_id');
    const jenisPenugasan = document.getElementById('edit_jenis_penugasan');
    
    // Remove any previously added hidden inputs
    const existingHidden = form.querySelectorAll('input[name="pendaftaran_id[]"][type="hidden"]');
    existingHidden.forEach(input => input.remove());
    
    // If jenis penugasan is 'asesor' and field is visible
    if (jenisPenugasan && jenisPenugasan.value === 'asesor' && 
        pendaftaranSelect && pendaftaranSelect.style.display !== 'none') {
        // Get selected values (only those that are actually selected)
        const selected = Array.from(pendaftaranSelect.selectedOptions).map(opt => opt.value);
        
        // For multiple select, if nothing is selected, the form won't send the field at all
        // So we need to ensure pendaftaran_id[] is always sent as array
        // Remove the select's name attribute to prevent it from sending its own data
        pendaftaranSelect.removeAttribute('name');
        
        // Add hidden inputs for each selected value
        // Always ensure pendaftaran_id[] is sent as array
        if (selected.length > 0) {
            selected.forEach(id => {
                if (id && id !== '') { // Only add non-empty values
                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = 'pendaftaran_id[]';
                    hiddenInput.value = id;
                    form.appendChild(hiddenInput);
                }
            });
        } else {
            // If no selection, add a hidden input with empty value to ensure empty array is sent
            // This ensures controller receives pendaftaran_id as empty array
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'pendaftaran_id[]';
            hiddenInput.value = '';
            form.appendChild(hiddenInput);
        }
    }
    
    return true; // Allow form to submit
}

function deletePenugasan(id) {
    if (confirm('Apakah Anda yakin ingin menghapus penugasan ini?')) {
        fetch(`/admin/penugasan/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(response => {
            // Check if response is OK (status 200-299)
            if (response.ok) {
                return response.json().catch(() => {
                    // If response is not JSON (redirect), still consider it successful
                    return { success: true };
                });
            } else {
                return response.json().then(data => {
                    throw new Error(data.message || 'Gagal menghapus penugasan');
                }).catch(() => {
                    throw new Error('Gagal menghapus penugasan');
                });
            }
        })
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message || 'Gagal menghapus penugasan');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert(error.message || 'Gagal menghapus penugasan');
        });
    }
}
</script>
@endsection
