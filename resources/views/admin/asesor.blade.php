@extends('layouts.app')

@section('title', 'Asesor')
@section('page-title', 'Asesor')

@section('content')
<!-- Main Content -->
<div class="container-fluid">
    <!-- Asesor Section -->
    <div class="mb-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4>Daftar Asesor</h4>
            <a href="{{ route('admin.create-asesor-account') }}" class="btn btn-success">
                <i class="fas fa-user-plus me-2"></i>Buat Akun Asesor
            </a>
        </div>

        <!-- Information Alert -->
        {{-- <div class="alert alert-info alert-dismissible fade show" role="alert">
            <i class="fas fa-info-circle me-2"></i>
            <strong>Informasi:</strong> Gunakan tombol "Buat Akun Asesor" untuk membuat user account baru dengan role asesor dan profil asesor sekaligus.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div> --}}

        <!-- Search Box -->
        <div class="row mb-3">
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                    <input type="text" class="form-control" id="searchAsesor" placeholder="Cari berdasarkan nama, NIP, atau instansi...">
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
                    <table class="table table-bordered table-hover" id="asesorTable">
                        <thead class="table-dark">
                            <tr>
                                <th>No</th>
                                <th>Nama Lengkap</th>
                                <th>NIP</th>
                                <th>Jabatan</th>
                                <th>Instansi</th>
                                <th>No. Sertifikat</th>
                                <th>Tanggal Expired</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($asesor as $index => $asesorItem)
                            <tr>
                                <td>{{ $asesor->firstItem() + $index }}</td>
                                <td>{{ $asesorItem->nama_lengkap }}</td>
                                <td>{{ $asesorItem->nip }}</td>
                                <td>{{ $asesorItem->jabatan }}</td>
                                <td>{{ $asesorItem->instansi }}</td>
                                <td>{{ $asesorItem->no_sertifikat_asesor }}</td>
                                <td>
                                    <span class="{{ $asesorItem->tanggal_expired < now() ? 'text-danger' : ($asesorItem->tanggal_expired < now()->addMonths(3) ? 'text-warning' : 'text-success') }}">
                                        {{ $asesorItem->tanggal_expired->format('d F Y') }}
                                    </span>
                                </td>
                                <td>
                                    @if($asesorItem->status)
                                        <span class="badge bg-success">Aktif</span>
                                    @else
                                        <span class="badge bg-danger">Tidak Aktif</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-info btn-sm" 
                                                onclick="viewAsesor({{ $asesorItem->id }}, '{{ $asesorItem->nama_lengkap }}', '{{ $asesorItem->nip }}', '{{ $asesorItem->jabatan }}', '{{ $asesorItem->instansi }}', '{{ $asesorItem->no_reg }}', '{{ $asesorItem->no_sertifikat_asesor }}', '{{ $asesorItem->tanggal_sertifikat->format('Y-m-d') }}', '{{ $asesorItem->tanggal_expired->format('Y-m-d') }}', {{ $asesorItem->status ? 'true' : 'false' }}, {{ json_encode($asesorItem->skema_kompetensi) }}, '{{ $asesorItem->user->email }}')">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button type="button" class="btn btn-warning btn-sm" 
                                                onclick="editAsesor({{ $asesorItem->id }}, {{ $asesorItem->user_id }}, '{{ $asesorItem->nama_lengkap }}', '{{ $asesorItem->nip }}', '{{ $asesorItem->jabatan }}', '{{ $asesorItem->instansi }}', '{{ $asesorItem->no_reg }}', '{{ $asesorItem->no_sertifikat_asesor }}', '{{ $asesorItem->tanggal_sertifikat->format('Y-m-d') }}', '{{ $asesorItem->tanggal_expired->format('Y-m-d') }}', {{ $asesorItem->status ? 'true' : 'false' }}, {{ json_encode($asesorItem->skema_kompetensi) }})">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <form action="{{ route('admin.asesor') }}/{{ $asesorItem->id }}" method="POST" 
                                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus asesor ini?')" 
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
                                <td colspan="9" class="text-center">Tidak ada data asesor</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div class="d-flex justify-content-center">
                    {{ $asesor->links() }}
                </div>
            </div>
        </div>
    </div>
</div>


