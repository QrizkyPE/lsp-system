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
                                            <h4 class="mb-0">{{ $totalAsesmen ?? 0 }}</h4>
                                            <p class="mb-0">Total Asesmen</p>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-clipboard-check fa-2x"></i>
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
                                            <h4 class="mb-0">{{ $pendingAsesmen ?? 0 }}</h4>
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
                                            <h4 class="mb-0">{{ $verifiedAsesmen ?? 0 }}</h4>
                                            <p class="mb-0">Terverifikasi</p>
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
                                            <h4 class="mb-0">{{ $rejectedAsesmen ?? 0 }}</h4>
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
                                <option value="in_progress">Dalam Proses</option>
                                <option value="persetujuan_submitted">Persetujuan Dikirim</option>
                                <option value="persetujuan_confirmed">Persetujuan Dikonfirmasi</option>
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
                                                        @if($p->asesmen_data)
                                                            <br><small class="text-success"><i class="fas fa-check"></i> Asesmen Mandiri Tersedia</small>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <div class="fw-bold">
                                                            @if($p->jadwalUji)
                                                                {{ $p->jadwalUji->nama_batch }}
                                                            @else
                                                                <span class="text-muted">-</span>
                                                            @endif
                                                        </div>
                                                        <small class="text-muted">
                                                            @if($p->jadwalUji)
                                                                {{ $p->jadwalUji->tanggal_mulai ? \Carbon\Carbon::parse($p->jadwalUji->tanggal_mulai)->format('d/m/Y') : '-' }}
                                                                - {{ $p->jadwalUji->tanggal_selesai ? \Carbon\Carbon::parse($p->jadwalUji->tanggal_selesai)->format('d/m/Y') : '-' }}
                                                            @else
                                                                <span class="text-muted">-</span>
                                                            @endif
                                                        </small>
                                                    </td>
                                                    <td>
                                                        @php
                                                            // Check if asesor has verified this pendaftaran
                                                            $isVerifiedByAsesor = $p->verifications()
                                                                ->where('type', 'asesor_verification')
                                                                ->where('status', 'verified')
                                                                ->exists();
                                                        @endphp
                                                        @switch($p->status)
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
                                                                    <span class="badge bg-success">Terverifikasi</span>
                                                                @else
                                                                    <span class="badge bg-info">Dalam Proses</span>
                                                                @endif
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
                                                                <span class="badge bg-secondary">{{ ucfirst($p->status) }}</span>
                                                        @endswitch
                                                    </td>
                                                    <td>
                                                        {{ $p->tanggal_pendaftaran ? \Carbon\Carbon::parse($p->tanggal_pendaftaran)->format('d/m/Y H:i') : '-' }}
                                                    </td>
                                                    <td>
                                                        <div class="btn-group" role="group">
                                                            <a href="{{ route('asesor.asesmen.pendaftaran.detail', $p->id) }}" 
                                                               class="btn btn-sm btn-outline-info" 
                                                               target="_blank"
                                                               title="Lihat Detail Asesmen Mandiri">
                                                                <i class="fas fa-eye"></i> Detail
                                                            </a>
                                                            @if($p->asesmen_data)
                                                                <button type="button" class="btn btn-sm btn-success" 
                                                                        data-bs-toggle="modal" 
                                                                        data-bs-target="#asesmenModal{{ $p->id }}">
                                                                    <i class="fas fa-clipboard-check"></i> Asesmen
                                                                </button>
                                                            @else
                                                                <span class="badge bg-info">Belum ada asesmen data</span>
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
                                <td>
                                    @if($p->jadwalUji)
                                        {{ $p->jadwalUji->nama_batch }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Tanggal:</strong></td>
                                <td>
                                    @if($p->jadwalUji)
                                        {{ $p->jadwalUji->tanggal_mulai ? \Carbon\Carbon::parse($p->jadwalUji->tanggal_mulai)->format('d/m/Y') : '-' }}
                                        - {{ $p->jadwalUji->tanggal_selesai ? \Carbon\Carbon::parse($p->jadwalUji->tanggal_selesai)->format('d/m/Y') : '-' }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
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

<!-- Asesmen Modal -->
<div class="modal fade" id="asesmenModal{{ $p->id }}" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Asesmen - {{ $p->no_pendaftaran }}</h5>
                <div class="d-flex align-items-center">
                    @if($p->status === 'in_progress')
                        <span class="badge bg-warning me-2">Sedang Berlangsung</span>
                    @elseif($p->status === 'completed')
                        <span class="badge bg-success me-2">Selesai</span>
                    @elseif($p->status === 'approved')
                        <span class="badge bg-primary me-2">Siap Diverifikasi</span>
                    @elseif($p->status === 'pending')
                        <span class="badge bg-secondary me-2">Menunggu Persetujuan Admin</span>
                    @else
                        <span class="badge bg-secondary me-2">Belum Diverifikasi</span>
                    @endif
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
            </div>
            <div class="modal-body">
                @if($p->status === 'approved')
                    <div class="alert alert-success">
                        <h6><i class="fas fa-check-circle me-2"></i>Asesmen Siap Diverifikasi</h6>
                        <p class="mb-0">Pendaftaran telah disetujui admin. Anda dapat melakukan verifikasi asesmen mandiri.</p>
                    </div>
                @elseif($p->status === 'pending')
                    <div class="alert alert-warning">
                        <h6><i class="fas fa-clock me-2"></i>Menunggu Persetujuan Admin</h6>
                        <p class="mb-0">Pendaftaran belum disetujui admin. Verifikasi asesmen akan tersedia setelah admin menyetujui pendaftaran.</p>
                    </div>
                @elseif($p->status === 'in_progress')
                    <div class="alert alert-info">
                        <h6><i class="fas fa-info-circle me-2"></i>Asesmen Sedang Berlangsung</h6>
                        <p class="mb-0">Asesmen telah diverifikasi dan sedang dalam proses persetujuan admin.</p>
                    </div>
                @elseif($p->status === 'completed')
                    <div class="alert alert-success">
                        <h6><i class="fas fa-check-circle me-2"></i>Asesmen Selesai</h6>
                        <p class="mb-0">Asesmen telah selesai dan disetujui admin.</p>
                    </div>
                @else
                    <div class="alert alert-info">
                        <h6><i class="fas fa-info-circle me-2"></i>Informasi Asesmen</h6>
                        <p class="mb-0">Halaman ini akan menampilkan data asesmen mandiri yang perlu diverifikasi oleh asesor.</p>
                    </div>
                @endif
                
                <!-- Asesmen Data Display -->
                @if($p->asesmen_data)
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">Data Asesmen Mandiri</h6>
                        </div>
                        <div class="card-body">
                            <!-- Informasi Pendaftaran -->
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <h6>Informasi Pendaftaran</h6>
                                    <table class="table table-sm">
                                        <tr>
                                            <td><strong>No. Pendaftaran:</strong></td>
                                            <td>{{ $p->no_pendaftaran }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Nama Mahasiswa:</strong></td>
                                            <td>{{ $p->user->nama_lengkap ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Email:</strong></td>
                                            <td>{{ $p->user->email ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Status:</strong></td>
                                            <td>
                                                @switch($p->status)
                                                    @case('pending')
                                                        <span class="badge bg-warning">Pending</span>
                                                        @break
                                                    @case('approved')
                                                        <span class="badge bg-success">Approved</span>
                                                        @break
                                                    @case('rejected')
                                                        <span class="badge bg-danger">Rejected</span>
                                                        @break
                                                    @default
                                                        <span class="badge bg-secondary">{{ ucfirst($p->status) }}</span>
                                                @endswitch
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <h6>Data Sertifikasi</h6>
                                    <table class="table table-sm">
                                        <tr>
                                            <td><strong>Skema Sertifikasi:</strong></td>
                                            <td>{{ $p->skemaSertifikasi->nama_skema ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Jadwal Uji:</strong></td>
                                            <td>
                                                @if($p->jadwalUji)
                                                    {{ $p->jadwalUji->nama_batch }}
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Tanggal Pendaftaran:</strong></td>
                                            <td>{{ $p->tanggal_pendaftaran ? \Carbon\Carbon::parse($p->tanggal_pendaftaran)->format('d/m/Y H:i') : '-' }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            <!-- Hasil Asesmen Mandiri (Structured View) -->
                            <div class="mb-4">
                                <h6>Hasil Asesmen Mandiri</h6>
                                @php
                                    $asesmenData = is_string($p->asesmen_data) ? json_decode($p->asesmen_data, true) : $p->asesmen_data;
                                    // Reset global counter for each pendaftaran
                                    static $globalKriteriaCounter = 0;
                                    $globalKriteriaCounter = 0;
                                @endphp
                                
                                @if(isset($p->unitKompetensiJudul) && $p->unitKompetensiJudul->count() > 0)
                                    @foreach($p->unitKompetensiJudul as $unit)
                                        <div class="card mb-3">
                                            <div class="card-header bg-primary text-white">
                                                <h6 class="mb-0">
                                                    <i class="fas fa-cube"></i> {{ $unit->kode_unit }} - {{ $unit->judul_unit }}
                                                </h6>
                                                <small>{{ $unit->standar_kompetensi_kerja }}</small>
                                            </div>
                                            <div class="card-body">
                                                <!-- Elemen untuk unit ini -->
                                                @php
                                                    $elemenForUnit = $p->elemenJudul->where('kode_unit', $unit->kode_unit);
                                                @endphp
                                                
                                                @if($elemenForUnit->count() > 0)
                                                    @foreach($elemenForUnit as $elemen)
                                                        <div class="mb-3">
                                                            <h6 class="text-info">
                                                                <i class="fas fa-list"></i> {{ $elemen->kode_elemen }} - {{ $elemen->nama_elemen }}
                                                            </h6>
                                                            @if($elemen->deskripsi)
                                                                <p class="text-muted small">{{ $elemen->deskripsi }}</p>
                                                            @endif
                                                            
                                                            <!-- Kriteria untuk elemen ini -->
                                                            @php
                                                                $kriteriaForElemen = $p->kriteriaJudul->where('kode_elemen', $elemen->kode_elemen);
                                                            @endphp
                                                            
                                                            @if($kriteriaForElemen->count() > 0)
                                                                <div class="table-responsive">
                                                                    <table class="table table-sm table-bordered">
                                                                        <thead class="table-light">
                                                                            <tr>
                                                                                <th>No. Kriteria</th>
                                                                                <th>Deskripsi Kriteria</th>
                                                                                {{-- <th>Jenis Bukti</th>
                                                                                <th>Metode Asesmen</th>
                                                                                <th>Perangkat Asesmen</th> --}}
                                                                                <th>Pilihan Mahasiswa</th>
                                                                                <th>Bukti yang relevan</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            @foreach($kriteriaForElemen as $index => $kriteria)
                                                                                @php
                                                                                    $kriteriaKey = $kriteria->nomor_kriteria;
                                                                                    $status = null;
                                                                                    $detail = '';
                                                                                    
                                                                                    // Map kriteriaKey to asesmenData key based on sequential order
                                                                                    // Since asesmenData uses keys "1", "2", "3", etc., we need to map based on order
                                                                                    // Use a global counter that resets for each pendaftaran
                                                                                    static $globalKriteriaCounter = 0;
                                                                                    $globalKriteriaCounter++;
                                                                                    $mappedKey = (string)$globalKriteriaCounter;
                                                                                    
                                                                                    // Debug: Check if asesmenData exists and has the mapped key
                                                                                    if(isset($asesmenData[$mappedKey])) {
                                                                                        // Handle both boolean and string formats
                                                                                        $kompeten = $asesmenData[$mappedKey]['kompeten'] ?? false;
                                                                                        $belumKompeten = $asesmenData[$mappedKey]['belum_kompeten'] ?? false;
                                                                                        
                                                                                        // Check for string "1" or boolean true
                                                                                        if(($kompeten === "1" || $kompeten === true || $kompeten === 1) && !$belumKompeten) {
                                                                                            $status = 'kompeten';
                                                                                            $detail = 'Mahasiswa menilai diri kompeten';
                                                                                        } elseif(($belumKompeten === "1" || $belumKompeten === true || $belumKompeten === 1) && !$kompeten) {
                                                                                            $status = 'belum_kompeten';
                                                                                            $detail = 'Mahasiswa menilai diri belum kompeten';
                                                                                        }
                                                                                    } else {
                                                                                        // Debug: Log when data is not found
                                                                                        // This will help identify if the issue is with data structure
                                                                                    }
                                                                                @endphp
                                                                                <tr>
                                                                                    <td>{{ $kriteria->nomor_kriteria }}</td>
                                                                                    <td>{{ $kriteria->deskripsi_kriteria }}</td>
                                                                                    {{-- <td>{{ $kriteria->jenis_bukti ?? '-' }}</td>
                                                                                    <td>{{ $kriteria->metode_asesmen ?? '-' }}</td>
                                                                                    <td>{{ $kriteria->perangkat_asesmen ?? '-' }}</td> --}}
                                                                                    <td>
                                                                                        @if($status === 'kompeten')
                                                                                            <div class="form-check form-check-inline">
                                                                                                <input class="form-check-input" type="checkbox" checked disabled>
                                                                                                <label class="form-check-label text-success">
                                                                                                    <i class="fas fa-check"></i> Kompeten
                                                                                                </label>
                                                                                            </div>
                                                                                        @elseif($status === 'belum_kompeten')
                                                                                            <div class="form-check form-check-inline">
                                                                                                <input class="form-check-input" type="checkbox" checked disabled>
                                                                                                <label class="form-check-label text-danger">
                                                                                                    <i class="fas fa-times"></i> Belum Kompeten
                                                                                                </label>
                                                                                            </div>
                                                                                        @else
                                                                                            <div class="form-check form-check-inline">
                                                                                                <input class="form-check-input" type="checkbox" disabled>
                                                                                                <label class="form-check-label text-muted">
                                                                                                    <i class="fas fa-question"></i> Belum Dipilih
                                                                                                </label>
                                                                                            </div>
                                                                                        @endif
                                                                                    </td>
                                                                                    <td>
                                                                                        @if($p->sertifikasi_data)
                                                                                            @php
                                                                                                $sertifikasiData = is_string($p->sertifikasi_data) ? json_decode($p->sertifikasi_data, true) : $p->sertifikasi_data;
                                                                                            @endphp
                                                                                            @if(isset($sertifikasiData['bukti_files']) && count($sertifikasiData['bukti_files']) > 0)
                                                                                                <div class="d-flex flex-wrap gap-1">
                                                                                                    @foreach($sertifikasiData['bukti_files'] as $index => $bukti)
                                                                                                        @if(isset($bukti['filename']))
                                                                                                            <a href="/storage/bukti_persyaratan/{{ $bukti['filename'] }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                                                                                <i class="fas fa-download"></i> {{ $bukti['original_name'] ?? 'File ' . ($index + 1) }}
                                                                                                            </a>
                                                                                                        @endif
                                                                                                    @endforeach
                                                                                                </div>
                                                                                            @else
                                                                                                <span class="text-muted">Tidak ada bukti</span>
                                                                                            @endif
                                                                                        @else
                                                                                            <span class="text-muted">Tidak ada bukti</span>
                                                                                        @endif
                                                                                    </td>
                                                                                </tr>
                                                                            @endforeach
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            @else
                                                                <div class="alert alert-info">
                                                                    <i class="fas fa-info-circle"></i> Tidak ada kriteria untuk elemen ini.
                                                                </div>
                                                            @endif
                                                        </div>
                                                    @endforeach
                                                @else
                                                    <div class="alert alert-warning">
                                                        <i class="fas fa-exclamation-triangle"></i> Tidak ada elemen untuk unit kompetensi ini.
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                    
                                    <!-- Summary Asesmen -->
                                    @if($asesmenData && is_array($asesmenData))
                                        @php
                                            $totalKriteria = 0;
                                            $kompetenCount = 0;
                                            $belumKompetenCount = 0;
                                            
                                            foreach($asesmenData as $key => $value) {
                                                if($key !== 'signature_data') {
                                                    $totalKriteria++;
                                                    $kompeten = $value['kompeten'] ?? false;
                                                    $belumKompeten = $value['belum_kompeten'] ?? false;
                                                    
                                                    // Check for string "1" or boolean true
                                                    if(($kompeten === "1" || $kompeten === true || $kompeten === 1) && !$belumKompeten) {
                                                        $kompetenCount++;
                                                    } elseif(($belumKompeten === "1" || $belumKompeten === true || $belumKompeten === 1) && !$kompeten) {
                                                        $belumKompetenCount++;
                                                    }
                                                }
                                            }
                                        @endphp
                                        
                                        <div class="row mt-3">
                                            <div class="col-md-4">
                                                <div class="card bg-light">
                                                    <div class="card-body text-center">
                                                        <h5 class="card-title text-primary">{{ $totalKriteria }}</h5>
                                                        <p class="card-text">Total Kriteria</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="card bg-success text-white">
                                                    <div class="card-body text-center">
                                                        <h5 class="card-title">{{ $kompetenCount }}</h5>
                                                        <p class="card-text">Kompeten</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="card bg-danger text-white">
                                                    <div class="card-body text-center">
                                                        <h5 class="card-title">{{ $belumKompetenCount }}</h5>
                                                        <p class="card-text">Belum Kompeten</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @else
                                    <div class="alert alert-warning">
                                        <i class="fas fa-exclamation-triangle"></i> Data unit kompetensi tidak tersedia.
                                    </div>
                                @endif
                            </div>

                            
                            <!-- Data Profil Mahasiswa -->
                            @if($p->profil_data)
                                @php
                                    $profilData = is_string($p->profil_data) ? json_decode($p->profil_data, true) : $p->profil_data;
                                @endphp
                                <div class="mb-4">
                                    <h6>Data Profil Mahasiswa</h6>
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
                                            <h6>Data Pekerjaan Sekarang</h6>
                                            <table class="table table-sm">
                                                <tr>
                                                    <td><strong>Pekerjaan:</strong></td>
                                                    <td>{{ $profilData['pekerjaan'] ?? '-' }}</td>
                                                </tr>
                                                @if(isset($profilData['pekerjaan']) && $profilData['pekerjaan'] !== 'Belum/Tidak Bekerja')
                                                    <tr>
                                                        <td><strong>Nama Institusi/Perusahaan:</strong></td>
                                                        <td>{{ $profilData['nama_institusi'] ?? '-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Jabatan:</strong></td>
                                                        <td>{{ $profilData['jabatan'] ?? '-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Alamat Lembaga/Perusahaan:</strong></td>
                                                        <td>{{ $profilData['alamat_lembaga'] ?? '-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Kode POS Perusahaan:</strong></td>
                                                        <td>{{ $profilData['kode_pos_lembaga'] ?? '-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>No. Telp:</strong></td>
                                                        <td>{{ $profilData['no_telp_lembaga'] ?? '-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>No. Fax:</strong></td>
                                                        <td>{{ $profilData['no_fax_lembaga'] ?? '-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Email:</strong></td>
                                                        <td>{{ $profilData['email_lembaga'] ?? '-' }}</td>
                                                    </tr>
                                                @endif
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Data Sertifikasi -->
                            @if($p->sertifikasi_data)
                                @php
                                    $sertifikasiData = is_string($p->sertifikasi_data) ? json_decode($p->sertifikasi_data, true) : $p->sertifikasi_data;
                                @endphp
                                <div class="mb-4">
                                    <h6>Data Sertifikasi</h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <table class="table table-sm">
                                                <tr>
                                                    <td><strong>Skema:</strong></td>
                                                    <td>{{ $sertifikasiData['skema'] ?? '-' }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Judul:</strong></td>
                                                    <td>{{ $sertifikasiData['judul'] ?? '-' }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Tujuan Asesmen:</strong></td>
                                                    <td>{{ $sertifikasiData['tujuan_asesmen'] ?? '-' }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                        <div class="col-md-6">
                                            <table class="table table-sm">
                                                <tr>
                                                    <td><strong>Bukti Persyaratan:</strong></td>
                                                    <td>
                                                        @if(isset($sertifikasiData['bukti_files']) && count($sertifikasiData['bukti_files']) > 0)
                                                            <span class="badge bg-success">{{ count($sertifikasiData['bukti_files']) }} file</span>
                                                        @else
                                                            <span class="badge bg-secondary">Tidak ada</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Bukti Administratif:</strong></td>
                                                    <td>
                                                        @if(isset($sertifikasiData['bukti_admin_files']) && count($sertifikasiData['bukti_admin_files']) > 0)
                                                            <span class="badge bg-success">{{ count($sertifikasiData['bukti_admin_files']) }} file</span>
                                                        @else
                                                            <span class="badge bg-secondary">Tidak ada</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Tanda Tangan Asesmen Mandiri -->
                            @if(isset($asesmenData['signature_data']) && $asesmenData['signature_data'])
                                <div class="mt-3">
                                    <h6>Tanda Tangan Asesmen Mandiri:</h6>
                                    <div class="border p-3 text-center">
                                        <img src="{{ $asesmenData['signature_data'] }}" alt="Tanda Tangan Asesmen" style="max-width: 300px; max-height: 150px;">
                                        <div class="mt-2">
                                            <small class="text-muted">
                                                <i class="fas fa-info-circle"></i> Tanda tangan digital mahasiswa untuk asesmen mandiri
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @else
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>Data asesmen mandiri belum tersedia.
                    </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                @if($p->status === 'in_progress' || $p->status === 'completed')
                    <button type="button" class="btn btn-success" disabled>
                        <i class="fas fa-check"></i> Sudah Diverifikasi
                    </button>
                @elseif($p->status === 'approved')
                    <button type="button" class="btn btn-success" onclick="openVerificationModal({{ $p->id }})">
                        <i class="fas fa-check"></i> Verifikasi Asesmen
                    </button>
                @else
                    <button type="button" class="btn btn-secondary" disabled>
                        <i class="fas fa-clock"></i> Menunggu Persetujuan Admin
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>
@endforeach

<!-- Modal Verifikasi Asesmen -->
@foreach($pendaftaran as $p)
<div class="modal fade" id="verifikasiModal{{ $p->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-signature me-2"></i>Konfirmasi Verifikasi Asesmen
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Konfirmasi Verifikasi</strong><br>
                    Silakan pilih bukti yang dikumpulkan dan berikan tanda tangan untuk memverifikasi asesmen mandiri ini.
                </div>
                
                
                
                <!-- Bukti yang dikumpulkan -->
                <div class="mb-4">
                    <h6><strong>Bukti yang dikumpulkan:</strong></h6>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="bukti[]" value="Hasil Verifikasi Portofolio" id="portofolio{{ $p->id }}">
                                <label class="form-check-label" for="portofolio{{ $p->id }}">
                                    Hasil Verifikasi Portofolio
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="bukti[]" value="Hasil Reviu Produk" id="reviu{{ $p->id }}">
                                <label class="form-check-label" for="reviu{{ $p->id }}">
                                    Hasil Reviu Produk
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="bukti[]" value="Hasil Observasi Langsung" id="observasi{{ $p->id }}">
                                <label class="form-check-label" for="observasi{{ $p->id }}">
                                    Hasil Observasi Langsung
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="bukti[]" value="Hasil Kegiatan Terstruktur" id="kegiatan{{ $p->id }}">
                                <label class="form-check-label" for="kegiatan{{ $p->id }}">
                                    Hasil Kegiatan Terstruktur
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="bukti[]" value="Hasil Pertanyaan Lisan" id="lisan{{ $p->id }}">
                                <label class="form-check-label" for="lisan{{ $p->id }}">
                                    Hasil Pertanyaan Lisan
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="bukti[]" value="Hasil Pertanyaan Tertulis" id="tertulis{{ $p->id }}">
                                <label class="form-check-label" for="tertulis{{ $p->id }}">
                                    Hasil Pertanyaan Tertulis
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="bukti[]" value="Hasil Pertanyaan Wawancara" id="wawancara{{ $p->id }}">
                                <label class="form-check-label" for="wawancara{{ $p->id }}">
                                    Hasil Pertanyaan Wawancara
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="bukti[]" value="Lainnya" id="lainnya{{ $p->id }}">
                                <label class="form-check-label" for="lainnya{{ $p->id }}">
                                    Lainnya
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Persetujuan asesmen dan kerahasiaan -->
                <div class="mb-4">
                    <h6><strong>Persetujuan asesmen dan kerahasiaan</strong></h6>
                    <div class="alert alert-primary">
                        <strong>Asesor:</strong><br>
                        Menyatakan tidak akan membuka hasil pekerjaan yang saya peroleh karena penugasan saya sebagai Asesor dalam pekerjaan Asesmen kepada siapapun atau organisasi apapun selain kepada pihak yang berwenang sehubungan dengan kewajiban saya sebagai Asesor yang ditugaskan oleh LSP.
                    </div>
                </div>
                <div class="text-center">
                    <h6 class="mb-3">Tanda Tangan Asesor</h6>
                    <div id="signaturePreview{{ $p->id }}" class="signature-preview">
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
                            Pastikan bukti yang dikumpulkan sudah dipilih dan tanda tangan sudah benar sebelum melanjutkan.
                        </small>
                    </p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Batal
                </button>
                <button type="button" class="btn btn-danger" onclick="rejectAsesmen({{ $p->id }})">
                    <i class="fas fa-times me-2"></i>Tolak
                </button>
                <button type="button" class="btn btn-success" id="confirmVerificationBtn{{ $p->id }}" disabled>
                    <i class="fas fa-check me-2"></i>Verifikasi Asesmen
                </button>
            </div>
        </div>
    </div>
</div>
@endforeach
@endsection

@section('scripts')
<script>
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

// Signature from personalization functionality
let signatureData = {};

// Load signature from personalization
function loadSignatureFromPersonalization(id) {
    // Fetch signature from personalization endpoint
    fetch('/asesor/personalization/get-signature')
        .then(response => response.json())
        .then(data => {
            const preview = document.getElementById(`signaturePreview${id}`);
            if (preview && data.signature) {
                preview.innerHTML = `
                    <div class="text-center">
                        <img src="${data.signature}" alt="Tanda Tangan Asesor" 
                             style="max-width: 300px; border: 1px solid #ddd; border-radius: 4px; background: white;">
                        <p class="mt-2 text-success">
                            <i class="fas fa-check-circle"></i> Tanda tangan dari personalisasi
                        </p>
                    </div>
                `;
                
                // Store signature data for verification
                signatureData[id] = data.signature;
                
                // Enable confirm button
                const confirmBtn = document.getElementById(`confirmVerificationBtn${id}`);
                if (confirmBtn) {
                    confirmBtn.disabled = false;
                    
                    // Ensure event listener is still attached
                    if (!confirmBtn.hasAttribute('data-listener-added')) {
                        confirmBtn.addEventListener('click', function(e) {
                            e.preventDefault();
                            verifyAsesmenWithSignature(id);
                        });
                        confirmBtn.setAttribute('data-listener-added', 'true');
                    }
                } else {
                }
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
            const preview = document.getElementById(`signaturePreview${id}`);
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

function verifyAsesmenWithSignature(id) {
    if (!signatureData[id]) {
        alert('Silakan buat dan simpan tanda tangan terlebih dahulu!');
        return;
    }
    
    // Get bukti data from form
    const buktiCheckboxes = document.querySelectorAll(`input[name="bukti[]"]:checked`);
    const bukti = Array.from(buktiCheckboxes).map(cb => cb.value);
    
    if (bukti.length === 0) {
        alert('Silakan pilih minimal satu bukti yang dikumpulkan!');
        return;
    }
    
    // Direct verification without confirmation
    {
        // Get CSRF token safely
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        const token = csrfToken ? csrfToken.getAttribute('content') : '';
        
        fetch(`/asesor/asesmen/${id}/verified`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token
            },
            body: JSON.stringify({
                signature_data: signatureData[id],
                bukti: bukti
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Close modal
                const modal = bootstrap.Modal.getInstance(document.getElementById(`verifikasiModal${id}`));
                if (modal) {
                    modal.hide();
                }
                location.reload();
            } else {
                alert('Gagal memverifikasi asesmen');
            }
        })
        .catch(error => {
            alert('Gagal memverifikasi asesmen');
        });
    }
}

// Initialize signature canvas when modal is shown
document.addEventListener('DOMContentLoaded', function() {
    // Initialize all signature canvases
    @foreach($pendaftaran as $p)
        initializeSignatureCanvas({{ $p->id }});
    @endforeach
    
    // Initialize modals for verification
    @foreach($pendaftaran as $p)
        const modal{{ $p->id }}Element = document.getElementById('verifikasiModal{{ $p->id }}');
        if (modal{{ $p->id }}Element) {
            // Initialize modal manually
            const modal{{ $p->id }} = new bootstrap.Modal(modal{{ $p->id }}Element);
        }
        
        // Add click event listener for verification button
        const verifikasiBtn{{ $p->id }} = document.getElementById('verifikasiBtn{{ $p->id }}');
        if (verifikasiBtn{{ $p->id }}) {
            verifikasiBtn{{ $p->id }}.addEventListener('click', function(e) {
                e.preventDefault();
                openVerificationModal({{ $p->id }});
            });
        }
        
        // Note: Event listener for confirm button will be added when modal opens
    @endforeach
});

// Function to open verification modal
function openVerificationModal(id) {
    // Check if button is disabled (already verified)
    const button = document.querySelector(`button[onclick="openVerificationModal(${id})"]`);
    if (button && button.disabled) {
        alert('Asesmen ini sudah diverifikasi dan tidak dapat diverifikasi ulang!');
        return;
    }
    
    const modalElement = document.getElementById(`verifikasiModal${id}`);
    
    if (modalElement) {
        const modal = new bootstrap.Modal(modalElement);
        modal.show();
        
        // Add event listener for confirm button when modal opens
        const confirmBtn = document.getElementById(`confirmVerificationBtn${id}`);
        if (confirmBtn) {
            // Remove any existing event listeners first
            const newConfirmBtn = confirmBtn.cloneNode(true);
            confirmBtn.parentNode.replaceChild(newConfirmBtn, confirmBtn);
            
            // Add new event listener
            newConfirmBtn.addEventListener('click', function(e) {
                e.preventDefault();
                verifyAsesmenWithSignature(id);
            });
        } else {
        }
        
        // Load signature from personalization when modal opens
        loadSignatureFromPersonalization(id);
    } else {
        alert('Modal tidak ditemukan!');
    }
}

// AJAX functions for verification
function verifyAsesmen(id) {
    if (confirm('Apakah Anda yakin ingin memverifikasi asesmen ini?')) {
        // Get CSRF token safely
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        const token = csrfToken ? csrfToken.getAttribute('content') : '';
        
        fetch(`/asesor/asesmen/${id}/verified`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Gagal memverifikasi asesmen');
            }
        })
        .catch(error => {
            alert('Gagal memverifikasi asesmen');
        });
    }
}

function rejectAsesmen(id) {
    if (confirm('Apakah Anda yakin ingin menolak asesmen ini?')) {
        // Get CSRF token safely
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        const token = csrfToken ? csrfToken.getAttribute('content') : '';
        
        fetch(`/asesor/asesmen/${id}/rejected`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Gagal menolak asesmen');
            }
        })
        .catch(error => {
            alert('Gagal menolak asesmen');
        });
    }
}
</script>
@endsection
