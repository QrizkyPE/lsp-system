@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Penugasan Saya</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <div class="btn-group me-2">
                        <button type="button" class="btn btn-outline-primary" onclick="refreshData()">
                            <i class="fas fa-sync-alt"></i> Refresh
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

            <!-- Summary Cards -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4 class="mb-0">{{ $totalPenugasan }}</h4>
                                    <p class="mb-0">Total Penugasan</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-tasks fa-2x"></i>
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
                                    <h4 class="mb-0">{{ $pendingPenugasan }}</h4>
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
                                    <h4 class="mb-0">{{ $acceptedPenugasan }}</h4>
                                    <p class="mb-0">Diterima</p>
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
                                    <h4 class="mb-0">{{ $rejectedPenugasan }}</h4>
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

            <!-- Search and Filter -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                        <input type="text" class="form-control" id="searchInput" placeholder="Cari penugasan...">
                    </div>
                </div>
                {{-- <div class="col-md-3">
                    <select class="form-select" id="statusFilter">
                        <option value="">Semua Status</option>
                        <option value="pending">Pending</option>
                        <option value="accepted">Diterima</option>
                        <option value="rejected">Ditolak</option>
                    </select>
                </div> --}}
                <div class="col-md-3">
                    <select class="form-select" id="jenisFilter">
                        <option value="">Semua Jenis</option>
                        <option value="asesor">MA (Master Asesor)</option>
                        <option value="mapa">MAPA</option>
                        <option value="mkva">MKVA</option>
                    </select>
                </div>
            </div>

            <!-- Penugasan Table -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-user-check me-2"></i>Daftar Penugasan Saya</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover" id="penugasanTable">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>Jadwal Uji</th>
                                    <th>Skema Sertifikasi</th>
                                    <th>Jenis Penugasan</th>
                                    <!-- <th>Status</th> -->
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
                                            <div>
                                                <strong>{{ $p->jadwalUji->nama_batch ?? '-' }}</strong>
                                                <br>
                                                <small class="text-muted">
                                                    <i class="fas fa-calendar me-1"></i>
                                                    {{ $p->jadwalUji->tanggal_mulai ? \Carbon\Carbon::parse($p->jadwalUji->tanggal_mulai)->format('d/m/Y') : '-' }}
                                                </small>
                                                <br>
                                                <small class="text-muted">
                                                    <i class="fas fa-clock me-1"></i>
                                                    {{ $p->jadwalUji->jam_mulai ?? '-' }} - {{ $p->jadwalUji->jam_selesai ?? '-' }}
                                                </small>
                                                <br>
                                                <small class="text-muted">
                                                    <i class="fas fa-map-marker-alt me-1"></i>
                                                    {{ $p->jadwalUji->tuk->alamat ?? '-' }}
                                                </small>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">
                                                {{ $p->jadwalUji->skemaSertifikasi->nama_skema ?? '-' }}
                                            </span>
                                        </td>
                                        <td data-jenis="{{ $p->jenis_penugasan }}">
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
                                        {{-- <td>
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
                                        </td> --}}
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
                                                @if($p->status === 'pending')
                                                    <button type="button" class="btn btn-sm btn-success" onclick="acceptPenugasan({{ $p->id }})" title="Terima">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-danger" onclick="rejectPenugasan({{ $p->id }})" title="Tolak">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                @else
                                                    <button type="button" class="btn btn-sm btn-outline-info" onclick="viewPenugasan({{ $p->id }})" title="Lihat Detail">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    @if($p->status === 'accepted' && isset($p->has_soal) && !$p->has_soal)
                                                        <a href="{{ route('asesor.soal-upload.create', $p->jadwalUji->id) }}" class="btn btn-sm btn-warning" title="Upload Soal">
                                                            <i class="fas fa-exclamation-triangle"></i> Upload Soal
                                                        </a>
                                                    @endif
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-4">
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

<!-- View Penugasan Modal -->
<div class="modal fade" id="viewPenugasanModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Penugasan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="penugasanDetail">
                <!-- Detail will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- Accept/Reject Confirmation Modal -->
<div class="modal fade" id="confirmModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmModalTitle">Konfirmasi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="confirmModalBody">
                <!-- Confirmation message will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn" id="confirmButton">Konfirmasi</button>
            </div>
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
        const statusValue = statusFilter ? statusFilter.value : '';
        const jenisValue = jenisFilter.value;

        for (let i = 0; i < rows.length; i++) {
            const row = rows[i];
            const text = row.textContent.toLowerCase();
            const jenisCell = row.cells[3]; // Jenis column (index 3)
            
            // Get jenis penugasan from data attribute
            const jenisPenugasan = jenisCell.getAttribute('data-jenis') || '';
            
            const matchesSearch = text.includes(searchTerm);
            const matchesStatus = !statusValue || true; // Status filter is commented out
            const matchesJenis = !jenisValue || jenisPenugasan === jenisValue;

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
});

function refreshData() {
    location.reload();
}

function getJenisPenugasanLabel(jenis) {
    const labels = {
        'asesor': 'MA (Master Asesor)',
        'mapa': 'MAPA',
        'ma': 'MA',
        'mkva': 'MKVA'
    };
    return labels[jenis] || jenis;
}

