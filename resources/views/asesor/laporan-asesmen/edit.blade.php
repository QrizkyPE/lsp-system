@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-edit me-2"></i>
                        Edit Laporan Asesmen
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('asesor.laporan-asesmen.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i>
                            Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('asesor.laporan-asesmen.update', $laporan->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Informasi Laporan -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5><strong>Informasi Laporan Asesmen</strong></h5>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <tr>
                                            <td width="20%"><strong>Skema:</strong></td>
                                            <td>
                                                <input type="text" class="form-control" value="{{ $jadwal->skemaSertifikasi->nama_skema ?? '' }}" readonly>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>TUK:</strong></td>
                                            <td>
                                                <div class="mb-2">
                                                    <!-- <label class="form-label">Pilih Jenis TUK:</label> -->
                                                    <select name="tuk_type" class="form-select" required>
                                                        <option value="">-- Pilih Jenis TUK --</option>
                                                        <option value="sewaktu" {{ old('tuk_type', $laporan->tuk_type) === 'sewaktu' ? 'selected' : '' }}>Sewaktu</option>
                                                        <option value="tempat_kerja" {{ old('tuk_type', $laporan->tuk_type) === 'tempat_kerja' ? 'selected' : '' }}>Tempat Kerja</option>
                                                        <option value="mandiri" {{ old('tuk_type', $laporan->tuk_type) === 'mandiri' ? 'selected' : '' }}>Mandiri</option>
                                                    </select>
                                                </div>
                                                <!-- <div>
                                                    <small class="text-muted">Nama TUK: {{ $jadwal->tuk->nama_tuk ?? '-' }}</small>
                                                </div> -->
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Nama Asesor:</strong></td>
                                            <td>
                                                <input type="text" class="form-control" value="{{ $asesor->nama_lengkap ?? auth()->user()->name }}" readonly>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Tanggal:</strong></td>
                                            <td>
                                                <input type="date" name="tanggal" class="form-control"
                                                       value="{{ ($laporan->tanggal ?? $jadwal->tanggal_mulai ?? now())->format('Y-m-d') }}">
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Daftar Asesi -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h5 class="mb-0"><strong>Daftar Asesi</strong></h5>
                                    <button type="button" class="btn btn-sm btn-success" onclick="checkAllK()">
                                        <i class="fas fa-check-square me-1"></i> Centang Semua K
                                    </button>
                                </div>

                                @if($asesiList->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-bordered align-middle">
                                            <thead class="table-dark">
                                                <tr>
                                                    <th rowspan="2" class="text-center" style="width:5%;">No.</th>
                                                    <th rowspan="2" style="width:35%;">Nama Asesi</th>
                                                    <th colspan="2" class="text-center" style="width:20%;">Rekomendasi</th>
                                                    <th rowspan="2" style="width:40%;">Keterangan</th>
                                                </tr>
                                                <tr>
                                                    <th class="text-center" style="width:10%;">K</th>
                                                    <th class="text-center" style="width:10%;">BK</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($asesiList as $index => $asesi)
                                                    <tr>
                                                        <td class="text-center">{{ $index + 1 }}</td>
                                                        <td>{{ $asesi['nama'] }}</td>
                                                        <td class="text-center">
                                                            <input type="checkbox" 
                                                                   name="hasil_asesi[{{ $asesi['id'] }}][k]" 
                                                                   class="form-check-input rekom-k" 
                                                                   data-asesi-id="{{ $asesi['id'] }}"
                                                                   {{ !empty($asesi['k']) ? 'checked' : '' }}>
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="checkbox" 
                                                                   name="hasil_asesi[{{ $asesi['id'] }}][bk]" 
                                                                   class="form-check-input rekom-bk" 
                                                                   data-asesi-id="{{ $asesi['id'] }}"
                                                                   {{ !empty($asesi['bk']) ? 'checked' : '' }}>
                                                        </td>
                                                        <td>
                                                            <input type="text" 
                                                                   name="hasil_asesi[{{ $asesi['id'] }}][keterangan]" 
                                                                   class="form-control form-control-sm" 
                                                                   placeholder="Masukkan keterangan untuk asesi ini"
                                                                   value="{{ $asesi['keterangan'] ?? '' }}">
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="alert alert-warning">
                                        <i class="fas fa-exclamation-triangle me-2"></i>
                                        Belum ada asesi yang ditugaskan untuk jadwal ini.
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Ringkasan & Catatan -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5><strong>Ringkasan Laporan</strong></h5>
                                <div class="mb-3">
                                    <label class="form-label"><strong>Aspek Negatif dan Positif dalam Asesmen</strong></label>
                                    <textarea name="aspek_positif_negatif" class="form-control" rows="3">{{ old('aspek_positif_negatif', $laporan->aspek_positif_negatif) }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label"><strong>Pencatatan Penolakan Hasil Asesmen</strong></label>
                                    <textarea name="penolakan_hasil" class="form-control" rows="3">{{ old('penolakan_hasil', $laporan->penolakan_hasil) }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label"><strong>Saran Perbaikan (Asesor/Personil Terkait)</strong></label>
                                    <textarea name="saran_perbaikan" class="form-control" rows="3">{{ old('saran_perbaikan', $laporan->saran_perbaikan) }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label"><strong>Catatan</strong></label>
                                    <textarea name="catatan" class="form-control" rows="3">{{ old('catatan', $laporan->catatan) }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Informasi Asesor -->
                        <div class="row mb-4">
                            <div class="col-md-8">
                                <table class="table table-bordered">
                                    <tr>
                                        <td rowspan="4" style="width: 30%; vertical-align: top;">
                                            <strong>Catatan :</strong><br>
                                            <small>(Diisi jika ada catatan tambahan terkait pelaksanaan asesmen)</small>
                                        </td>
                                        <td style="width: 20%;"><strong>Asesor :</strong></td>
                                        <td style="width: 50%;">
                                            {{ $asesor->nama_lengkap ?? auth()->user()->name }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Nama</strong></td>
                                        <td>{{ $asesor->nama_lengkap ?? auth()->user()->name }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>No. Reg</strong></td>
                                        <td>{{ $asesor->no_reg ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center"><strong>Tanda Tangan / Tanggal</strong></td>
                                        <td>
                                            @if($asesorSignature)
                                                <div>
                                                    <img src="{{ $asesorSignature }}" alt="Tanda Tangan Asesor" style="max-width: 180px; max-height: 80px;">
                                                </div>
                                            @endif
                                            <div class="mt-1">
                                                {{ ($laporan->tanggal ?? now())->format('d/m/Y') }}
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 text-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i>
                                    Simpan Perubahan
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
function checkAllK() {
    document.querySelectorAll('.rekom-k').forEach(function(cb) {
        cb.checked = true;
    });
    document.querySelectorAll('.rekom-bk').forEach(function(cb) {
        cb.checked = false;
    });
}

document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.rekom-k').forEach(function(cb) {
        cb.addEventListener('change', function() {
            if (this.checked) {
                const id = this.getAttribute('data-asesi-id');
                const bk = document.querySelector('.rekom-bk[data-asesi-id="' + id + '"]');
                if (bk) bk.checked = false;
            }
        });
    });
    document.querySelectorAll('.rekom-bk').forEach(function(cb) {
        cb.addEventListener('change', function() {
            if (this.checked) {
                const id = this.getAttribute('data-asesi-id');
                const k = document.querySelector('.rekom-k[data-asesi-id="' + id + '"]');
                if (k) k.checked = false;
            }
        });
    });
});
</script>
@endsection
@endsection

