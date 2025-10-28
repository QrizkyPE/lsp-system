@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-clipboard-list me-2"></i>
                        Rekaman Asesmen Kompetensi
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

                    @if($rekamanAsesmen->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th width="5%">No</th>
                                        <th width="20%">Skema Sertifikasi</th>
                                        <th width="15%">TUK</th>
                                        <th width="15%">Tanggal Asesmen</th>
                                        <th width="15%">Rekomendasi</th>
                                        <th width="10%">Status</th>
                                        <th width="20%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($rekamanAsesmen as $index => $rekaman)
                                        <tr>
                                            <td class="text-center">{{ $rekamanAsesmen->firstItem() + $index }}</td>
                                            <td>{{ $rekaman->judul }}</td>
                                            <td>
                                                @switch($rekaman->tuk)
                                                    @case('sewaktu')
                                                        <span class="badge bg-info">Sewaktu</span>
                                                        @break
                                                    @case('tempat_kerja')
                                                        <span class="badge bg-warning">Tempat Kerja</span>
                                                        @break
                                                    @case('mandiri')
                                                        <span class="badge bg-success">Mandiri</span>
                                                        @break
                                                @endswitch
                                            </td>
                                            <td>
                                                {{ $rekaman->tanggal_mulai->format('d/m/Y') }} - {{ $rekaman->tanggal_selesai->format('d/m/Y') }}
                                            </td>
                                            <td>
                                                @if($rekaman->rekomendasi_hasil)
                                                    @if($rekaman->rekomendasi_hasil == 'kompeten')
                                                        <span class="badge bg-success">Kompeten</span>
                                                    @else
                                                        <span class="badge bg-warning">Belum Kompeten</span>
                                                    @endif
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($rekaman->mahasiswa_signature)
                                                    <span class="badge bg-success">Ditandatangani</span>
                                                @else
                                                    <span class="badge bg-warning">Menunggu Tanda Tangan</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('mahasiswa.rekaman-asesmen.show', $rekaman->id) }}" 
                                                       class="btn btn-info btn-sm" title="Detail">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    @if(!$rekaman->mahasiswa_signature)
                                                        <a href="{{ route('mahasiswa.rekaman-asesmen.signature', $rekaman->id) }}" 
                                                           class="btn btn-primary btn-sm" title="Tandatangani">
                                                            <i class="fas fa-signature"></i>
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-center">
                            {{ $rekamanAsesmen->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Belum ada rekaman asesmen kompetensi</h5>
                            <p class="text-muted">Rekaman asesmen kompetensi akan muncul setelah asesor membuatnya untuk Anda.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
