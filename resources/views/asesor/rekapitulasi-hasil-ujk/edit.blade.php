@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-edit me-2"></i>
                        Edit Rekapitulasi Hasil UJK
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('asesor.rekapitulasi-hasil-ujk.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i>
                            Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('asesor.rekapitulasi-hasil-ujk.update', $rekapitulasi->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <!-- Informasi Dokumen -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5><strong>Informasi Dokumen</strong></h5>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <tr>
                                            <td width="20%"><strong>No. Dokumen:</strong></td>
                                            <td>
                                                <input type="text" name="no_dokumen" class="form-control" value="{{ $rekapitulasi->no_dokumen ?? '' }}" placeholder="Masukkan nomor dokumen">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Edisi/Revisi:</strong></td>
                                            <td>
                                                <input type="text" name="edisi_revisi" class="form-control" value="{{ $rekapitulasi->edisi_revisi ?? '' }}" placeholder="Masukkan edisi/revisi">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Tanggal Berlaku:</strong></td>
                                            <td>
                                                <input type="date" name="tanggal_berlaku" class="form-control" value="{{ $rekapitulasi->tanggal_berlaku ? \Carbon\Carbon::parse($rekapitulasi->tanggal_berlaku)->format('Y-m-d') : '' }}">
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Informasi Jadwal -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5><strong>Informasi Jadwal</strong></h5>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <tr>
                                            <td width="20%"><strong>Skema:</strong></td>
                                            <td>
                                                <input type="text" name="skema" class="form-control" value="{{ $rekapitulasi->skema ?? '' }}" placeholder="Masukkan skema">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Pukul:</strong></td>
                                            <td>
                                                <div class="row">
                                                    <div class="col-md-5">
                                                        <input type="time" name="pukul_mulai" class="form-control" value="{{ $rekapitulasi->pukul_mulai ? \Carbon\Carbon::parse($rekapitulasi->pukul_mulai)->format('H:i') : '' }}">
                                                    </div>
                                                    <div class="col-md-2 text-center">
                                                        <label class="form-label">s/d</label>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <input type="time" name="pukul_selesai" class="form-control" value="{{ $rekapitulasi->pukul_selesai ? \Carbon\Carbon::parse($rekapitulasi->pukul_selesai)->format('H:i') : '' }}">
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Hari/Tanggal:</strong></td>
                                            <td>
                                                <input type="date" name="hari_tanggal" class="form-control" value="{{ $rekapitulasi->hari_tanggal ? \Carbon\Carbon::parse($rekapitulasi->hari_tanggal)->format('Y-m-d') : '' }}">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>TUK:</strong></td>
                                            <td>{{ $rekapitulasi->tuk->nama_tuk ?? ($rekapitulasi->jadwalUji->tuk->nama_tuk ?? '-') }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Daftar Asesi -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5><strong>Daftar Asesi</strong></h5>
                                @if($asesiList->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead class="table-dark">
                                                <tr>
                                                    <th width="5%">No</th>
                                                    <th width="35%">Nama Peserta</th>
                                                    <th width="20%">NPM</th>
                                                    <th width="10%" class="text-center">K</th>
                                                    <th width="10%" class="text-center">BK</th>
                                                    <th width="20%">Tanda Tangan</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($asesiList as $index => $asesi)
                                                    <tr>
                                                        <td class="text-center">{{ $index + 1 }}</td>
                                                        <td>{{ $asesi['nama'] }}</td>
                                                        <td>{{ $asesi['npm'] }}</td>
                                                        <td class="text-center">
                                                            <input type="checkbox" 
                                                                   name="hasil_asesi[{{ $asesi['id'] }}][k]" 
                                                                   value="1" 
                                                                   {{ $asesi['k'] ? 'checked' : '' }}
                                                                   class="form-check-input checkbox-k"
                                                                   data-asesi-id="{{ $asesi['id'] }}">
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="checkbox" 
                                                                   name="hasil_asesi[{{ $asesi['id'] }}][bk]" 
                                                                   value="1" 
                                                                   {{ $asesi['bk'] ? 'checked' : '' }}
                                                                   class="form-check-input checkbox-bk"
                                                                   data-asesi-id="{{ $asesi['id'] }}">
                                                        </td>
                                                        <td class="text-center">
                                                            @if($asesi['signature'])
                                                                <img src="{{ $asesi['signature'] }}" 
                                                                     alt="Tanda Tangan" 
                                                                     style="max-width: 150px; max-height: 60px; border: 1px solid #ddd;">
                                                            @else
                                                                <span class="text-muted">-</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="alert alert-warning">
                                        <i class="fas fa-exclamation-triangle me-2"></i>
                                        Belum ada asesi yang ditugaskan untuk jadwal ini.
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i>
                                    Simpan
                                </button>
                                <a href="{{ route('asesor.rekapitulasi-hasil-ujk.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times me-1"></i>
                                    Batal
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Ensure only one checkbox (K or BK) can be checked per student
        document.querySelectorAll('.checkbox-k, .checkbox-bk').forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                const asesiId = this.getAttribute('data-asesi-id');
                const isK = this.classList.contains('checkbox-k');
                
                if (this.checked) {
                    // Uncheck the other checkbox for the same student
                    const otherCheckbox = document.querySelector(
                        (isK ? '.checkbox-bk' : '.checkbox-k') + '[data-asesi-id="' + asesiId + '"]'
                    );
                    if (otherCheckbox) {
                        otherCheckbox.checked = false;
                    }
                }
            });
        });
    });
</script>
@endsection

