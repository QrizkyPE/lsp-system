@extends('layouts.app')

@section('title', 'Kelola Akun')
@section('page-title', 'Kelola Akun')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4>Kelola Akun Pengguna</h4>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
        <i class="fas fa-user-plus me-2"></i>Tambah Akun
    </button>
</div>

<div class="card shadow">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>NIM/NIP</th>
                        <th>Program Studi/Instansi</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $index => $user)
                    <tr>
                        <td>{{ $users->firstItem() + $index }}</td>
                        <td>{{ $user->nama_lengkap ?? $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            <span class="badge bg-{{ $user->role == 'admin' ? 'danger' : ($user->role == 'asesor' ? 'warning' : 'info') }}">
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>
                        <td>{{ $user->nim ?? $user->asesor->nip ?? '-' }}</td>
                        <td>{{ $user->program_studi ?? $user->asesor->instansi ?? '-' }}</td>
                        <td>
                            <span class="badge bg-{{ $user->email_verified_at ? 'success' : 'secondary' }}">
                                {{ $user->email_verified_at ? 'Verified' : 'Unverified' }}
                            </span>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-info btn-sm" 
                                        onclick="viewUser({{ $user->id }}, '{{ $user->name }}', '{{ $user->email }}', '{{ $user->role }}', '{{ $user->nim }}', '{{ $user->program_studi }}', '{{ $user->fakultas }}', '{{ $user->no_telepon }}', '{{ $user->alamat }}')">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button type="button" class="btn btn-warning btn-sm" 
                                        onclick="editUser({{ $user->id }}, '{{ $user->name }}', '{{ $user->email }}', '{{ $user->role }}', '{{ $user->nim }}', '{{ $user->program_studi }}', '{{ $user->fakultas }}', '{{ $user->no_telepon }}', '{{ $user->alamat }}')">
                                    <i class="fas fa-edit"></i>
                                </button>
                                @if($user->role !== 'admin')
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" 
                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun ini?')" 
                                      style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center">Tidak ada data pengguna</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="d-flex justify-content-center">
            {{ $users->links() }}
        </div>
    </div>
</div>

<!-- Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Akun Pengguna</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.users.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
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

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                   id="password" name="password" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="role" class="form-label">Role <span class="text-danger">*</span></label>
                            <select class="form-select @error('role') is-invalid @enderror" 
                                    id="role" name="role" required>
                                <option value="">Pilih Role</option>
                                <option value="mahasiswa" {{ old('role') == 'mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                                <option value="asesor" {{ old('role') == 'asesor' ? 'selected' : '' }}>Asesor</option>
                            </select>
                            @error('role')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Mahasiswa Fields -->
                    <div id="mahasiswaFields" style="display: none;">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nim" class="form-label">NIM <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="nim" name="nim" value="{{ old('nim') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="program_studi" class="form-label">Program Studi <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="program_studi" name="program_studi" value="{{ old('program_studi') }}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="fakultas" class="form-label">Fakultas <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="fakultas" name="fakultas" value="{{ old('fakultas') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="no_telepon" class="form-label">No Telepon <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="no_telepon" name="no_telepon" value="{{ old('no_telepon') }}">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="alamat" class="form-label">Alamat <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="alamat" name="alamat" rows="3">{{ old('alamat') }}</textarea>
                        </div>
                    </div>

                    <!-- Asesor Fields -->
                    <div id="asesorFields" style="display: none;">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nip" class="form-label">NIP <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="nip" name="nip" value="{{ old('nip') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="jabatan" class="form-label">Jabatan <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="jabatan" name="jabatan" value="{{ old('jabatan') }}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="instansi" class="form-label">Instansi <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="instansi" name="instansi" value="{{ old('instansi') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="no_sertifikat_asesor" class="form-label">No Sertifikat Asesor <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="no_sertifikat_asesor" name="no_sertifikat_asesor" value="{{ old('no_sertifikat_asesor') }}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="tanggal_sertifikat" class="form-label">Tanggal Sertifikat <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="tanggal_sertifikat" name="tanggal_sertifikat" value="{{ old('tanggal_sertifikat') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="tanggal_expired" class="form-label">Tanggal Expired <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="tanggal_expired" name="tanggal_expired" value="{{ old('tanggal_expired') }}">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="skema_kompetensi" class="form-label">Skema Kompetensi <span class="text-danger">*</span></label>
                            <select class="form-select" id="skema_kompetensi" name="skema_kompetensi[]" multiple>
                                @foreach($skemas as $skema)
                                    <option value="{{ $skema->id }}">{{ $skema->nama_skema }}</option>
                                @endforeach
                            </select>
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

<!-- Edit User Modal -->
<div class="modal fade" id="editUserModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Akun Pengguna</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editUserForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="edit_name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit_name" name="name" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="edit_email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="edit_email" name="email" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="edit_password" class="form-label">Password (Kosongkan jika tidak diubah)</label>
                            <input type="password" class="form-control" id="edit_password" name="password">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="edit_role" class="form-label">Role <span class="text-danger">*</span></label>
                            <select class="form-select" id="edit_role" name="role" required>
                                <option value="mahasiswa">Mahasiswa</option>
                                <option value="asesor">Asesor</option>
                            </select>
                        </div>
                    </div>

                    <!-- Mahasiswa Fields -->
                    <div id="editMahasiswaFields" style="display: none;">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="edit_nim" class="form-label">NIM <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="edit_nim" name="nim">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="edit_program_studi" class="form-label">Program Studi <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="edit_program_studi" name="program_studi">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="edit_fakultas" class="form-label">Fakultas <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="edit_fakultas" name="fakultas">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="edit_no_telepon" class="form-label">No Telepon <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="edit_no_telepon" name="no_telepon">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="edit_alamat" class="form-label">Alamat <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="edit_alamat" name="alamat" rows="3"></textarea>
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
document.getElementById('role').addEventListener('change', function() {
    const mahasiswaFields = document.getElementById('mahasiswaFields');
    const asesorFields = document.getElementById('asesorFields');
    
    if (this.value === 'mahasiswa') {
        mahasiswaFields.style.display = 'block';
        asesorFields.style.display = 'none';
    } else if (this.value === 'asesor') {
        mahasiswaFields.style.display = 'none';
        asesorFields.style.display = 'block';
    } else {
        mahasiswaFields.style.display = 'none';
        asesorFields.style.display = 'none';
    }
});

function editUser(id, name, email, role, nim, programStudi, fakultas, noTelepon, alamat) {
    document.getElementById('editUserForm').action = '{{ route("admin.users.update", "") }}/' + id;
    document.getElementById('edit_name').value = name;
    document.getElementById('edit_email').value = email;
    document.getElementById('edit_role').value = role;
    document.getElementById('edit_nim').value = nim;
    document.getElementById('edit_program_studi').value = programStudi;
    document.getElementById('edit_fakultas').value = fakultas;
    document.getElementById('edit_no_telepon').value = noTelepon;
    document.getElementById('edit_alamat').value = alamat;
    
    // Show/hide fields based on role
    const mahasiswaFields = document.getElementById('editMahasiswaFields');
    if (role === 'mahasiswa') {
        mahasiswaFields.style.display = 'block';
    } else {
        mahasiswaFields.style.display = 'none';
    }
    
    new bootstrap.Modal(document.getElementById('editUserModal')).show();
}

function viewUser(id, name, email, role, nim, programStudi, fakultas, noTelepon, alamat) {
    // Implementation for view user details
    alert('View user: ' + name);
}
</script>
@endsection
