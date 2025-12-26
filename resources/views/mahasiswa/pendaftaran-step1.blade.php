@extends('layouts.app')

@section('title', 'Pendaftaran LSP - Data Pengajuan')
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
                            <div class="step-item active">
                                <div class="step-icon bg-primary text-white">
                                    <i class="fas fa-folder"></i>
                                </div>
                                <div class="step-label">Data Pengajuan</div>
                            </div>
                            <div class="step-line"></div>
                            <div class="step-item">
                                <div class="step-icon bg-secondary text-white">
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
                        <i class="fas fa-folder me-2"></i>DATA PENGAJUAN
                    </h4>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-4">
                        Pada bagian ini, Pilih Skema Sertifikasi yang akan di ambil, Pilih jadwal sesuai Tempat Uji Kompetensi.
                    </p>

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('mahasiswa.pendaftaran.step1.store') }}" method="POST" id="pendaftaranForm">
                        @csrf
                        
                        <!-- Skema Sertifikasi -->
                        <div class="mb-4">
                            <label for="skema_sertifikasi_id" class="form-label">
                                <strong>Skema <span class="text-danger">*</span></strong>
                            </label>
                            <select class="form-select @error('skema_sertifikasi_id') is-invalid @enderror" 
                                    id="skema_sertifikasi_id" name="skema_sertifikasi_id" required>
                                <option value="">Pilih Skema Sertifikasi</option>
                                @foreach($skemas as $skema)
                                    <option value="{{ $skema->id }}" 
                                            {{ old('skema_sertifikasi_id') == $skema->id ? 'selected' : '' }}
                                            data-kode="{{ $skema->kode_skema }}">
                                        {{ $skema->nama_skema }}
                                    </option>
                                @endforeach
                            </select>
                            @error('skema_sertifikasi_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Jadwal Uji Kompetensi -->
                        <div class="mb-4">
                            <label for="jadwal_uji_id" class="form-label">
                                <strong>Jadwal Uji Kompetensi <span class="text-danger">*</span></strong>
                            </label>
                            <select class="form-select @error('jadwal_uji_id') is-invalid @enderror" 
                                    id="jadwal_uji_id" name="jadwal_uji_id" required disabled>
                                <option value="">Pilih Skema Sertifikasi terlebih dahulu</option>
                                @foreach($jadwalUji as $jadwal)
                                    <option value="{{ $jadwal->id }}" 
                                            {{ old('jadwal_uji_id') == $jadwal->id ? 'selected' : '' }}
                                            data-skema="{{ $jadwal->skema_sertifikasi_id }}"
                                            data-tuk="{{ $jadwal->tuk->nama_tuk }}"
                                            data-tanggal="{{ $jadwal->tanggal_mulai->format('d F Y') }}"
                                            data-kuota="{{ $jadwal->kuota_terisi }}/{{ $jadwal->kuota_maksimal }}">
                                        {{ $jadwal->nama_batch }} - {{ $jadwal->tuk->nama_tuk }} 
                                        ({{ $jadwal->tanggal_mulai->format('d F Y') }})
                                    </option>
                                @endforeach
                            </select>
                            <div class="form-text text-success">
                                <i class="fas fa-info-circle me-1"></i>
                                Perhatikan Skema dan Jadwal Uji Kompetensi yang dipilih.
                            </div>
                            @error('jadwal_uji_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>


                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('mahasiswa.dashboard') }}" class="btn btn-secondary">
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

.step-item:not(.active) .step-icon {
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

.step-line {
    width: 100px;
    height: 2px;
    background-color: #e9ecef;
    margin: 0 10px;
    align-self: center;
}

.step-item.active + .step-line {
    background-color: #007bff;
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
    const skemaSelect = document.getElementById('skema_sertifikasi_id');
    const jadwalSelect = document.getElementById('jadwal_uji_id');
    
    // Function to filter jadwal berdasarkan skema yang dipilih
    function filterJadwalBySkema() {
        const selectedSkema = skemaSelect.value;
        const jadwalOptions = jadwalSelect.querySelectorAll('option');
        let hasVisibleOptions = false;
        
        jadwalOptions.forEach(option => {
            if (option.value === '') {
                // Update placeholder option
                if (selectedSkema === '') {
                    option.textContent = 'Pilih Skema Sertifikasi terlebih dahulu';
                    option.style.display = 'block';
                } else {
                    option.textContent = 'Pilih Jadwal Uji Kompetensi';
                    option.style.display = 'block';
                }
                return;
            }
            
            const skemaId = option.getAttribute('data-skema');
            if (selectedSkema === '') {
                // Hide all options if no skema selected
                option.style.display = 'none';
            } else if (skemaId === selectedSkema) {
                option.style.display = 'block';
                hasVisibleOptions = true;
            } else {
                option.style.display = 'none';
            }
        });
        
        // Enable/disable select based on skema selection
        if (selectedSkema === '') {
            jadwalSelect.disabled = true;
            jadwalSelect.value = '';
        } else {
            jadwalSelect.disabled = false;
            // Reset jadwal selection when skema changes
            if (jadwalSelect.value !== '') {
                const selectedOption = jadwalSelect.options[jadwalSelect.selectedIndex];
                const selectedSkemaId = selectedOption.getAttribute('data-skema');
                if (selectedSkemaId !== selectedSkema) {
                    jadwalSelect.value = '';
                }
            }
        }
    }
    
    // Initialize on page load
    filterJadwalBySkema();
    
    // Filter jadwal berdasarkan skema yang dipilih
    skemaSelect.addEventListener('change', function() {
        filterJadwalBySkema();
    });
    
    // Update jadwal info when selected
    jadwalSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        if (selectedOption.value) {
            const tuk = selectedOption.getAttribute('data-tuk');
            const tanggal = selectedOption.getAttribute('data-tanggal');
            const kuota = selectedOption.getAttribute('data-kuota');
            
            // Set default sumber anggaran dan pemberi anggaran if elements exist
            const sumberAnggaran = document.getElementById('sumber_anggaran');
            const pemberiAnggaran = document.getElementById('pemberi_anggaran');
            if (sumberAnggaran) {
                sumberAnggaran.value = 'sumber anggaran biaya mandiri';
            }
            if (pemberiAnggaran) {
                pemberiAnggaran.value = 'Biaya Mandiri';
            }
            
            console.log('Selected TUK:', tuk);
            console.log('Selected Date:', tanggal);
            console.log('Quota:', kuota);
        }
    });
});
</script>
@endsection
