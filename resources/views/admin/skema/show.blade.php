@extends('layouts.app')

@section('title', 'Detail Skema Sertifikasi')
@section('page-title', 'Detail Skema Sertifikasi')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card shadow">
            <div class="card-header">
                <h5 class="mb-0">Detail Skema Sertifikasi</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-sm-3"><strong>Kode Skema:</strong></div>
                    <div class="col-sm-9">{{ $skema->kode_skema }}</div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-sm-3"><strong>Nama Skema:</strong></div>
                    <div class="col-sm-9">{{ $skema->nama_skema }}</div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-sm-3"><strong>Nomor Skema:</strong></div>
                    <div class="col-sm-9"><span class="text-dark fw-normal">{{ $skema->nomor_skema }}</span></div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-sm-3"><strong>Level Kompetensi:</strong></div>
                    <div class="col-sm-9">{{ $skema->level_kompetensi }}</div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-sm-3"><strong>Status:</strong></div>
                    <div class="col-sm-9">
                        <span class="badge bg-{{ $skema->status ? 'success' : 'danger' }}">
                            {{ $skema->status ? 'Aktif' : 'Tidak Aktif' }}
                        </span>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-sm-3"><strong>Deskripsi:</strong></div>
                    <div class="col-sm-9">{{ $skema->deskripsi }}</div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-sm-3"><strong>Standar Kompetensi:</strong></div>
                    <div class="col-sm-9">{{ $skema->standar_kompetensi }}</div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-sm-3"><strong>Dibuat:</strong></div>
                    <div class="col-sm-9">{{ $skema->created_at->format('d/m/Y H:i') }}</div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-sm-3"><strong>Diupdate:</strong></div>
                    <div class="col-sm-9">{{ $skema->updated_at->format('d/m/Y H:i') }}</div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card shadow">
            <div class="card-header">
                <h5 class="mb-0">Actions</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.skema.edit', $skema) }}" class="btn btn-warning">
                        <i class="fas fa-edit me-2"></i>Edit Skema
                    </a>
                    <a href="{{ route('admin.skema.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Kembali
                    </a>
                </div>
            </div>
        </div>
        
        <div class="card shadow mt-3">
            <div class="card-header">
                <h5 class="mb-0">Unit Kompetensi</h5>
            </div>
            <div class="card-body">
                @if($skema->unitKompetensi->count() > 0)
                    <div class="list-group">
                        @foreach($skema->unitKompetensi as $unit)
                        <div class="list-group-item">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">{{ $unit->kode_unit }}</h6>
                            </div>
                            <p class="mb-1">{{ $unit->nama_unit }}</p>
                        </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted">Belum ada unit kompetensi</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
