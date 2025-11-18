@extends('layouts.app')

@section('title', 'MAPA - Merencanakan Aktivitas dan Proses Asesmen')
@section('page-title', 'MAPA - Merencanakan Aktivitas dan Proses Asesmen')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Daftar MAPA</h4>
                </div>
                <div class="card-body">
                    <!-- Daftar Skema yang Tersedia -->
                    <div class="mb-4">
                        <h5 class="mb-3">Pilih Skema untuk Membuat MAPA</h5>
                        @if($skemas->count() > 0)
                            <div class="row">
                                @foreach($skemas as $skema)
                                    <div class="col-md-6 col-lg-4 mb-3">
                                        <div class="card border-primary">
                                            <div class="card-body">
                                                <h6 class="card-title">{{ $skema->nama_skema }}</h6>
                                                <p class="card-text text-muted small mb-2">
                                                    <strong>Nomor:</strong> {{ $skema->nomor_skema ?? '-' }}<br>
                                                    <strong>Kode:</strong> {{ $skema->kode_skema ?? '-' }}
                                                </p>
                                                @php
                                                    $existingMapa = $mapas->where('skema_sertifikasi_id', $skema->id)->first();
                                                @endphp
                                                @if($existingMapa)
                                                    <div class="d-flex gap-2">
                                                        <a href="{{ route('asesor.mapa.show', $existingMapa->id) }}" class="btn btn-sm btn-info">
                                                            <i class="fas fa-eye me-1"></i>Lihat
                                                        </a>
                                                        <a href="{{ route('asesor.mapa.edit', $existingMapa->id) }}" class="btn btn-sm btn-warning">
                                                            <i class="fas fa-edit me-1"></i>Edit
                                                        </a>
                                                    </div>
                                                @else
                                                    <a href="{{ route('asesor.mapa.create', $skema->id) }}" class="btn btn-sm btn-primary">
                                                        <i class="fas fa-plus me-1"></i>Buat MAPA
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                Anda belum memiliki skema kompetensi yang ditugaskan. Silakan hubungi administrator.
                            </div>
                        @endif
                    </div>

                    <!-- Daftar MAPA yang Sudah Dibuat -->
                    @if($mapas->count() > 0)
                        <div class="mt-5">
                            <h5 class="mb-3">MAPA yang Sudah Dibuat</h5>
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>No</th>
                                            <th>Skema Sertifikasi</th>
                                            <th>Nomor Skema</th>
                                            <th>Tanggal Dibuat</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($mapas as $index => $mapa)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>
                                                    <strong>{{ $mapa->skemaSertifikasi->nama_skema }}</strong>
                                                </td>
                                                <td>{{ $mapa->skemaSertifikasi->nomor_skema ?? '-' }}</td>
                                                <td>{{ $mapa->created_at->format('d/m/Y') }}</td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <a href="{{ route('asesor.mapa.show', $mapa->id) }}" class="btn btn-sm btn-info" title="Lihat">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href="{{ route('asesor.mapa.edit', $mapa->id) }}" class="btn btn-sm btn-warning" title="Edit">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <form action="{{ route('asesor.mapa.destroy', $mapa->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus MAPA ini?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
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
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