function viewPenugasan(id) {
    fetch(`/asesor/penugasan/${id}`)
        .then(response => response.json())
        .then(data => {
            // Build mahasiswa list HTML
            let mahasiswaHtml = '';
            if (data.pendaftaran && data.pendaftaran.length > 0) {
                mahasiswaHtml = `
                    <div class="mt-3">
                        <h6><i class="fas fa-users me-2"></i>Mahasiswa yang akan diverifikasi</h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 50px;">No</th>
                                        <th>No. Pendaftaran</th>
                                        <th>Nama Mahasiswa</th>
                                        <th>Skema Sertifikasi</th>
                                    </tr>
                                </thead>
                                <tbody>
                `;
                data.pendaftaran.forEach((pendaftaran, index) => {
                    const namaMahasiswa = pendaftaran.user?.nama_lengkap || pendaftaran.user?.name || '-';
                    const skemaNama = pendaftaran.skema_sertifikasi?.nama_skema || pendaftaran.skemaSertifikasi?.nama_skema || '-';
                    mahasiswaHtml += `
                        <tr>
                            <td>${index + 1}</td>
                            <td><strong>${pendaftaran.no_pendaftaran || '-'}</strong></td>
                            <td>${namaMahasiswa}</td>
                            <td><span class="badge bg-info">${skemaNama}</span></td>
                        </tr>
                    `;
                });
                mahasiswaHtml += `
                                </tbody>
                            </table>
                        </div>
                    </div>
                `;
            } else {
                mahasiswaHtml = `
                    <div class="mt-3">
                        <h6><i class="fas fa-users me-2"></i>Mahasiswa yang akan Diverifikasi</h6>
                        <div class="alert alert-info mb-0">
                            <i class="fas fa-info-circle me-2"></i>Tidak ada mahasiswa yang ditugaskan untuk penugasan ini.
                        </div>
                    </div>
                `;
            }

            const detailHtml = `
                <div class="row">
                    <div class="col-md-6">
                        <h6><i class="fas fa-info-circle me-2"></i>Informasi Penugasan</h6>
                        <table class="table table-sm">
                            <tr>
                                <td><strong>Jadwal Uji:</strong></td>
                                <td>${data.jadwal_uji?.nama_batch || '-'}</td>
                            </tr>
                            <tr>
                                <td><strong>Skema Sertifikasi:</strong></td>
                                <td>${data.jadwal_uji?.skema_sertifikasi?.nama_skema || '-'}</td>
                            </tr>
                            <tr>
                                <td><strong>Jenis Penugasan:</strong></td>
                                <td><span class="badge bg-primary">${getJenisPenugasanLabel(data.jenis_penugasan)}</span></td>
                            </tr>
                            <tr>
                                <td><strong>Status:</strong></td>
                                <td><span class="badge bg-${data.status === 'accepted' ? 'success' : data.status === 'rejected' ? 'danger' : 'warning'}">${data.status === 'accepted' ? 'Diterima' : data.status === 'rejected' ? 'Ditolak' : 'Pending'}</span></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6><i class="fas fa-calendar-alt me-2"></i>Detail Jadwal</h6>
                        <table class="table table-sm">
                            <tr>
                                <td><strong>Tanggal Uji:</strong></td>
                                <td>${data.jadwal_uji?.tanggal_mulai || '-'}</td>
                            </tr>
                            <tr>
                                <td><strong>Waktu:</strong></td>
                                <td>${data.jadwal_uji?.jam_mulai || '-'} - ${data.jadwal_uji?.jam_selesai || '-'}</td>
                            </tr>
                            <tr>
                                <td><strong>Lokasi:</strong></td>
                                <td>${data.jadwal_uji?.tuk?.alamat || '-'}</td>
                            </tr>
                            <tr>
                                <td><strong>Kuota:</strong></td>
                                <td>${data.jadwal_uji?.kuota_terisi || 0} / ${data.jadwal_uji?.kuota_maksimal || 0}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                ${data.keterangan ? `
                    <div class="mt-3">
                        <h6><i class="fas fa-sticky-note me-2"></i>Keterangan</h6>
                        <p class="text-muted">${data.keterangan}</p>
                    </div>
                ` : ''}
                ${mahasiswaHtml}
            `;
            
            document.getElementById('penugasanDetail').innerHTML = detailHtml;
            new bootstrap.Modal(document.getElementById('viewPenugasanModal')).show();
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Gagal memuat detail penugasan');
        });
}

function acceptPenugasan(id) {
    showConfirmModal(
        'Terima Penugasan',
        'Apakah Anda yakin ingin menerima penugasan ini?',
        'btn-success',
        () => updatePenugasanStatus(id, 'accepted')
    );
}

function rejectPenugasan(id) {
    showConfirmModal(
        'Tolak Penugasan',
        'Apakah Anda yakin ingin menolak penugasan ini?',
        'btn-danger',
        () => updatePenugasanStatus(id, 'rejected')
    );
}

function showConfirmModal(title, message, buttonClass, callback) {
    document.getElementById('confirmModalTitle').textContent = title;
    document.getElementById('confirmModalBody').textContent = message;
    const confirmButton = document.getElementById('confirmButton');
    confirmButton.className = `btn ${buttonClass}`;
    confirmButton.textContent = title;
    
    confirmButton.onclick = () => {
        callback();
        bootstrap.Modal.getInstance(document.getElementById('confirmModal')).hide();
    };
    
    new bootstrap.Modal(document.getElementById('confirmModal')).show();
}

function updatePenugasanStatus(id, status) {
    fetch(`/asesor/penugasan/${id}/status`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ status: status })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Gagal mengupdate status penugasan');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Gagal mengupdate status penugasan');
    });
}
</script>
@endsection
