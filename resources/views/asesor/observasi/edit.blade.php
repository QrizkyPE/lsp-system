@extends('layouts.app')

@section('title', 'Edit Ceklis Observasi Aktivitas')
@section('page-title', 'Edit Ceklis Observasi Aktivitas')

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
                    <form method="POST" action="{{ route('asesor.observasi.update', $observasiChecklist->id) }}" id="observasiEditForm">
                        @csrf
                        @method('PUT')
                        
                        <!-- Hidden inputs untuk elemen_data -->
                        <input type="hidden" name="elemen_data" id="elemen_data_input" value="{{ json_encode($observasiChecklist->elemen_data) }}">
                        
                        <!-- Informasi Dasar -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="alert alert-info">
                                    <strong>Edit Ceklis Observasi</strong><br>
                                    Anda dapat mengedit informasi ceklis observasi dan melakukan observasi.
                                </div>
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
                                <div class="form-control-plaintext">{{ $observasiChecklist->judul }}</div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label"><strong>Nomor</strong></label>
                                <div class="form-control-plaintext">{{ $observasiChecklist->nomor_skema }}</div>
                            </div>
                            <div class="col-md-6">
                                <label for="tuk" class="form-label"><strong>TUK</strong></label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="tuk" id="tuk_sewaktu" value="sewaktu" 
                                           {{ $observasiChecklist->tuk === 'sewaktu' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="tuk_sewaktu">Sewaktu</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="tuk" id="tuk_tempat_kerja" value="tempat_kerja" 
                                           {{ $observasiChecklist->tuk === 'tempat_kerja' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="tuk_tempat_kerja">Tempat Kerja</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="tuk" id="tuk_mandiri" value="mandiri" 
                                           {{ $observasiChecklist->tuk === 'mandiri' ? 'checked' : '' }}>
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
                                <div class="form-control-plaintext">{{ $observasiChecklist->nama_asesor }}</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label"><strong>Nama Asesi</strong></label>
                                <div class="form-control-plaintext">{{ $observasiChecklist->nama_asesi }}</div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="tanggal" class="form-label"><strong>Tanggal</strong></label>
                                <input type="date" class="form-control @error('tanggal') is-invalid @enderror" 
                                       id="tanggal" name="tanggal" value="{{ $observasiChecklist->tanggal->format('Y-m-d') }}" required>
                                @error('tanggal')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Unit Kompetensi, Elemen dan Kriteria Unjuk Kerja -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5><strong>Unit Kompetensi, Elemen dan Kriteria Unjuk Kerja</strong></h5>
                                <div class="alert alert-warning">
                                    <i class="fas fa-info-circle me-2"></i>
                                    Beri tanda centang (√) pada kolom K jika asesi dapat melakukan/mendemonstrasikan tugas sesuai KUK, atau centang (√) pada kolom BK bila sebaliknya.
                                </div>
                                
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
                                                                                <td>
                                                                                    <small>{{ $kriteria['nama_kriteria'] }}</small>
                                                                                </td>
                                                                                <td class="text-center">
                                                                                    @php
                                                                                        $observasiValue = isset($observasiChecklist->observasi_data[$unitIndex][$elemenIndex][$kriteriaIndex]) ? $observasiChecklist->observasi_data[$unitIndex][$elemenIndex][$kriteriaIndex] : null;
                                                                                    @endphp
                                                                                    <input type="radio" name="observasi_data[{{ $unitIndex }}][{{ $elemenIndex }}][{{ $kriteriaIndex }}]" value="K" 
                                                                                           class="form-check-input" 
                                                                                           onchange="togglePenilaianLanjut({{ $unitIndex }}, {{ $elemenIndex }}, {{ $kriteriaIndex }}, this)"
                                                                                           {{ $observasiValue === 'K' ? 'checked' : '' }}>
                                                                                </td>
                                                                                <td class="text-center">
                                                                                    <input type="radio" name="observasi_data[{{ $unitIndex }}][{{ $elemenIndex }}][{{ $kriteriaIndex }}]" value="BK" 
                                                                                           class="form-check-input" 
                                                                                           onchange="togglePenilaianLanjut({{ $unitIndex }}, {{ $elemenIndex }}, {{ $kriteriaIndex }}, this)"
                                                                                           {{ $observasiValue === 'BK' ? 'checked' : '' }}>
                                                                                </td>
                                                                                <td>
                                                                                    @php
                                                                                        $benchmarkValue = isset($observasiChecklist->benchmark[$unitIndex][$elemenIndex][$kriteriaIndex]) ? $observasiChecklist->benchmark[$unitIndex][$elemenIndex][$kriteriaIndex] : '';
                                                                                        $benchmarkText = is_string($benchmarkValue) ? $benchmarkValue : (is_array($benchmarkValue) ? json_encode($benchmarkValue) : '');
                                                                                    @endphp
                                                                                    <input type="text" name="benchmark[{{ $unitIndex }}][{{ $elemenIndex }}][{{ $kriteriaIndex }}]" 
                                                                                           class="form-control form-control-sm" 
                                                                                           value="{{ $benchmarkText }}"
                                                                                           placeholder="SOP/Spesifikasi">
                                                                                </td>
                                                                            </tr>
                                                                            <tr id="penilaian-lanjut-{{ $unitIndex }}-{{ $elemenIndex }}-{{ $kriteriaIndex }}" 
                                                                                style="display: {{ $observasiValue === 'BK' ? '' : 'none' }};">
                                                                                <td colspan="4">
                                                                                    <div class="alert alert-warning mb-0">
                                                                                        <label class="form-label"><strong>Penilaian Lanjut:</strong></label>
                                                                                        @php
                                                                                            $penilaianLanjutValue = isset($observasiChecklist->penilaian_lanjut[$unitIndex][$elemenIndex][$kriteriaIndex]) ? $observasiChecklist->penilaian_lanjut[$unitIndex][$elemenIndex][$kriteriaIndex] : '';
                                                                                            $penilaianLanjutText = is_string($penilaianLanjutValue) ? $penilaianLanjutValue : (is_array($penilaianLanjutValue) ? json_encode($penilaianLanjutValue) : '');
                                                                                        @endphp
                                                                                        <textarea name="penilaian_lanjut[{{ $unitIndex }}][{{ $elemenIndex }}][{{ $kriteriaIndex }}]" 
                                                                                                  class="form-control form-control-sm" rows="2" 
                                                                                                  placeholder="Diisi bila hasil belum dapat disimpulkan, untuk itu gunakan metode lain sehingga keputusan dapat dibuat">{{ $penilaianLanjutText }}</textarea>
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
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
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('asesor.observasi.show', $observasiChecklist->id) }}" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left me-1"></i>Kembali
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-1"></i>Simpan Perubahan
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
// Function to toggle penilaian lanjut when BK is selected
function togglePenilaianLanjut(unitIndex, elemenIndex, kriteriaIndex, radioButton) {
    const penilaianRow = document.getElementById(`penilaian-lanjut-${unitIndex}-${elemenIndex}-${kriteriaIndex}`);
    
    if (radioButton.value === 'BK') {
        penilaianRow.style.display = '';
    } else {
        penilaianRow.style.display = 'none';
    }
}

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
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('observasiEditForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            // Collect observasi data
            const { observasiData, benchmark, penilaianLanjut } = collectObservasiData();
            
            // Remove existing hidden inputs if any
            const existingObservasiInput = form.querySelector('input[name="observasi_data"][type="hidden"]:not(#elemen_data_input)');
            const existingBenchmarkInput = form.querySelector('input[name="benchmark"][type="hidden"]');
            const existingPenilaianInput = form.querySelector('input[name="penilaian_lanjut"][type="hidden"]');
            
            if (existingObservasiInput) existingObservasiInput.remove();
            if (existingBenchmarkInput) existingBenchmarkInput.remove();
            if (existingPenilaianInput) existingPenilaianInput.remove();
            
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
        });
    }
});
</script>
@endsection
