@extends('layouts.app')

@section('title', 'Buat Ceklis Observasi Aktivitas')
@section('page-title', 'Buat Ceklis Observasi Aktivitas')

@section('content')
<div class="container-fluid">
    <!-- Main Content -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header">
                    <h4 class="mb-0">FR.IA.01. CEKLIS OBSERVASI AKTIVITAS DI TEMPAT KERJA ATAU TEMPAT KERJA SIMULASI</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('asesor.observasi.store') }}" id="observasiForm">
                        @csrf
                        
                        <!-- Informasi Dasar -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="alert alert-info">
                                    <strong>Informasi Ceklis Observasi</strong><br>
                                    Pilih mahasiswa yang telah menyelesaikan persetujuan asesmen untuk membuat ceklis observasi.
                                </div>
                            </div>
                        </div>

                        <!-- Pilih Mahasiswa -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <label for="pendaftaran_id" class="form-label"><strong>Pilih Mahasiswa</strong></label>
                                <select class="form-select @error('pendaftaran_id') is-invalid @enderror" 
                                        id="pendaftaran_id" name="pendaftaran_id" required>
                                    <option value="">-- Pilih Mahasiswa --</option>
                                    @foreach($pendaftaran as $p)
                                        <option value="{{ $p->id }}" 
                                                data-judul="{{ $p->skemaSertifikasi->nama_skema }}"
                                                data-nomor="{{ $p->skemaSertifikasi->nomor_skema }}"
                                                data-nama="{{ $p->user->name }}">
                                            {{ $p->user->name }} - {{ $p->skemaSertifikasi->nama_skema }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('pendaftaran_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Informasi Skema -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label"><strong>Skema Sertifikasi</strong></label>
                                <div class="form-control-plaintext">
                                    <span class="text-decoration-line-through">KKNI</span> / 
                                    <strong>Okupasi</strong> / 
                                    <span class="text-decoration-line-through">Klaster</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label"><strong>Judul</strong></label>
                                <div class="form-control-plaintext" id="judul-display">-</div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label"><strong>Nomor</strong></label>
                                <div class="form-control-plaintext" id="nomor-display">-</div>
                            </div>
                            <div class="col-md-6">
                                <label for="tuk" class="form-label"><strong>TUK</strong></label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="tuk" id="tuk_sewaktu" value="sewaktu">
                                    <label class="form-check-label" for="tuk_sewaktu">Sewaktu</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="tuk" id="tuk_tempat_kerja" value="tempat_kerja">
                                    <label class="form-check-label" for="tuk_tempat_kerja">Tempat Kerja</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="tuk" id="tuk_mandiri" value="mandiri">
                                    <label class="form-check-label" for="tuk_mandiri">Mandiri</label>
                                </div>
                                @error('tuk')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Informasi Asesor dan Asesi -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label"><strong>Nama Asesor</strong></label>
                                <div class="form-control-plaintext">{{ Auth::user()->name }}</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label"><strong>Nama Asesi</strong></label>
                                <div class="form-control-plaintext" id="nama-asesi-display">-</div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="tanggal" class="form-label"><strong>Tanggal</strong></label>
                                <input type="date" class="form-control @error('tanggal') is-invalid @enderror" 
                                       id="tanggal" name="tanggal" value="{{ date('Y-m-d') }}" required>
                                @error('tanggal')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Unit Kompetensi, Elemen dan Kriteria Unjuk Kerja -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                                    <h5 class="mb-0"><strong>Unit Kompetensi, Elemen dan Kriteria Unjuk Kerja</strong></h5>
                                    <div class="d-flex gap-2 ms-auto d-none" id="select-k-bk-buttons">
                                        <button type="button" class="btn btn-sm btn-outline-success" id="btn-pilih-semua-k" title="Centang semua kolom K">
                                            <i class="fas fa-check-double me-1"></i>Pilih Semua K
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-warning" id="btn-pilih-semua-bk" title="Centang semua kolom BK">
                                            <i class="fas fa-times-circle me-1"></i>Pilih Semua BK
                                        </button>
                                    </div>
                                </div>
                                <div id="loading-message" class="alert alert-warning">
                                    <i class="fas fa-info-circle me-2"></i>
                                    Pilih mahasiswa terlebih dahulu untuk memuat unit kompetensi, elemen dan kriteria unjuk kerja.
                                </div>
                                
                                <div id="unit-kompetensi-container">
                                    <!-- Unit kompetensi, elemen dan kriteria akan dimuat via AJAX -->
                                </div>
                            </div>
                        </div>

                        <!-- Umpan Balik Untuk Asesi -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5><strong>Umpan Balik Untuk Asesi</strong></h5>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <tbody>
                                            <tr>
                                                <td class="bg-light">
                                                    <label for="umpan_balik" class="form-label mb-0"><strong>Umpan Balik:</strong></label>
                                                </td>
                                                <td>
                                                    <textarea class="form-control @error('umpan_balik') is-invalid @enderror" 
                                                              id="umpan_balik" name="umpan_balik" rows="4" 
                                                              placeholder="Berikan umpan balik untuk asesi berdasarkan hasil observasi">{{ old('umpan_balik') }}</textarea>
                                                    @error('umpan_balik')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </td>
                                            </tr>
                                        </tbody>
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
                                                    <span id="nama-asesi-signature">{{ old('nama_asesi', '-') }}</span>
                                                </td>
                                                <td>
                                                    <div class="text-center">
                                                        <p class="text-muted mb-2">
                                                            <i class="fas fa-info-circle me-1"></i>
                                                            Tanda tangan asesi akan diisi oleh mahasiswa setelah ceklis observasi dikirim
                                                        </p>
                                                        <div class="border rounded p-3 bg-light">
                                                            <i class="fas fa-signature fa-2x text-muted"></i>
                                                            <p class="mb-0 mt-2 text-muted">Tanda Tangan Asesi</p>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="text-center">
                                                        <p class="text-muted mb-2">
                                                            <i class="fas fa-info-circle me-1"></i>
                                                            Tanggal akan diisi otomatis saat mahasiswa menandatangani
                                                        </p>
                                                        <div class="border rounded p-3 bg-light">
                                                            <i class="fas fa-calendar fa-2x text-muted"></i>
                                                            <p class="mb-0 mt-2 text-muted">Tanggal Asesi</p>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="bg-light">
                                                    <strong>Asesor:</strong><br>
                                                    <span id="nama-asesor-signature">{{ Auth::user()->name }}</span>
                                                </td>
                                                <td>
                                                    <div class="text-center">
                                                        <canvas id="asesorSignatureCanvas" width="300" height="150" 
                                                                style="border: 1px solid #ddd; cursor: crosshair; background: white;"></canvas>
                                                        <div class="mt-2">
                                                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="clearAsesorSignature()">
                                                                <i class="fas fa-eraser me-1"></i>Hapus
                                                            </button>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="text-center">
                                                        <input type="date" class="form-control" name="tanggal_asesor" 
                                                               value="{{ old('tanggal_asesor', date('Y-m-d')) }}" 
                                                               readonly>
                                                        <small class="text-muted">Tanggal saat ini</small>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Hidden inputs untuk elemen_data -->
                        <input type="hidden" name="elemen_data" id="elemen_data_input">
                        <input type="hidden" name="asesor_signature" id="asesor_signature_input">


                        <!-- Buttons -->
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('asesor.observasi.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left me-1"></i>Kembali
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-1"></i>Simpan Ceklis Observasi
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const pendaftaranSelect = document.getElementById('pendaftaran_id');
    const judulDisplay = document.getElementById('judul-display');
    const nomorDisplay = document.getElementById('nomor-display');
    const namaAsesiDisplay = document.getElementById('nama-asesi-display');
    const unitKompetensiContainer = document.getElementById('unit-kompetensi-container');
    const elemenDataInput = document.getElementById('elemen_data_input');
    const loadingMessage = document.getElementById('loading-message');

    pendaftaranSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        
        if (selectedOption.value) {
            judulDisplay.textContent = selectedOption.dataset.judul;
            nomorDisplay.textContent = selectedOption.dataset.nomor;
            namaAsesiDisplay.textContent = selectedOption.dataset.nama;
            
            // Update nama asesi in signature section
            document.getElementById('nama-asesi-signature').textContent = selectedOption.dataset.nama;
            
            // Hide loading message and show loading indicator
            loadingMessage.style.display = 'none';
            unitKompetensiContainer.innerHTML = '<div class="alert alert-info"><i class="fas fa-spinner fa-spin me-2"></i>Memuat unit kompetensi, elemen dan kriteria unjuk kerja...</div>';
            
            // Load unit kompetensi, elemen dan kriteria
            loadUnitKompetensi(selectedOption.value);
        } else {
            judulDisplay.textContent = '-';
            nomorDisplay.textContent = '-';
            namaAsesiDisplay.textContent = '-';
            document.getElementById('nama-asesi-signature').textContent = '-';
            loadingMessage.style.display = 'block';
            unitKompetensiContainer.innerHTML = '';
            const selectKBkButtons = document.getElementById('select-k-bk-buttons');
            if (selectKBkButtons) selectKBkButtons.classList.add('d-none');
        }
    });

    function loadUnitKompetensi(pendaftaranId) {
        
        fetch(`/asesor/observasi/get-unit-kompetensi/${pendaftaranId}`)
            .then(response => {
                return response.json();
            })
            .then(data => {
                
                if (data.success) {
                    displayUnitKompetensi(data.unitKompetensi);
                } else {
                    unitKompetensiContainer.innerHTML = '<div class="alert alert-danger">Gagal memuat unit kompetensi</div>';
                    loadingMessage.style.display = 'none';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                unitKompetensiContainer.innerHTML = '<div class="alert alert-danger">Terjadi kesalahan saat memuat data</div>';
                loadingMessage.style.display = 'none';
            });
    }

    function displayUnitKompetensi(unitKompetensi) {
        
        let html = '';
        
        unitKompetensi.forEach((unit, unitIndex) => {
            html += `
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Unit Kompetensi ${unitIndex + 1}: ${unit.nama_unit_kompetensi}</h5>
                    </div>
                    <div class="card-body">
            `;
            
            unit.elemen.forEach((elemen, elemenIndex) => {
                html += `
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">Elemen ${elemenIndex + 1}: ${elemen.nama_elemen}</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm">
                                    <thead class="table-dark">
                                        <tr>
                                            <th width="40%">Kriteria Unjuk Kerja</th>
                                            <th width="20%" class="text-center">K</th>
                                            <th width="20%" class="text-center">BK</th>
                                            <th width="20%">Benchmark (SOP/Spesifikasi Produk Industri)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                `;
                
                elemen.kriteria.forEach((kriteria, kriteriaIndex) => {
                    html += `
                        <tr>
                            <td>
                                <small>${kriteria.nama_kriteria}</small>
                            </td>
                            <td class="text-center">
                                <input type="radio" name="observasi_data[${unitIndex}][${elemenIndex}][${kriteriaIndex}]" 
                                       value="K" class="form-check-input" 
                                       onchange="togglePenilaianLanjut(${unitIndex}, ${elemenIndex}, ${kriteriaIndex}, this)">
                            </td>
                            <td class="text-center">
                                <input type="radio" name="observasi_data[${unitIndex}][${elemenIndex}][${kriteriaIndex}]" 
                                       value="BK" class="form-check-input" 
                                       onchange="togglePenilaianLanjut(${unitIndex}, ${elemenIndex}, ${kriteriaIndex}, this)">
                            </td>
                            <td>
                                <input type="text" name="benchmark[${unitIndex}][${elemenIndex}][${kriteriaIndex}]" 
                                       class="form-control form-control-sm" 
                                       placeholder="SOP/Spesifikasi">
                            </td>
                        </tr>
                        <tr id="penilaian-lanjut-${unitIndex}-${elemenIndex}-${kriteriaIndex}" style="display: none;">
                            <td colspan="4">
                                <div class="alert alert-warning mb-0">
                                    <label class="form-label"><strong>Penilaian Lanjut:</strong></label>
                                    <textarea name="penilaian_lanjut[${unitIndex}][${elemenIndex}][${kriteriaIndex}]" 
                                              class="form-control form-control-sm" rows="2" 
                                              placeholder="Diisi bila hasil belum dapat disimpulkan, untuk itu gunakan metode lain sehingga keputusan dapat dibuat"></textarea>
                                </div>
                            </td>
                        </tr>
                    `;
                });
                
                html += `
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                `;
            });
            
            html += `
                    </div>
                </div>
            `;
        });
        
        unitKompetensiContainer.innerHTML = html;
        
        // Store unit kompetensi data
        elemenDataInput.value = JSON.stringify(unitKompetensi);
        
        // Tampilkan tombol Pilih Semua K / BK
        const selectKBkButtons = document.getElementById('select-k-bk-buttons');
        if (selectKBkButtons) selectKBkButtons.classList.remove('d-none');
        
        // Ensure loading message is hidden
        loadingMessage.style.display = 'none';
    }

    // Tombol Pilih Semua K / BK
    document.getElementById('btn-pilih-semua-k').addEventListener('click', function() {
        const container = document.getElementById('unit-kompetensi-container');
        const kRadios = container.querySelectorAll('input[name^="observasi_data"][value="K"]');
        kRadios.forEach(function(r) { r.checked = true; });
        container.querySelectorAll('[id^="penilaian-lanjut-"]').forEach(function(row) {
            row.style.display = 'none';
        });
    });
    document.getElementById('btn-pilih-semua-bk').addEventListener('click', function() {
        const container = document.getElementById('unit-kompetensi-container');
        const bkRadios = container.querySelectorAll('input[name^="observasi_data"][value="BK"]');
        bkRadios.forEach(function(r) { r.checked = true; });
        container.querySelectorAll('[id^="penilaian-lanjut-"]').forEach(function(row) {
            row.style.display = '';
        });
    });

    // Function to toggle penilaian lanjut when BK is selected
    window.togglePenilaianLanjut = function(unitIndex, elemenIndex, kriteriaIndex, radioButton) {
        const penilaianRow = document.getElementById(`penilaian-lanjut-${unitIndex}-${elemenIndex}-${kriteriaIndex}`);
        
        if (radioButton.value === 'BK') {
            penilaianRow.style.display = '';
        } else {
            penilaianRow.style.display = 'none';
        }
    }

    // Signature canvas functionality
    let isDrawing = false;
    const canvas = document.getElementById('asesorSignatureCanvas');
    const ctx = canvas.getContext('2d');
    const signatureInput = document.getElementById('asesor_signature_input');

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
    window.clearAsesorSignature = function() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        signatureInput.value = '';
    }

    // Load signature from personalization
    function loadSignatureFromPersonalization() {
        fetch('/asesor/personalization/get-signature')
            .then(response => response.json())
            .then(data => {
                if (data.success && data.signature) {
                    const img = new Image();
                    img.onload = function() {
                        ctx.clearRect(0, 0, canvas.width, canvas.height);
                        ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
                        signatureInput.value = canvas.toDataURL();
                    };
                    img.src = data.signature;
                }
            })
            .catch(error => {
                console.log('No signature found in personalization');
            });
    }

    // Load signature on page load
    loadSignatureFromPersonalization();


    // Function to collect observasi data
    function collectObservasiData() {
        const observasiData = {};
        const benchmark = {};
        const penilaianLanjut = {};
        
        // Get all radio buttons for K/BK
        const radioButtons = document.querySelectorAll('input[name^="observasi_data"]');
        radioButtons.forEach(radio => {
            if (radio.checked) {
                const name = radio.name;
                const value = radio.value;
                
                // Extract indices from name like "observasi_data[0][0][0]"
                const matches = name.match(/observasi_data\[(\d+)\]\[(\d+)\]\[(\d+)\]/);
                if (matches) {
                    const unitIndex = parseInt(matches[1]);
                    const elemenIndex = parseInt(matches[2]);
                    const kriteriaIndex = parseInt(matches[3]);
                    
                    if (!observasiData[unitIndex]) observasiData[unitIndex] = {};
                    if (!observasiData[unitIndex][elemenIndex]) observasiData[unitIndex][elemenIndex] = {};
                    observasiData[unitIndex][elemenIndex][kriteriaIndex] = value;
                }
            }
        });
        
        // Get all benchmark inputs
        const benchmarkInputs = document.querySelectorAll('input[name^="benchmark"]');
        benchmarkInputs.forEach(input => {
            const matches = input.name.match(/benchmark\[(\d+)\]\[(\d+)\]\[(\d+)\]/);
            if (matches && input.value.trim()) {
                const unitIndex = parseInt(matches[1]);
                const elemenIndex = parseInt(matches[2]);
                const kriteriaIndex = parseInt(matches[3]);
                
                if (!benchmark[unitIndex]) benchmark[unitIndex] = {};
                if (!benchmark[unitIndex][elemenIndex]) benchmark[unitIndex][elemenIndex] = {};
                benchmark[unitIndex][elemenIndex][kriteriaIndex] = input.value;
            }
        });
        
        // Get all penilaian lanjut textareas
        const penilaianInputs = document.querySelectorAll('textarea[name^="penilaian_lanjut"]');
        penilaianInputs.forEach(textarea => {
            const matches = textarea.name.match(/penilaian_lanjut\[(\d+)\]\[(\d+)\]\[(\d+)\]/);
            if (matches && textarea.value.trim()) {
                const unitIndex = parseInt(matches[1]);
                const elemenIndex = parseInt(matches[2]);
                const kriteriaIndex = parseInt(matches[3]);
                
                if (!penilaianLanjut[unitIndex]) penilaianLanjut[unitIndex] = {};
                if (!penilaianLanjut[unitIndex][elemenIndex]) penilaianLanjut[unitIndex][elemenIndex] = {};
                penilaianLanjut[unitIndex][elemenIndex][kriteriaIndex] = textarea.value;
            }
        });
        
        return { observasiData, benchmark, penilaianLanjut };
    }

    // Form submission handling
    const form = document.getElementById('observasiForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            // Collect observasi data
            const { observasiData, benchmark, penilaianLanjut } = collectObservasiData();
            
            // Add observasi data to form
            const observasiDataInput = document.createElement('input');
            observasiDataInput.type = 'hidden';
            observasiDataInput.name = 'observasi_data';
            observasiDataInput.value = JSON.stringify(observasiData);
            form.appendChild(observasiDataInput);
            
            const benchmarkInput = document.createElement('input');
            benchmarkInput.type = 'hidden';
            benchmarkInput.name = 'benchmark';
            benchmarkInput.value = JSON.stringify(benchmark);
            form.appendChild(benchmarkInput);
            
            const penilaianLanjutInput = document.createElement('input');
            penilaianLanjutInput.type = 'hidden';
            penilaianLanjutInput.name = 'penilaian_lanjut';
            penilaianLanjutInput.value = JSON.stringify(penilaianLanjut);
            form.appendChild(penilaianLanjutInput);
            
            // Collect all form data
            const formData = new FormData(form);
            
            // Validate required fields
            const pendaftaranId = document.getElementById('pendaftaran_id').value;
            const elemenData = document.getElementById('elemen_data_input').value;
            
            
            if (!pendaftaranId) {
                e.preventDefault();
                alert('Pilih mahasiswa terlebih dahulu');
                return false;
            }
            
            if (!elemenData) {
                e.preventDefault();
                alert('Unit kompetensi belum dimuat. Silakan pilih mahasiswa terlebih dahulu');
                return false;
            }
            
        });
    }

});
</script>
@endsection
