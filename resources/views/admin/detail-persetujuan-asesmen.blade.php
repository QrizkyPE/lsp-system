@extends('layouts.app')

@section('title', 'Detail Persetujuan Asesmen')
@section('page-title', 'Detail Persetujuan Asesmen')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header">
                    <h4 class="mb-0">FR.AK.01. PERSETUJUAN ASESMEN DAN KERAHASIAAN</h4>
                </div>
                <div class="card-body">
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
                                    <td>{{ $pendaftaran->user->nama_lengkap ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Nama Asesi</strong></td>
                                    <td>{{ $pendaftaran->user->nama_lengkap ?? '-' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- Bukti yang dikumpulkan (dipilih oleh asesor) -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6><strong>Bukti yang dikumpulkan:</strong></h6>
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Asesor</strong> - Bukti yang dipilih oleh asesor
                            </div>
                            @if(isset($asesmenData['bukti']) && !empty($asesmenData['bukti']))
                                <div class="row">
                                    @foreach($asesmenData['bukti'] as $bukti)
                                        <div class="col-md-6 mb-2">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" checked disabled>
                                                <label class="form-check-label text-muted">
                                                    {{ $bukti }}
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    Belum ada bukti yang dipilih oleh asesor
                                </div>
                            @endif
                        </div>
                    </div>


                    <!-- Tanda Tangan -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6><strong>Tanda tangan Asesor:</strong></h6>
                            <div class="text-center p-3 border">
                                @if(isset($asesmenData['signature_data']))
                                    <img src="{{ $asesmenData['signature_data'] }}" alt="Tanda Tangan Asesor" 
                                         style="max-width: 300px; border: 1px solid #ddd; border-radius: 4px; background: white;">
                                @else
                                    <p class="text-muted">Tanda tangan asesor tidak tersedia</p>
                                @endif
                            </div>
                            @if(isset($asesmenData['verified_at']))
                                <div class="mt-2 text-center">
                                    <small class="text-muted">
                                        <i class="fas fa-calendar me-1"></i>
                                        Diverifikasi pada: {{ \Carbon\Carbon::parse($asesmenData['verified_at'])->format('d F Y, H:i') }}
                                    </small>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <h6><strong>Tanda tangan Admin:</strong></h6>
                            <div class="signature-section">
                                <canvas id="signatureCanvas" width="400" height="200" style="border: 1px solid #ddd; cursor: crosshair;"></canvas>
                                <div class="mt-2">
                                    <button type="button" class="btn btn-secondary btn-sm" onclick="clearSignature()">
                                        <i class="fas fa-eraser me-1"></i>Hapus
                                    </button>
                                </div>
                                <input type="hidden" name="admin_signature" id="adminSignature">
                            </div>
                            <div class="mt-2">
                                <label class="form-label">Tanggal:</label>
                                <input type="date" class="form-control" name="tanggal_admin" id="tanggalAdmin" required>
                            </div>
                        </div>
                    </div>

                    <!-- Form Konfirmasi -->
                    <form id="konfirmasiForm" method="POST" action="{{ route('admin.persetujuan-asesmen.konfirmasi', $pendaftaran->id) }}">
                        @csrf
                        
                        <!-- Data Asesmen (diisi oleh admin) -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6><strong>Data Asesmen:</strong></h6>
                                <div class="alert alert-warning">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <strong>Admin</strong> - Silakan isi data asesmen yang diperlukan
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="tanggal_asesmen" class="form-label"><strong>Hari/ Tanggal Asesmen</strong></label>
                                            <input type="date" class="form-control" id="tanggal_asesmen" name="tanggal_asesmen" 
                                                   value="{{ $asesmenData['tanggal_asesmen'] ?? '' }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="waktu_asesmen" class="form-label"><strong>Waktu Asesmen</strong></label>
                                            <div class="input-group">
                                                <input type="time" class="form-control" id="waktu_asesmen" name="waktu_asesmen" 
                                                       value="{{ isset($asesmenData['waktu_asesmen']) ? \Carbon\Carbon::parse($asesmenData['waktu_asesmen'])->format('H:i') : '' }}" required>
                                                {{-- <span class="input-group-text">
                                                    <i class="fas fa-clock"></i>
                                                </span> --}}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="tuk_asesmen" class="form-label"><strong>TUK Asesmen</strong></label>
                                            <input type="text" class="form-control" id="tuk_asesmen" name="tuk_asesmen" 
                                                   value="{{ $asesmenData['tuk_asesmen'] ?? '' }}" 
                                                   placeholder="Masukkan TUK asesmen" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <input type="hidden" name="admin_signature" id="adminSignatureHidden">
                        <input type="hidden" name="tanggal_admin" id="tanggalAdminHidden">
                        <input type="hidden" name="tanggal_asesmen" id="tanggalAsesmenHidden">
                        <input type="hidden" name="waktu_asesmen" id="waktuAsesmenHidden">
                        <input type="hidden" name="tuk_asesmen" id="tukAsesmenHidden">
                        
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.persetujuan-asesmen') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Kembali
                            </a>
                            <button type="button" class="btn btn-success" onclick="loadSignatureAndConfirm()">
                                <i class="fas fa-check me-2"></i>Konfirmasi Persetujuan
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
.signature-preview {
    min-height: 100px;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #f8f9fa;
    border-radius: 4px;
}

#signatureCanvas {
    border: 1px solid #ddd;
    border-radius: 4px;
    background: white;
}

/* Time input styling */
input[type="time"] {
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
    background: white;
    border: 1px solid #ced4da;
    border-radius: 0.375rem;
    padding: 0.375rem 0.75rem;
    font-size: 1rem;
    line-height: 1.5;
    color: #495057;
}

input[type="time"]::-webkit-calendar-picker-indicator {
    background: transparent;
    bottom: 0;
    color: transparent;
    cursor: pointer;
    height: auto;
    left: 0;
    position: absolute;
    right: 0;
    top: 0;
    width: auto;
}

/* Input group styling for time picker */
.input-group .input-group-text {
    background-color: #f8f9fa;
    border-color: #ced4da;
    color: #6c757d;
}

.input-group .form-control:focus {
    border-color: #86b7fe;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
}

/* Fallback for browsers that don't support time input */
input[type="time"]:not(:valid) {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20' fill='%236c757d'%3e%3cpath fill-rule='evenodd' d='M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z' clip-rule='evenodd'/%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right 0.75rem center;
    background-size: 1rem;
}
</style>
@endsection

@section('scripts')
<script>
let canvas, ctx;
let isDrawing = false;

// Initialize signature canvas
document.addEventListener('DOMContentLoaded', function() {
    canvas = document.getElementById('signatureCanvas');
    ctx = canvas.getContext('2d');
    
    // Set canvas background
    ctx.fillStyle = 'white';
    ctx.fillRect(0, 0, canvas.width, canvas.height);
    
    // Set current date
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('tanggalAdmin').value = today;
    
    // Initialize time picker
    initializeTimePicker();
    
    // Load signature from personalization
    loadSignatureFromPersonalization();
    
    // Event listeners
    canvas.addEventListener('mousedown', startDrawing);
    canvas.addEventListener('mousemove', draw);
    canvas.addEventListener('mouseup', stopDrawing);
    canvas.addEventListener('mouseout', stopDrawing);
    
    // Touch events for mobile
    canvas.addEventListener('touchstart', handleTouch);
    canvas.addEventListener('touchmove', handleTouch);
    canvas.addEventListener('touchend', stopDrawing);
});

function startDrawing(e) {
    isDrawing = true;
    const rect = canvas.getBoundingClientRect();
    const x = e.clientX - rect.left;
    const y = e.clientY - rect.top;
    
    ctx.beginPath();
    ctx.moveTo(x, y);
    updateSignatureData();
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
    document.getElementById('adminSignature').value = '';
    updateSubmitButton();
}

function updateSignatureData() {
    const dataURL = canvas.toDataURL();
    document.getElementById('adminSignature').value = dataURL;
    updateSubmitButton();
}

function updateSubmitButton() {
    const signature = document.getElementById('adminSignature').value;
    const submitBtn = document.querySelector('button[onclick="loadSignatureAndConfirm()"]');
    
    if (signature) {
        submitBtn.disabled = false;
    } else {
        submitBtn.disabled = true;
    }
}

function initializeTimePicker() {
    const timeInput = document.getElementById('waktu_asesmen');
    
    // Check if browser supports time input
    if (timeInput.type === 'time') {
        // Add click event to input group text for better UX
        const inputGroupText = timeInput.parentElement.querySelector('.input-group-text');
        if (inputGroupText) {
            inputGroupText.style.cursor = 'pointer';
            inputGroupText.addEventListener('click', function() {
                timeInput.focus();
                timeInput.click();
            });
        }
        
        // Add focus event to show time picker
        timeInput.addEventListener('focus', function() {
            this.showPicker && this.showPicker();
        });
        
        // Add click event to show time picker
        timeInput.addEventListener('click', function() {
            this.showPicker && this.showPicker();
        });
    }
}

function loadSignatureFromPersonalization() {
    fetch('/admin/personalization/get-signature', {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.signature) {
            // Load signature into canvas
            const img = new Image();
            img.onload = function() {
                ctx.clearRect(0, 0, canvas.width, canvas.height);
                ctx.fillStyle = 'white';
                ctx.fillRect(0, 0, canvas.width, canvas.height);
                ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
                updateSignatureData();
            };
            img.src = data.signature;
        }
    })
    .catch(error => {
        console.error('Error loading signature:', error);
    });
}

function loadSignatureAndConfirm() {
    // Get signature and date
    const signature = document.getElementById('adminSignature').value;
    const tanggal = document.getElementById('tanggalAdmin').value;
    
    // Get asesmen data
    const tanggalAsesmen = document.getElementById('tanggal_asesmen').value;
    const waktuAsesmen = document.getElementById('waktu_asesmen').value;
    const tukAsesmen = document.getElementById('tuk_asesmen').value;
    
    if (!signature) {
        alert('Silakan berikan tanda tangan terlebih dahulu!');
        return;
    }
    
    if (!tanggal) {
        alert('Silakan isi tanggal terlebih dahulu!');
        return;
    }
    
    if (!tanggalAsesmen) {
        alert('Silakan isi tanggal asesmen terlebih dahulu!');
        return;
    }
    
    if (!waktuAsesmen) {
        alert('Silakan isi waktu asesmen terlebih dahulu!');
        return;
    }
    
    if (!tukAsesmen) {
        alert('Silakan isi TUK asesmen terlebih dahulu!');
        return;
    }
    
    // Set hidden inputs
    document.getElementById('adminSignatureHidden').value = signature;
    document.getElementById('tanggalAdminHidden').value = tanggal;
    document.getElementById('tanggalAsesmenHidden').value = tanggalAsesmen;
    document.getElementById('waktuAsesmenHidden').value = waktuAsesmen;
    document.getElementById('tukAsesmenHidden').value = tukAsesmen;
    
    // Show confirmation
    if (confirm('Apakah Anda yakin ingin mengkonfirmasi persetujuan asesmen ini?')) {
        document.getElementById('konfirmasiForm').submit();
    }
}
</script>
@endsection
