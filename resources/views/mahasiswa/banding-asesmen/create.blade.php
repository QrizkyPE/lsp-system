@extends('layouts.app')

@section('title', 'Banding Asesmen')
@section('page-title', 'Banding Asesmen')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="fas fa-gavel me-2"></i>FR.AK.04. BANDING ASESMEN</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('mahasiswa.banding-asesmen.store', $rekamanAsesmen->id) }}" method="POST" id="bandingForm">
                        @csrf

                        <!-- Informasi Dasar -->
                        <div class="table-responsive mb-4">
                            <table class="table table-bordered">
                                <tr>
                                    <td width="25%"><strong>Nama Asesi:</strong></td>
                                    <td>{{ Auth::user()->name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Nama Asesor:</strong></td>
                                    <td>{{ $rekamanAsesmen->nama_asesor }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Tanggal Asesmen:</strong></td>
                                    <td>{{ $rekamanAsesmen->tanggal_mulai->format('d F Y') }}</td>
                                </tr>
                            </table>
                        </div>

                        <!-- Pertanyaan Ya/Tidak -->
                        <div class="mb-4">
                            <h6><strong>Jawablah dengan Ya atau Tidak pertanyaan-pertanyaan berikut ini :</strong></h6>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th style="width: 70%;">Pertanyaan</th>
                                            <th class="text-center" style="width: 15%;">YA</th>
                                            <th class="text-center" style="width: 15%;">TIDAK</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Apakah Proses Banding telah dijelaskan kepada Anda?</td>
                                            <td class="text-center">
                                                <input type="radio" name="proses_banding_dijelaskan" value="ya" id="proses_ya" class="form-check-input" required>
                                                <label for="proses_ya"></label>
                                            </td>
                                            <td class="text-center">
                                                <input type="radio" name="proses_banding_dijelaskan" value="tidak" id="proses_tidak" class="form-check-input" required>
                                                <label for="proses_tidak"></label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Apakah Anda telah mendiskusikan Banding dengan Asesor?</td>
                                            <td class="text-center">
                                                <input type="radio" name="mendiskusikan_banding" value="ya" id="diskusi_ya" class="form-check-input" required>
                                                <label for="diskusi_ya"></label>
                                            </td>
                                            <td class="text-center">
                                                <input type="radio" name="mendiskusikan_banding" value="tidak" id="diskusi_tidak" class="form-check-input" required>
                                                <label for="diskusi_tidak"></label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Apakah Anda mau melibatkan "orang lain" membantu Anda dalam Proses Banding?</td>
                                            <td class="text-center">
                                                <input type="radio" name="melibatkan_orang_lain" value="ya" id="orang_lain_ya" class="form-check-input" required>
                                                <label for="orang_lain_ya"></label>
                                            </td>
                                            <td class="text-center">
                                                <input type="radio" name="melibatkan_orang_lain" value="tidak" id="orang_lain_tidak" class="form-check-input" required>
                                                <label for="orang_lain_tidak"></label>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Informasi Skema -->
                        <div class="mb-4">
                            <p>Banding ini diajukan atas Keputusan Asesmen yang dibuat terhadap Skema Sertifikasi (<s>Kualifikasi</s>/<s>Klaster</s>/Okupasi) berikut :</p>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <tr>
                                        <td width="30%"><strong>Skema Sertifikasi :</strong></td>
                                        <td>{{ $rekamanAsesmen->pendaftaran->skemaSertifikasi->nama_skema }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>No. Skema Sertifikasi :</strong></td>
                                        <td>{{ $rekamanAsesmen->nomor_skema }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <!-- Alasan Banding -->
                        <div class="mb-4">
                            <label for="alasan_banding" class="form-label"><strong>Banding ini diajukan atas alasan sebagai berikut :</strong></label>
                            <textarea name="alasan_banding" id="alasan_banding" class="form-control" rows="5" required placeholder="Tuliskan alasan banding Anda..."></textarea>
                            <small class="text-muted">Anda mempunyai hak mengajukan banding jika Anda menilai Proses Asesmen tidak sesuai SOP dan tidak memenuhi Prinsip Asesmen.</small>
                        </div>

                        <!-- Tanda Tangan -->
                        <div class="mb-4">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <tr>
                                        <td width="30%"><strong>Tanda tangan Asesi :</strong></td>
                                        <td>
                                            <div class="text-center">
                                                <canvas id="signatureCanvas" width="400" height="200" style="border: 1px solid #ddd; border-radius: 4px; cursor: crosshair; background: white;"></canvas>
                                                <div class="mt-2">
                                                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="clearSignature()">
                                                        <i class="fas fa-eraser me-1"></i>Hapus
                                                    </button>
                                                </div>
                                                <input type="hidden" name="mahasiswa_signature" id="signatureInput" required>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Tanggal :</strong></td>
                                        <td>
                                            <input type="date" name="tanggal_banding" class="form-control" style="width: 200px;" value="{{ date('Y-m-d') }}" required>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="row">
                            <div class="col-12 text-center">
                                <a href="{{ route('mahasiswa.rekaman-asesmen.show', $rekamanAsesmen->id) }}" class="btn btn-secondary me-2">
                                    <i class="fas fa-times me-1"></i>Batal
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-paper-plane me-1"></i>Kirim Banding Asesmen
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Signature Canvas
    const canvas = document.getElementById('signatureCanvas');
    const ctx = canvas.getContext('2d');
    let isDrawing = false;
    let lastX = 0;
    let lastY = 0;

    // Set canvas background to white
    ctx.fillStyle = 'white';
    ctx.fillRect(0, 0, canvas.width, canvas.height);

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
        e.preventDefault();

        const rect = canvas.getBoundingClientRect();
        const currentX = e.clientX - rect.left;
        const currentY = e.clientY - rect.top;

        ctx.beginPath();
        ctx.moveTo(lastX, lastY);
        ctx.lineTo(currentX, currentY);
        ctx.strokeStyle = '#000';
        ctx.lineWidth = 2;
        ctx.lineCap = 'round';
        ctx.stroke();

        lastX = currentX;
        lastY = currentY;

        // Update hidden input
        document.getElementById('signatureInput').value = canvas.toDataURL();
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

    function stopDrawing() {
        if (isDrawing) {
            isDrawing = false;
            document.getElementById('signatureInput').value = canvas.toDataURL();
        }
    }

    function clearSignature() {
        ctx.fillStyle = 'white';
        ctx.fillRect(0, 0, canvas.width, canvas.height);
        document.getElementById('signatureInput').value = '';
    }

    // Form validation
    document.getElementById('bandingForm').addEventListener('submit', function(e) {
        const signature = document.getElementById('signatureInput').value;
        if (!signature) {
            e.preventDefault();
            alert('Harap berikan tanda tangan terlebih dahulu.');
            return false;
        }
    });
</script>
@endsection

