@extends('layouts.app')

@section('title', 'Upload Soal')
@section('page-title', 'Upload Soal')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h4>Upload Soal / Instrumen Asesmen</h4>
                <a href="{{ route('asesor.soal-upload.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i>Kembali
                </a>
            </div>
        </div>
    </div>

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Informasi Jadwal</h5>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-6">
                    <strong>Nama Batch:</strong> {{ $jadwal->nama_batch }}
                </div>
                <div class="col-md-6">
                    <strong>Skema Sertifikasi:</strong> {{ $jadwal->skemaSertifikasi->nama_skema ?? '-' }}
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <strong>TUK:</strong> {{ $jadwal->tuk->nama_tuk ?? '-' }}
                </div>
                <div class="col-md-6">
                    <strong>Tanggal:</strong> 
                    {{ $jadwal->tanggal_mulai ? \Carbon\Carbon::parse($jadwal->tanggal_mulai)->format('d/m/Y') : '-' }}
                    s/d
                    {{ $jadwal->tanggal_selesai ? \Carbon\Carbon::parse($jadwal->tanggal_selesai)->format('d/m/Y') : '-' }}
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <strong>Waktu:</strong> {{ $jadwal->jam_mulai ?? '-' }} - {{ $jadwal->jam_selesai ?? '-' }}
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow mt-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Form Upload Soal</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('asesor.soal-upload.store', $jadwal->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="mb-3">
                    <label for="jenis_instrumen" class="form-label">
                        <strong>Jenis Instrumen Asesmen <span class="text-danger">*</span></strong>
                    </label>
                    <select class="form-select @error('jenis_instrumen') is-invalid @enderror" 
                            id="jenis_instrumen" name="jenis_instrumen" required>
                        <option value="">-- Pilih Jenis Instrumen --</option>
                        @foreach($instrumenOptions as $key => $label)
                            <option value="{{ $key }}" {{ old('jenis_instrumen') == $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    @error('jenis_instrumen')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="file" class="form-label">
                        <strong>File Soal (Word/PDF) <span class="text-danger">*</span></strong>
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
                        <i class="fas fa-upload me-1"></i>Upload Soal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

