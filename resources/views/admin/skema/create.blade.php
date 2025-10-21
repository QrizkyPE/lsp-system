@extends('layouts.app')

@section('title', 'Tambah Skema Sertifikasi')
@section('page-title', 'Tambah Skema Sertifikasi')

@section('content')
<div class="card shadow">
    <div class="card-header">
        <h5 class="mb-0">Form Tambah Skema Sertifikasi</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.skema.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="kode_skema" class="form-label">Kode Skema <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('kode_skema') is-invalid @enderror" 
                           id="kode_skema" name="kode_skema" value="{{ old('kode_skema') }}" required>
                    @error('kode_skema')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="nama_skema" class="form-label">Nama Skema <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('nama_skema') is-invalid @enderror" 
                           id="nama_skema" name="nama_skema" value="{{ old('nama_skema') }}" required>
                    @error('nama_skema')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="nomor_skema" class="form-label">Nomor Skema <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('nomor_skema') is-invalid @enderror" 
                           id="nomor_skema" name="nomor_skema" value="{{ old('nomor_skema') }}" 
                           placeholder="Contoh: Nomor: 621/UMDP/XI/Q/2022" required>
                    @error('nomor_skema')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="level_kompetensi" class="form-label">Level Kompetensi <span class="text-danger">*</span></label>
                    <select class="form-select @error('level_kompetensi') is-invalid @enderror" 
                            id="level_kompetensi" name="level_kompetensi" required>
                        <option value="">Pilih Level Kompetensi</option>
                        <option value="Level 1" {{ old('level_kompetensi') == 'Level 1' ? 'selected' : '' }}>Level 1</option>
                        <option value="Level 2" {{ old('level_kompetensi') == 'Level 2' ? 'selected' : '' }}>Level 2</option>
                        <option value="Level 3" {{ old('level_kompetensi') == 'Level 3' ? 'selected' : '' }}>Level 3</option>
                        <option value="Level 4" {{ old('level_kompetensi') == 'Level 4' ? 'selected' : '' }}>Level 4</option>
                        <option value="Level 5" {{ old('level_kompetensi') == 'Level 5' ? 'selected' : '' }}>Level 5</option>
                    </select>
                    @error('level_kompetensi')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                    <select class="form-select @error('status') is-invalid @enderror" 
                            id="status" name="status" required>
                        <option value="1" {{ old('status', '1') == '1' ? 'selected' : '' }}>Aktif</option>
                        <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Tidak Aktif</option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-3">
                <label for="deskripsi" class="form-label">Deskripsi <span class="text-danger">*</span></label>
                <textarea class="form-control @error('deskripsi') is-invalid @enderror" 
                          id="deskripsi" name="deskripsi" rows="4" required>{{ old('deskripsi') }}</textarea>
                @error('deskripsi')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="standar_kompetensi" class="form-label">Standar Kompetensi <span class="text-danger">*</span></label>
                <textarea class="form-control @error('standar_kompetensi') is-invalid @enderror" 
                          id="standar_kompetensi" name="standar_kompetensi" rows="4" required>{{ old('standar_kompetensi') }}</textarea>
                @error('standar_kompetensi')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex justify-content-end">
                <a href="{{ route('admin.skema.index') }}" class="btn btn-secondary me-2">Batal</a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Simpan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
