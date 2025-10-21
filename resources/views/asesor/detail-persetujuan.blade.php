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
                                        <span class="text-decoration-line-through">Sewaktu</span> / 
                                        <span class="text-decoration-line-through">Tempat Kerja</span> / 
                                        <strong>Mandiri</strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Nama Asesor</strong></td>
                                    <td>{{ Auth::user()->nama_lengkap }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Nama Asesi</strong></td>
                                    <td>{{ $pendaftaran->user->nama_lengkap ?? '-' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- Bukti yang dikumpulkan -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6><strong>Bukti yang dikumpulkan:</strong></h6>
                            <div class="row">
                                @foreach($persetujuanData['bukti'] as $bukti)
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" checked disabled>
                                        <label class="form-check-label">{{ $bukti }}</label>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Pelaksanaan asesmen -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6><strong>Pelaksanaan asesmen disepakati pada:</strong></h6>
                            <div class="row">
                                <div class="col-md-4">
                                    <label class="form-label">Hari/ Tanggal:</label>
                                    <input type="text" class="form-control" value="{{ \Carbon\Carbon::parse($persetujuanData['tanggal_asesmen'])->format('d/m/Y') }}" readonly>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Waktu:</label>
                                    <input type="text" class="form-control" value="{{ $persetujuanData['waktu_asesmen'] }}" readonly>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">TUK:</label>
                                    <input type="text" class="form-control" value="{{ $persetujuanData['tuk_asesmen'] }}" readonly>
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
                            <div class="text-center p-3 border">
                                <div id="asesorSignaturePreview" class="signature-preview">
                                    <p class="text-muted">Tanda tangan akan dimasukkan di bawah ini</p>
                                </div>
                            </div>
                            <div class="mt-2">
                                <label class="form-label">Tanggal:</label>
                                <input type="date" class="form-control" id="tanggalAsesor" value="{{ date('Y-m-d') }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6><strong>Tanda tangan Asesi:</strong></h6>
                            <div class="text-center p-3 border">
                                @if(isset($persetujuanData['asesi_signature']))
                                    <img src="{{ $persetujuanData['asesi_signature'] }}" alt="Tanda Tangan Asesi" 
                                         style="max-width: 300px; border: 1px solid #ddd; border-radius: 4px; background: white;">
                                @else
                                    <p class="text-muted">Tanda tangan tidak tersedia</p>
                                @endif
                            </div>
                            <div class="mt-2">
                                <label class="form-label">Tanggal:</label>
                                <input type="text" class="form-control" value="{{ \Carbon\Carbon::parse($persetujuanData['tanggal_asesi'])->format('d/m/Y') }}" readonly>
                            </div>
                        </div>
                    </div>

                    <!-- Form Konfirmasi -->
                    <form id="konfirmasiForm" method="POST" action="{{ route('asesor.persetujuan.konfirmasi', $pendaftaran->id) }}">
                        @csrf
                        <input type="hidden" name="asesor_signature" id="asesorSignature">
                        <input type="hidden" name="tanggal_asesor" id="tanggalAsesorHidden">
                        
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('asesor.persetujuan') }}" class="btn btn-secondary">
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

#asesorSignaturePreview img {
    max-width: 300px;
    border: 1px solid #ddd;
    border-radius: 4px;
    background: white;
}
</style>
@endsection

@section('scripts')
<script>
function loadSignatureAndConfirm() {
    // Load signature from personalization
    fetch('/asesor/personalization/get-signature')
        .then(response => response.json())
        .then(data => {
            if (data.signature) {
                // Update preview
                const preview = document.getElementById('asesorSignaturePreview');
                preview.innerHTML = `
                    <img src="${data.signature}" alt="Tanda Tangan Asesor" 
                         style="max-width: 300px; border: 1px solid #ddd; border-radius: 4px; background: white;">
                `;
                
                // Set hidden inputs
                document.getElementById('asesorSignature').value = data.signature;
                document.getElementById('tanggalAsesorHidden').value = document.getElementById('tanggalAsesor').value;
                
                // Show confirmation modal
                if (confirm('Apakah Anda yakin ingin mengkonfirmasi persetujuan asesmen ini?')) {
                    document.getElementById('konfirmasiForm').submit();
                }
            } else {
                alert('Tanda tangan tidak ditemukan. Silakan buat tanda tangan di halaman personalisasi terlebih dahulu.');
            }
        })
        .catch(error => {
            console.error('Error loading signature:', error);
            alert('Gagal memuat tanda tangan. Silakan coba lagi.');
        });
}
</script>
@endsection
