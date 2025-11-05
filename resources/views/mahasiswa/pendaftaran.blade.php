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
                    <!-- Action Buttons -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <a href="{{ route('mahasiswa.pendaftaran.step1') }}" class="btn btn-success btn-lg">
                                <i class="fas fa-plus-circle me-2"></i>Pendaftaran Baru
                            </a>
                        </div>
                        <div class="col-md-6 text-end">
                            <a href="{{ route('mahasiswa.dashboard') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Kembali ke Dashboard
                            </a>
                        </div>
                    </div>

                    <!-- Pendaftaran List -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-list me-2"></i>Riwayat Pendaftaran</h5>
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

                            @php
                                // Check if there are completed pendaftaran that need persetujuan
                                $needsPersetujuan = false;
                                foreach ($pendaftaran as $p) {
                                    if ($p->status == 'completed') {
                                        $hasPersetujuan = $p->persetujuan_data;
                                        $isVerifiedByAsesor = $p->verifications()
                                            ->where('type', 'asesor_verification')
                                            ->where('status', 'verified')
                                            ->exists();
                                        
                                        $hasCompleteAsesorData = false;
                                        if ($p->asesmen_data) {
                                            $asesmenData = is_string($p->asesmen_data) ? 
                                                json_decode($p->asesmen_data, true) : 
                                                $p->asesmen_data;
                                            
                                            if ($asesmenData && isset($asesmenData['bukti']) && isset($asesmenData['tanggal_asesmen']) && 
                                                isset($asesmenData['waktu_asesmen']) && isset($asesmenData['tuk_asesmen'])) {
                                                $hasCompleteAsesorData = true;
                                            }
                                        }
                                        
                                        if ($isVerifiedByAsesor && $hasCompleteAsesorData && !$hasPersetujuan) {
                                            $needsPersetujuan = true;
                                            break;
                                        }
                                    }
                                }
                            @endphp

                            @if($needsPersetujuan)
                                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    <strong>Perhatian!</strong> Anda memiliki pendaftaran yang perlu ditandatangani Persetujuan Asesmen. 
                                    Silakan klik tombol <strong>"Tandatangani"</strong> pada pendaftaran yang bersangkutan.
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            @if($pendaftaran->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th>No. Pendaftaran</th>
                                                <th>Skema Sertifikasi</th>
                                                <th>Jadwal Uji</th>
                                                <th>Status</th>
                                                <th>Tanggal Pendaftaran</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($pendaftaran as $p)
                                                <tr>
                                                    <td>
                                                        <strong>{{ $p->no_pendaftaran }}</strong>
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
                                                        @switch($p->status)
                                                            @case('draft')
                                                                <span class="badge bg-secondary">Draft</span>
                                                                @break
                                                            @case('pending')
                                                                <span class="badge bg-warning">Menunggu Verifikasi</span>
                                                                @break
                                                            @case('approved')
                                                                <span class="badge bg-success">Disetujui</span>
                                                                @break
                                                            @case('rejected')
                                                                <span class="badge bg-danger">Ditolak</span>
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
                                                            @case('completed')
                                                                <span class="badge bg-primary">Selesai</span>
                                                                @break
                                                            @default
                                                                <span class="badge bg-secondary">{{ ucfirst($p->status) }}</span>
                                                        @endswitch
                                                        @if($p->status == 'completed')
                                                            @php
                                                                $hasPersetujuan = $p->persetujuan_data;
                                                                $isVerifiedByAsesor = $p->verifications()
                                                                    ->where('type', 'asesor_verification')
                                                                    ->where('status', 'verified')
                                                                    ->exists();
                                                                
                                                                // Check if asesor has filled complete asesmen data
                                                                $hasCompleteAsesorData = false;
                                                                if ($p->asesmen_data) {
                                                                    $asesmenData = is_string($p->asesmen_data) ? 
                                                                        json_decode($p->asesmen_data, true) : 
                                                                        $p->asesmen_data;
                                                                    
                                                                    if ($asesmenData && isset($asesmenData['bukti']) && isset($asesmenData['tanggal_asesmen']) && 
                                                                        isset($asesmenData['waktu_asesmen']) && isset($asesmenData['tuk_asesmen'])) {
                                                                        $hasCompleteAsesorData = true;
                                                                    }
                                                                }
                                                            @endphp
                                                            @if($isVerifiedByAsesor && $hasCompleteAsesorData && !$hasPersetujuan)
                                                                <br><small class="text-danger d-block mt-1">
                                                                    <i class="fas fa-exclamation-circle"></i> Perlu Tanda Tangan Persetujuan
                                                                </small>
                                                            @elseif($hasPersetujuan)
                                                                <br><small class="text-success d-block mt-1">
                                                                    <i class="fas fa-check-circle"></i> Persetujuan Tersimpan
                                                                </small>
                                                            @endif
                                                        @endif
                                                    </td>
                                                    <td>
                                                        {{ $p->tanggal_pendaftaran ? \Carbon\Carbon::parse($p->tanggal_pendaftaran)->format('d/m/Y H:i') : '-' }}
                                                    </td>
                                                    <td>
                                                        <div class="btn-group" role="group">
                                                            @if($p->status == 'draft')
                                                                <a href="{{ route('mahasiswa.pendaftaran.continue', $p->id) }}" 
                                                                   class="btn btn-sm btn-primary" 
                                                                   data-bs-toggle="tooltip" 
                                                                   title="Lanjutkan Pendaftaran">
                                                                    <i class="fas fa-edit me-1"></i>Lanjutkan
                                                                </a>
                                                            @endif
                                                            <button type="button" class="btn btn-sm btn-outline-info" 
                                                                    data-bs-toggle="modal" 
                                                                    data-bs-target="#detailModal{{ $p->id }}">
                                                                <i class="fas fa-eye"></i>
                                                            </button>
                                                            @php
                                                                $isVerifiedByAsesor = $p->verifications()
                                                                    ->where('type', 'asesor_verification')
                                                                    ->where('status', 'verified')
                                                                    ->exists();
                                                                $hasPersetujuan = $p->persetujuan_data;
                                                                
                                                                // Check if asesor has filled complete asesmen data
                                                                $hasCompleteAsesorData = false;
                                                                if ($p->asesmen_data) {
                                                                    $asesmenData = is_string($p->asesmen_data) ? 
                                                                        json_decode($p->asesmen_data, true) : 
                                                                        $p->asesmen_data;
                                                                    
                                                                    if ($asesmenData && isset($asesmenData['bukti']) && isset($asesmenData['tanggal_asesmen']) && 
                                                                        isset($asesmenData['waktu_asesmen']) && isset($asesmenData['tuk_asesmen'])) {
                                                                        $hasCompleteAsesorData = true;
                                                                    }
                                                                }
                                                            @endphp
                                                            @if($isVerifiedByAsesor && $hasCompleteAsesorData && !$hasPersetujuan)
                                                                <a href="{{ route('mahasiswa.persetujuan', $p->id) }}" 
                                                                   class="btn btn-sm btn-success" 
                                                                   data-bs-toggle="tooltip" 
                                                                   title="Tandatangani Persetujuan Asesmen">
                                                                    <i class="fas fa-file-signature me-1"></i>Tandatangani
                                                                </a>
                                                            @elseif($hasPersetujuan)
                                                                <span class="badge bg-success" data-bs-toggle="tooltip" title="Persetujuan sudah ditandatangani">
                                                                    <i class="fas fa-check-circle me-1"></i>Persetujuan Tersimpan
                                                                </span>
                                                            @elseif($isVerifiedByAsesor && !$hasCompleteAsesorData)
                                                                <span class="badge bg-warning" data-bs-toggle="tooltip" title="Menunggu data asesor lengkap">
                                                                    <i class="fas fa-clock me-1"></i>Menunggu Data Asesor
                                                                </span>
                                                            @endif
                                                            @if($p->status == 'rejected' && $p->alasan_penolakan)
                                                                <button type="button" class="btn btn-sm btn-outline-warning" 
                                                                        data-bs-toggle="tooltip" 
                                                                        title="Alasan Penolakan: {{ $p->alasan_penolakan }}">
                                                                    <i class="fas fa-exclamation-triangle"></i>
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
                                    <p class="text-muted">Silakan klik tombol "Pendaftaran Baru" untuk memulai pendaftaran LSP.</p>
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
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Pendaftaran</h5>
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
                                        @case('draft')
                                            <span class="badge bg-secondary">Draft</span>
                                            @break
                                        @case('pending')
                                            <span class="badge bg-warning">Menunggu Verifikasi</span>
                                            @break
                                        @case('approved')
                                            <span class="badge bg-success">Disetujui</span>
                                            @break
                                        @case('rejected')
                                            <span class="badge bg-danger">Ditolak</span>
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
                </div>
                
                @if($p->status == 'rejected' && $p->alasan_penolakan)
                    <div class="alert alert-warning mt-3">
                        <h6><i class="fas fa-exclamation-triangle me-2"></i>Alasan Penolakan:</h6>
                        <p class="mb-0">{{ $p->alasan_penolakan }}</p>
                    </div>
                @endif

                @if($p->status == 'completed')
                    @php
                        $hasPersetujuan = $p->persetujuan_data;
                        $isVerifiedByAsesor = $p->verifications()
                            ->where('type', 'asesor_verification')
                            ->where('status', 'verified')
                            ->exists();
                        
                        $hasCompleteAsesorData = false;
                        if ($p->asesmen_data) {
                            $asesmenData = is_string($p->asesmen_data) ? 
                                json_decode($p->asesmen_data, true) : 
                                $p->asesmen_data;
                            
                            if ($asesmenData && isset($asesmenData['bukti']) && isset($asesmenData['tanggal_asesmen']) && 
                                isset($asesmenData['waktu_asesmen']) && isset($asesmenData['tuk_asesmen'])) {
                                $hasCompleteAsesorData = true;
                            }
                        }
                    @endphp
                    @if($isVerifiedByAsesor && $hasCompleteAsesorData && !$hasPersetujuan)
                        <div class="alert alert-warning mt-3">
                            <h6><i class="fas fa-file-signature me-2"></i>Persetujuan Asesmen:</h6>
                            <p class="mb-2">Pendaftaran Anda dengan status "Selesai" memerlukan tanda tangan Persetujuan Asesmen.</p>
                            <a href="{{ route('mahasiswa.persetujuan', $p->id) }}" class="btn btn-sm btn-success">
                                <i class="fas fa-file-signature me-1"></i>Tandatangani Persetujuan Asesmen
                            </a>
                        </div>
                    @elseif($hasPersetujuan)
                        <div class="alert alert-success mt-3">
                            <h6><i class="fas fa-check-circle me-2"></i>Persetujuan Asesmen:</h6>
                            <p class="mb-0">Persetujuan Asesmen sudah ditandatangani dan tersimpan.</p>
                        </div>
                    @endif
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endforeach
@endsection

@section('scripts')
<script>
// Initialize tooltips
var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl)
})
</script>
@endsection
