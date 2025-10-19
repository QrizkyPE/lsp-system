@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Personalisasi</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <div class="btn-group me-2">
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
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

            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-signature me-2"></i>Digital Signature</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.personalization.store') }}" method="POST" id="signatureForm">
                                @csrf
                                <div class="mb-3">
                                    <label for="signatureCanvas" class="form-label">Tanda Tangan Digital</label>
                                    <div class="border rounded p-3 bg-light">
                                        <canvas id="signatureCanvas" width="600" height="200" class="border rounded"></canvas>
                                    </div>
                                    <small class="form-text text-muted">Gunakan mouse atau touch untuk menggambar tanda tangan Anda.</small>
                                </div>

                                <div class="mb-3">
                                    <button type="button" class="btn btn-outline-danger me-2" id="clearSignature">
                                        <i class="fas fa-eraser"></i> Hapus
                                    </button>
                                    <button type="button" class="btn btn-outline-primary" id="saveSignature">
                                        <i class="fas fa-save"></i> Simpan Tanda Tangan
                                    </button>
                                </div>

                                <input type="hidden" name="signature_data" id="signatureData">
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informasi</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <h6>Nama Admin:</h6>
                                <p class="text-muted">{{ auth()->user()->nama_lengkap ?? auth()->user()->name }}</p>
                            </div>
                            
                            <div class="mb-3">
                                <h6>Email:</h6>
                                <p class="text-muted">{{ auth()->user()->email }}</p>
                            </div>

                            <div class="mb-3">
                                <h6>Status Tanda Tangan:</h6>
                                @if(isset($personalization) && $personalization->signature_data)
                                    <span class="badge bg-success">
                                        <i class="fas fa-check"></i> Tersimpan
                                    </span>
                                @else
                                    <span class="badge bg-warning">
                                        <i class="fas fa-exclamation-triangle"></i> Belum Ada
                                    </span>
                                @endif
                            </div>

                            @if(isset($personalization) && $personalization->signature_data)
                                <div class="mb-3">
                                    <h6>Preview Tanda Tangan:</h6>
                                    <div class="border rounded p-2 bg-light text-center">
                                        <img src="{{ $personalization->signature_data }}" alt="Tanda Tangan" style="max-width: 100%; max-height: 100px;">
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const canvas = document.getElementById('signatureCanvas');
    const ctx = canvas.getContext('2d');
    const clearBtn = document.getElementById('clearSignature');
    const saveBtn = document.getElementById('saveSignature');
    const signatureDataInput = document.getElementById('signatureData');
    const form = document.getElementById('signatureForm');

    let isDrawing = false;
    let lastX = 0;
    let lastY = 0;

    // Set canvas background to white
    ctx.fillStyle = 'white';
    ctx.fillRect(0, 0, canvas.width, canvas.height);

    // Set drawing properties
    ctx.strokeStyle = '#000000';
    ctx.lineWidth = 2;
    ctx.lineCap = 'round';
    ctx.lineJoin = 'round';

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
        lastX = e.clientX - rect.left;
        lastY = e.clientY - rect.top;
    }

    function draw(e) {
        if (!isDrawing) return;

        const rect = canvas.getBoundingClientRect();
        const currentX = e.clientX - rect.left;
        const currentY = e.clientY - rect.top;

        ctx.beginPath();
        ctx.moveTo(lastX, lastY);
        ctx.lineTo(currentX, currentY);
        ctx.stroke();

        lastX = currentX;
        lastY = currentY;
    }

    function stopDrawing() {
        isDrawing = false;
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

    clearBtn.addEventListener('click', function() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        ctx.fillStyle = 'white';
        ctx.fillRect(0, 0, canvas.width, canvas.height);
        signatureDataInput.value = '';
    });

    saveBtn.addEventListener('click', function() {
        // Check if canvas has any drawing by comparing with blank canvas
        const blankCanvas = document.createElement('canvas');
        blankCanvas.width = canvas.width;
        blankCanvas.height = canvas.height;
        const blankCtx = blankCanvas.getContext('2d');
        blankCtx.fillStyle = 'white';
        blankCtx.fillRect(0, 0, blankCanvas.width, blankCanvas.height);
        
        const currentData = canvas.toDataURL('image/png');
        const blankData = blankCanvas.toDataURL('image/png');
        
        if (currentData === blankData) {
            alert('Silakan buat tanda tangan terlebih dahulu!');
            return;
        }
        
        signatureDataInput.value = currentData;
        form.submit();
    });

    // Load existing signature if available
    @if(isset($personalization) && $personalization->signature_data)
        const existingSignature = '{{ $personalization->signature_data }}';
        const img = new Image();
        img.onload = function() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
        };
        img.src = existingSignature;
    @endif
});
</script>
@endsection
