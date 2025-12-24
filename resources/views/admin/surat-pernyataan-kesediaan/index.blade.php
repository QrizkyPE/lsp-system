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
                    <div class="card-tools">
                        <a href="{{ route('admin.surat-pernyataan-kesediaan.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-1"></i>
                            Buat Surat
                        </a>
                    </div>
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
                                        <th width="20%">Nama Asesor</th>
                                        <th width="15%">No. Dokumen</th>
                                        <th width="15%">TUK</th>
                                        <th width="15%">Status</th>
                                        <th width="15%">Tanggal Dibuat</th>
                                        <th width="15%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($surat as $index => $item)
                                        <tr>
                                            <td class="text-center">{{ $index + 1 }}</td>
                                            <td>{{ $item->asesor->nama_lengkap ?? '-' }}</td>
                                            <td>{{ $item->no_dokumen ?? '-' }}</td>
                                            <td>{{ $item->tuk->nama_tuk ?? '-' }}</td>
                                            <td>
                                                @if($item->status === 'draft')
                                                    <span class="badge bg-secondary">Draft</span>
                                                @elseif($item->status === 'sent')
                                                    <span class="badge bg-warning">Terkirim</span>
                                                @elseif($item->status === 'signed')
                                                    <span class="badge bg-success">Ditandatangani</span>
                                                @endif
                                            </td>
                                            <td>{{ $item->created_at->format('d/m/Y') }}</td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('admin.surat-pernyataan-kesediaan.show', $item->id) }}" 
                                                       class="btn btn-info btn-sm" title="Detail">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    @if($item->status === 'draft')
                                                        <form action="{{ route('admin.surat-pernyataan-kesediaan.send', $item->id) }}" 
                                                              method="POST" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="btn btn-success btn-sm" title="Kirim ke Asesor"
                                                                    onclick="return confirm('Apakah Anda yakin ingin mengirim surat ini ke asesor?')">
                                                                <i class="fas fa-paper-plane"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                    @if($item->status === 'signed')
                                                        <a href="{{ route('admin.surat-pernyataan-kesediaan.pdf', $item->id) }}" 
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
                            <p class="text-muted">Klik tombol "Buat Surat" untuk membuat surat pernyataan kesediaan baru.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

