@extends('layouts.app')

@section('title', 'Pendaftaran LSP')
@section('page-title', 'Pendaftaran LSP')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="fas fa-clipboard-list me-2"></i>Pendaftaran LSP</h4>
                </div>
                <div class="card-body">
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

                    <!-- Summary Cards -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h4 class="mb-0">{{ $totalPendaftaran ?? 0 }}</h4>
                                            <p class="mb-0">Total Pendaftaran</p>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-file-alt fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h4 class="mb-0">{{ $pendingPendaftaran ?? 0 }}</h4>
                                            <p class="mb-0">Pending</p>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-clock fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h4 class="mb-0">{{ $approvedPendaftaran ?? 0 }}</h4>
                                            <p class="mb-0">Disetujui</p>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-check-circle fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-danger text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h4 class="mb-0">{{ $rejectedPendaftaran ?? 0 }}</h4>
                                            <p class="mb-0">Ditolak</p>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-times-circle fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filter and Search -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-search"></i></span>
                                <input type="text" class="form-control" id="searchInput" placeholder="Cari pendaftaran...">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <select class="form-select" id="statusFilter">
                                <option value="">Semua Status</option>
                                <option value="pending">Menunggu Verifikasi</option>
                                <option value="approved">Disetujui</option>
                                <option value="rejected">Ditolak</option>
                                <option value="completed">Selesai</option>
                            </select>
                        </div>
                    </div>

                    <!-- Pendaftaran List -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-list me-2"></i>Daftar Pendaftaran</h5>
                        </div>
                        <div class="card-body">
                            @if($pendaftaran->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover" id="pendaftaranTable">
                                        <thead class="table-light">
                                            <tr>
                                                <th>No. Pendaftaran</th>
                                                <th>Mahasiswa</th>
                                                <th>Skema Sertifikasi</th>
                                                <th>Jadwal Uji</th>
                                                <th>Status</th>
                                                <th>Tanggal Pendaftaran</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($pendaftaran as $p)
                                                <tr data-status="{{ $p->status }}">
                                                    <td>
                                                        <strong>{{ $p->no_pendaftaran }}</strong>
                                                    </td>
                                                    <td>
                                                        <div class="fw-bold">{{ $p->user->nama_lengkap ?? '-' }}</div>
                                                        <small class="text-muted">{{ $p->user->email ?? '-' }}</small>
                                                    </td>
                                                    <td>
                                                        <div class="fw-bold">{{ $p->skemaSertifikasi->nama_skema ?? '-' }}</div>
                                                        <small class="text-muted">{{ $p->skemaSertifikasi->kode_skema ?? '-' }}</small>
                                                    </td>
                                                    <td>
                                                        <div class="fw-bold">{{ $p->jadwalUji->nama_batch ?? '-' }}</div>
                                                        <small class="text-muted">
                                                            {{ $p->jadwalUji->tanggal_mulai ? \Carbon\Carbon::parse($p->jadwalUji->tanggal_mulai)->format('d/m/Y') : '-' }}
                                                            - {{ $p->jadwalUji->tanggal_selesai ? \Carbon\Carbon::parse($p->jadwalUji->tanggal_selesai)->format('d/m/Y') : '-' }}
                                                        </small>
                                                    </td>
                                                    <td>
                                                        @php
                                                            // Check if asesor has verified this pendaftaran
                                                            $isVerifiedByAsesor = $p->verifications()
                                                                ->where('type', 'asesor_verification')
                                                                ->where('status', 'verified')
                                                                ->exists();
                                                            // Check if admin has verified this pendaftaran
                                                            $isVerifiedByAdmin = $p->verifications()
                                                                ->where('type', 'admin_verification')
                                                                ->where('status', 'verified')
                                                                ->exists();
                                                        @endphp
                                                        @switch($p->status)
                                                            @case('draft')
                                                                <span class="badge bg-secondary">Draft</span>
                                                                @break
                                                            @case('pending')
                                                                <span class="badge bg-warning">Menunggu Verifikasi</span>
                                                                @break
                                                            @case('approved')
                                                                @if($isVerifiedByAsesor)
                                                                    <span class="badge bg-info">Terverifikasi Asesor</span>
                                                                @else
                                                                    <span class="badge bg-success">Disetujui</span>
                                                                @endif
                                                                @break
                                                            @case('in_progress')
                                                                @if($isVerifiedByAsesor)
                                                                    <span class="badge bg-success">Terverifikasi Asesor</span>
                                                                @else
                                                                    <span class="badge bg-info">Dalam Proses</span>
                                                                @endif
                                                                @break
                                                            @case('rejected')
                                                                <span class="badge bg-danger">Ditolak</span>
                                                                @break
                                                            @case('completed')
                                                                <span class="badge bg-primary">Selesai</span>
                                                                @break
                                                            @default
                                                                <span class="badge bg-secondary">{{ ucfirst($p->status) }}</span>
                                                        @endswitch
                                                    </td>
                                                    <td>
                                                        {{ $p->tanggal_pendaftaran ? \Carbon\Carbon::parse($p->tanggal_pendaftaran)->format('d/m/Y H:i') : '-' }}
                                                    </td>
                                                    <td>
                                                        <div class="btn-group" role="group">
                                                            <a href="{{ route('admin.pendaftaran.detail', $p->id) }}" class="btn btn-sm btn-outline-info">
                                                                <i class="fas fa-eye"></i> Detail
                                                            </a>
                                                            @if($p->status == 'pending')
                                                                <button type="button" class="btn btn-sm btn-success" onclick="openApprovalModal({{ $p->id }})" title="Setujui">
                                                                    <i class="fas fa-check"></i>
                                                                </button>
                                                                <button type="button" class="btn btn-sm btn-danger" onclick="rejectPendaftaran({{ $p->id }})" title="Tolak">
                                                                    <i class="fas fa-times"></i>
                                                                </button>
                                                            @endif
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Pagination -->
                                <div class="d-flex justify-content-center">
                                    {{ $pendaftaran->links() }}
                                </div>
                            @else
                                <div class="text-center py-5">
                                    <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">Belum ada pendaftaran</h5>
                                    <p class="text-muted">Tidak ada pendaftaran yang perlu diverifikasi.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Detail Modal -->
@foreach($pendaftaran as $p)
<div class="modal fade" id="detailModal{{ $p->id }}" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Pendaftaran - {{ $p->no_pendaftaran }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Informasi Pendaftaran</h6>
                        <table class="table table-sm">
                            <tr>
                                <td><strong>No. Pendaftaran:</strong></td>
                                <td>{{ $p->no_pendaftaran }}</td>
                            </tr>
                            <tr>
                                <td><strong>Status:</strong></td>
                                <td>
                                    @switch($p->status)
                                        @case('pending')
                                            <span class="badge bg-warning">Menunggu Verifikasi</span>
                                            @break
                                        @case('approved')
                                            <span class="badge bg-success">Disetujui</span>
                                            @break
                                        @case('rejected')
                                            <span class="badge bg-danger">Ditolak</span>
                                            @break
                                        @case('completed')
                                            <span class="badge bg-primary">Selesai</span>
                                            @break
                                        @default
                                            <span class="badge bg-secondary">{{ ucfirst($p->status) }}</span>
                                    @endswitch
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Tanggal Pendaftaran:</strong></td>
                                <td>{{ $p->tanggal_pendaftaran ? \Carbon\Carbon::parse($p->tanggal_pendaftaran)->format('d/m/Y H:i') : '-' }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6>Data Mahasiswa</h6>
                        <table class="table table-sm">
                            <tr>
                                <td><strong>Nama:</strong></td>
                                <td>{{ $p->user->nama_lengkap ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Email:</strong></td>
                                <td>{{ $p->user->email ?? '-' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                
                <div class="row mt-3">
                    <div class="col-md-6">
                        <h6>Skema Sertifikasi</h6>
                        <table class="table table-sm">
                            <tr>
                                <td><strong>Nama Skema:</strong></td>
                                <td>{{ $p->skemaSertifikasi->nama_skema ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Kode Skema:</strong></td>
                                <td>{{ $p->skemaSertifikasi->kode_skema ?? '-' }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6>Jadwal Uji</h6>
                        <table class="table table-sm">
                            <tr>
                                <td><strong>Nama Batch:</strong></td>
                                <td>{{ $p->jadwalUji->nama_batch ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Tanggal:</strong></td>
                                <td>
                                    {{ $p->jadwalUji->tanggal_mulai ? \Carbon\Carbon::parse($p->jadwalUji->tanggal_mulai)->format('d/m/Y') : '-' }}
                                    - {{ $p->jadwalUji->tanggal_selesai ? \Carbon\Carbon::parse($p->jadwalUji->tanggal_selesai)->format('d/m/Y') : '-' }}
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                @if($p->status == 'rejected' && $p->alasan_penolakan)
                    <div class="alert alert-warning mt-3">
                        <h6><i class="fas fa-exclamation-triangle me-2"></i>Alasan Penolakan:</h6>
                        <p class="mb-0">{{ $p->alasan_penolakan }}</p>
                    </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal{{ $p->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tolak Pendaftaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.pendaftaran.reject', $p->id) }}" method="POST">
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
@endforeach
@endsection

@section('scripts')
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
    if (!currentPendaftaranId) {
        console.error('Pendaftaran ID tidak valid');
        return;
    }
    
    // Disable button to prevent double click
    const confirmBtn = document.getElementById('confirmApprovalBtn');
    confirmBtn.disabled = true;
    confirmBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Memproses...';
    
    // Get admin signature from personalization
    fetch('/admin/personalization/get-signature')
        .then(response => {
            if (!response.ok) {
                return response.text().then(text => {
                    throw new Error(`HTTP ${response.status}: ${text}`);
                });
            }
            return response.json();
        })
        .then(signatureData => {
            const signature = signatureData.signature || null;
            
            // Send approval request with signature
            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            if (!csrfToken) {
                throw new Error('CSRF token not found');
            }
            
            return fetch(`/admin/pendaftaran/${currentPendaftaranId}/approved`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    signature_data: signature
                })
            });
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(data => {
                    throw new Error(data.message || 'Gagal menyetujui pendaftaran');
                }).catch(() => {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                });
            }
            return response.json();
        })
        .then(data => {
            // Close modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('approvalModal'));
            if (modal) {
                modal.hide();
            }
            // Reload page to show updated status
            location.reload();
        })
        .catch(error => {
            console.error('Error:', error);
            // Re-enable button
            confirmBtn.disabled = false;
            confirmBtn.innerHTML = '<i class="fas fa-check me-2"></i>Setujui Pendaftaran';
            // Show error in modal or reload page
            alert('Gagal menyetujui pendaftaran. Silakan coba lagi.');
        });
}

// Event listener for confirm button
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('confirmApprovalBtn').addEventListener('click', function() {
        approvePendaftaran();
    });
});

// Search functionality
document.getElementById('searchInput').addEventListener('keyup', function() {
    const searchTerm = this.value.toLowerCase();
    const table = document.getElementById('pendaftaranTable');
    const rows = table.getElementsByTagName('tr');
    
    for (let i = 1; i < rows.length; i++) {
        const row = rows[i];
        const text = row.textContent.toLowerCase();
        if (text.includes(searchTerm)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    }
});

// Status filter
document.getElementById('statusFilter').addEventListener('change', function() {
    const selectedStatus = this.value;
    const table = document.getElementById('pendaftaranTable');
    const rows = table.getElementsByTagName('tr');
    
    for (let i = 1; i < rows.length; i++) {
        const row = rows[i];
        const status = row.getAttribute('data-status');
        if (selectedStatus === '' || status === selectedStatus) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    }
});

// Note: approvePendaftaran() function is defined above and used with modal

function rejectPendaftaran(id) {
    if (confirm('Apakah Anda yakin ingin menolak pendaftaran ini?')) {
        fetch(`/admin/pendaftaran/${id}/rejected`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Gagal menolak pendaftaran');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Gagal menolak pendaftaran');
        });
    }
}
</script>
@endsection
