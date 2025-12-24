@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-edit me-2"></i>
                        Edit Daftar Hadir Peserta
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('asesor.daftar-hadir-peserta.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i>
                            Kembali
                        </a>
                        <a href="{{ route('asesor.daftar-hadir-peserta.pdf', $daftarHadir->id) }}" class="btn btn-danger" target="_blank">
                            <i class="fas fa-file-pdf me-1"></i>
                            Download PDF
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('asesor.daftar-hadir-peserta.update', $daftarHadir->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <!-- Informasi Dokumen -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5><strong>Informasi Dokumen</strong></h5>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <tr>
                                            <td width="20%"><strong>No. Dokumen:</strong></td>
                                            <td>
                                                <input type="text" name="no_dokumen" class="form-control" value="{{ $daftarHadir->no_dokumen ?? '' }}" placeholder="Masukkan nomor dokumen">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Edisi/Revisi:</strong></td>
                                            <td>
                                                <input type="text" name="edisi_revisi" class="form-control" value="{{ $daftarHadir->edisi_revisi ?? '' }}" placeholder="Masukkan edisi/revisi">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Tanggal Berlaku:</strong></td>
                                            <td>
                                                <input type="date" name="tanggal_berlaku" class="form-control" value="{{ $daftarHadir->tanggal_berlaku ? $daftarHadir->tanggal_berlaku->format('Y-m-d') : '' }}">
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Informasi Jadwal -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5><strong>Informasi Jadwal</strong></h5>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <tr>
                                            <td width="20%"><strong>Skema:</strong></td>
                                            <td>
                                                <input type="text" name="skema" class="form-control" value="{{ $daftarHadir->skema ?? ($daftarHadir->jadwalUji->skemaSertifikasi->nama_skema ?? '') }}" placeholder="Masukkan skema" readonly>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Hari/Tanggal:</strong></td>
                                            <td>
                                                <input type="date" name="hari_tanggal" class="form-control" value="{{ $daftarHadir->hari_tanggal ? $daftarHadir->hari_tanggal->format('Y-m-d') : '' }}">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>TUK:</strong></td>
                                            <td>{{ $daftarHadir->tuk->nama_tuk ?? ($daftarHadir->jadwalUji->tuk->nama_tuk ?? '-') }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Penanggung Jawab TUK:</strong></td>
                                            <td>
                                                <input type="text" name="penanggung_jawab_tuk" class="form-control" value="{{ $daftarHadir->penanggung_jawab_tuk ?? '' }}" placeholder="Masukkan nama penanggung jawab TUK">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Kepala TUK:</strong></td>
                                            <td>
                                                <input type="text" name="kepala_tuk" class="form-control" value="{{ $daftarHadir->kepala_tuk ?? '' }}" placeholder="Masukkan nama kepala TUK">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Jumlah Peserta (Huruf):</strong></td>
                                            <td>
                                                <input type="text" name="jumlah_peserta_huruf" class="form-control" value="{{ $daftarHadir->jumlah_peserta_huruf ?? '' }}" placeholder="Contoh: sepuluh, sebelas, dst">
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Daftar Peserta -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5><strong>Daftar Peserta</strong></h5>
                                @if($asesiList->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead class="table-dark">
                                                <tr>
                                                    <th width="5%">No</th>
                                                    <th width="25%">Nama Peserta</th>
                                                    <th width="15%">NPM</th>
                                                    <th width="20%">Tanda Tangan</th>
                                                    <th width="15%">Kehadiran</th>
                                                    <th width="20%">Keterangan</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($asesiList as $index => $asesi)
                                                    <tr>
                                                        <td class="text-center">{{ $index + 1 }}</td>
                                                        <td>{{ $asesi['nama'] }}</td>
                                                        <td>{{ $asesi['npm'] }}</td>
                                                        <td class="text-center">
                                                            @if($asesi['signature'] && $asesi['hadir'])
                                                                <img src="{{ $asesi['signature'] }}" 
                                                                     alt="Tanda Tangan" 
                                                                     class="signature-preview-{{ $asesi['id'] }}"
                                                                     style="max-width: 150px; max-height: 60px; border: 1px solid #ddd; {{ !$asesi['hadir'] ? 'display: none;' : '' }}">
                                                            @else
                                                                <span class="text-muted signature-preview-{{ $asesi['id'] }}" style="{{ !$asesi['hadir'] ? 'display: none;' : '' }}">-</span>
                                                            @endif
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="hidden" name="kehadiran_peserta[{{ $asesi['id'] }}][hadir]" value="{{ $asesi['hadir'] ? '1' : '0' }}" id="kehadiran-hidden-{{ $asesi['id'] }}">
                                                            <button type="button" 
                                                                    class="btn btn-sm kehadiran-btn-{{ $asesi['id'] }} {{ $asesi['hadir'] ? 'btn-success' : 'btn-outline-secondary' }}"
                                                                    data-asesi-id="{{ $asesi['id'] }}"
                                                                    onclick="toggleKehadiran({{ $asesi['id'] }})">
                                                                <i class="fas {{ $asesi['hadir'] ? 'fa-check' : 'fa-times' }}"></i>
                                                                <span class="kehadiran-text-{{ $asesi['id'] }}">{{ $asesi['hadir'] ? 'Hadir' : 'Tidak Hadir' }}</span>
                                                            </button>
                                                        </td>
                                                        <td>
                                                            <input type="text" name="kehadiran_peserta[{{ $asesi['id'] }}][keterangan]" class="form-control form-control-sm" placeholder="Keterangan" value="{{ $asesi['keterangan'] ?? '' }}">
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="alert alert-warning">
                                        <i class="fas fa-exclamation-triangle me-2"></i>
                                        Belum ada peserta yang ditugaskan untuk jadwal ini.
                                    </div>
                                @endif
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
function toggleKehadiran(asesiId) {
    const hiddenInput = document.getElementById('kehadiran-hidden-' + asesiId);
    const btn = document.querySelector('.kehadiran-btn-' + asesiId);
    const text = document.querySelector('.kehadiran-text-' + asesiId);
    const icon = btn.querySelector('i');
    const preview = document.querySelector('.signature-preview-' + asesiId);
    
    const isHadir = hiddenInput.value === '1';
    
    if (isHadir) {
        // Change to Tidak Hadir
        hiddenInput.value = '0';
        btn.classList.remove('btn-success');
        btn.classList.add('btn-outline-secondary');
        icon.classList.remove('fa-check');
        icon.classList.add('fa-times');
        text.textContent = 'Tidak Hadir';
        if (preview) {
            preview.style.display = 'none';
        }
    } else {
        // Change to Hadir
        hiddenInput.value = '1';
        btn.classList.remove('btn-outline-secondary');
        btn.classList.add('btn-success');
        icon.classList.remove('fa-times');
        icon.classList.add('fa-check');
        text.textContent = 'Hadir';
        if (preview) {
            preview.style.display = 'inline-block';
        }
    }
}
</script>
@endsection
@endsection

