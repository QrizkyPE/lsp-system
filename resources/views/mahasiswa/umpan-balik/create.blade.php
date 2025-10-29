@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-plus me-2"></i>
                        Buat Umpan Balik dan Catatan Asesmen
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('mahasiswa.umpan-balik.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i>
                            Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if($rekamanAsesmen->count() == 0)
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Peringatan:</strong> Tidak ada rekaman asesmen yang tersedia.
                            <br>Belum ada rekaman asesmen kompetensi yang telah ditandatangani. Silakan tunggu asesor membuat rekaman asesmen dan Anda menandatanganinya terlebih dahulu.
                        </div>
                    @endif

                    <form action="{{ route('mahasiswa.umpan-balik.store') }}" method="POST" id="umpanBalikForm">
                        @csrf
                        
                        <!-- Header -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h4 class="text-center"><strong>FR.AK.03. UMPAN BALIK DAN CATATAN ASESMEN</strong></h4>
                            </div>
                        </div>

                        <!-- Informasi Dasar -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5><strong>Informasi Dasar</strong></h5>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <tr>
                                            <td width="20%"><strong>Skema Sertifikasi (<s>KKNI</s>/Okupasi/<s>Klaster</s>):</strong></td></td>
                                            <td>
                                                <select name="rekaman_asesmen_id" id="rekaman_asesmen_id" class="form-select" required {{ $rekamanAsesmen->count() == 0 ? 'disabled' : '' }}>
                                                    <option value="">Pilih Rekaman Asesmen</option>
                                                    @foreach($rekamanAsesmen as $rekaman)
                                                        <option value="{{ $rekaman->id }}" 
                                                                data-judul="{{ $rekaman->judul }}" 
                                                                data-nomor="{{ $rekaman->nomor_skema }}"
                                                                data-tuk="{{ $rekaman->tuk }}"
                                                                data-nama-asesor="{{ $rekaman->nama_asesor }}"
                                                                data-nama-asesi="{{ $rekaman->nama_asesi }}"
                                                                data-tanggal-mulai="{{ $rekaman->tanggal_mulai->format('Y-m-d') }}"
                                                                data-waktu-mulai="{{ $rekaman->waktu_mulai }}"
                                                                data-tanggal-selesai="{{ $rekaman->tanggal_selesai->format('Y-m-d') }}"
                                                                data-waktu-selesai="{{ $rekaman->waktu_selesai }}">
                                                            {{ $rekaman->judul }} - {{ $rekaman->tanggal_mulai->format('d/m/Y') }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Judul:</strong></td>
                                            <td id="judul_display">-</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Nomor:</strong></td>
                                            <td id="nomor_display">-</td>
                                        </tr>
                                        <tr>
                                            <td><strong>TUK:</strong></td>
                                            <td>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="tuk" id="tuk_sewaktu" value="sewaktu" {{ $rekamanAsesmen->count() == 0 ? 'disabled' : '' }}>
                                                    <label class="form-check-label" for="tuk_sewaktu">Sewaktu</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="tuk" id="tuk_tempat_kerja" value="tempat_kerja" {{ $rekamanAsesmen->count() == 0 ? 'disabled' : '' }}>
                                                    <label class="form-check-label" for="tuk_tempat_kerja">Tempat Kerja</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="tuk" id="tuk_mandiri" value="mandiri" {{ $rekamanAsesmen->count() == 0 ? 'disabled' : '' }}>
                                                    <label class="form-check-label" for="tuk_mandiri">Mandiri</label>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Nama Asesor:</strong></td>
                                            <td id="nama_asesor_display">-</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Nama Asesi:</strong></td>
                                            <td id="nama_asesi_display">-</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Tanggal Asesmen:</strong></td>
                                            <td>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label class="form-label">Mulai:</label>
                                                        <div class="input-group">
                                                            <input type="date" id="tanggal_mulai_display" class="form-control" readonly>
                                                            <input type="time" id="waktu_mulai_display" class="form-control" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label">Selesai:</label>
                                                        <div class="input-group">
                                                            <input type="date" id="tanggal_selesai_display" class="form-control" readonly>
                                                            <input type="time" id="waktu_selesai_display" class="form-control" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Umpan Balik dari Asesi -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5><strong>Umpan balik dari Asesi (diisi oleh Asesi setelah pengambilan keputusan):</strong></h5>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead class="table-dark">
                                            <tr>
                                                <th width="50%">KOMPONEN</th>
                                                <th width="10%" class="text-center">Hasil</th>
                                                <th width="10%" class="text-center">Ya</th>
                                                <th width="10%" class="text-center">Tidak</th>
                                                <th width="20%">Catatan/Komentar Asesi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $komponen = [
                                                    'Saya mendapatkan penjelasan yang cukup memadai mengenai proses asesmen/uji kompetensi',
                                                    'Saya diberikan kesempatan untuk mempelajari standar kompetensi yang akan diujikan dan menilai diri sendiri terhadap pencapaiannya',
                                                    'Asesor memberikan kesempatan untuk mendiskusikan/menegosiasikan metoda, instrumen dan sumber asesmen serta jadwal asesmen',
                                                    'Asesor berusaha menggali seluruh bukti pendukung yang sesuai dengan latar belakang pelatihan dan pengalaman yang saya miliki',
                                                    'Saya sepenuhnya diberikan kesempatan untuk mendemonstrasikan kompetensi yang saya miliki selama asesmen',
                                                    'Saya mendapatkan penjelasan yang memadai mengenai keputusan asesmen',
                                                    'Asesor memberikan umpan balik yang mendukung setelah asesmen serta tindak lanjutnya',
                                                    'Asesor bersama saya mempelajari semua dokumen asesmen serta menandatanganinya',
                                                    'Saya mendapatkan jaminan kerahasiaan hasil asesmen serta penjelasan penanganan dokumen asesmen',
                                                    'Asesor menggunakan keterampilan komunikasi yang efektif selama asesmen'
                                                ];
                                            @endphp
                                            @foreach($komponen as $index => $komponenText)
                                                <tr>
                                                    <td>{{ $komponenText }}</td>
                                                    <td class="text-center">
                                                        <span class="badge bg-secondary" id="hasil_{{ $index }}">-</span>
                                                    </td>
                                                    <td class="text-center">
                                                        <input type="checkbox" name="umpan_balik_data[{{ $index }}][ya]" class="form-check-input" onchange="updateHasil({{ $index }}, 'ya')" {{ $rekamanAsesmen->count() == 0 ? 'disabled' : '' }}>
                                                    </td>
                                                    <td class="text-center">
                                                        <input type="checkbox" name="umpan_balik_data[{{ $index }}][tidak]" class="form-check-input" onchange="updateHasil({{ $index }}, 'tidak')" {{ $rekamanAsesmen->count() == 0 ? 'disabled' : '' }}>
                                                    </td>
                                                    <td>
                                                        <textarea name="umpan_balik_data[{{ $index }}][catatan]" class="form-control" rows="2" placeholder="Catatan/Komentar" {{ $rekamanAsesmen->count() == 0 ? 'disabled' : '' }}></textarea>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Catatan Lainnya -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5><strong>Catatan/komentar lainnya (apabila ada):</strong></h5>
                                <textarea name="catatan_lainnya" class="form-control" rows="4" placeholder="Masukkan catatan atau komentar lainnya" {{ $rekamanAsesmen->count() == 0 ? 'disabled' : '' }}></textarea>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="row">
                            <div class="col-12 text-center">
                                <button type="submit" class="btn btn-primary btn-lg" {{ $rekamanAsesmen->count() == 0 ? 'disabled' : '' }}>
                                    <i class="fas fa-save me-2"></i>
                                    Simpan Umpan Balik
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle rekaman asesmen selection
    document.getElementById('rekaman_asesmen_id').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        if (selectedOption.value) {
            // Update display fields
            document.getElementById('judul_display').textContent = selectedOption.dataset.judul;
            document.getElementById('nomor_display').textContent = selectedOption.dataset.nomor;
            document.getElementById('nama_asesor_display').textContent = selectedOption.dataset.namaAsesor;
            document.getElementById('nama_asesi_display').textContent = selectedOption.dataset.namaAsesi;
            
            // Update date and time fields
            document.getElementById('tanggal_mulai_display').value = selectedOption.dataset.tanggalMulai;
            document.getElementById('waktu_mulai_display').value = selectedOption.dataset.waktuMulai;
            document.getElementById('tanggal_selesai_display').value = selectedOption.dataset.tanggalSelesai;
            document.getElementById('waktu_selesai_display').value = selectedOption.dataset.waktuSelesai;
            
            // Update TUK radio button
            const tukValue = selectedOption.dataset.tuk;
            document.getElementById('tuk_' + tukValue).checked = true;
        } else {
            // Reset display fields
            document.getElementById('judul_display').textContent = '-';
            document.getElementById('nomor_display').textContent = '-';
            document.getElementById('nama_asesor_display').textContent = '-';
            document.getElementById('nama_asesi_display').textContent = '-';
            document.getElementById('tanggal_mulai_display').value = '';
            document.getElementById('waktu_mulai_display').value = '';
            document.getElementById('tanggal_selesai_display').value = '';
            document.getElementById('waktu_selesai_display').value = '';
            
            // Reset TUK radio buttons
            document.querySelectorAll('input[name="tuk"]').forEach(radio => {
                radio.checked = false;
            });
        }
    });
});

function updateHasil(index, changed) {
    const yaCheckbox = document.querySelector(`input[name="umpan_balik_data[${index}][ya]"]`);
    const tidakCheckbox = document.querySelector(`input[name="umpan_balik_data[${index}][tidak]"]`);
    const hasilSpan = document.getElementById(`hasil_${index}`);

    // Enforce mutual exclusivity
    if (changed === 'ya' && yaCheckbox.checked) {
        tidakCheckbox.checked = false;
    } else if (changed === 'tidak' && tidakCheckbox.checked) {
        yaCheckbox.checked = false;
    }

    // Update badge content and style
    if (yaCheckbox.checked) {
        hasilSpan.textContent = 'Ya';
        hasilSpan.className = 'badge bg-success';
    } else if (tidakCheckbox.checked) {
        hasilSpan.textContent = 'Tidak';
        hasilSpan.className = 'badge bg-danger';
    } else {
        hasilSpan.textContent = '-';
        hasilSpan.className = 'badge bg-secondary';
    }
}
</script>
@endsection
