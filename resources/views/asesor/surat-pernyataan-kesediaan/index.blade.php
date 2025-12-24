@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-file-signature me-2"></i>
                        Surat Pernyataan Kesediaan
                    </h3>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($surat->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th width="5%">No</th>
                                        <th width="20%">No. Dokumen</th>
                                        <th width="20%">TUK</th>
                                        <th width="15%">Status</th>
                                        <th width="15%">Tanggal Diterima</th>
                                        <th width="15%">Tanggal Tanda Tangan</th>
                                        <th width="15%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($surat as $index => $item)
                                        <tr>
                                            <td class="text-center">{{ $index + 1 }}</td>
                                            <td>{{ $item->no_dokumen ?? '-' }}</td>
                                            <td>{{ $item->tuk->nama_tuk ?? '-' }}</td>
                                            <td>
                                                @if($item->status === 'sent')
                                                    <span class="badge bg-warning">Menunggu Tanda Tangan</span>
                                                @elseif($item->status === 'signed')
                                                    <span class="badge bg-success">Ditandatangani</span>
                                                @endif
                                            </td>
                                            <td>{{ $item->sent_at ? $item->sent_at->format('d/m/Y') : '-' }}</td>
                                            <td>{{ $item->tanggal_tanda_tangan ? $item->tanggal_tanda_tangan->format('d/m/Y') : '-' }}</td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('asesor.surat-pernyataan-kesediaan.show', $item->id) }}" 
                                                       class="btn btn-info btn-sm" title="Detail">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    @if($item->status === 'signed')
                                                        <a href="{{ route('asesor.surat-pernyataan-kesediaan.pdf', $item->id) }}" 
                                                           class="btn btn-danger btn-sm" title="Download PDF" target="_blank">
                                                            <i class="fas fa-file-pdf"></i>
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-file-signature fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Belum ada surat pernyataan kesediaan</h5>
                            <p class="text-muted">Surat pernyataan kesediaan yang dikirim admin akan muncul di sini.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

