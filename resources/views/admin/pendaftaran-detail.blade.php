@extends('layouts.app')

@section('title', 'Detail Pendaftaran')
@section('page-title', 'Detail Pendaftaran')

@section('content')
@php
    $profilData = is_string($pendaftaran->profil_data) ? json_decode($pendaftaran->profil_data, true) : ($pendaftaran->profil_data ?? []);
@endphp
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0"><i class="fas fa-file-alt me-2"></i>Detail Pendaftaran - {{ $pendaftaran->no_pendaftaran }}</h4>
                        <div>
                            <a href="{{ route('admin.pendaftaran') }}" class="btn btn-light">
                                <i class="fas fa-arrow-left me-2"></i>Kembali
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Informasi Dasar -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informasi Pendaftaran</h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-sm">
                                        <tr>
                                            <td><strong>No. Pendaftaran:</strong></td>
                                            <td>{{ $pendaftaran->no_pendaftaran }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Status:</strong></td>
                                            <td>
                                                @switch($pendaftaran->status)
                                                    @case('draft')
                                                        <span class="badge bg-secondary">Draft</span>
                                                        @break
                                                    @case('pending')
                                                        <span class="badge bg-warning">Menunggu Verifikasi</span>
                                                        @break
                                                    @case('approved')
                                                        <span class="badge bg-success">Disetujui</span>
                                                        @break
                                                    @case('in_progress')
                                                        <span class="badge bg-info">Sedang Berlangsung</span>
                                                        @break
                                                    @case('persetujuan_submitted')
                                                        <span class="badge bg-info">Persetujuan Dikirim</span>
                                                        @break
                                                    @case('persetujuan_confirmed')
                                                        <span class="badge bg-success">Persetujuan Dikonfirmasi</span>
                                                        @break
                                                    @case('rejected')
                                                        <span class="badge bg-danger">Ditolak</span>
                                                        @break
                                                    @case('completed')
                                                        <span class="badge bg-primary">Selesai</span>
                                                        @break
                                                    @default
                                                        <span class="badge bg-secondary">{{ ucfirst($pendaftaran->status) }}</span>
                                                @endswitch
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Tanggal Pendaftaran:</strong></td>
                                            <td>{{ $pendaftaran->tanggal_pendaftaran ? \Carbon\Carbon::parse($pendaftaran->tanggal_pendaftaran)->format('d/m/Y H:i') : '-' }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0"><i class="fas fa-user me-2"></i>Data Mahasiswa</h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-sm">
                                        <tr>
                                            <td><strong>Nama:</strong></td>
                                            <td>{{ $profilData['nama_lengkap'] ?? $pendaftaran->user->nama_lengkap ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Email:</strong></td>
                                            <td>{{ $pendaftaran->user->email ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>No. KTP:</strong></td>
                                            <td>{{ $profilData['no_ktp'] ?? '-' }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Data Profil (Step 2) -->
                    @if($pendaftaran->profil_data)
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="fas fa-user-edit me-2"></i>Data Profil Peserta (Step 2)</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6>Data Pribadi</h6>
                                        <table class="table table-sm">
                                            <tr>
                                                <td><strong>Nama Lengkap:</strong></td>
                                                <td>{{ $profilData['nama_lengkap'] ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>No. KTP:</strong></td>
                                                <td>{{ $profilData['no_ktp'] ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Tempat/Tanggal Lahir:</strong></td>
                                                <td>{{ $profilData['tempat_lahir'] ?? '-' }} / {{ $profilData['tanggal_lahir'] ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Jenis Kelamin:</strong></td>
                                                <td>{{ $profilData['jenis_kelamin'] ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Kebangsaan:</strong></td>
                                                <td>{{ $profilData['kebangsaan'] ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Alamat Rumah:</strong></td>
                                                <td>{{ $profilData['alamat_rumah'] ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Kode Pos:</strong></td>
                                                <td>{{ $profilData['kode_pos'] ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>No. Telp:</strong></td>
                                                <td>{{ $profilData['no_telp'] ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Email:</strong></td>
                                                <td>{{ $profilData['email'] ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Kualifikasi Pendidikan:</strong></td>
                                                <td>{{ $profilData['kualifikasi_pendidikan'] ?? '-' }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-md-6">
                                        <h6>Data Pekerjaan</h6>
                                        <table class="table table-sm">
                                            <tr>
                                                <td><strong>Pekerjaan:</strong></td>
                                                <td>{{ $profilData['pekerjaan'] ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Nama Institusi:</strong></td>
                                                <td>{{ $profilData['nama_institusi'] ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Jabatan:</strong></td>
                                                <td>{{ $profilData['jabatan'] ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Alamat Lembaga:</strong></td>
                                                <td>{{ $profilData['alamat_lembaga'] ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Kode POS Perusahaan:</strong></td>
                                                <td>{{ $profilData['kode_pos_lembaga'] ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>No. Telp Lembaga:</strong></td>
                                                <td>{{ $profilData['no_telp_lembaga'] ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>No. Fax Lembaga:</strong></td>
                                                <td>{{ $profilData['no_fax_lembaga'] ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Email Lembaga:</strong></td>
                                                <td>{{ $profilData['email_lembaga'] ?? '-' }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Data Sertifikasi (Step 3) -->
                    @if($pendaftaran->sertifikasi_data)
                        @php
                            $sertifikasiData = is_string($pendaftaran->sertifikasi_data) ? json_decode($pendaftaran->sertifikasi_data, true) : $pendaftaran->sertifikasi_data;
                        @endphp
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="fas fa-certificate me-2"></i>Data Sertifikasi (Step 3)</h5>
                            </div>
                            <div class="card-body">
                                <!-- Bagian 2: Data Sertifikasi -->
                                <div class="mb-4">
                                    <h6>Bagian 2: Data Sertifikasi</h6>
                                    <table class="table table-sm">
                                        <tr>
                                            <td><strong>Skema Sertifikasi:</strong></td>
                                            <td>{{ $sertifikasiData['skema'] ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Judul:</strong></td>
                                            <td>{{ $sertifikasiData['judul'] ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Nomor Skema Sertifikasi:</strong></td>
                                            <td>{{ $sertifikasiData['nomor_skema'] ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Tujuan Asesmen:</strong></td>
                                            <td>{{ $sertifikasiData['tujuan_asesmen'] ?? '-' }}</td>
                                        </tr>
                                    </table>
                                </div>

                                <!-- Daftar Unit Kompetensi -->
                                @if(isset($sertifikasiData['unit_kompetensi']))
                                    <div class="mb-4">
                                        <h6>Daftar Unit Kompetensi</h6>
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-sm">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>No.</th>
                                                        <th>Kode Unit</th>
                                                        <th>Judul Unit</th>
                                                        <th>Standar Kompetensi Kerja</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($pendaftaran->sertifikasi_data['unit_kompetensi'] as $index => $unit)
                                                        <tr>
                                                            <td>{{ $index + 1 }}</td>
                                                            <td>{{ $unit['kode_unit'] ?? '-' }}</td>
                                                            <td>{{ $unit['judul_unit'] ?? '-' }}</td>
                                                            <td>{{ $unit['standar_kompetensi'] ?? '-' }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                @endif

                                <!-- Bukti Persyaratan Dasar Pemohon -->
                                @if(isset($sertifikasiData['bukti_files']) && count($sertifikasiData['bukti_files']) > 0)
                                    <div class="mb-4">
                                        <h6>3.1 Bukti Persyaratan Dasar Pemohon</h6>
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-sm">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>No.</th>
                                                        <th>Bukti Persyaratan Dasar</th>
                                                        <th>Memenuhi Syarat</th>
                                                        <th>Tidak Memenuhi Syarat</th>
                                                        <th>File Upload</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($sertifikasiData['bukti_files'] as $index => $bukti)
                                                        <tr>
                                                            <td>{{ $index + 1 }}</td>
                                                            <td>{{ $bukti['bukti_type'] ?? '-' }}</td>
                                                            <td>
                                                                @if(isset($sertifikasiData['bukti_persyaratan'][$index + 1]['memenuhi_syarat']))
                                                                    <span class="badge bg-success">✓</span>
                                                                @else
                                                                    <span class="badge bg-secondary">-</span>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if(isset($sertifikasiData['bukti_persyaratan'][$index + 1]['tidak_memenuhi_syarat']))
                                                                    <span class="badge bg-danger">✓</span>
                                                                @else
                                                                    <span class="badge bg-secondary">-</span>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if(isset($bukti['filename']))
                                                                    <a href="/storage/bukti_persyaratan/{{ $bukti['filename'] }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                                        <i class="fas fa-download"></i> Download
                                                                    </a>
                                                                @else
                                                                    <span class="text-muted">-</span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                @endif

                                <!-- Bukti Administratif -->
                                @if(isset($sertifikasiData['bukti_admin_files']) && count($sertifikasiData['bukti_admin_files']) > 0)
                                    <div class="mb-4">
                                        <h6>3.2 Bukti Administratif</h6>
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-sm">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>No.</th>
                                                        <th>Bukti Administratif</th>
                                                        <th>Memenuhi Syarat</th>
                                                        <th>Tidak Memenuhi Syarat</th>
                                                        <th>File Upload</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($sertifikasiData['bukti_admin_files'] as $index => $bukti)
                                                        <tr>
                                                            <td>{{ $index + 1 }}</td>
                                                            <td>{{ $bukti['bukti_type'] ?? '-' }}</td>
                                                            <td>
                                                                @if(isset($sertifikasiData['bukti_administratif'][$index + 1]['memenuhi_syarat']))
                                                                    <span class="badge bg-success">✓</span>
                                                                @else
                                                                    <span class="badge bg-secondary">-</span>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if(isset($sertifikasiData['bukti_administratif'][$index + 1]['tidak_memenuhi_syarat']))
                                                                    <span class="badge bg-danger">✓</span>
                                                                @else
                                                                    <span class="badge bg-secondary">-</span>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if(isset($bukti['filename']))
                                                                    <a href="/storage/bukti_administratif/{{ $bukti['filename'] }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                                        <i class="fas fa-download"></i> Download
                                                                    </a>
                                                                @else
                                                                    <span class="text-muted">-</span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                @endif

                                <!-- Rekomendasi dan Persetujuan -->
                                @if(isset($sertifikasiData['rekomendasi']))
                                    <div class="mb-4">
                                        <h6>Rekomendasi dan Persetujuan</h6>
                                        <table class="table table-bordered table-sm">
                                            <tr>
                                                <td><strong>Rekomendasi:</strong></td>
                                                <td>
                                                    @if(isset($sertifikasiData['rekomendasi']['diterima']) && $sertifikasiData['rekomendasi']['diterima'])
                                                        <span class="badge bg-success">Diterima</span>
                                                    @elseif(isset($sertifikasiData['rekomendasi']['tidak_diterima']) && $sertifikasiData['rekomendasi']['tidak_diterima'])
                                                        <span class="badge bg-danger">Tidak Diterima</span>
                                                    @else
                                                        <span class="badge bg-secondary">Belum Diputuskan</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><strong>Nama Pemohon:</strong></td>
                                                <td>{{ $sertifikasiData['rekomendasi']['nama_pemohon'] ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Tanggal Pemohon:</strong></td>
                                                <td>{{ $sertifikasiData['rekomendasi']['tanggal_pemohon'] ?? '-' }}</td>
                                            </tr>
                                        </table>
                                        
                                        @if(isset($sertifikasiData['signature_data']) && $sertifikasiData['signature_data'])
                                            <div class="mt-3">
                                                <h6>Tanda Tangan Pemohon:</h6>
                                                <div class="border p-3 text-center">
                                                    <img src="{{ $sertifikasiData['signature_data'] }}" alt="Tanda Tangan" style="max-width: 300px; max-height: 150px;">
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif


                    <!-- Action Buttons -->
                    <div class="d-flex gap-2">
                        @if($pendaftaran->status == 'pending')
                            <button type="button" class="btn btn-success" onclick="openApprovalModal({{ $pendaftaran->id }})">
                                <i class="fas fa-check me-2"></i>Setujui Pendaftaran
                            </button>
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal">
                                <i class="fas fa-times me-2"></i>Tolak Pendaftaran
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tolak Pendaftaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.pendaftaran.reject', $pendaftaran->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="alasan_penolakan" class="form-label">Alasan Penolakan:</label>
                        <textarea class="form-control" id="alasan_penolakan" name="alasan_penolakan" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Tolak Pendaftaran</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Preview Tanda Tangan untuk Approval -->
<div class="modal fade" id="approvalModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-signature me-2"></i>Konfirmasi Persetujuan Pendaftaran
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Konfirmasi Persetujuan</strong><br>
                    Tanda tangan admin akan digunakan untuk menyetujui pendaftaran ini.
                </div>
                
                <div class="text-center">
                    <h6 class="mb-3">Tanda Tangan Admin</h6>
                    <div id="signaturePreview" class="signature-preview">
                        <div class="text-center">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="mt-2 text-muted">Memuat tanda tangan dari personalisasi...</p>
                        </div>
                    </div>
                </div>
                
                <div class="mt-3">
                    <p class="text-muted">
                        <small>
                            <i class="fas fa-exclamation-triangle me-1"></i>
                            Pastikan tanda tangan sudah benar sebelum melanjutkan.
                        </small>
                    </p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Batal
                </button>
                <button type="button" class="btn btn-success" id="confirmApprovalBtn" disabled>
                    <i class="fas fa-check me-2"></i>Setujui Pendaftaran
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let currentPendaftaranId = null;
let signatureData = null;

function openApprovalModal(id) {
    currentPendaftaranId = id;
    
    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('approvalModal'));
    modal.show();
    
    // Load signature
    loadSignatureFromPersonalization();
}

function loadSignatureFromPersonalization() {
    fetch('/admin/personalization/get-signature')
        .then(response => response.json())
        .then(data => {
            const preview = document.getElementById('signaturePreview');
            if (preview && data.signature) {
                preview.innerHTML = `
                    <div class="text-center">
                        <img src="${data.signature}" alt="Tanda Tangan Admin" 
                             style="max-width: 300px; border: 1px solid #ddd; border-radius: 4px; background: white;">
                        <p class="mt-2 text-success">
                            <i class="fas fa-check-circle"></i> Tanda tangan dari personalisasi
                        </p>
                    </div>
                `;
                signatureData = data.signature;
                
                // Enable confirm button
                document.getElementById('confirmApprovalBtn').disabled = false;
            } else {
                preview.innerHTML = `
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i>
                        Tanda tangan tidak ditemukan. Silakan buat tanda tangan di halaman personalisasi terlebih dahulu.
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Error loading signature:', error);
            const preview = document.getElementById('signaturePreview');
            if (preview) {
                preview.innerHTML = `
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle"></i>
                        Gagal memuat tanda tangan. Silakan coba lagi.
                    </div>
                `;
            }
        });
}

function approvePendaftaran() {
    if (!currentPendaftaranId || !signatureData) {
        alert('Data tidak valid untuk persetujuan');
        return;
    }
    
    if (confirm('Apakah Anda yakin ingin menyetujui pendaftaran ini?')) {
        // Get admin signature from personalization
        fetch('/admin/personalization/get-signature')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(signatureData => {
                const signature = signatureData.signature || null;
                
                // Debug info
                console.log('Signature loaded:', signature ? 'YES' : 'NO');
                console.log('Signature length:', signature ? signature.length : 0);
                
                // Send approval request with signature
                const csrfToken = document.querySelector('meta[name="csrf-token"]');
                if (!csrfToken) {
                    throw new Error('CSRF token not found');
                }
                
                fetch(`/admin/pendaftaran/${currentPendaftaranId}/approved`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken.getAttribute('content')
                    },
                    body: JSON.stringify({
                        signature_data: signature
                    })
                })
                .then(response => {
                    console.log('Response status:', response.status);
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Response data:', data);
                    if (data.success) {
                        // Close modal
                        const modal = bootstrap.Modal.getInstance(document.getElementById('approvalModal'));
                        modal.hide();
                        location.reload();
                    } else {
                        alert('Gagal menyetujui pendaftaran');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Gagal menyetujui pendaftaran: ' + error.message);
                });
            })
            .catch(error => {
                console.error('Error loading signature:', error);
                alert('Gagal memuat tanda tangan admin: ' + error.message);
            });
    }
}

// Event listener for confirm button
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('confirmApprovalBtn').addEventListener('click', function() {
        approvePendaftaran();
    });
});

</script>
@endsection
