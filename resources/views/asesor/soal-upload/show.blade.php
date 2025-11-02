@extends('layouts.app')

@section('title', 'Detail Soal')
@section('page-title', 'Detail Soal')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h4>Detail Soal / Instrumen Asesmen</h4>
                <a href="{{ route('asesor.soal-upload.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i>Kembali
                </a>
            </div>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Informasi Soal</h5>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-6">
                    <strong>Jadwal:</strong> {{ $soalUpload->jadwalUji->nama_batch }}
                </div>
                <div class="col-md-6">
                    <strong>Skema Sertifikasi:</strong> {{ $soalUpload->jadwalUji->skemaSertifikasi->nama_skema ?? '-' }}
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <strong>Jenis Instrumen:</strong> {{ $soalUpload->jenis_instrumen }}
                </div>
                <div class="col-md-6">
                    <strong>File:</strong> 
                    <a href="{{ asset('storage/' . $soalUpload->file_path) }}" target="_blank" class="btn btn-sm btn-info">
                        <i class="fas fa-download me-1"></i>Download
                    </a>
                    <span class="text-muted">({{ $soalUpload->original_filename }})</span>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <strong>Tanggal Upload:</strong> {{ $soalUpload->created_at->format('d/m/Y H:i') }}
                </div>
                <div class="col-md-6">
                    <strong>Ukuran File:</strong> {{ number_format($soalUpload->file_size / 1024, 2) }} KB
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow mt-4">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0">Jawaban yang Diterima</h5>
        </div>
        <div class="card-body">
            @if($soalUpload->submissions->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>No</th>
                                <th>Nama Mahasiswa</th>
                                <th>File Jawaban</th>
                                <th>Tanggal Submit</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($soalUpload->submissions as $index => $submission)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $submission->pendaftaran->user->name ?? '-' }}</td>
                                <td>
                                    <a href="{{ asset('storage/' . $submission->file_path) }}" target="_blank" class="btn btn-sm btn-primary">
                                        <i class="fas fa-download me-1"></i>{{ $submission->original_filename }}
                                    </a>
                                </td>
                                <td>{{ $submission->submitted_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <a href="{{ asset('storage/' . $submission->file_path) }}" target="_blank" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye me-1"></i>Lihat
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    Belum ada mahasiswa yang mengumpulkan jawaban.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

