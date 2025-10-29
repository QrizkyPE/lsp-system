@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-plus me-2"></i>
                        Buat Rekaman Asesmen Kompetensi
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('asesor.rekaman-asesmen.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i>
                            Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if($pendaftaran->count() == 0)
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Peringatan:</strong> Tidak ada mahasiswa yang tersedia.
                            <br>Belum ada mahasiswa yang telah menyelesaikan persetujuan asesmen. Silakan tunggu mahasiswa menyelesaikan persetujuan asesmen terlebih dahulu.
                        </div>
                    @endif

                    <form action="{{ route('asesor.rekaman-asesmen.store') }}" method="POST" id="rekamanAsesmenForm">
                        @csrf
                        
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
                                                <select name="pendaftaran_id" id="pendaftaran_id" class="form-select" required {{ $pendaftaran->count() == 0 ? 'disabled' : '' }}>
                                                    <option value="">Pilih Mahasiswa</option>
                                                    @foreach($pendaftaran as $p)
                                                        <option value="{{ $p->id }}" data-judul="{{ $p->skemaSertifikasi->nama_skema }}" data-nomor="{{ $p->skemaSertifikasi->nomor_skema }}">
                                                            {{ $p->user->name }} - {{ $p->skemaSertifikasi->nama_skema }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Judul:</strong></td>
                                            <td id="judul_display">-</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Nomor:</strong></td>
                                            <td id="nomor_display">-</td>
                                        </tr>
                                        <tr>
                                            <td><strong>TUK:</strong></td>
                                            <td>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="tuk" id="tuk_sewaktu" value="sewaktu" {{ $pendaftaran->count() == 0 ? 'disabled' : '' }}>
                                                    <label class="form-check-label" for="tuk_sewaktu">Sewaktu</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="tuk" id="tuk_tempat_kerja" value="tempat_kerja" {{ $pendaftaran->count() == 0 ? 'disabled' : '' }}>
                                                    <label class="form-check-label" for="tuk_tempat_kerja">Tempat Kerja</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="tuk" id="tuk_mandiri" value="mandiri" {{ $pendaftaran->count() == 0 ? 'disabled' : '' }}>
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
                                            <td id="nama_asesi_display">-</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Tanggal Asesmen:</strong></td>
                                            <td>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label class="form-label">Mulai:</label>
                                                        <div class="input-group">
                                                            <input type="date" name="tanggal_mulai" class="form-control" required {{ $pendaftaran->count() == 0 ? 'disabled' : '' }}>
                                                            <input type="time" name="waktu_mulai" class="form-control" required {{ $pendaftaran->count() == 0 ? 'disabled' : '' }}>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label">Selesai:</label>
                                                        <div class="input-group">
                                                            <input type="date" name="tanggal_selesai" class="form-control" required {{ $pendaftaran->count() == 0 ? 'disabled' : '' }}>
                                                            <input type="time" name="waktu_selesai" class="form-control" required {{ $pendaftaran->count() == 0 ? 'disabled' : '' }}>
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
                                <div id="unit_kompetensi_section" style="display: none;">
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
                                                <!-- Data akan dimuat via AJAX -->
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
                                <div id="loading_message" class="text-center text-muted" style="display: none;">
                                    <i class="fas fa-spinner fa-spin me-2"></i>
                                    Memuat unit kompetensi...
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
                                                    <input class="form-check-input" type="radio" name="rekomendasi_hasil" id="kompeten" value="kompeten" {{ $pendaftaran->count() == 0 ? 'disabled' : '' }}>
                                                    <label class="form-check-label" for="kompeten">Kompeten</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="rekomendasi_hasil" id="belum_kompeten" value="belum_kompeten" {{ $pendaftaran->count() == 0 ? 'disabled' : '' }}>
                                                    <label class="form-check-label" for="belum_kompeten">Belum kompeten</label>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Tindak lanjut yang dibutuhkan:</strong></td>
                                            <td>
                                                <textarea name="tindak_lanjut" class="form-control" rows="3" placeholder="Masukkan pekerjaan tambahan dan asesmen yang diperlukan untuk mencapai kompetensi" {{ $pendaftaran->count() == 0 ? 'disabled' : '' }}></textarea>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Komentar/Observasi oleh asesor:</strong></td>
                                            <td>
                                                <textarea name="komentar_observasi" class="form-control" rows="3" placeholder="Masukkan komentar atau observasi" {{ $pendaftaran->count() == 0 ? 'disabled' : '' }}></textarea>
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
                                                    <span id="nama_asesi_signature">-</span>
                                                </td>
                                                <td>
                                                    <div class="text-center">
                                                        <div class="border rounded p-3 bg-light">
                                                            <i class="fas fa-signature fa-2x text-muted"></i>
                                                            <p class="mb-0 mt-2 text-muted">Tanda tangan mahasiswa</p>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="text-center">
                                                        <p class="mb-0 text-muted">-</p>
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
                                                        <canvas id="asesorSignatureCanvas" width="300" height="150" style="border: 1px solid #ddd; border-radius: 4px; cursor: crosshair;" {{ $pendaftaran->count() == 0 ? 'disabled' : '' }}></canvas>
                                                        <div class="mt-2">
                                                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="clearSignature()" {{ $pendaftaran->count() == 0 ? 'disabled' : '' }}>
                                                                <i class="fas fa-eraser me-1"></i>Hapus
                                                            </button>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="text-center">
                                                        <input type="date" name="tanggal_asesor" class="form-control" value="{{ date('Y-m-d') }}" {{ $pendaftaran->count() == 0 ? 'disabled' : '' }}>
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
                                                <input type="text" name="no_reg_asesor" class="form-control" value="{{ $asesor->no_reg ?? '' }}" readonly style="background-color: #f8f9fa;">
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
                                <button type="submit" class="btn btn-primary btn-lg" {{ $pendaftaran->count() == 0 ? 'disabled' : '' }}>
                                    <i class="fas fa-save me-2"></i>
                                    Simpan Rekaman Asesmen
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
    
    // Load signature from personalization
    loadSignatureFromPersonalization();
    
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
            
            // Load unit kompetensi
            loadUnitKompetensi(selectedOption.value);
        } else {
            document.getElementById('judul_display').textContent = '-';
            document.getElementById('nomor_display').textContent = '-';
            document.getElementById('nama_asesi_display').textContent = '-';
            document.getElementById('nama_asesi_signature').textContent = '-';
            document.getElementById('unit_kompetensi_section').style.display = 'none';
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

function loadUnitKompetensi(pendaftaranId) {
    document.getElementById('loading_message').style.display = 'block';
    document.getElementById('unit_kompetensi_section').style.display = 'none';
    
    fetch(`/asesor/rekaman-asesmen/get-unit-kompetensi/${pendaftaranId}`)
        .then(response => response.json())
        .then(data => {
            const tbody = document.getElementById('unit_kompetensi_tbody');
            tbody.innerHTML = '';
            
            data.forEach((unit, index) => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td><strong>${unit.judul_unit}</strong></td>
                    <td class="text-center">
                        <input type="checkbox" name="unit_kompetensi_data[${index}][observasi_demonstrasi]" class="form-check-input">
                    </td>
                    <td class="text-center">
                        <input type="checkbox" name="unit_kompetensi_data[${index}][portofolio]" class="form-check-input">
                    </td>
                    <td class="text-center">
                        <input type="checkbox" name="unit_kompetensi_data[${index}][pernyataan_pihak_ketiga]" class="form-check-input">
                    </td>
                    <td class="text-center">
                        <input type="checkbox" name="unit_kompetensi_data[${index}][pertanyaan_wawancara]" class="form-check-input">
                    </td>
                    <td class="text-center">
                        <input type="checkbox" name="unit_kompetensi_data[${index}][pertanyaan_lisan]" class="form-check-input">
                    </td>
                    <td class="text-center">
                        <input type="checkbox" name="unit_kompetensi_data[${index}][pertanyaan_tertulis]" class="form-check-input">
                    </td>
                    <td class="text-center">
                        <input type="checkbox" name="unit_kompetensi_data[${index}][proyek_kerja]" class="form-check-input">
                    </td>
                    <td class="text-center">
                        <input type="checkbox" name="unit_kompetensi_data[${index}][lainnya]" class="form-check-input">
                    </td>
                `;
                tbody.appendChild(row);
            });
            
            document.getElementById('loading_message').style.display = 'none';
            document.getElementById('unit_kompetensi_section').style.display = 'block';
        })
        .catch(error => {
            console.error('Error loading unit kompetensi:', error);
            document.getElementById('loading_message').style.display = 'none';
        });
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
