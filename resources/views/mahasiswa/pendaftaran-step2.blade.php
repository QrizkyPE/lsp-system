@extends('layouts.app')

@section('title', 'Pendaftaran LSP - Profil Peserta')
@section('page-title', 'Pendaftaran LSP')

@section('content')
<div class="container-fluid">
    <!-- Progress Steps -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <div class="step-item completed">
                                <div class="step-icon bg-success text-white">
                                    <i class="fas fa-check"></i>
                                </div>
                                <div class="step-label">Data Pengajuan</div>
                            </div>
                            <div class="step-line completed"></div>
                            <div class="step-item active">
                                <div class="step-icon bg-primary text-white">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div class="step-label">Profil Peserta</div>
                            </div>
                            <div class="step-line"></div>
                            <div class="step-item">
                                <div class="step-icon bg-secondary text-white">
                                    <i class="fas fa-upload"></i>
                                </div>
                                <div class="step-label">Dokumen Portofolio</div>
                            </div>
                            <div class="step-line"></div>
                            <div class="step-item">
                                <div class="step-icon bg-secondary text-white">
                                    <i class="fas fa-question-circle"></i>
                                </div>
                                <div class="step-label">Asesmen Mandiri</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-user me-2"></i>FR.APL.01. PERMOHONAN SERTIFIKASI KOMPETENSI
                    </h4>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <h5><strong>Bagian 1 : Rincian Data Pemohon Sertifikasi</strong></h5>
                        <p class="mb-0">Pada bagian ini, cantumkan data pribadi, data pendidikan formal serta data pekerjaan anda pada saat ini.</p>
                    </div>

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('mahasiswa.pendaftaran.step2.store') }}" method="POST" id="profilForm">
                        @csrf
                        
                        <!-- a. Data Pribadi -->
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h5 class="mb-0"><strong>a. Data Pribadi</strong></h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="nama_lengkap" class="form-label">
                                            <strong>Nama Lengkap <span class="text-danger">*</span></strong>
                                        </label>
                                        <input type="text" class="form-control @error('nama_lengkap') is-invalid @enderror" 
                                               id="nama_lengkap" name="nama_lengkap" 
                                               value="{{ old('nama_lengkap', auth()->user()->nama_lengkap) }}" 
                                               required>
                                        @error('nama_lengkap')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="no_ktp" class="form-label">
                                            <strong>No. KTP/NIK/Paspor <span class="text-danger">*</span></strong>
                                        </label>
                                        <input type="text" class="form-control @error('no_ktp') is-invalid @enderror" 
                                               id="no_ktp" name="no_ktp" 
                                               value="{{ old('no_ktp') }}" 
                                               pattern="[0-9]+" 
                                               title="Harus berupa angka"
                                               required>
                                        @error('no_ktp')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="tempat_lahir" class="form-label">
                                            <strong>Tempat Lahir <span class="text-danger">*</span></strong>
                                        </label>
                                        <input type="text" class="form-control @error('tempat_lahir') is-invalid @enderror" 
                                               id="tempat_lahir" name="tempat_lahir" 
                                               value="{{ old('tempat_lahir') }}" 
                                               required>
                                        @error('tempat_lahir')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="tanggal_lahir" class="form-label">
                                            <strong>Tanggal Lahir <span class="text-danger">*</span></strong>
                                        </label>
                                        <input type="date" class="form-control @error('tanggal_lahir') is-invalid @enderror" 
                                               id="tanggal_lahir" name="tanggal_lahir" 
                                               value="{{ old('tanggal_lahir') }}" 
                                               required>
                                        @error('tanggal_lahir')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="jenis_kelamin" class="form-label">
                                            <strong>Jenis Kelamin <span class="text-danger">*</span></strong>
                                        </label>
                                        <select class="form-select @error('jenis_kelamin') is-invalid @enderror" 
                                                id="jenis_kelamin" name="jenis_kelamin" required>
                                            <option value="">Pilih Jenis Kelamin</option>
                                            <option value="Laki-laki" {{ old('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                            <option value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                        </select>
                                        @error('jenis_kelamin')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="kebangsaan" class="form-label">
                                            <strong>Kebangsaan <span class="text-danger">*</span></strong>
                                        </label>
                                        <input type="text" class="form-control @error('kebangsaan') is-invalid @enderror" 
                                               id="kebangsaan" name="kebangsaan" 
                                               value="{{ old('kebangsaan', 'Indonesia') }}" 
                                               required>
                                        @error('kebangsaan')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="alamat_rumah" class="form-label">
                                        <strong>Alamat Rumah <span class="text-danger">*</span></strong>
                                    </label>
                                    <textarea class="form-control @error('alamat_rumah') is-invalid @enderror" 
                                              id="alamat_rumah" name="alamat_rumah" rows="3" 
                                              required>{{ old('alamat_rumah', auth()->user()->alamat) }}</textarea>
                                    @error('alamat_rumah')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="kode_pos" class="form-label">
                                            <strong>Kode Pos <span class="text-danger">*</span></strong>
                                        </label>
                                        <input type="text" class="form-control @error('kode_pos') is-invalid @enderror" 
                                               id="kode_pos" name="kode_pos" 
                                               value="{{ old('kode_pos') }}" 
                                               pattern="[0-9]+" 
                                               title="Harus berupa angka"
                                               required>
                                        @error('kode_pos')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="rumah" class="form-label">
                                            <strong>No. Telp Rumah</strong>
                                        </label>
                                        <input type="text" class="form-control @error('rumah') is-invalid @enderror" 
                                               id="rumah" name="rumah" 
                                               value="{{ old('rumah') }}">
                                        @error('rumah')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <!-- <div class="col-md-6 mb-3">
                                        <label for="kantor" class="form-label">
                                            <strong>Kantor</strong>
                                        </label>
                                        <input type="text" class="form-control @error('kantor') is-invalid @enderror" 
                                               id="kantor" name="kantor" 
                                               value="{{ old('kantor') }}">
                                        @error('kantor')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div> -->

                                    <div class="col-md-6 mb-3">
                                        <label for="no_telp" class="form-label">
                                            <strong>No. Telp HP<span class="text-danger">*</span></strong>
                                        </label>
                                        <input type="text" class="form-control @error('no_telp') is-invalid @enderror" 
                                               id="no_telp" name="no_telp" 
                                               value="{{ old('no_telp', auth()->user()->no_telepon) }}" 
                                               pattern="[0-9]+" 
                                               title="Harus berupa angka"
                                               required>
                                        @error('no_telp')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="email" class="form-label">
                                        <strong>Email</strong>
                                    </label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           id="email" name="email" 
                                           value="{{ old('email', auth()->user()->email) }}">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="kualifikasi_pendidikan" class="form-label">
                                        <strong>Kualifikasi Pendidikan <span class="text-danger">*</span></strong>
                                    </label>
                                    <select class="form-select @error('kualifikasi_pendidikan') is-invalid @enderror" 
                                            id="kualifikasi_pendidikan" name="kualifikasi_pendidikan" required>
                                        <option value="">Pilih Kualifikasi Pendidikan</option>
                                        <option value="SD" {{ old('kualifikasi_pendidikan') == 'SD' ? 'selected' : '' }}>SD</option>
                                        <option value="SMP" {{ old('kualifikasi_pendidikan') == 'SMP' ? 'selected' : '' }}>SMP</option>
                                        <option value="SMA/SMK/Sederajat" {{ old('kualifikasi_pendidikan') == 'SMA/SMK/Sederajat' ? 'selected' : '' }}>SMA/SMK/Sederajat</option>
                                        <option value="D1" {{ old('kualifikasi_pendidikan') == 'D1' ? 'selected' : '' }}>D1</option>
                                        <option value="D2" {{ old('kualifikasi_pendidikan') == 'D2' ? 'selected' : '' }}>D2</option>
                                        <option value="D3" {{ old('kualifikasi_pendidikan') == 'D3' ? 'selected' : '' }}>D3</option>
                                        <option value="D4" {{ old('kualifikasi_pendidikan') == 'D4' ? 'selected' : '' }}>D4</option>
                                        <option value="S1" {{ old('kualifikasi_pendidikan') == 'S1' ? 'selected' : '' }}>S1</option>
                                        <option value="S2" {{ old('kualifikasi_pendidikan') == 'S2' ? 'selected' : '' }}>S2</option>
                                        <option value="S3" {{ old('kualifikasi_pendidikan') == 'S3' ? 'selected' : '' }}>S3</option>
                                    </select>
                                    @error('kualifikasi_pendidikan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- b. Data Pekerjaan Sekarang -->
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h5 class="mb-0"><strong>b. Data Pekerjaan Sekarang</strong></h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="pekerjaan" class="form-label">
                                        <strong>Pekerjaan <span class="text-danger">*</span></strong>
                                    </label>
                                    <select class="form-select @error('pekerjaan') is-invalid @enderror" 
                                            id="pekerjaan" name="pekerjaan" required>
                                        <option value="">Pilih Pekerjaan</option>
                                        <option value="Belum/Tidak Bekerja" {{ old('pekerjaan') == 'Belum/Tidak Bekerja' ? 'selected' : '' }}>Belum/Tidak Bekerja</option>
                                        <option value="Mengurus Rumah Tangga" {{ old('pekerjaan') == 'Mengurus Rumah Tangga' ? 'selected' : '' }}>Mengurus Rumah Tangga</option>
                                        <option value="Pelajar/Mahasiswa" {{ old('pekerjaan') == 'Pelajar/Mahasiswa' ? 'selected' : '' }}>Pelajar/Mahasiswa</option>
                                        <option value="Pensiunan" {{ old('pekerjaan') == 'Pensiunan' ? 'selected' : '' }}>Pensiunan</option>
                                        <option value="Pegawai Negeri Sipil (PNS)" {{ old('pekerjaan') == 'Pegawai Negeri Sipil (PNS)' ? 'selected' : '' }}>Pegawai Negeri Sipil (PNS)</option>
                                        <option value="Perdagangan" {{ old('pekerjaan') == 'Perdagangan' ? 'selected' : '' }}>Perdagangan</option>
                                        <option value="Industri" {{ old('pekerjaan') == 'Industri' ? 'selected' : '' }}>Industri</option>
                                        <option value="Konstruksi" {{ old('pekerjaan') == 'Konstruksi' ? 'selected' : '' }}>Konstruksi</option>
                                        <option value="Transportasi" {{ old('pekerjaan') == 'Transportasi' ? 'selected' : '' }}>Transportasi</option>
                                        <option value="Karyawan Swasta" {{ old('pekerjaan') == 'Karyawan Swasta' ? 'selected' : '' }}>Karyawan Swasta</option>
                                        <option value="Karyawan BUMN" {{ old('pekerjaan') == 'Karyawan BUMN' ? 'selected' : '' }}>Karyawan BUMN</option>
                                        <option value="Karyawan BUMD" {{ old('pekerjaan') == 'Karyawan BUMD' ? 'selected' : '' }}>Karyawan BUMD</option>
                                        <option value="Karyawan Honorer" {{ old('pekerjaan') == 'Karyawan Honorer' ? 'selected' : '' }}>Karyawan Honorer</option>
                                        <option value="Dosen" {{ old('pekerjaan') == 'Dosen' ? 'selected' : '' }}>Dosen</option>
                                        <option value="Guru" {{ old('pekerjaan') == 'Guru' ? 'selected' : '' }}>Guru</option>
                                        <option value="Arsitek" {{ old('pekerjaan') == 'Arsitek' ? 'selected' : '' }}>Arsitek</option>
                                        <option value="Akuntan" {{ old('pekerjaan') == 'Akuntan' ? 'selected' : '' }}>Akuntan</option>
                                        <option value="Konsultan" {{ old('pekerjaan') == 'Konsultan' ? 'selected' : '' }}>Konsultan</option>
                                        <option value="Pialang" {{ old('pekerjaan') == 'Pialang' ? 'selected' : '' }}>Pialang</option>
                                        <option value="Lainnya" {{ old('pekerjaan') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                                    </select>
                                    @error('pekerjaan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div id="pekerjaan-details" style="display: none;">
                                    <div class="mb-3">
                                        <label for="nama_institusi" class="form-label">
                                            <strong>Nama Institusi/Perusahaan <span class="text-danger">*</span></strong>
                                        </label>
                                        <input type="text" class="form-control @error('nama_institusi') is-invalid @enderror" 
                                               id="nama_institusi" name="nama_institusi" 
                                               value="{{ old('nama_institusi') }}">
                                        @error('nama_institusi')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="jabatan" class="form-label">
                                            <strong>Jabatan <span class="text-danger">*</span></strong>
                                        </label>
                                        <input type="text" class="form-control @error('jabatan') is-invalid @enderror" 
                                               id="jabatan" name="jabatan" 
                                               value="{{ old('jabatan') }}">
                                        @error('jabatan')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="alamat_lembaga" class="form-label">
                                            <strong>Alamat Lembaga/Perusahaan <span class="text-danger">*</span></strong>
                                        </label>
                                        <textarea class="form-control @error('alamat_lembaga') is-invalid @enderror" 
                                                  id="alamat_lembaga" name="alamat_lembaga" rows="3">{{ old('alamat_lembaga') }}</textarea>
                                        @error('alamat_lembaga')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="kode_pos_lembaga" class="form-label">
                                                <strong>Kode POS Perusahaan <span class="text-danger">*</span></strong>
                                            </label>
                                            <input type="text" class="form-control @error('kode_pos_lembaga') is-invalid @enderror" 
                                                   id="kode_pos_lembaga" name="kode_pos_lembaga" 
                                                   value="{{ old('kode_pos_lembaga') }}"
                                                   pattern="[0-9]+" 
                                                   title="Harus berupa angka">
                                            @error('kode_pos_lembaga')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="no_telp_lembaga" class="form-label">
                                                <strong>No. Telp Lembaga <span class="text-danger">*</span></strong>
                                            </label>
                                            <input type="text" class="form-control @error('no_telp_lembaga') is-invalid @enderror" 
                                                   id="no_telp_lembaga" name="no_telp_lembaga" 
                                                   value="{{ old('no_telp_lembaga') }}"
                                                   pattern="[0-9]+" 
                                                   title="Harus berupa angka">
                                            @error('no_telp_lembaga')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="no_fax_lembaga" class="form-label">
                                                <strong>No. Fax Lembaga <span class="text-danger">*</span></strong>
                                            </label>
                                            <input type="text" class="form-control @error('no_fax_lembaga') is-invalid @enderror" 
                                                   id="no_fax_lembaga" name="no_fax_lembaga" 
                                                   value="{{ old('no_fax_lembaga') }}"
                                                   pattern="[0-9]+" 
                                                   title="Harus berupa angka">
                                            @error('no_fax_lembaga')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="email_lembaga" class="form-label">
                                                <strong>Email Lembaga <span class="text-danger">*</span></strong>
                                            </label>
                                            <input type="email" class="form-control @error('email_lembaga') is-invalid @enderror" 
                                                   id="email_lembaga" name="email_lembaga" 
                                                   value="{{ old('email_lembaga') }}">
                                            @error('email_lembaga')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('mahasiswa.pendaftaran.step1') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Kembali
                            </a>
                            <button type="submit" class="btn btn-success">
                                Selanjutnya <i class="fas fa-arrow-right ms-2"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.step-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    position: relative;
}

.step-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 8px;
    font-size: 16px;
}

.step-item.active .step-icon {
    background-color: #007bff !important;
}

.step-item.completed .step-icon {
    background-color: #28a745 !important;
}

.step-item:not(.active):not(.completed) .step-icon {
    background-color: #6c757d !important;
}

.step-label {
    font-size: 12px;
    font-weight: 500;
    color: #6c757d;
    text-align: center;
}

.step-item.active .step-label {
    color: #007bff;
    font-weight: 600;
}

.step-item.completed .step-label {
    color: #28a745;
    font-weight: 600;
}

.step-line {
    width: 100px;
    height: 2px;
    background-color: #e9ecef;
    margin: 0 10px;
    align-self: center;
}

.step-line.completed {
    background-color: #28a745;
}

.form-select, .form-control {
    border-radius: 8px;
    border: 2px solid #e9ecef;
    padding: 12px 15px;
    font-size: 14px;
}

.form-select:focus, .form-control:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.btn {
    border-radius: 8px;
    padding: 12px 24px;
    font-weight: 500;
}

.btn-success {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    border: none;
}

.btn-success:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(40, 167, 69, 0.4);
}
</style>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const pekerjaanSelect = document.getElementById('pekerjaan');
    const pekerjaanDetails = document.getElementById('pekerjaan-details');
    
    // Show/hide pekerjaan details based on selection
    pekerjaanSelect.addEventListener('change', function() {
        const selectedValue = this.value;
        
        if (selectedValue && selectedValue !== 'Belum/Tidak Bekerja') {
            pekerjaanDetails.style.display = 'block';
            
            // Make fields required if pekerjaan is selected
            const requiredFields = pekerjaanDetails.querySelectorAll('input, textarea');
            requiredFields.forEach(field => {
                field.required = true;
            });
        } else {
            pekerjaanDetails.style.display = 'none';
            
            // Make fields not required if no pekerjaan
            const requiredFields = pekerjaanDetails.querySelectorAll('input, textarea');
            requiredFields.forEach(field => {
                field.required = false;
                field.value = '';
            });
        }
    });
    
    // Initialize on page load
    if (pekerjaanSelect.value && pekerjaanSelect.value !== 'Belum/Tidak Bekerja') {
        pekerjaanDetails.style.display = 'block';
    }
});
</script>
@endsection
