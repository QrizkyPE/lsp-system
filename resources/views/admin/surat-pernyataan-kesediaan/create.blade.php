@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-plus me-2"></i>
                        Buat Surat Pernyataan Kesediaan Asesor
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.surat-pernyataan-kesediaan.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i>
                            Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.surat-pernyataan-kesediaan.store') }}" method="POST">
                        @csrf
                        
                        <!-- Informasi Dokumen -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5><strong>Informasi Dokumen</strong></h5>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <tr>
                                            <td width="20%"><strong>No. Dokumen:</strong></td>
                                            <td>
                                                <input type="text" name="no_dokumen" class="form-control" placeholder="Masukkan nomor dokumen">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Edisi/Revisi:</strong></td>
                                            <td>
                                                <input type="text" name="edisi_revisi" class="form-control" placeholder="Masukkan edisi/revisi">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Tanggal Berlaku:</strong></td>
                                            <td>
                                                <input type="date" name="tanggal_berlaku" class="form-control">
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Informasi Asesor -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5><strong>Informasi Asesor</strong></h5>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <tr>
                                            <td width="20%"><strong>Nama:</strong></td>
                                            <td>
                                                <select name="asesor_id" id="asesor_id" class="form-control" required>
                                                    <option value="">Pilih Asesor</option>
                                                    @foreach($asesorList as $asesor)
                                                        <option value="{{ $asesor->id }}" 
                                                                data-alamat="{{ $asesor->instansi ?? '' }}"
                                                                data-no-reg="{{ $asesor->no_reg ?? '' }}">
                                                            {{ $asesor->nama_lengkap }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Alamat:</strong></td>
                                            <td>
                                                <input type="text" name="alamat" id="alamat" class="form-control" placeholder="Alamat asesor">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>No. MET Sertifikat:</strong></td>
                                            <td>
                                                <input type="text" name="no_met_sertifikat" id="no_met_sertifikat" class="form-control" placeholder="No. MET Sertifikat">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>TUK:</strong></td>
                                            <td>
                                                <select name="tuk_id" class="form-control">
                                                    <option value="">Pilih TUK</option>
                                                    @foreach($tukList as $tuk)
                                                        <option value="{{ $tuk->id }}">{{ $tuk->nama_tuk }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 text-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i>
                                    Simpan
                                </button>
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
    const asesorSelect = document.getElementById('asesor_id');
    const alamatInput = document.getElementById('alamat');
    const noMetInput = document.getElementById('no_met_sertifikat');

    asesorSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        if (selectedOption.value) {
            const alamat = selectedOption.getAttribute('data-alamat');
            const noReg = selectedOption.getAttribute('data-no-reg');
            
            if (alamat && !alamatInput.value) {
                alamatInput.value = alamat;
            }
            if (noReg && !noMetInput.value) {
                noMetInput.value = noReg;
            }
        } else {
            alamatInput.value = '';
            noMetInput.value = '';
        }
    });
});
</script>
@endsection

