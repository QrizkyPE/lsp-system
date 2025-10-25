@extends('layouts.app')

@section('title', 'Detail Observasi Checklist')
@section('page-title', 'Detail Observasi Checklist')

@section('content')
<!-- Main Content -->
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h4>Detail Observasi Checklist</h4>
                <div>
                    <a href="{{ route('mahasiswa.observasi-checklist') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i>Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Observasi Checklist Details -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-clipboard-list me-2"></i>
                        FR.IA.01. CEKLIS OBSERVASI AKTIVITAS DI TEMPAT KERJA ATAU TEMPAT KERJA SIMULASI
                    </h5>
                </div>
                <div class="card-body">
                    <!-- Header Information -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <td class="bg-light" width="30%"><strong>Skema Sertifikasi:</strong></td>
                                        <td>{{ $observasiChecklist->judul }}</td>
                                    </tr>
                                    <tr>
                                        <td class="bg-light"><strong>Judul:</strong></td>
                                        <td>{{ $observasiChecklist->judul }}</td>
                                    </tr>
                                    <tr>
                                        <td class="bg-light"><strong>Nomor:</strong></td>
                                        <td>{{ $observasiChecklist->nomor_skema }}</td>
                                    </tr>
                                    <tr>
                                        <td class="bg-light"><strong>TUK:</strong></td>
                                        <td>
                                            @if($observasiChecklist->tuk == 'sewaktu')
                                                <i class="fas fa-check me-1"></i>Sewaktu
                                            @elseif($observasiChecklist->tuk == 'tempat_kerja')
                                                <i class="fas fa-check me-1"></i>Tempat Kerja
                                            @else
                                                <i class="fas fa-check me-1"></i>Mandiri
                                            @endif
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <td class="bg-light" width="30%"><strong>Nama Asesor:</strong></td>
                                        <td>{{ $observasiChecklist->nama_asesor }}</td>
                                    </tr>
                                    <tr>
                                        <td class="bg-light"><strong>Nama Asesi:</strong></td>
                                        <td>{{ $observasiChecklist->nama_asesi }}</td>
                                    </tr>
                                    <tr>
                                        <td class="bg-light"><strong>Tanggal:</strong></td>
                                        <td>{{ \Carbon\Carbon::parse($observasiChecklist->tanggal)->format('d F Y') }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Panduan Bagi Asesor -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5><strong>PANDUAN BAGI ASESOR</strong></h5>
                            <div class="alert alert-info">
                                <ul class="mb-0">
                                    <li>Lengkapi nama unit kompetensi, elemen, dan kriteria unjuk kerja sesuai kolom dalam tabel.</li>
                                    <li>Istilah Acuan Pembanding dengan SOP/spesifikasi produk dari industri/organisasi dari tempat kerja atau simulasi tempat kerja</li>
                                    <li>Beri tanda centang (√) pada kolom K jika Anda yakin asesi dapat melakukan/ mendemonstrasikan tugas sesuai KUK, atau centang (√) pada kolom BK bila sebaliknya.</li>
                                    <li>Penilaian Lanjut diisi bila hasil belum dapat disimpulkan, untuk itu gunakan metode lain sehingga keputusan dapat dibuat.</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Unit Kompetensi, Elemen dan Kriteria Unjuk Kerja -->
                    @if($observasiChecklist->elemen_data)
                        @foreach($observasiChecklist->elemen_data as $unitIndex => $unit)
                            <div class="card mb-4">
                                <div class="card-header bg-primary text-white">
                                    <h5 class="mb-0">Unit Kompetensi {{ $unitIndex + 1 }}: {{ $unit['nama_unit_kompetensi'] }}</h5>
                                </div>
                                <div class="card-body">
                                    @foreach($unit['elemen'] as $elemenIndex => $elemen)
                                        <div class="card mb-3">
                                            <div class="card-header bg-light">
                                                <h6 class="mb-0">Elemen {{ $elemenIndex + 1 }}: {{ $elemen['nama_elemen'] }}</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered table-sm">
                                                        <thead class="table-dark">
                                                            <tr>
                                                                <th width="40%">Kriteria Unjuk Kerja</th>
                                                                <th width="15%" class="text-center">K</th>
                                                                <th width="15%" class="text-center">BK</th>
                                                                <th width="30%">Benchmark (SOP/Spesifikasi Produk Industri)</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($elemen['kriteria'] as $kriteriaIndex => $kriteria)
                                                                <tr>
                                                                    <td><small>{{ $kriteria['nama_kriteria'] }}</small></td>
                                                                    <td class="text-center">
                                                                        @if(isset($observasiChecklist->observasi_data[$unitIndex][$elemenIndex][$kriteriaIndex]) && $observasiChecklist->observasi_data[$unitIndex][$elemenIndex][$kriteriaIndex] === 'K')
                                                                            <i class="fas fa-check text-success"></i>
                                                                        @endif
                                                                    </td>
                                                                    <td class="text-center">
                                                                        @if(isset($observasiChecklist->observasi_data[$unitIndex][$elemenIndex][$kriteriaIndex]) && $observasiChecklist->observasi_data[$unitIndex][$elemenIndex][$kriteriaIndex] === 'BK')
                                                                            <i class="fas fa-check text-danger"></i>
                                                                        @endif
                                                                    </td>
                                                                    <td>
                                                                        @if(isset($observasiChecklist->benchmark[$unitIndex][$elemenIndex][$kriteriaIndex]))
                                                                            <small>{{ $observasiChecklist->benchmark[$unitIndex][$elemenIndex][$kriteriaIndex] }}</small>
                                                                        @else
                                                                            <small class="text-muted">-</small>
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                                @if(isset($observasiChecklist->penilaian_lanjut[$unitIndex][$elemenIndex][$kriteriaIndex]) && $observasiChecklist->penilaian_lanjut[$unitIndex][$elemenIndex][$kriteriaIndex])
                                                                    <tr>
                                                                        <td colspan="4">
                                                                            <div class="alert alert-warning mb-0">
                                                                                <strong>Penilaian Lanjut:</strong>
                                                                                <p class="mb-0">{{ $observasiChecklist->penilaian_lanjut[$unitIndex][$elemenIndex][$kriteriaIndex] }}</p>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                @endif
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Belum ada data unit kompetensi, elemen dan kriteria unjuk kerja.
                        </div>
                    @endif

                    <!-- Umpan Balik Untuk Asesi -->
                    @if($observasiChecklist->umpan_balik)
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5><strong>Umpan Balik Untuk Asesi</strong></h5>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr>
                                            <td class="bg-light" width="20%">
                                                <strong>Umpan Balik:</strong>
                                            </td>
                                            <td>
                                                <div class="p-3">
                                                    {!! nl2br(e($observasiChecklist->umpan_balik)) !!}
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Tanda Tangan dan Tanggal -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5><strong>Tanda Tangan dan Tanggal</strong></h5>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="table-dark">
                                        <tr>
                                            <th width="20%">Nama</th>
                                            <th width="40%">Tanda Tangan</th>
                                            <th width="40%">Tanggal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="bg-light">
                                                <strong>Asesi:</strong><br>
                                                <span>{{ $observasiChecklist->nama_asesi }}</span>
                                            </td>
                                            <td>
                                                <div class="text-center">
                                                    @if($observasiChecklist->mahasiswa_signature)
                                                        <img src="{{ $observasiChecklist->mahasiswa_signature }}" 
                                                             alt="Tanda Tangan Asesi" 
                                                             style="max-width: 200px; border: 1px solid #ddd; border-radius: 4px; background: white;">
                                                    @else
                                                        <div class="border rounded p-3 bg-light">
                                                            <i class="fas fa-signature fa-2x text-muted"></i>
                                                            <p class="mb-0 mt-2 text-muted">Belum Ditandatangani</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <div class="text-center">
                                                    @if($observasiChecklist->tanggal_mahasiswa)
                                                        <div class="border rounded p-3 bg-light">
                                                            <i class="fas fa-calendar fa-2x text-success"></i>
                                                            <p class="mb-0 mt-2 text-success">
                                                                {{ \Carbon\Carbon::parse($observasiChecklist->tanggal_mahasiswa)->format('d F Y') }}
                                                            </p>
                                                        </div>
                                                    @else
                                                        <div class="border rounded p-3 bg-light">
                                                            <i class="fas fa-calendar fa-2x text-muted"></i>
                                                            <p class="mb-0 mt-2 text-muted">Belum Ditandatangani</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="bg-light">
                                                <strong>Asesor:</strong><br>
                                                <span>{{ $observasiChecklist->nama_asesor }}</span>
                                            </td>
                                            <td>
                                                <div class="text-center">
                                                    @if($observasiChecklist->asesor_signature)
                                                        <img src="{{ $observasiChecklist->asesor_signature }}" 
                                                             alt="Tanda Tangan Asesor" 
                                                             style="max-width: 200px; border: 1px solid #ddd; border-radius: 4px; background: white;">
                                                    @else
                                                        <div class="border rounded p-3 bg-light">
                                                            <i class="fas fa-signature fa-2x text-muted"></i>
                                                            <p class="mb-0 mt-2 text-muted">Belum Ditandatangani</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <div class="text-center">
                                                    @if($observasiChecklist->tanggal_asesor)
                                                        <div class="border rounded p-3 bg-light">
                                                            <i class="fas fa-calendar fa-2x text-success"></i>
                                                            <p class="mb-0 mt-2 text-success">
                                                                {{ \Carbon\Carbon::parse($observasiChecklist->tanggal_asesor)->format('d F Y') }}
                                                            </p>
                                                        </div>
                                                    @else
                                                        <div class="border rounded p-3 bg-light">
                                                            <i class="fas fa-calendar fa-2x text-muted"></i>
                                                            <p class="mb-0 mt-2 text-muted">Belum Ditandatangani</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Signature Form for Mahasiswa -->
                    @if(!$observasiChecklist->mahasiswa_signature)
                    <div class="row">
                        <div class="col-12">
                            <div class="card border-warning">
                                <div class="card-header bg-warning text-dark">
                                    <h5 class="mb-0">
                                        <i class="fas fa-signature me-2"></i>
                                        Tanda Tangani Observasi Checklist
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <form action="{{ route('mahasiswa.observasi-checklist.signature', $observasiChecklist->id) }}" method="POST">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label class="form-label"><strong>Tanda Tangan Anda:</strong></label>
                                                <canvas id="mahasiswaSignatureCanvas" width="300" height="150" 
                                                        style="border: 1px solid #ddd; cursor: crosshair; background: white;"></canvas>
                                                <div class="mt-2">
                                                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="clearMahasiswaSignature()">
                                                        <i class="fas fa-eraser me-1"></i>Hapus
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label"><strong>Tanggal:</strong></label>
                                                <input type="date" class="form-control" name="tanggal_mahasiswa" 
                                                       value="{{ date('Y-m-d') }}" readonly>
                                                <small class="text-muted">Tanggal saat ini</small>
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-12">
                                                <div class="alert alert-info">
                                                    <i class="fas fa-info-circle me-2"></i>
                                                    Dengan menandatangani, Anda menyatakan bahwa Anda telah membaca dan memahami hasil observasi checklist ini.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <button type="submit" class="btn btn-success">
                                                    <i class="fas fa-signature me-1"></i>Konfirmasi Observasi Checklist
                                                </button>
                                            </div>
                                        </div>
                                        <input type="hidden" name="mahasiswa_signature" id="mahasiswa_signature_input">
                                    </form>
                                </div>
                            </div>
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
    // Signature canvas functionality
    let isDrawing = false;
    const canvas = document.getElementById('mahasiswaSignatureCanvas');
    const ctx = canvas.getContext('2d');
    const signatureInput = document.getElementById('mahasiswa_signature_input');

    if (canvas) {
        // Set canvas properties
        ctx.strokeStyle = '#000';
        ctx.lineWidth = 2;
        ctx.lineCap = 'round';

        // Mouse events
        canvas.addEventListener('mousedown', startDrawing);
        canvas.addEventListener('mousemove', draw);
        canvas.addEventListener('mouseup', stopDrawing);
        canvas.addEventListener('mouseout', stopDrawing);

        // Touch events for mobile
        canvas.addEventListener('touchstart', handleTouch);
        canvas.addEventListener('touchmove', handleTouch);
        canvas.addEventListener('touchend', stopDrawing);

        function startDrawing(e) {
            isDrawing = true;
            const rect = canvas.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            ctx.beginPath();
            ctx.moveTo(x, y);
        }

        function draw(e) {
            if (!isDrawing) return;
            const rect = canvas.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            ctx.lineTo(x, y);
            ctx.stroke();
        }

        function stopDrawing() {
            if (isDrawing) {
                isDrawing = false;
                ctx.beginPath();
                // Save signature to hidden input
                signatureInput.value = canvas.toDataURL();
            }
        }

        function handleTouch(e) {
            e.preventDefault();
            const touch = e.touches[0];
            const mouseEvent = new MouseEvent(e.type === 'touchstart' ? 'mousedown' : 
                                            e.type === 'touchmove' ? 'mousemove' : 'mouseup', {
                clientX: touch.clientX,
                clientY: touch.clientY
            });
            canvas.dispatchEvent(mouseEvent);
        }

        // Clear signature function
        window.clearMahasiswaSignature = function() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            signatureInput.value = '';
        }
    }
});
</script>
@endsection
