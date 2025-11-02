@extends('layouts.app')

@section('title', 'Upload Soal')
@section('page-title', 'Upload Soal')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h4>Daftar Jadwal - Upload Soal</h4>
                <div class="text-muted">
                    <i class="fas fa-info-circle me-1"></i>
                    Pilih jadwal untuk mengupload soal/instrumen asesmen
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow">
        <div class="card-body">
            @if($jadwals->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>No</th>
                                <th>Nama Batch</th>
                                <th>Skema Sertifikasi</th>
                                <th>TUK</th>
                                <th>Tanggal</th>
                                <th>Status Soal</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($jadwals as $index => $jadwal)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <strong>{{ $jadwal->nama_batch }}</strong>
                                </td>
                                <td>{{ $jadwal->skemaSertifikasi->nama_skema ?? '-' }}</td>
                                <td>{{ $jadwal->tuk->nama_tuk ?? '-' }}</td>
                                <td>
                                    {{ $jadwal->tanggal_mulai ? \Carbon\Carbon::parse($jadwal->tanggal_mulai)->format('d/m/Y') : '-' }}
                                    <br>
                                    <small class="text-muted">
                                        {{ $jadwal->jam_mulai ?? '-' }} - {{ $jadwal->jam_selesai ?? '-' }}
                                    </small>
                                </td>
                                <td>
                                    @if($jadwal->has_soal)
                                        <span class="badge bg-success">
                                            <i class="fas fa-check me-1"></i>Sudah Upload
                                        </span>
                                    @else
                                        <span class="badge bg-warning">
                                            <i class="fas fa-exclamation-triangle me-1"></i>Belum Upload
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('asesor.soal-upload.create', $jadwal->id) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-upload me-1"></i>Upload Soal
                                    </a>
                                    @if($jadwal->has_soal)
                                        <a href="{{ route('asesor.soal-upload.show', $jadwal->soalUploads->first()->id) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye me-1"></i>Lihat
                                        </a>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    Belum ada jadwal yang ditugaskan kepada Anda.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

