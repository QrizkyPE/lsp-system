@extends('layouts.app')

@section('title', 'Buat Akun Asesor')
@section('page-title', 'Buat Akun Asesor')

@section('content')
<!-- Main Content -->
<div class="container-fluid">
    <!-- Create Asesor Account Section -->
    <div class="mb-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4>Buat Akun Asesor Baru</h4>
            <a href="{{ route('admin.asesor') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Kembali ke Daftar Asesor
            </a>
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

        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-user-plus me-2"></i>Form Pembuatan Akun Asesor
                        </h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.create-asesor-account') }}" method="POST" id="createAsesorForm">
                            @csrf
                            
                            <!-- User Account Information -->
                            <div class="mb-4">
                                <h6 class="text-primary border-bottom pb-2">
                                    <i class="fas fa-user me-2"></i>Informasi Akun User
                                </h6>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="name" class="form-label">Username <span class="text-danger">*</span></label>
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
                                        <div class="form-text">Minimal 8 karakter</div>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="password_confirmation" class="form-label">Konfirmasi Password <span class="text-danger">*</span></label>
                                        <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" 
                                               id="password_confirmation" name="password_confirmation" required>
                                        @error('password_confirmation')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Asesor Profile Information -->
                            <div class="mb-4">
                                <h6 class="text-primary border-bottom pb-2">
                                    <i class="fas fa-id-card me-2"></i>Informasi Profil Asesor
                                </h6>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="nama_lengkap" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('nama_lengkap') is-invalid @enderror" 
                                               id="nama_lengkap" name="nama_lengkap" value="{{ old('nama_lengkap') }}" required>
                                        @error('nama_lengkap')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="nip" class="form-label">NIP <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('nip') is-invalid @enderror" 
                                               id="nip" name="nip" value="{{ old('nip') }}" required>
                                        @error('nip')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="jabatan" class="form-label">Jabatan <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('jabatan') is-invalid @enderror" 
                                               id="jabatan" name="jabatan" value="{{ old('jabatan') }}" required>
                                        @error('jabatan')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="instansi" class="form-label">Instansi <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('instansi') is-invalid @enderror" 
                                               id="instansi" name="instansi" value="{{ old('instansi') }}" required>
                                        @error('instansi')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="no_reg" class="form-label">No. Reg. <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('no_reg') is-invalid @enderror" 
                                               id="no_reg" name="no_reg" value="{{ old('no_reg') }}" required>
                                        @error('no_reg')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="no_sertifikat_asesor" class="form-label">No. Sertifikat Asesor <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('no_sertifikat_asesor') is-invalid @enderror" 
                                               id="no_sertifikat_asesor" name="no_sertifikat_asesor" value="{{ old('no_sertifikat_asesor') }}" required>
                                        @error('no_sertifikat_asesor')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="no_telepon" class="form-label">No. Telepon <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('no_telepon') is-invalid @enderror" 
                                               id="no_telepon" name="no_telepon" value="{{ old('no_telepon') }}" required>
                                        @error('no_telepon')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="tanggal_sertifikat" class="form-label">Tanggal Sertifikat <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control @error('tanggal_sertifikat') is-invalid @enderror" 
                                               id="tanggal_sertifikat" name="tanggal_sertifikat" value="{{ old('tanggal_sertifikat') }}" required>
                                        @error('tanggal_sertifikat')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="tanggal_expired" class="form-label">Tanggal Expired <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control @error('tanggal_expired') is-invalid @enderror" 
                                               id="tanggal_expired" name="tanggal_expired" value="{{ old('tanggal_expired') }}" required>
                                        @error('tanggal_expired')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="alamat" class="form-label">Alamat</label>
                                    <textarea class="form-control @error('alamat') is-invalid @enderror" 
                                              id="alamat" name="alamat" rows="3">{{ old('alamat') }}</textarea>
                                    @error('alamat')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Skema Kompetensi -->
                            <div class="mb-4">
                                <h6 class="text-primary border-bottom pb-2">
                                    <i class="fas fa-certificate me-2"></i>Skema Kompetensi
                                </h6>
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

                            <!-- Submit Buttons -->
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('admin.asesor') }}" class="btn btn-secondary">
                                    <i class="fas fa-times me-2"></i>Batal
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Buat Akun Asesor
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Password confirmation validation
document.getElementById('password_confirmation').addEventListener('input', function() {
    const password = document.getElementById('password').value;
    const confirmPassword = this.value;
    
    if (password !== confirmPassword) {
        this.setCustomValidity('Password tidak cocok');
    } else {
        this.setCustomValidity('');
    }
});

document.getElementById('password').addEventListener('input', function() {
    const confirmPassword = document.getElementById('password_confirmation');
    if (confirmPassword.value) {
        confirmPassword.dispatchEvent(new Event('input'));
    }
});

// Auto-fill nama_lengkap from name
document.getElementById('name').addEventListener('input', function() {
    const namaLengkap = document.getElementById('nama_lengkap');
    if (!namaLengkap.value) {
        namaLengkap.value = this.value;
    }
});

// Form validation
document.getElementById('createAsesorForm').addEventListener('submit', function(e) {
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('password_confirmation').value;
    
    if (password !== confirmPassword) {
        e.preventDefault();
        alert('Password dan konfirmasi password tidak cocok');
        return false;
    }
    
    if (password.length < 8) {
        e.preventDefault();
        alert('Password minimal 8 karakter');
        return false;
    }
});
</script>
@endsection
