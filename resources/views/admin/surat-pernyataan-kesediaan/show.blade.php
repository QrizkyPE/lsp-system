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
                        <a href="{{ route('admin.surat-pernyataan-kesediaan.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i>
                            Kembali
                        </a>
                        @if($surat->status === 'signed')
                            <a href="{{ route('admin.surat-pernyataan-kesediaan.pdf', $surat->id) }}" class="btn btn-danger" target="_blank">
                                <i class="fas fa-file-pdf me-1"></i>
                                Download PDF
                            </a>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <!-- Informasi Dokumen -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5><strong>Informasi Dokumen</strong></h5>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <tr>
                                        <td width="20%"><strong>No. Dokumen:</strong></td>
                                        <td>{{ $surat->no_dokumen ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Edisi/Revisi:</strong></td>
                                        <td>{{ $surat->edisi_revisi ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Tanggal Berlaku:</strong></td>
                                        <td>{{ $surat->tanggal_berlaku ? $surat->tanggal_berlaku->format('d/m/Y') : '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Status:</strong></td>
                                        <td>
                                            @if($surat->status === 'draft')
                                                <span class="badge bg-secondary">Draft</span>
                                            @elseif($surat->status === 'sent')
                                                <span class="badge bg-warning">Terkirim</span>
                                            @elseif($surat->status === 'signed')
                                                <span class="badge bg-success">Ditandatangani</span>
                                            @endif
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Informasi Asesor -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5><strong>Informasi Asesor</strong></h5>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <tr>
                                        <td width="20%"><strong>Nama:</strong></td>
                                        <td>{{ $surat->asesor->nama_lengkap ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Alamat:</strong></td>
                                        <td>{{ $surat->alamat ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>No. MET Sertifikat:</strong></td>
                                        <td>{{ $surat->no_met_sertifikat ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>TUK:</strong></td>
                                        <td>{{ $surat->tuk->nama_tuk ?? '-' }}</td>
                                    </tr>
                                    @if($surat->status === 'signed')
                                        <tr>
                                            <td><strong>Tanggal Tanda Tangan:</strong></td>
                                            <td>{{ $surat->tanggal_tanda_tangan ? $surat->tanggal_tanda_tangan->format('d/m/Y') : '-' }}</td>
                                        </tr>
                                    @endif
                                </table>
                            </div>
                        </div>
                    </div>

                    @if($surat->status === 'draft')
                        <div class="row">
                            <div class="col-12">
                                <form action="{{ route('admin.surat-pernyataan-kesediaan.send', $surat->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-success" onclick="return confirm('Apakah Anda yakin ingin mengirim surat ini ke asesor?')">
                                        <i class="fas fa-paper-plane me-1"></i>
                                        Kirim ke Asesor
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

