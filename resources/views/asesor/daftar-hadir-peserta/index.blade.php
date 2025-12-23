@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-clipboard-list me-2"></i>
                        Daftar Hadir Peserta
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

                    @if($daftarHadir->count() > 0 || $jadwalsWithoutDaftarHadir->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th width="5%">No</th>
                                        <th width="20%">Skema Sertifikasi</th>
                                        <th width="15%">TUK</th>
                                        <th width="15%">Tanggal</th>
                                        <th width="15%">No. Dokumen</th>
                                        <th width="10%">Status</th>
                                        <th width="20%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $no = 1; @endphp
                                    
                                    {{-- Existing daftar hadir --}}
                                    @foreach($daftarHadir as $item)
                                        <tr>
                                            <td class="text-center">{{ $no++ }}</td>
                                            <td>{{ $item->skema ?? ($item->jadwalUji->skemaSertifikasi->nama_skema ?? '-') }}</td>
                                            <td>{{ $item->tuk->nama_tuk ?? ($item->jadwalUji->tuk->nama_tuk ?? '-') }}</td>
                                            <td>
                                                @if($item->hari_tanggal)
                                                    {{ \Carbon\Carbon::parse($item->hari_tanggal)->format('d/m/Y') }}
                                                @else
                                                    {{ \Carbon\Carbon::parse($item->jadwalUji->tanggal_mulai)->format('d/m/Y') }}
                                                @endif
                                            </td>
                                            <td>{{ $item->no_dokumen ?? '-' }}</td>
                                            <td>
                                                <span class="badge bg-success">Sudah Dibuat</span>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('asesor.daftar-hadir-peserta.edit', $item->id) }}" 
                                                       class="btn btn-warning btn-sm" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <a href="{{ route('asesor.daftar-hadir-peserta.pdf', $item->id) }}" 
                                                       class="btn btn-danger btn-sm" title="Download PDF" target="_blank">
                                                        <i class="fas fa-file-pdf"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach

                                    {{-- Jadwals without daftar hadir --}}
                                    @foreach($jadwalsWithoutDaftarHadir as $jadwal)
                                        <tr>
                                            <td class="text-center">{{ $no++ }}</td>
                                            <td>{{ $jadwal->skemaSertifikasi->nama_skema ?? '-' }}</td>
                                            <td>{{ $jadwal->tuk->nama_tuk ?? '-' }}</td>
                                            <td>{{ \Carbon\Carbon::parse($jadwal->tanggal_mulai)->format('d/m/Y') }}</td>
                                            <td>-</td>
                                            <td>
                                                <span class="badge bg-warning">Belum Dibuat</span>
                                            </td>
                                            <td>
                                                <a href="{{ route('asesor.daftar-hadir-peserta.create', $jadwal->id) }}" 
                                                   class="btn btn-primary btn-sm" title="Buat Daftar Hadir">
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
                            <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Belum ada jadwal yang ditugaskan</h5>
                            <p class="text-muted">Anda belum memiliki jadwal uji yang ditugaskan untuk membuat daftar hadir peserta.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

