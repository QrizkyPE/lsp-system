@extends('layouts.app')

@section('title', 'Persetujuan Asesmen dan Kerahasiaan')
@section('page-title', 'Persetujuan Asesmen dan Kerahasiaan')

@section('content')
<div class="container-fluid">
    <!-- Main Content -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header">
                    <h4 class="mb-0">FR.AK.01. PERSETUJUAN ASESMEN DAN KERAHASIAAN</h4>
                </div>
                <div class="card-body">
                    <form id="persetujuanForm" method="POST" action="{{ route('mahasiswa.persetujuan.store') }}">
                        @csrf
                        <input type="hidden" name="pendaftaran_id" value="{{ $pendaftaran->id }}">
                        
                        <!-- Informasi Pendaftaran -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="alert alert-info">
                                    <strong>Persetujuan Asesmen</strong><br>
                                    Persetujuan Asesmen ini untuk menjamin bahwa Asesi telah diberi arahan secara rinci tentang perencanaan dan proses asesmen
                                </div>
                            </div>
                        </div>

                        <!-- Data Skema dan Asesor -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <table class="table table-bordered">
                                    <tr>
                                        <td><strong>Skema Sertifikasi</strong></td>
                                        <td>
                                            <span class="text-decoration-line-through">KKNI</span> / 
                                            <strong>Okupasi</strong> / 
                                            <span class="text-decoration-line-through">Klaster</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Judul</strong></td>
                                        <td>{{ $pendaftaran->skemaSertifikasi->nama_skema ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Nomor</strong></td>
                                        <td>{{ $pendaftaran->skemaSertifikasi->nomor_skema ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>TUK</strong></td>
                                        <td>
                                            <strong>Sewaktu</strong> /
                                            <span class="text-decoration-line-through">Tempat Kerja</span> / 
                                            <span class="text-decoration-line-through">Mandiri</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Nama Asesor</strong></td>
                                        <td>{{ $asesor->nama_lengkap ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Nama Asesi</strong></td>
                                        <td>{{ $pendaftaran->user->nama_lengkap ?? '-' }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <!-- Bukti yang dikumpulkan (Read-only dari asesor) -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6><strong>Bukti yang dikumpulkan:</strong></h6>
                                <div class="alert alert-info">
                                    <strong>Data yang telah diisi oleh Asesor:</strong>
                                    <ul class="mb-0 mt-2">
                                        @if(isset($asesmenData['bukti']) && is_array($asesmenData['bukti']))
                                            @foreach($asesmenData['bukti'] as $bukti)
                                                <li>{{ $bukti }}</li>
                                            @endforeach
                                        @else
                                            <li>Belum ada data bukti dari asesor</li>
                                        @endif
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Pelaksanaan asesmen (Read-only dari asesor) -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6><strong>Pelaksanaan asesmen disepakati pada:</strong></h6>
                                <div class="alert alert-info">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <strong>Hari/ Tanggal:</strong><br>
                                            <span class="text-primary">{{ $asesmenData['tanggal_asesmen'] ?? 'Belum diisi asesor' }}</span>
                                        </div>
                                        <div class="col-md-4">
                                            <strong>Waktu:</strong><br>
                                            <span class="text-primary">{{ $asesmenData['waktu_asesmen'] ?? 'Belum diisi asesor' }}</span>
                                        </div>
                                        <div class="col-md-4">
                                            <strong>TUK:</strong><br>
                                            <span class="text-primary">{{ $asesmenData['tuk_asesmen'] ?? 'Belum diisi asesor' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Khusus untuk Junior Web Programmer dan Analisis Senior Hubungan Industrial -->
                        @if(in_array($pendaftaran->skemaSertifikasi->nama_skema ?? '', ['Junior Web Programmer', 'Analisis Senior Hubungan Industrial']))
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="alert alert-warning">
                                    <strong>Asesi:</strong><br>
                                    Bahwa saya telah mendapatkan penjelasan terkait hak dan prosedur banding asesmen dari asesor.
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Pernyataan Asesor -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="alert alert-primary">
                                    <strong>Asesor:</strong><br>
                                    Menyatakan tidak akan membuka hasil pekerjaan yang saya peroleh karena penugasan saya sebagai Asesor dalam pekerjaan Asesmen kepada siapapun atau organisasi apapun selain kepada pihak yang berwenang sehubungan dengan kewajiban saya sebagai Asesor yang ditugaskan oleh LSP.
                                </div>
                            </div>
                        </div>

                        <!-- Pernyataan Asesi -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="alert alert-success">
                                    <strong>Asesi:</strong><br>
                                    Saya setuju mengikuti asesmen dengan pemahaman bahwa informasi yang dikumpulkan hanya digunakan untuk pengembangan profesional dan hanya dapat diakses oleh orang tertentu saja.
                                </div>
                            </div>
                        </div>

                        <!-- Tanda Tangan -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h6><strong>Tanda tangan Asesor:</strong></h6>
                                <div class="text-center p-3 border bg-light">
                                    <p class="text-muted mb-0">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Tanda tangan asesor akan dimasukkan setelah mahasiswa mengirim persetujuan
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h6><strong>Tanda tangan Asesi (Mahasiswa):</strong></h6>
                                <div class="signature-section">
                                    <canvas id="signatureCanvas" width="400" height="200" style="border: 1px solid #ddd; cursor: crosshair;"></canvas>
                                    <div class="mt-2">
                                        <button type="button" class="btn btn-secondary btn-sm" onclick="clearSignature()">
                                            <i class="fas fa-eraser me-1"></i>Hapus
                                        </button>
                                    </div>
                                    <input type="hidden" name="asesi_signature" id="asesiSignature">
                                </div>
                                <div class="mt-2">
                                    <label class="form-label">Tanggal:</label>
                                    <input type="date" class="form-control" name="tanggal_asesi" required>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('mahasiswa.dashboard') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Kembali
                            </a>
                            <button type="submit" class="btn btn-success" id="submitBtn" disabled>
                                <i class="fas fa-check me-2"></i>Kirim Persetujuan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
/* Simple Progress Steps */
.progress-wrapper {
    display: flex;
    align-items: center;
    gap: 10px;
}

.progress-step {
    display: flex;
    align-items: center;
    position: relative;
}

.step-circle {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 16px;
    color: white;
    border: 2px solid #fff;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.progress-step.completed .step-circle {
    background-color: #28a745;
}

.progress-step.active .step-circle {
    background-color: #007bff;
}

.progress-step.pending .step-circle {
    background-color: #e9ecef;
    color: #6c757d;
    border-color: #dee2e6;
}

.progress-line {
    width: 50px;
    height: 3px;
    background-color: #dee2e6;
    margin: 0 5px;
}

.progress-line.completed {
    background-color: #28a745;
}

/* Responsive */
@media (max-width: 768px) {
    .step-circle {
        width: 35px;
        height: 35px;
        font-size: 14px;
    }
    
    .progress-line {
        width: 30px;
    }
}

.signature-section {
    text-align: center;
}

#signatureCanvas {
    background-color: white;
    border-radius: 4px;
}
</style>
@endsection

@section('scripts')
<script>
let isDrawing = false;
let canvas = document.getElementById('signatureCanvas');
let ctx = canvas.getContext('2d');

// Set canvas background
ctx.fillStyle = 'white';
ctx.fillRect(0, 0, canvas.width, canvas.height);

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
    draw(e);
}

function draw(e) {
    if (!isDrawing) return;
    
    const rect = canvas.getBoundingClientRect();
    const x = e.clientX - rect.left;
    const y = e.clientY - rect.top;
    
    ctx.lineWidth = 2;
    ctx.lineCap = 'round';
    ctx.strokeStyle = '#000';
    
    ctx.lineTo(x, y);
    ctx.stroke();
    ctx.beginPath();
    ctx.moveTo(x, y);
    
    // Update hidden input
    updateSignatureData();
}

function stopDrawing() {
    if (isDrawing) {
        isDrawing = false;
        ctx.beginPath();
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

function clearSignature() {
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    ctx.fillStyle = 'white';
    ctx.fillRect(0, 0, canvas.width, canvas.height);
    document.getElementById('asesiSignature').value = '';
    updateSubmitButton();
}

function updateSignatureData() {
    const dataURL = canvas.toDataURL();
    document.getElementById('asesiSignature').value = dataURL;
    updateSubmitButton();
}

function updateSubmitButton() {
    const signature = document.getElementById('asesiSignature').value;
    const submitBtn = document.getElementById('submitBtn');
    
    if (signature) {
        submitBtn.disabled = false;
    } else {
        submitBtn.disabled = true;
    }
}

// Set current date for asesi
document.addEventListener('DOMContentLoaded', function() {
    const today = new Date().toISOString().split('T')[0];
    document.querySelector('input[name="tanggal_asesi"]').value = today;
});

// Form submission
document.getElementById('persetujuanForm').addEventListener('submit', function(e) {
    const signature = document.getElementById('asesiSignature').value;
    if (!signature) {
        e.preventDefault();
        alert('Silakan berikan tanda tangan terlebih dahulu!');
        return;
    }
});
</script>
@endsection
