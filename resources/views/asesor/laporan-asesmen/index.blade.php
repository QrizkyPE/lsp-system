@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-file-alt me-2"></i>
                        Laporan Asesmen
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

                    @if($laporan->count() > 0 || $jadwalsWithoutLaporan->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th width="5%">No</th>
                                        <th width="25%">Skema Sertifikasi</th>
                                        <th width="15%">TUK</th>
                                        <th width="15%">Tanggal</th>
                                        <th width="10%">Status</th>
                                        <th width="30%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $no = 1; @endphp

                                    {{-- Laporan yang sudah dibuat --}}
                                    @foreach($laporan as $item)
                                        <tr>
                                            <td class="text-center">{{ $no++ }}</td>
                                            <td>{{ $item->jadwalUji->skemaSertifikasi->nama_skema ?? '-' }}</td>
                                            <td>{{ $item->jadwalUji->tuk->nama_tuk ?? '-' }}</td>
                                            <td>
                                                {{ $item->tanggal ? \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') : '-' }}
                                            </td>
                                            <td>
                                                <span class="badge bg-success">Sudah Dibuat</span>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('asesor.laporan-asesmen.edit', $item->id) }}" 
                                                       class="btn btn-warning btn-sm" title="Edit Laporan Asesmen">
                                                        <i class="fas fa-edit"></i> Edit
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach

                                    {{-- Jadwal yang belum punya laporan asesmen --}}
                                    @foreach($jadwalsWithoutLaporan as $jadwal)
                                        <tr>
                                            <td class="text-center">{{ $no++ }}</td>
                                            <td>{{ $jadwal->skemaSertifikasi->nama_skema ?? '-' }}</td>
                                            <td>{{ $jadwal->tuk->nama_tuk ?? '-' }}</td>
                                            <td>{{ \Carbon\Carbon::parse($jadwal->tanggal_mulai)->format('d/m/Y') }}</td>
                                            <td>
                                                <span class="badge bg-warning">Belum Dibuat</span>
                                            </td>
                                            <td>
                                                <a href="{{ route('asesor.laporan-asesmen.create', $jadwal->id) }}" 
                                                   class="btn btn-primary btn-sm" title="Buat Laporan Asesmen">
                                                    <i class="fas fa-plus"></i> Buat
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Belum ada jadwal yang ditugaskan</h5>
                            <p class="text-muted">Anda belum memiliki jadwal uji yang ditugaskan untuk membuat laporan asesmen.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

