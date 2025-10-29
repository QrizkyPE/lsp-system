@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-edit me-2"></i>
                        Edit Rekaman Asesmen Kompetensi
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('asesor.rekaman-asesmen.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i>
                            Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('asesor.rekaman-asesmen.update', $rekamanAsesmen->id) }}" method="POST" id="rekamanAsesmenForm">
                        @csrf
                        @method('PUT')
                        
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
                                            <td width="20%"><strong>Skema Sertifikasi (KKNI/Okupasi/Klaster):</strong></td>
                                            <td>
                                                <select name="pendaftaran_id" id="pendaftaran_id" class="form-select" required>
                                                    <option value="">Pilih Mahasiswa</option>
                                                    @foreach($pendaftaran as $p)
                                                        <option value="{{ $p->id }}" 
                                                                data-judul="{{ $p->skemaSertifikasi->nama_skema }}" 
                                                                data-nomor="{{ $p->skemaSertifikasi->nomor_skema }}"
                                                                {{ $p->id == $rekamanAsesmen->pendaftaran_id ? 'selected' : '' }}>
                                                            {{ $p->user->name }} - {{ $p->skemaSertifikasi->nama_skema }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Judul:</strong></td>
                                            <td id="judul_display">{{ $rekamanAsesmen->judul }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Nomor:</strong></td>
                                            <td id="nomor_display">{{ $rekamanAsesmen->nomor_skema }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>TUK:</strong></td>
                                            <td>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="tuk" id="tuk_sewaktu" value="sewaktu" {{ $rekamanAsesmen->tuk == 'sewaktu' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="tuk_sewaktu">Sewaktu</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="tuk" id="tuk_tempat_kerja" value="tempat_kerja" {{ $rekamanAsesmen->tuk == 'tempat_kerja' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="tuk_tempat_kerja">Tempat Kerja</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="tuk" id="tuk_mandiri" value="mandiri" {{ $rekamanAsesmen->tuk == 'mandiri' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="tuk_mandiri">Mandiri</label>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Nama Asesor:</strong></td>
                                            <td>{{ Auth::user()->name }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Nama Asesi:</strong></td>
                                            <td id="nama_asesi_display">{{ $rekamanAsesmen->nama_asesi }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Tanggal Asesmen:</strong></td>
                                            <td>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label class="form-label">Mulai:</label>
                                                        <div class="input-group">
                                                            <input type="date" name="tanggal_mulai" class="form-control" value="{{ $rekamanAsesmen->tanggal_mulai->format('Y-m-d') }}" required>
                                                            <input type="time" name="waktu_mulai" class="form-control" value="{{ $rekamanAsesmen->waktu_mulai }}" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label">Selesai:</label>
                                                        <div class="input-group">
                                                            <input type="date" name="tanggal_selesai" class="form-control" value="{{ $rekamanAsesmen->tanggal_selesai->format('Y-m-d') }}" required>
                                                            <input type="time" name="waktu_selesai" class="form-control" value="{{ $rekamanAsesmen->waktu_selesai }}" required>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Unit Kompetensi -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5><strong>Beri tanda centang (√) di kolom yang sesuai untuk mencerminkan bukti yang sesuai untuk setiap Unit Kompetensi.</strong></h5>
                                <div id="unit_kompetensi_section">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead class="table-dark">
                                                <tr>
                                                    <th width="25%">Unit Kompetensi</th>
                                                    <th width="10%" class="text-center">Observasi Demonstrasi</th>
                                                    <th width="10%" class="text-center">Portofolio</th>
                                                    <th width="10%" class="text-center">Pernyataan Pihak Ketiga</th>
                                                    <th width="10%" class="text-center">Pertanyaan Wawancara</th>
                                                    <th width="10%" class="text-center">Pertanyaan Lisan</th>
                                                    <th width="10%" class="text-center">Pertanyaan Tertulis</th>
                                                    <th width="10%" class="text-center">Proyek Kerja</th>
                                                    <th width="10%" class="text-center">Lainnya</th>
                                                </tr>
                                            </thead>
                                            <tbody id="unit_kompetensi_tbody">
                                                @if($rekamanAsesmen->unit_kompetensi_data)
                                                    @foreach($rekamanAsesmen->unit_kompetensi_data as $index => $unitData)
                                                        <tr>
                                                            <td><strong>{{ $unitData['judul_unit'] ?? 'Unit ' . ($index + 1) }}</strong></td>
                                                            <td class="text-center">
                                                                <input type="checkbox" name="unit_kompetensi_data[{{ $index }}][observasi_demonstrasi]" class="form-check-input" {{ isset($unitData['observasi_demonstrasi']) && $unitData['observasi_demonstrasi'] ? 'checked' : '' }}>
                                                            </td>
                                                            <td class="text-center">
                                                                <input type="checkbox" name="unit_kompetensi_data[{{ $index }}][portofolio]" class="form-check-input" {{ isset($unitData['portofolio']) && $unitData['portofolio'] ? 'checked' : '' }}>
                                                            </td>
                                                            <td class="text-center">
                                                                <input type="checkbox" name="unit_kompetensi_data[{{ $index }}][pernyataan_pihak_ketiga]" class="form-check-input" {{ isset($unitData['pernyataan_pihak_ketiga']) && $unitData['pernyataan_pihak_ketiga'] ? 'checked' : '' }}>
                                                            </td>
                                                            <td class="text-center">
                                                                <input type="checkbox" name="unit_kompetensi_data[{{ $index }}][pertanyaan_wawancara]" class="form-check-input" {{ isset($unitData['pertanyaan_wawancara']) && $unitData['pertanyaan_wawancara'] ? 'checked' : '' }}>
                                                            </td>
                                                            <td class="text-center">
                                                                <input type="checkbox" name="unit_kompetensi_data[{{ $index }}][pertanyaan_lisan]" class="form-check-input" {{ isset($unitData['pertanyaan_lisan']) && $unitData['pertanyaan_lisan'] ? 'checked' : '' }}>
                                                            </td>
                                                            <td class="text-center">
                                                                <input type="checkbox" name="unit_kompetensi_data[{{ $index }}][pertanyaan_tertulis]" class="form-check-input" {{ isset($unitData['pertanyaan_tertulis']) && $unitData['pertanyaan_tertulis'] ? 'checked' : '' }}>
                                                            </td>
                                                            <td class="text-center">
                                                                <input type="checkbox" name="unit_kompetensi_data[{{ $index }}][proyek_kerja]" class="form-check-input" {{ isset($unitData['proyek_kerja']) && $unitData['proyek_kerja'] ? 'checked' : '' }}>
                                                            </td>
                                                            <td class="text-center">
                                                                <input type="checkbox" name="unit_kompetensi_data[{{ $index }}][lainnya]" class="form-check-input" {{ isset($unitData['lainnya']) && $unitData['lainnya'] ? 'checked' : '' }}>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                    
                                    <!-- Tombol untuk centang semua kolom -->
                                    <div class="row mb-3">
                                        <div class="col-12">
                                            <h6><strong>Centang Semua Kolom:</strong></h6>
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="checkAllColumn('observasi_demonstrasi')">Observasi Demonstrasi</button>
                                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="checkAllColumn('portofolio')">Portofolio</button>
                                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="checkAllColumn('pernyataan_pihak_ketiga')">Pernyataan Pihak Ketiga</button>
                                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="checkAllColumn('pertanyaan_wawancara')">Pertanyaan Wawancara</button>
                                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="checkAllColumn('pertanyaan_lisan')">Pertanyaan Lisan</button>
                                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="checkAllColumn('pertanyaan_tertulis')">Pertanyaan Tertulis</button>
                                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="checkAllColumn('proyek_kerja')">Proyek Kerja</button>
                                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="checkAllColumn('lainnya')">Lainnya</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Rekomendasi Hasil -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5><strong>Rekomendasi hasil asesmen</strong></h5>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <tr>
                                            <td width="20%"><strong>Rekomendasi hasil asesmen:</strong></td>
                                            <td>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="rekomendasi_hasil" id="kompeten" value="kompeten" {{ $rekamanAsesmen->rekomendasi_hasil == 'kompeten' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="kompeten">Kompeten</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="rekomendasi_hasil" id="belum_kompeten" value="belum_kompeten" {{ $rekamanAsesmen->rekomendasi_hasil == 'belum_kompeten' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="belum_kompeten">Belum kompeten</label>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Tindak lanjut yang dibutuhkan:</strong></td>
                                            <td>
                                                <textarea name="tindak_lanjut" class="form-control" rows="3" placeholder="Masukkan pekerjaan tambahan dan asesmen yang diperlukan untuk mencapai kompetensi">{{ $rekamanAsesmen->tindak_lanjut }}</textarea>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Komentar/Observasi oleh asesor:</strong></td>
                                            <td>
                                                <textarea name="komentar_observasi" class="form-control" rows="3" placeholder="Masukkan komentar atau observasi">{{ $rekamanAsesmen->komentar_observasi }}</textarea>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

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
                                                    <span id="nama_asesi_signature">{{ $rekamanAsesmen->nama_asesi }}</span>
                                                </td>
                                                <td>
                                                    <div class="text-center">
                                                        @if($rekamanAsesmen->mahasiswa_signature)
                                                            <img src="{{ $rekamanAsesmen->mahasiswa_signature }}" alt="Tanda Tangan Asesi" style="max-width: 100%; height: auto; border: 1px solid #ddd; border-radius: 4px;">
                                                        @else
                                                            <div class="border rounded p-3 bg-light">
                                                                <i class="fas fa-signature fa-2x text-muted"></i>
                                                                <p class="mb-0 mt-2 text-muted">Tanda tangan mahasiswa</p>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="text-center">
                                                        @if($rekamanAsesmen->tanggal_mahasiswa)
                                                            <p class="mb-0">{{ $rekamanAsesmen->tanggal_mahasiswa->format('d F Y') }}</p>
                                                        @else
                                                            <p class="mb-0 text-muted">-</p>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="bg-light">
                                                    <strong>Asesor:</strong><br>
                                                    <span>{{ Auth::user()->name }}</span>
                                                </td>
                                                <td>
                                                    <div class="text-center">
                                                        <canvas id="asesorSignatureCanvas" width="300" height="150" style="border: 1px solid #ddd; border-radius: 4px; cursor: crosshair;"></canvas>
                                                        <div class="mt-2">
                                                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="clearSignature()">
                                                                <i class="fas fa-eraser me-1"></i>Hapus
                                                            </button>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="text-center">
                                                        <input type="date" name="tanggal_asesor" class="form-control" value="{{ $rekamanAsesmen->tanggal_asesor ? $rekamanAsesmen->tanggal_asesor->format('Y-m-d') : date('Y-m-d') }}">
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- No Reg Asesor -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <tr>
                                            <td width="20%"><strong>No. Reg Asesor:</strong></td>
                                            <td>
                                                <input type="text" name="no_reg_asesor" class="form-control" value="{{ $asesor->no_reg ?? $rekamanAsesmen->no_reg_asesor }}" readonly style="background-color: #f8f9fa;">
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Lampiran Dokumen -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5><strong>LAMPIRAN DOKUMEN:</strong></h5>
                                <ol>
                                    <li>Dokumen APL 01 peserta</li>
                                    <li>Dokumen APL 02 peserta</li>
                                    <li>Bukti-bukti berkualitas peserta</li>
                                    <li>Tinjauan proses asesmen</li>
                                </ol>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="row">
                            <div class="col-12 text-center">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-save me-2"></i>
                                    Update Rekaman Asesmen
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
    signatureCanvas = document.getElementById('asesorSignatureCanvas');
    const ctx = signatureCanvas.getContext('2d');
    
    // Set canvas properties
    ctx.strokeStyle = '#000';
    ctx.lineWidth = 2;
    ctx.lineCap = 'round';
    ctx.lineJoin = 'round';
    
    // Load existing signature if available
    @if($rekamanAsesmen->asesor_signature)
        const img = new Image();
        img.onload = function() {
            ctx.drawImage(img, 0, 0, signatureCanvas.width, signatureCanvas.height);
        };
        img.src = '{{ $rekamanAsesmen->asesor_signature }}';
    @else
        // Load signature from personalization
        loadSignatureFromPersonalization();
    @endif
    
    // Add event listeners for drawing
    signatureCanvas.addEventListener('mousedown', startDrawing);
    signatureCanvas.addEventListener('mousemove', draw);
    signatureCanvas.addEventListener('mouseup', stopDrawing);
    signatureCanvas.addEventListener('mouseout', stopDrawing);
    
    // Handle pendaftaran selection
    document.getElementById('pendaftaran_id').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        if (selectedOption.value) {
            document.getElementById('judul_display').textContent = selectedOption.dataset.judul;
            document.getElementById('nomor_display').textContent = selectedOption.dataset.nomor;
            document.getElementById('nama_asesi_display').textContent = selectedOption.textContent.split(' - ')[0];
            document.getElementById('nama_asesi_signature').textContent = selectedOption.textContent.split(' - ')[0];
        }
    });
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

function loadSignatureFromPersonalization() {
    fetch('{{ route("asesor.personalization.get-signature") }}')
        .then(response => response.json())
        .then(data => {
            if (data.signature) {
                const img = new Image();
                img.onload = function() {
                    const ctx = signatureCanvas.getContext('2d');
                    ctx.drawImage(img, 0, 0, signatureCanvas.width, signatureCanvas.height);
                };
                img.src = data.signature;
            }
        })
        .catch(error => console.error('Error loading signature:', error));
}

function checkAllColumn(columnName) {
    const checkboxes = document.querySelectorAll(`input[name*="[${columnName}]"]`);
    const allChecked = Array.from(checkboxes).every(cb => cb.checked);
    
    checkboxes.forEach(checkbox => {
        checkbox.checked = !allChecked;
    });
}

// Handle form submission
document.getElementById('rekamanAsesmenForm').addEventListener('submit', function(e) {
    // Convert signature canvas to data URL
    const signatureData = signatureCanvas.toDataURL();
    const signatureInput = document.createElement('input');
    signatureInput.type = 'hidden';
    signatureInput.name = 'asesor_signature';
    signatureInput.value = signatureData;
    this.appendChild(signatureInput);
});
</script>
@endsection