<!-- View Asesor Modal -->
<div class="modal fade" id="viewAsesorModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Asesor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <strong>Nama Lengkap:</strong>
                        <p id="view_nama_lengkap"></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>NIP:</strong>
                        <p id="view_nip"></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <strong>Jabatan:</strong>
                        <p id="view_jabatan"></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Instansi:</strong>
                        <p id="view_instansi"></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <strong>No. Reg.:</strong>
                        <p id="view_no_reg"></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <strong>No. Sertifikat Asesor:</strong>
                        <p id="view_no_sertifikat_asesor"></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Email:</strong>
                        <p id="view_email"></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <strong>Tanggal Sertifikat:</strong>
                        <p id="view_tanggal_sertifikat"></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Tanggal Expired:</strong>
                        <p id="view_tanggal_expired"></p>
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

<!-- Edit Asesor Modal -->
<div class="modal fade" id="editAsesorModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Asesor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editAsesorForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="edit_user_id" class="form-label">User Account <span class="text-danger">*</span></label>
                            <select class="form-select" id="edit_user_id" name="user_id" required>
                                <option value="">Pilih User Account</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="edit_nama_lengkap" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit_nama_lengkap" name="nama_lengkap" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="edit_nip" class="form-label">NIP <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit_nip" name="nip" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="edit_jabatan" class="form-label">Jabatan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit_jabatan" name="jabatan" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="edit_instansi" class="form-label">Instansi <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit_instansi" name="instansi" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="edit_no_reg" class="form-label">No. Reg.</label>
                            <input type="text" class="form-control" id="edit_no_reg" name="no_reg">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="edit_no_sertifikat_asesor" class="form-label">No. Sertifikat Asesor <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit_no_sertifikat_asesor" name="no_sertifikat_asesor" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="edit_tanggal_sertifikat" class="form-label">Tanggal Sertifikat <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="edit_tanggal_sertifikat" name="tanggal_sertifikat" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="edit_tanggal_expired" class="form-label">Tanggal Expired <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="edit_tanggal_expired" name="tanggal_expired" required>
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
document.getElementById('searchAsesor').addEventListener('keyup', function() {
    const searchTerm = this.value.toLowerCase();
    const table = document.getElementById('asesorTable');
    const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
    
    for (let i = 0; i < rows.length; i++) {
        const row = rows[i];
        const cells = row.getElementsByTagName('td');
        let found = false;
        
        // Search in nama lengkap (index 1), NIP (index 2), and instansi (index 4)
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
function viewAsesor(id, namaLengkap, nip, jabatan, instansi, noReg, noSertifikat, tanggalSertifikat, tanggalExpired, status, skemaKompetensi, email) {
    document.getElementById('view_nama_lengkap').textContent = namaLengkap;
    document.getElementById('view_nip').textContent = nip;
    document.getElementById('view_jabatan').textContent = jabatan;
    document.getElementById('view_instansi').textContent = instansi;
    document.getElementById('view_no_reg').textContent = noReg || '-';
    document.getElementById('view_no_sertifikat_asesor').textContent = noSertifikat;
    document.getElementById('view_email').textContent = email;
    document.getElementById('view_tanggal_sertifikat').textContent = new Date(tanggalSertifikat).toLocaleDateString('id-ID');
    document.getElementById('view_tanggal_expired').textContent = new Date(tanggalExpired).toLocaleDateString('id-ID');
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
    
    new bootstrap.Modal(document.getElementById('viewAsesorModal')).show();
}

// Edit function
function editAsesor(id, userId, namaLengkap, nip, jabatan, instansi, noReg, noSertifikat, tanggalSertifikat, tanggalExpired, status, skemaKompetensi) {
    document.getElementById('editAsesorForm').action = "{{ route('admin.asesor') }}/" + id;
    document.getElementById('edit_user_id').value = userId;
    document.getElementById('edit_nama_lengkap').value = namaLengkap;
    document.getElementById('edit_nip').value = nip;
    document.getElementById('edit_jabatan').value = jabatan;
    document.getElementById('edit_instansi').value = instansi;
    document.getElementById('edit_no_reg').value = noReg || '';
    document.getElementById('edit_no_sertifikat_asesor').value = noSertifikat;
    document.getElementById('edit_tanggal_sertifikat').value = tanggalSertifikat;
    document.getElementById('edit_tanggal_expired').value = tanggalExpired;
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
    
    new bootstrap.Modal(document.getElementById('editAsesorModal')).show();
}
</script>
@endsection
