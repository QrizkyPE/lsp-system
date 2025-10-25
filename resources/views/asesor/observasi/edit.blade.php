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
                    <form method="POST" action="{{ route('asesor.observasi.update', $observasiChecklist->id) }}">
                        @csrf
                        @method('PUT')
                        
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
                                    <label class="form-check-label" for="tuk_sewaktu">☐ Sewaktu</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="tuk" id="tuk_tempat_kerja" value="tempat_kerja" 
                                           {{ $observasiChecklist->tuk === 'tempat_kerja' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="tuk_tempat_kerja">☐ Tempat Kerja</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="tuk" id="tuk_mandiri" value="mandiri" 
                                           {{ $observasiChecklist->tuk === 'mandiri' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="tuk_mandiri">☐ Mandiri</label>
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
                                                                                    <input type="radio" name="observasi_data[{{ $unitIndex }}][{{ $elemenIndex }}][{{ $kriteriaIndex }}]" value="K" 
                                                                                           class="form-check-input" 
                                                                                           onchange="togglePenilaianLanjut({{ $unitIndex }}, {{ $elemenIndex }}, {{ $kriteriaIndex }}, this)"
                                                                                           {{ isset($observasiChecklist->observasi_data[$unitIndex][$elemenIndex][$kriteriaIndex]) && $observasiChecklist->observasi_data[$unitIndex][$elemenIndex][$kriteriaIndex] === 'K' ? 'checked' : '' }}>
                                                                                </td>
                                                                                <td class="text-center">
                                                                                    <input type="radio" name="observasi_data[{{ $unitIndex }}][{{ $elemenIndex }}][{{ $kriteriaIndex }}]" value="BK" 
                                                                                           class="form-check-input" 
                                                                                           onchange="togglePenilaianLanjut({{ $unitIndex }}, {{ $elemenIndex }}, {{ $kriteriaIndex }}, this)"
                                                                                           {{ isset($observasiChecklist->observasi_data[$unitIndex][$elemenIndex][$kriteriaIndex]) && $observasiChecklist->observasi_data[$unitIndex][$elemenIndex][$kriteriaIndex] === 'BK' ? 'checked' : '' }}>
                                                                                </td>
                                                                                <td>
                                                                                    <input type="text" name="benchmark[{{ $unitIndex }}][{{ $elemenIndex }}][{{ $kriteriaIndex }}]" 
                                                                                           class="form-control form-control-sm" 
                                                                                           value="{{ isset($observasiChecklist->benchmark[$unitIndex][$elemenIndex][$kriteriaIndex]) ? $observasiChecklist->benchmark[$unitIndex][$elemenIndex][$kriteriaIndex] : '' }}"
                                                                                           placeholder="SOP/Spesifikasi">
                                                                                </td>
                                                                            </tr>
                                                                            <tr id="penilaian-lanjut-{{ $unitIndex }}-{{ $elemenIndex }}-{{ $kriteriaIndex }}" 
                                                                                style="display: {{ isset($observasiChecklist->observasi_data[$unitIndex][$elemenIndex][$kriteriaIndex]) && $observasiChecklist->observasi_data[$unitIndex][$elemenIndex][$kriteriaIndex] === 'BK' ? '' : 'none' }};">
                                                                                <td colspan="4">
                                                                                    <div class="alert alert-warning mb-0">
                                                                                        <label class="form-label"><strong>Penilaian Lanjut:</strong></label>
                                                                                        <textarea name="penilaian_lanjut[{{ $unitIndex }}][{{ $elemenIndex }}][{{ $kriteriaIndex }}]" 
                                                                                                  class="form-control form-control-sm" rows="2" 
                                                                                                  placeholder="Diisi bila hasil belum dapat disimpulkan, untuk itu gunakan metode lain sehingga keputusan dapat dibuat">{{ isset($observasiChecklist->penilaian_lanjut[$unitIndex][$elemenIndex][$kriteriaIndex]) ? $observasiChecklist->penilaian_lanjut[$unitIndex][$elemenIndex][$kriteriaIndex] : '' }}</textarea>
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

                        <!-- Istilah Acuan -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <label for="istilah_acuan" class="form-label"><strong>Istilah Acuan Pembanding</strong></label>
                                <textarea class="form-control @error('istilah_acuan') is-invalid @enderror" 
                                          id="istilah_acuan" name="istilah_acuan" rows="3" 
                                          placeholder="SOP/spesifikasi produk dari industri/organisasi dari tempat kerja atau simulasi tempat kerja">{{ old('istilah_acuan', $observasiChecklist->istilah_acuan) }}</textarea>
                                @error('istilah_acuan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Penilaian Lanjut -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <label for="penilaian_lanjut" class="form-label"><strong>Penilaian Lanjut</strong></label>
                                <textarea class="form-control @error('penilaian_lanjut') is-invalid @enderror" 
                                          id="penilaian_lanjut" name="penilaian_lanjut" rows="3" 
                                          placeholder="Diisi bila hasil belum dapat disimpulkan, untuk itu gunakan metode lain sehingga keputusan dapat dibuat">{{ old('penilaian_lanjut', $observasiChecklist->penilaian_lanjut) }}</textarea>
                                @error('penilaian_lanjut')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
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
</script>
@endsection
