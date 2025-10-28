@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-signature me-2"></i>
                        Tandatangani Rekaman Asesmen Kompetensi
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('mahasiswa.rekaman-asesmen.show', $rekamanAsesmen->id) }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i>
                            Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('mahasiswa.rekaman-asesmen.signature', $rekamanAsesmen->id) }}" method="POST" id="signatureForm">
                        @csrf
                        @method('POST')
                        
                        <!-- Header -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h4 class="text-center"><strong>FR.AK.02. REKAMAN ASESMEN KOMPETENSI</strong></h4>
                            </div>
                        </div>

                        <!-- Informasi Dasar -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5><strong>Informasi Dasar</strong></h5>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <tr>
                                            <td width="20%"><strong>Skema Sertifikasi:</strong></td>
                                            <td>{{ $rekamanAsesmen->judul }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Nama Asesor:</strong></td>
                                            <td>{{ $rekamanAsesmen->nama_asesor }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Nama Asesi:</strong></td>
                                            <td>{{ $rekamanAsesmen->nama_asesi }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Tanggal Asesmen:</strong></td>
                                            <td>
                                                {{ $rekamanAsesmen->tanggal_mulai->format('d F Y') }} {{ $rekamanAsesmen->waktu_mulai }} - 
                                                {{ $rekamanAsesmen->tanggal_selesai->format('d F Y') }} {{ $rekamanAsesmen->waktu_selesai }}
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Tanda Tangan -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5><strong>Tanda Tangan Asesi</strong></h5>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <tr>
                                            <td width="20%"><strong>Nama:</strong></td>
                                            <td>{{ $rekamanAsesmen->nama_asesi }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Tanda Tangan:</strong></td>
                                            <td>
                                                <div class="text-center">
                                                    <canvas id="mahasiswaSignatureCanvas" width="400" height="200" style="border: 1px solid #ddd; border-radius: 4px; cursor: crosshair;"></canvas>
                                                    <div class="mt-2">
                                                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="clearSignature()">
                                                            <i class="fas fa-eraser me-1"></i>Hapus
                                                        </button>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Tanggal:</strong></td>
                                            <td>
                                                <div class="col-md-6">
                                                    <input type="date" name="tanggal_mahasiswa" class="form-control" value="{{ date('Y-m-d') }}" required>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="row">
                            <div class="col-12 text-center">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-signature me-2"></i>
                                    Konfirmasi Tanda Tangan
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let signatureCanvas;
let isDrawing = false;

document.addEventListener('DOMContentLoaded', function() {
    // Initialize signature canvas
    signatureCanvas = document.getElementById('mahasiswaSignatureCanvas');
    const ctx = signatureCanvas.getContext('2d');
    
    // Set canvas properties
    ctx.strokeStyle = '#000';
    ctx.lineWidth = 2;
    ctx.lineCap = 'round';
    ctx.lineJoin = 'round';
    
    // Add event listeners for drawing
    signatureCanvas.addEventListener('mousedown', startDrawing);
    signatureCanvas.addEventListener('mousemove', draw);
    signatureCanvas.addEventListener('mouseup', stopDrawing);
    signatureCanvas.addEventListener('mouseout', stopDrawing);
});

function startDrawing(e) {
    isDrawing = true;
    const rect = signatureCanvas.getBoundingClientRect();
    const x = e.clientX - rect.left;
    const y = e.clientY - rect.top;
    
    const ctx = signatureCanvas.getContext('2d');
    ctx.beginPath();
    ctx.moveTo(x, y);
}

function draw(e) {
    if (!isDrawing) return;
    
    const rect = signatureCanvas.getBoundingClientRect();
    const x = e.clientX - rect.left;
    const y = e.clientY - rect.top;
    
    const ctx = signatureCanvas.getContext('2d');
    ctx.lineTo(x, y);
    ctx.stroke();
}

function stopDrawing() {
    isDrawing = false;
}

function clearSignature() {
    const ctx = signatureCanvas.getContext('2d');
    ctx.clearRect(0, 0, signatureCanvas.width, signatureCanvas.height);
}

// Handle form submission
document.getElementById('signatureForm').addEventListener('submit', function(e) {
    // Check if signature is empty
    const canvas = document.getElementById('mahasiswaSignatureCanvas');
    const ctx = canvas.getContext('2d');
    const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
    const data = imageData.data;
    
    let isEmpty = true;
    for (let i = 0; i < data.length; i += 4) {
        if (data[i] !== 255 || data[i + 1] !== 255 || data[i + 2] !== 255) {
            isEmpty = false;
            break;
        }
    }
    
    if (isEmpty) {
        e.preventDefault();
        alert('Silakan berikan tanda tangan terlebih dahulu.');
        return;
    }
    
    // Convert signature canvas to data URL
    const signatureData = signatureCanvas.toDataURL();
    const signatureInput = document.createElement('input');
    signatureInput.type = 'hidden';
    signatureInput.name = 'mahasiswa_signature';
    signatureInput.value = signatureData;
    this.appendChild(signatureInput);
});
</script>
@endsection
