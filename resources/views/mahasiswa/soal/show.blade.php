@extends('layouts.app')

@section('title', 'Detail Soal')
@section('page-title', 'Detail Soal')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h4>Detail Soal / Instrumen Asesmen</h4>
                <a href="{{ route('mahasiswa.soal.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i>Kembali
                </a>
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
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Informasi Soal</h5>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-6">
                    <strong>Skema Sertifikasi:</strong> {{ $soalUpload->jadwalUji->skemaSertifikasi->nama_skema ?? '-' }}
                </div>
                <div class="col-md-6">
                    <strong>Jadwal:</strong> {{ $soalUpload->jadwalUji->nama_batch }}
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <strong>Jenis Instrumen:</strong> {{ $soalUpload->jenis_instrumen }}
                </div>
                <div class="col-md-6">
                    <strong>Batas Waktu:</strong> 
                    {{ $soalUpload->jadwalUji->tanggal_selesai ? \Carbon\Carbon::parse($soalUpload->jadwalUji->tanggal_selesai)->format('d F Y') : '-' }}
                    {{ $soalUpload->jadwalUji->jam_selesai ?? '' }}
                    @if($isDeadlinePassed)
                        <span class="badge bg-danger ms-2">Deadline Telah Berlalu</span>
                    @endif
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-12">
                    <strong>File Soal:</strong> 
                    <a href="{{ asset('storage/' . $soalUpload->file_path) }}" target="_blank" class="btn btn-sm btn-info">
                        <i class="fas fa-download me-1"></i>Download Soal
                    </a>
                    <span class="text-muted">({{ $soalUpload->original_filename }})</span>
                </div>
            </div>
        </div>
    </div>

    @if($submission)
        <div class="card shadow mt-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">
                    <i class="fas fa-check-circle me-2"></i>Jawaban Anda
                </h5>
            </div>
            <div class="card-body">
                <div class="alert alert-success">
                    <i class="fas fa-info-circle me-2"></i>
                    Anda sudah mengumpulkan jawaban pada 
                    <strong>{{ $submission->submitted_at->format('d F Y H:i') }}</strong>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <strong>File Jawaban:</strong> 
                        <a href="{{ asset('storage/' . $submission->file_path) }}" target="_blank" class="btn btn-sm btn-primary">
                            <i class="fas fa-download me-1"></i>{{ $submission->original_filename }}
                        </a>
                        @if(!$isDeadlinePassed)
                            <button type="button" class="btn btn-sm btn-warning" onclick="showUploadForm()">
                                <i class="fas fa-upload me-1"></i>Ubah Jawaban
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if(!$submission || !$isDeadlinePassed)
        <div class="card shadow mt-4" id="uploadForm" style="{{ $submission ? 'display: none;' : '' }}">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0">
                    <i class="fas fa-upload me-2"></i>Upload Jawaban
                </h5>
            </div>
            <div class="card-body">
                @if($isDeadlinePassed)
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Peringatan:</strong> Batas waktu pengumpulan sudah lewat. Anda tidak dapat mengubah jawaban lagi.
                    </div>
                @else
                    <form action="{{ route('mahasiswa.soal.upload', $soalUpload->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="file" class="form-label">
                                <strong>File Jawaban (Word/PDF) <span class="text-danger">*</span></strong>
                            </label>
                            <input type="file" 
                                   class="form-control @error('file') is-invalid @enderror" 
                                   id="file" 
                                   name="file" 
                                   accept=".doc,.docx,.pdf"
                                   required>
                            @error('file')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                Format file: Word (.doc, .docx) atau PDF (.pdf). Maksimal 10MB.
                            </small>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-upload me-1"></i>{{ $submission ? 'Ubah Jawaban' : 'Submit Jawaban' }}
                            </button>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    @endif
</div>

@if($submission)
<script>
    function showUploadForm() {
        document.getElementById('uploadForm').style.display = 'block';
    }
</script>
@endif
@endsection

