@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Riwayat Pendaftaran</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <div class="btn-group me-2">
                        <a href="{{ route('mahasiswa.pendaftaran.step1') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Pendaftaran Baru
                        </a>
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
                                    <h4 class="mb-0">{{ $totalPendaftaran }}</h4>
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
                                    <h4 class="mb-0">{{ $pendingPendaftaran }}</h4>
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
                                    <h4 class="mb-0">{{ $approvedPendaftaran }}</h4>
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
                                    <h4 class="mb-0">{{ $rejectedPendaftaran }}</h4>
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
                        <input type="text" class="form-control" id="searchInput" placeholder="Cari pendaftaran...">
                    </div>
                </div>
                <div class="col-md-3">
                    <select class="form-select" id="statusFilter">
                        <option value="">Semua Status</option>
                        <option value="pending">Pending</option>
                        <option value="approved">Disetujui</option>
                        <option value="rejected">Ditolak</option>
                        <option value="in_progress">Sedang Berlangsung</option>
                        <option value="completed">Selesai</option>
                        <option value="failed">Gagal</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-select" id="skemaFilter">
                        <option value="">Semua Skema</option>
                        @foreach($skemaOptions as $skema)
                            <option value="{{ $skema->id }}">{{ $skema->nama_skema }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Pendaftaran Table -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-file-alt me-2"></i>Riwayat Pendaftaran Saya</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover" id="pendaftaranTable">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>No. Pendaftaran</th>
                                    <th>Skema Sertifikasi</th>
                                    <th>Jadwal Uji</th>
                                    <th>Status</th>
                                    <th>Tanggal Pendaftaran</th>
                                    <th>Verifikasi</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pendaftaran as $index => $p)
                                    <tr>
                                        <td>{{ $pendaftaran->firstItem() + $index }}</td>
                                        <td>
                                            <strong>{{ $p->no_pendaftaran ?? '-' }}</strong>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">
                                                {{ $p->skemaSertifikasi->nama_skema ?? '-' }}
                                            </span>
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
                                            @switch($p->status)
                                                @case('pending')
                                                    <span class="badge bg-warning">Pending</span>
                                                    @break
                                                @case('approved')
                                                    <span class="badge bg-success">Disetujui</span>
                                                    @break
                                                @case('rejected')
                                                    <span class="badge bg-danger">Ditolak</span>
                                                    @break
                                                @case('in_progress')
                                                    <span class="badge bg-primary">Sedang Berlangsung</span>
                                                    @break
                                                @case('completed')
                                                    <span class="badge bg-success">Selesai</span>
                                                    @break
                                                @case('failed')
                                                    <span class="badge bg-danger">Gagal</span>
                                                    @break
                                                @default
                                                    <span class="badge bg-secondary">{{ ucfirst($p->status) }}</span>
                                            @endswitch
                                        </td>
                                        <td>
                                            {{ $p->tanggal_pendaftaran ? \Carbon\Carbon::parse($p->tanggal_pendaftaran)->format('d/m/Y H:i') : '-' }}
                                        </td>
                                        <td>
                                            <div class="d-flex flex-column">
                                                @if($p->verifications)
                                                    @foreach($p->verifications as $verification)
                                                        <div class="mb-1">
                                                            @if($verification->type === 'admin_verification')
                                                                <span class="badge {{ $verification->status === 'verified' ? 'bg-success' : 'bg-warning' }}">
                                                                    <i class="fas fa-user-shield me-1"></i>
                                                                    Admin: {{ ucfirst($verification->status) }}
                                                                </span>
                                                            @elseif($verification->type === 'asesor_verification')
                                                                <span class="badge {{ $verification->status === 'verified' ? 'bg-success' : 'bg-warning' }}">
                                                                    <i class="fas fa-user-check me-1"></i>
                                                                    Asesor: {{ ucfirst($verification->status) }}
                                                                </span>
                                                            @endif
                                                        </div>
                                                    @endforeach
                                                @else
                                                    <span class="text-muted">Belum ada verifikasi</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-sm btn-outline-info" onclick="viewPendaftaran({{ $p->id }})" title="Lihat Detail">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                @if($p->status === 'rejected')
                                                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="editPendaftaran({{ $p->id }})" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="fas fa-inbox fa-2x mb-2"></i>
                                                <p>Belum ada pendaftaran</p>
                                                <a href="{{ route('mahasiswa.pendaftaran.step1') }}" class="btn btn-primary">
                                                    <i class="fas fa-plus"></i> Buat Pendaftaran Baru
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($pendaftaran->hasPages())
                        <div class="d-flex justify-content-center mt-3">
                            {{ $pendaftaran->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- View Pendaftaran Modal -->
<div class="modal fade" id="viewPendaftaranModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Pendaftaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="pendaftaranDetail">
                <!-- Detail will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Search functionality
    const searchInput = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');
    const skemaFilter = document.getElementById('skemaFilter');
    const table = document.getElementById('pendaftaranTable');
    const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');

    function filterTable() {
        const searchTerm = searchInput.value.toLowerCase();
        const statusValue = statusFilter.value;
        const skemaValue = skemaFilter.value;

        for (let i = 0; i < rows.length; i++) {
            const row = rows[i];
            const text = row.textContent.toLowerCase();
            const statusCell = row.cells[4]; // Status column
            const skemaCell = row.cells[2]; // Skema column

            const matchesSearch = text.includes(searchTerm);
            const matchesStatus = !statusValue || statusCell.textContent.toLowerCase().includes(statusValue);
            const matchesSkema = !skemaValue || skemaCell.textContent.toLowerCase().includes(skemaValue);

            if (matchesSearch && matchesStatus && matchesSkema) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        }
    }

    searchInput.addEventListener('input', filterTable);
    statusFilter.addEventListener('change', filterTable);
    skemaFilter.addEventListener('change', filterTable);
});

function viewPendaftaran(id) {
    window.open(`/mahasiswa/pendaftaran/${id}/detail`, '_blank');
}

function editPendaftaran(id) {
    if (confirm('Apakah Anda yakin ingin mengedit pendaftaran ini?')) {
        window.location.href = `/mahasiswa/pendaftaran/${id}/edit`;
    }
}
</script>
@endsection
