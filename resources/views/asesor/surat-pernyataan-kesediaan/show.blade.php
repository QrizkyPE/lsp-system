@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-eye me-2"></i>
                        Detail Surat Pernyataan Kesediaan
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('asesor.surat-pernyataan-kesediaan.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i>
                            Kembali
                        </a>
                        @if($surat->status === 'signed')
                            <a href="{{ route('asesor.surat-pernyataan-kesediaan.pdf', $surat->id) }}" class="btn btn-danger" target="_blank">
                                <i class="fas fa-file-pdf me-1"></i>
                                Download PDF
                            </a>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5><strong>Yang bertandatangan di bawah ini:</strong></h5>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <tr>
                                        <td width="20%"><strong>Nama</strong></td>
                                        <td>:</td>
                                        <td>{{ $surat->asesor->nama_lengkap ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Alamat</strong></td>
                                        <td>:</td>
                                        <td>{{ $surat->alamat ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>No. MET Sertifikat</strong></td>
                                        <td>:</td>
                                        <td>{{ $surat->no_met_sertifikat ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>TUK</strong></td>
                                        <td>:</td>
                                        <td>{{ $surat->tuk->nama_tuk ?? '-' }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-12">
                            <h5><strong>Dengan ini menyatakan:</strong></h5>
                            <ol>
                                <li>Bersedia untuk menjadi asesor dalam proses pelaksanaan asesmen</li>
                                <li>Menjaga kerahasiaan terhadap hasil asesmen selama proses asesmen maupun setelahnya.</li>
                                <li>Hasil asesmen hanya akan diberitahukan kepada LSP Universitas Multi Data Palembang dan asesi.</li>
                                <li>Menjaga kode etik profesi.</li>
                            </ol>
                            <p>
                                Demikian pernyataan ini saya buat dalam keadaan sadar dan tanpa paksaan dari pihak manapun dan bilamana di kemudian hari ternyata pernyataan ini tidak benar maka saya bersedia untuk menerima sanksi yang diberikan dari pihak yang terkait.
                            </p>
                        </div>
                    </div>

                    @if($surat->status === 'sent')
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5><strong>Tanda Tangan</strong></h5>
                                @if($signature)
                                    <div class="signature-container mb-3">
                                        <div id="signature-preview" style="display: none;">
                                            <img src="" alt="Tanda Tangan Preview" id="signature-img" style="max-width: 300px; max-height: 150px; border: 2px solid #28a745; padding: 10px; background: #f8f9fa;">
                                        </div>
                                        <button type="button" class="btn btn-success" id="btn-use-signature" onclick="useSignature()">
                                            <i class="fas fa-signature"></i> Gunakan Tanda Tangan Saya
                                        </button>
                                        <span id="signature-check" class="badge bg-success ms-2" style="display: none;">
                                            <i class="fas fa-check-circle"></i> Tanda Tangan Siap Digunakan
                                        </span>
                                    </div>
                                    <form action="{{ route('asesor.surat-pernyataan-kesediaan.sign', $surat->id) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="signature" id="signature-input" value="">
                                        <button type="submit" class="btn btn-primary" id="btn-submit" disabled>
                                            <i class="fas fa-check me-1"></i>
                                            Tandatangani Surat
                                        </button>
                                    </form>
                                @else
                                    <div class="alert alert-warning">
                                        <i class="fas fa-exclamation-triangle me-2"></i>
                                        Anda belum memiliki tanda tangan. Silakan buat tanda tangan di halaman Personalisasi terlebih dahulu.
                                    </div>
                                @endif
                            </div>
                        </div>
                    @elseif($surat->status === 'signed')
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5><strong>Tanda Tangan</strong></h5>
                                @if($surat->signature_data)
                                    <div>
                                        <img src="{{ $surat->signature_data }}" alt="Tanda Tangan" style="max-width: 300px; max-height: 150px; border: 2px solid #28a745; padding: 10px; background: #f8f9fa;">
                                    </div>
                                    <p class="mt-2"><strong>Tanggal Tanda Tangan:</strong> {{ $surat->tanggal_tanda_tangan ? $surat->tanggal_tanda_tangan->format('d/m/Y') : '-' }}</p>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@if($surat->status === 'sent' && $signature)
@section('scripts')
<script>
const signatureData = @json($signature);

function useSignature() {
    const inputHidden = document.getElementById('signature-input');
    const btnSubmit = document.getElementById('btn-submit');
    const btnUse = document.getElementById('btn-use-signature');
    const preview = document.getElementById('signature-preview');
    const imgPreview = document.getElementById('signature-img');
    const badgeCheck = document.getElementById('signature-check');
    
    if (!signatureData) {
        alert('Data tanda tangan tidak lengkap.');
        return false;
    }
    
    inputHidden.value = signatureData;
    imgPreview.src = signatureData;
    preview.style.display = 'block';
    badgeCheck.style.display = 'inline-block';
    btnSubmit.disabled = false;
    btnUse.classList.remove('btn-success');
    btnUse.classList.add('btn-secondary');
    btnUse.disabled = true;
    btnUse.innerHTML = '<i class="fas fa-check-circle"></i> Tanda Tangan Digunakan';
    
    return false;
}
</script>
@endsection
@endif
@endsection

