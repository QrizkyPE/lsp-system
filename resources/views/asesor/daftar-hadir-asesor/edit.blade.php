@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-edit me-2"></i>
                        Edit Daftar Hadir Asesor
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('asesor.daftar-hadir-asesor.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i>
                            Kembali
                        </a>
                        <a href="{{ route('asesor.daftar-hadir-asesor.pdf', $daftarHadir->id) }}" class="btn btn-danger" target="_blank">
                            <i class="fas fa-file-pdf me-1"></i>
                            Download PDF
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('asesor.daftar-hadir-asesor.update', $daftarHadir->id) }}" method="POST">
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
                                                <input type="text" name="no_dokumen" class="form-control" value="{{ $daftarHadir->no_dokumen }}" placeholder="Masukkan nomor dokumen">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Edisi/Revisi:</strong></td>
                                            <td>
                                                <input type="text" name="edisi_revisi" class="form-control" value="{{ $daftarHadir->edisi_revisi }}" placeholder="Masukkan edisi/revisi">
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
                                                <input type="text" name="skema" class="form-control" value="{{ $daftarHadir->skema ?? ($daftarHadir->jadwalUji->skemaSertifikasi->nama_skema ?? '') }}" placeholder="Masukkan skema">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Pukul:</strong></td>
                                            <td>
                                                <div class="row">
                                                    <div class="col-md-5">
                                                        <input type="time" name="pukul_mulai" class="form-control" value="{{ $daftarHadir->pukul_mulai ? \Carbon\Carbon::parse($daftarHadir->pukul_mulai)->format('H:i') : '' }}">
                                                    </div>
                                                    <div class="col-md-2 text-center">
                                                        <label class="form-label">s/d</label>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <input type="time" name="pukul_selesai" class="form-control" value="{{ $daftarHadir->pukul_selesai ? \Carbon\Carbon::parse($daftarHadir->pukul_selesai)->format('H:i') : '' }}">
                                                    </div>
                                                </div>
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
                                                <input type="text" name="penanggung_jawab_tuk" class="form-control" value="{{ $daftarHadir->penanggung_jawab_tuk }}" placeholder="Masukkan nama penanggung jawab TUK">
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Daftar Asesor -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5><strong>Daftar Asesor</strong></h5>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead class="table-dark">
                                            <tr>
                                                <th width="5%">No</th>
                                                <th width="45%">Nama Asesor</th>
                                                <th width="50%">Tanda Tangan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($asesorList as $index => $asesor)
                                                @php
                                                    $existingSignature = $existingSignatures[$asesor['id']] ?? null;
                                                    $hasSignature = $existingSignature || isset($signatures[$asesor['id']]);
                                                @endphp
                                                <tr>
                                                    <td class="text-center">{{ $index + 1 }}</td>
                                                    <td>{{ $asesor['nama'] }}</td>
                                                    <td>
                                                        <div class="signature-container" id="signature-container-{{ $asesor['id'] }}" data-asesor-id="{{ $asesor['id'] }}">
                                                            @php
                                                                $signatureData = $signatures[$asesor['id']] ?? '';
                                                            @endphp
                                                            @if($signatureData)
                                                                @if($existingSignature)
                                                                    <div id="signature-status-{{ $asesor['id'] }}">
                                                                        <button type="button" class="btn btn-sm btn-secondary" disabled>
                                                                            <i class="fas fa-check-circle"></i> Tanda Tangan Digunakan
                                                                        </button>
                                                                        <span class="badge bg-success ms-2">
                                                                            <i class="fas fa-check-circle"></i> Sudah Digunakan
                                                                        </span>
                                                                    </div>
                                                                    <div class="signature-preview mt-2" id="signature-preview-{{ $asesor['id'] }}">
                                                                        <img src="{{ $existingSignature }}" alt="Tanda Tangan" id="signature-img-{{ $asesor['id'] }}" style="max-width: 200px; max-height: 100px; border: 1px solid #28a745; padding: 5px; background: #f8f9fa;">
                                                                    </div>
                                                                @else
                                                                    <div id="signature-status-{{ $asesor['id'] }}">
                                                                    <button type="button" class="btn btn-sm btn-success btn-use-signature" id="btn-signature-{{ $asesor['id'] }}" data-asesor-id="{{ $asesor['id'] }}" data-signature="{{ htmlspecialchars($signatureData, ENT_QUOTES, 'UTF-8') }}" onclick="useSignature({{ $asesor['id'] }}, '{{ htmlspecialchars($signatureData, ENT_QUOTES, 'UTF-8') }}'); return false;">
                                                                        <i class="fas fa-signature"></i> Gunakan Tanda Tangan
                                                                    </button>
                                                                        <span id="signature-check-{{ $asesor['id'] }}" class="badge bg-success ms-2" style="display: none;">
                                                                            <i class="fas fa-check-circle"></i> Sudah Digunakan
                                                                        </span>
                                                                    </div>
                                                                    <div class="signature-preview mt-2" id="signature-preview-{{ $asesor['id'] }}" style="display: none;">
                                                                        <img src="" alt="Tanda Tangan" id="signature-img-{{ $asesor['id'] }}" style="max-width: 200px; max-height: 100px; border: 1px solid #28a745; padding: 5px; background: #f8f9fa;">
                                                                    </div>
                                                                @endif
                                                            @else
                                                                <span class="text-muted"><i class="fas fa-exclamation-triangle"></i> Tanda tangan belum tersedia</span>
                                                            @endif
                                                            <input type="hidden" name="asesor_signatures[{{ $asesor['id'] }}]" id="signature-input-{{ $asesor['id'] }}" value="{{ $existingSignature ?? '' }}">
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
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
// Global function untuk memastikan bisa dipanggil dari onclick
function useSignature(asesorId, signature) {
    console.log('useSignature called:', asesorId, signature ? 'Has signature' : 'No signature');
    
    if (!signature || !asesorId) {
        alert('Data tanda tangan tidak lengkap.');
        return false;
    }
    
    // Set input hidden
    const inputHidden = document.getElementById('signature-input-' + asesorId);
    if (inputHidden) {
        inputHidden.value = signature;
        console.log('Input hidden set');
    } else {
        console.error('Input hidden not found:', 'signature-input-' + asesorId);
        alert('Error: Input tidak ditemukan');
        return false;
    }
    
    // Show preview
    const preview = document.getElementById('signature-preview-' + asesorId);
    const imgPreview = document.getElementById('signature-img-' + asesorId);
    if (preview && imgPreview) {
        imgPreview.src = signature;
        preview.style.display = 'block';
        console.log('Preview shown');
    } else {
        console.error('Preview elements not found');
    }
    
    // Show badge
    const badgeCheck = document.getElementById('signature-check-' + asesorId);
    if (badgeCheck) {
        badgeCheck.style.display = 'inline-block';
        console.log('Badge shown');
    }
    
    // Update button
    const btn = document.getElementById('btn-signature-' + asesorId);
    if (btn) {
        btn.classList.remove('btn-success');
        btn.classList.add('btn-secondary');
        btn.innerHTML = '<i class="fas fa-check-circle"></i> Tanda Tangan Digunakan';
        btn.disabled = true;
        btn.onclick = null; // Remove onclick handler
    }
    
    // Animation
    const container = document.getElementById('signature-container-' + asesorId);
    if (container) {
        container.style.transition = 'all 0.3s ease';
        container.style.backgroundColor = '#d4edda';
        container.style.padding = '10px';
        container.style.borderRadius = '5px';
        
        setTimeout(function() {
            container.style.backgroundColor = '';
        }, 2000);
    }
    
    return false;
}

// Also attach event listeners as backup
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, attaching event listeners');
    const buttons = document.querySelectorAll('.btn-use-signature');
    console.log('Found buttons:', buttons.length);
    
    buttons.forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const asesorId = this.getAttribute('data-asesor-id');
            const signature = this.getAttribute('data-signature');
            useSignature(asesorId, signature);
            return false;
        });
    });
});
</script>
@endsection
@endsection

