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
                    <div class="card-tools">
                        <a href="{{ route('asesor.rekaman-asesmen.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-1"></i>
                            Buat Rekaman Asesmen
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

                    @if($rekamanAsesmen->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th width="5%">No</th>
                                        <th width="20%">Nama Asesi</th>
                                        <th width="20%">Skema Sertifikasi</th>
                                        <th width="15%">TUK</th>
                                        <th width="15%">Tanggal Asesmen</th>
                                        <th width="10%">Status</th>
                                        <th width="15%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($rekamanAsesmen as $index => $rekaman)
                                        <tr>
                                            <td class="text-center">{{ $rekamanAsesmen->firstItem() + $index }}</td>
                                            <td>{{ $rekaman->nama_asesi }}</td>
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
                                                @if($rekaman->mahasiswa_signature)
                                                    <span class="badge bg-success">Ditandatangani Mahasiswa</span>
                                                @elseif($rekaman->asesor_signature)
                                                    <span class="badge bg-warning">Menunggu Tanda Tangan Mahasiswa</span>
                                                @else
                                                    <span class="badge bg-secondary">Draft</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('asesor.rekaman-asesmen.show', $rekaman->id) }}" 
                                                       class="btn btn-info btn-sm" title="Detail">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('asesor.rekaman-asesmen.edit', $rekaman->id) }}" 
                                                       class="btn btn-warning btn-sm" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('asesor.rekaman-asesmen.destroy', $rekaman->id) }}" 
                                                          method="POST" class="d-inline"
                                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus rekaman asesmen ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm" title="Hapus">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
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
                            <p class="text-muted">Klik tombol "Buat Rekaman Asesmen" untuk membuat rekaman asesmen kompetensi baru.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
