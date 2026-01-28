@extends('layouts.app')

@section('title', 'Laporan')
@section('page-title', 'Laporan')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">
                            <i class="fas fa-chart-bar me-2"></i>
                            @if(($type ?? 'rekaman') === 'laporan-asesmen')
                                Laporan Asesmen
                            @else
                                Laporan Rekaman Asesmen
                            @endif
                        </h4>
                        <div class="btn-group">
                            <a href="{{ route('admin.laporan', ['type' => 'rekaman']) }}" 
                               class="btn btn-sm btn-light {{ ($type ?? 'rekaman') === 'rekaman' ? 'active' : '' }}">
                                Laporan Rekaman Asesmen
                            </a>
                            <a href="{{ route('admin.laporan', ['type' => 'laporan-asesmen']) }}" 
                               class="btn btn-sm btn-light {{ ($type ?? 'rekaman') === 'laporan-asesmen' ? 'active' : '' }}">
                                Laporan Asesmen
                            </a>
                        </div>
                    </div>
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

                    @if(($type ?? 'rekaman') === 'rekaman')
                    <!-- Summary Cards Rekaman Asesmen -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h4 class="mb-0">{{ $rekamanAsesmen->total() ?? 0 }}</h4>
                                            <p class="mb-0">Total Rekaman</p>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-file-alt fa-2x"></i>
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
                                            <h4 class="mb-0">{{ $rekamanAsesmen->where('rekomendasi_hasil', 'kompeten')->count() }}</h4>
                                            <p class="mb-0">Kompeten</p>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-check-circle fa-2x"></i>
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
                                            <h4 class="mb-0">{{ $rekamanAsesmen->where('rekomendasi_hasil', 'belum_kompeten')->count() }}</h4>
                                            <p class="mb-0">Belum Kompeten</p>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-exclamation-triangle fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-info text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h4 class="mb-0">{{ $rekamanAsesmen->whereNull('rekomendasi_hasil')->count() }}</h4>
                                            <p class="mb-0">Belum Dinilai</p>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-clock fa-2x"></i>
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
                                <input type="text" class="form-control" id="searchInput" placeholder="Cari rekaman asesmen...">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" id="rekomendasiFilter">
                                <option value="">Semua Rekomendasi</option>
                                <option value="kompeten">Kompeten</option>
                                <option value="belum_kompeten">Belum Kompeten</option>
                                <option value="belum_dinilai">Belum Dinilai</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button type="button" class="btn btn-secondary w-100" onclick="resetFilters()">
                                <i class="fas fa-redo me-1"></i>Reset Filter
                            </button>
                        </div>
                    </div>

                    <!-- Rekaman Asesmen List -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-list me-2"></i>Daftar Rekaman Asesmen</h5>
                        </div>
                        <div class="card-body">
                            @if($rekamanAsesmen->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover" id="rekamanTable">
                                        <thead class="table-light">
                                            <tr>
                                                <th>No</th>
                                                <th>Judul</th>
                                                <th>No. Skema</th>
                                                <th>Nama Asesi</th>
                                                <th>Nama Asesor</th>
                                                <th>Tanggal Asesmen</th>
                                                <th>Waktu</th>
                                                <th>TUK</th>
                                                <th>Rekomendasi Hasil</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($rekamanAsesmen as $index => $rekaman)
                                                <tr>
                                                    <td>{{ $rekamanAsesmen->firstItem() + $index }}</td>
                                                    <td>
                                                        <strong>{{ $rekaman->judul }}</strong>
                                                    </td>
                                                    <td>{{ $rekaman->nomor_skema ?? '-' }}</td>
                                                    <td>
                                                        {{ $rekaman->nama_asesi }}
                                                        @if($rekaman->pendaftaran && $rekaman->pendaftaran->user)
                                                            <br><small class="text-muted">{{ $rekaman->pendaftaran->user->email ?? '' }}</small>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        {{ $rekaman->nama_asesor }}
                                                        @if($rekaman->asesor)
                                                            <br><small class="text-muted">{{ $rekaman->asesor->email ?? '' }}</small>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($rekaman->tanggal_mulai && $rekaman->tanggal_selesai)
                                                            {{ \Carbon\Carbon::parse($rekaman->tanggal_mulai)->format('d/m/Y') }}
                                                            @if($rekaman->tanggal_mulai != $rekaman->tanggal_selesai)
                                                                - {{ \Carbon\Carbon::parse($rekaman->tanggal_selesai)->format('d/m/Y') }}
                                                            @endif
                                                        @elseif($rekaman->tanggal_mulai)
                                                            {{ \Carbon\Carbon::parse($rekaman->tanggal_mulai)->format('d/m/Y') }}
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($rekaman->waktu_mulai && $rekaman->waktu_selesai)
                                                            {{ $rekaman->waktu_mulai }} - {{ $rekaman->waktu_selesai }}
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($rekaman->tuk)
                                                            @if($rekaman->tuk == 'sewaktu')
                                                                <span class="badge bg-info">Sewaktu</span>
                                                            @elseif($rekaman->tuk == 'tempat_kerja')
                                                                <span class="badge bg-primary">Tempat Kerja</span>
                                                            @elseif($rekaman->tuk == 'mandiri')
                                                                <span class="badge bg-secondary">Mandiri</span>
                                                            @else
                                                                <span class="badge bg-secondary">{{ ucfirst($rekaman->tuk) }}</span>
                                                            @endif
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($rekaman->rekomendasi_hasil)
                                                            @if($rekaman->rekomendasi_hasil == 'kompeten')
                                                                <span class="badge bg-success">Kompeten</span>
                                                            @else
                                                                <span class="badge bg-warning">Belum Kompeten</span>
                                                            @endif
                                                        @else
                                                            <span class="badge bg-secondary">Belum Dinilai</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <div class="btn-group" role="group">
                                                            <a href="{{ route('admin.laporan.rekaman-asesmen.show', $rekaman->id) }}" class="btn btn-sm btn-outline-info" target="_blank" title="Lihat Detail">
                                                                <i class="fas fa-eye"></i> Detail
                                                            </a>
                                                            <a href="{{ route('admin.laporan.rekaman-asesmen.pdf', $rekaman->id) }}" class="btn btn-sm btn-outline-danger" title="Download PDF" target="_blank">
                                                                <i class="fas fa-file-pdf"></i> PDF
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Pagination -->
                                <div class="d-flex justify-content-center mt-3">
                                    {{ $rekamanAsesmen->links() }}
                                </div>
                            @else
                                <div class="text-center py-5">
                                    <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">Belum ada rekaman asesmen</h5>
                                    <p class="text-muted">Tidak ada rekaman asesmen yang tersimpan di sistem.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                    @else
                    <!-- Laporan Asesmen List -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-list me-2"></i>Daftar Laporan Asesmen</h5>
                        </div>
                        <div class="card-body">
                            @if($laporanAsesmen && $laporanAsesmen->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th>No</th>
                                                <th>Judul Skema</th>
                                                <th>No. Skema</th>
                                                <th>TUK</th>
                                                <th>Nama Asesor</th>
                                                <th>Tanggal</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($laporanAsesmen as $index => $laporan)
                                                @php
                                                    $skema = $laporan->jadwalUji->skemaSertifikasi ?? null;
                                                @endphp
                                                <tr>
                                                    <td>{{ $laporanAsesmen->firstItem() + $index }}</td>
                                                    <td>{{ $skema->nama_skema ?? '-' }}</td>
                                                    <td>{{ $skema->kode_skema ?? '-' }}</td>
                                                    <td>{{ $laporan->jadwalUji->tuk->nama_tuk ?? '-' }}</td>
                                                    <td>{{ $laporan->asesor->nama_lengkap ?? '-' }}</td>
                                                    <td>
                                                        {{ $laporan->tanggal ? \Carbon\Carbon::parse($laporan->tanggal)->format('d/m/Y') : '-' }}
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('admin.laporan-asesmen.pdf', $laporan->id) }}"
                                                           class="btn btn-sm btn-outline-danger" target="_blank">
                                                            <i class="fas fa-file-pdf"></i> PDF
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="d-flex justify-content-center mt-3">
                                    {{ $laporanAsesmen->links() }}
                                </div>
                            @else
                                <div class="text-center py-5">
                                    <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">Belum ada laporan asesmen</h5>
                                    <p class="text-muted">Laporan asesmen akan muncul di sini setelah dikirim oleh asesor.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const rekomendasiFilter = document.getElementById('rekomendasiFilter');
    const table = document.getElementById('rekamanTable');
    
    if (table) {
        const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');

        function filterTable() {
            const searchTerm = searchInput.value.toLowerCase();
            const rekomendasiValue = rekomendasiFilter.value;

            for (let i = 0; i < rows.length; i++) {
                const row = rows[i];
                const text = row.textContent.toLowerCase();
                const rekomendasiCell = row.cells[8]; // Rekomendasi column

                const matchesSearch = text.includes(searchTerm);
                let matchesRekomendasi = true;

                if (rekomendasiValue) {
                    if (rekomendasiValue === 'belum_dinilai') {
                        matchesRekomendasi = rekomendasiCell.textContent.toLowerCase().includes('belum dinilai');
                    } else {
                        matchesRekomendasi = rekomendasiCell.textContent.toLowerCase().includes(rekomendasiValue);
                    }
                }

                if (matchesSearch && matchesRekomendasi) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            }
        }

        searchInput.addEventListener('input', filterTable);
        rekomendasiFilter.addEventListener('change', filterTable);
    }
});

function resetFilters() {
    document.getElementById('searchInput').value = '';
    document.getElementById('rekomendasiFilter').value = '';
    
    const table = document.getElementById('rekamanTable');
    if (table) {
        const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
        for (let i = 0; i < rows.length; i++) {
            rows[i].style.display = '';
        }
    }
}
</script>
@endsection

