@extends('layouts.app')

@section('title', 'Soal')
@section('page-title', 'Soal')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h4>Daftar Soal / Instrumen Asesmen</h4>
                <div class="text-muted">
                    <i class="fas fa-info-circle me-1"></i>
                    Soal yang tersedia berdasarkan jadwal Anda
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

    @if($pendaftaran->count() > 0)
        @foreach($pendaftaran as $p)
            @if($p->available_soal->count() > 0)
                <div class="card shadow mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-file-alt me-2"></i>
                            {{ $p->skemaSertifikasi->nama_skema ?? 'Skema' }} - {{ $p->jadwalUji->nama_batch ?? 'Jadwal' }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            <strong>Batas Waktu:</strong> 
                            {{ $p->jadwalUji->tanggal_selesai ? \Carbon\Carbon::parse($p->jadwalUji->tanggal_selesai)->format('d F Y') : '-' }}
                            {{ $p->jadwalUji->jam_selesai ?? '' }}
                            @if($p->is_deadline_passed)
                                <span class="badge bg-danger ms-2">Deadline Telah Berlalu</span>
                            @else
                                <span class="badge bg-success ms-2">Masih Aktif</span>
                            @endif
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Jenis Instrumen</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($p->available_soal as $soal)
                                    <tr>
                                        <td>
                                            <strong>{{ $soal->jenis_instrumen }}</strong>
                                            <br>
                                            <small class="text-muted">Upload: {{ $soal->created_at->format('d/m/Y H:i') }}</small>
                                        </td>
                                        <td>
                                            @if(isset($p->submissions[$soal->id]))
                                                <span class="badge bg-success">
                                                    <i class="fas fa-check me-1"></i>Sudah Dikumpulkan
                                                </span>
                                                <br>
                                                <small class="text-muted">{{ $p->submissions[$soal->id]->submitted_at->format('d/m/Y H:i') }}</small>
                                            @else
                                                <span class="badge bg-warning">
                                                    <i class="fas fa-clock me-1"></i>Belum Dikumpulkan
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('mahasiswa.soal.show', $soal->id) }}" class="btn btn-sm btn-primary">
                                                <i class="fas fa-eye me-1"></i>Lihat & Submit
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach

        @if($pendaftaran->every(function($p) { return $p->available_soal->count() == 0; }))
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i>
                Belum ada soal yang tersedia untuk jadwal Anda.
            </div>
        @endif
    @else
        <div class="alert alert-info">
            <i class="fas fa-info-circle me-2"></i>
            Belum ada pendaftaran dengan jadwal yang tersedia.
        </div>
    @endif
</div>
@endsection

