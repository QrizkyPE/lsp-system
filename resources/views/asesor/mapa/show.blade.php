@extends('layouts.app')

@section('title', 'Detail MAPA')
@section('page-title', 'Detail MAPA - Merencanakan Aktivitas dan Proses Asesmen')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Detail MAPA</h4>
                    <div>
                        <a href="{{ route('asesor.mapa.edit', $mapa->id) }}" class="btn btn-warning">
                            <i class="fas fa-edit me-1"></i>Edit
                        </a>
                        <a href="{{ route('asesor.mapa.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i>Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Header -->
                    <div class="text-center mb-4">
                        <h4><strong>FR.MAPA.01 MERENCANAKAN AKTIVITAS DAN PROSES ASESMEN</strong></h4>
                    </div>

                    <!-- Informasi Skema -->
                    <div class="card mb-4">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">Informasi Skema</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered">
                                <tr>
                                    <td width="30%"><strong>Skema Sertifikasi</strong></td>
                                    <td>
                                        <span style="text-decoration: line-through;">KKNI</span>/Okupasi/<span style="text-decoration: line-through;">Klaster</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Judul:</strong></td>
                                    <td>{{ $mapa->skemaSertifikasi->nama_skema }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Nomor:</strong></td>
                                    <td>{{ $mapa->skemaSertifikasi->nomor_skema ?? '-' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- Section 1: Menentukan Pendekatan Asesmen -->
                    <div class="card mb-4">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">1. Menentukan Pendekatan Asesmen</h5>
                        </div>
                        <div class="card-body">
                            <!-- 1.1 Peserta -->
                            <div class="mb-4">
                                <h6><strong>1.1 Peserta</strong></h6>
                                <p>{{ $mapa->peserta ?? '-' }}</p>
                            </div>

                            <!-- Tujuan Asesmen -->
                            <div class="mb-4">
                                <h6><strong>Tujuan Asesmen</strong></h6>
                                <p>{{ $mapa->tujuan_asesmen ?? '-' }}</p>
                            </div>

                            <!-- Konteks Asesmen -->
                            <div class="mb-4">
                                <h6><strong>Konteks Asesmen:</strong></h6>
                                
                            </div>

                            <!-- Lingkungan -->
                            <div class="mb-4">
                                <h6><strong>Lingkungan</strong></h6>
                                <p>{{ $mapa->lingkungan ?? '-' }}</p>
                            </div>

                            <!-- Peluang untuk mengumpulkan bukti -->
                            <div class="mb-4">
                                <h6><strong>Peluang untuk mengumpulkan bukti dalam sejumlah situasi</strong></h6>
                                <p>{{ $mapa->peluang_bukti ?? '-' }}</p>
                            </div>

                            <!-- Hubungan antara standar kompetensi -->
                            <div class="mb-4">
                                <h6><strong>Hubungan antara standar kompetensi dan:</strong></h6>
                                <div class="row">
                                    <div class="col-md-4">
                                        <label>Bukti untuk mendukung asesmen / RPL:</label>
                                        <p>{{ $mapa->hubungan_standar_kompetensi['bukti_asesmen'] ?? '-' }}</p>
                                    </div>
                                    <div class="col-md-4">
                                        <label>Aktivitas kerja di tempat kerja kandidat:</label>
                                        <p>{{ $mapa->hubungan_standar_kompetensi['aktivitas_kerja'] ?? '-' }}</p>
                                    </div>
                                    <div class="col-md-4">
                                        <label>Kegiatan Pembelajaran:</label>
                                        <p>{{ $mapa->hubungan_standar_kompetensi['kegiatan_pembelajaran'] ?? '-' }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Siapa yang melakukan Asesmen / RPL -->
                            <div class="mb-4">
                                <h6><strong>Siapa yang melakukan Asesmen / RPL</strong></h6>
                                <p>{{ $mapa->pelaku_asesmen ?? '-' }}</p>
                            </div>

                            <!-- Konfirmasi dengan orang yang relevan -->
                            <div class="mb-4">
                                <h6><strong>Konfirmasi dengan orang yang relevan</strong></h6>
                                <p>{{ $mapa->konfirmasi_orang_relevan_1 ?? '-' }}</p>
                                @if($mapa->konfirmasi_orang_relevan_1 == 'Lainnya' && $mapa->konfirmasi_orang_relevan_1_lainnya)
                                    <p><strong>Lainnya:</strong> {{ $mapa->konfirmasi_orang_relevan_1_lainnya }}</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Section 1.2: Tolok ukur Asesmen -->
                    <div class="card mb-4">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">1.2 Tolok ukur Asesmen</h5>
                        </div>
                        <div class="card-body">
                            <p>{{ $mapa->tolok_ukur_asesmen ?? '-' }}</p>
                        </div>
                    </div>

                    <!-- Section 2: Mempersiapkan rencana asesmen -->
                    <div class="card mb-4">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">2. Mempersiapkan rencana asesmen</h5>
                        </div>
                        <div class="card-body">
                            @php
                                $rencanaAsesmen = $mapa->rencana_asesmen ?? [];
                            @endphp
                            @foreach($unitKompetensiJudul as $unit)
                                <div class="mb-4">
                                    <h6><strong>Unit Kompetensi</strong></h6>
                                    <p><strong>Kode Unit:</strong> {{ $unit->kode_unit }} | <strong>Judul Unit:</strong> {{ $unit->judul_unit }}</p>
                                    
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-sm">
                                            <thead class="table-dark">
                                                <tr>
                                                    <th>Kriteria Unjuk Kerja</th>
                                                    <th>Bukti - Bukti</th>
                                                    <th>Jenis bukti<br>(L/TL/T)</th>
                                                    <th>Metode dan Perangkat Asesmen</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($unit->elemenJudul as $elemen)
                                                    @foreach($elemen->kriteriaUnjukKerja as $kriteria)
                                                        @php
                                                            $rencanaData = $rencanaAsesmen[$unit->kode_unit][$kriteria->id] ?? [];
                                                            // Gunakan data tersimpan jika ada, jika tidak gunakan dari KUK
                                                            $jenisBuktiValue = $rencanaData['jenis_bukti'] ?? $kriteria->jenis_bukti ?? '';
                                                            $metodeValue = $rencanaData['metode'] ?? $kriteria->metode_asesmen ?? '';
                                                            $perangkatValue = $rencanaData['perangkat'] ?? $kriteria->perangkat_asesmen ?? '';
                                                            
                                                            $jenisBuktiOptions = [
                                                                'L' => 'L (Langsung)',
                                                                'TL' => 'TL (Tidak Langsung)',
                                                                'T' => 'T (Tambahan)'
                                                            ];
                                                            $metodeOptions = [
                                                                'CL' => 'CL (Daftar Periksa)',
                                                                'DIT' => 'DIT (Daftar Instruksi Terstruktur)',
                                                                'DPL' => 'DPL (Daftar Pertanyaan Lisan)',
                                                                'DPT' => 'DPT (Daftar Pertanyaan Tertulis)',
                                                                'PW' => 'PW (Pertanyaan Wawancara)',
                                                                'VP' => 'VP (Verifikasi Portofolio)',
                                                                'CUP' => 'CUP (Ceklis Ulasan Produk)',
                                                                'PMO' => 'PMO (Pertanyaan mendukung observasi)'
                                                            ];
                                                        @endphp
                                                        <tr>
                                                            <td>
                                                                <strong>{{ $elemen->nama_elemen }}</strong><br>
                                                                {{ $kriteria->deskripsi_kriteria }}
                                                            </td>
                                                            <td>{{ $rencanaData['bukti'] ?? '-' }}</td>
                                                            <td>{{ $jenisBuktiValue ? ($jenisBuktiOptions[$jenisBuktiValue] ?? $jenisBuktiValue) : '-' }}</td>
                                                            <td>
                                                                <strong>Metode:</strong> {{ $metodeValue ? ($metodeOptions[$metodeValue] ?? $metodeValue) : '-' }}<br>
                                                                <strong>Perangkat:</strong> {{ $perangkatValue ?: '-' }}
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Section 3: Mengidentifikasi Persyaratan Modifikasi dan Kontekstualisasi -->
                    <div class="card mb-4">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">3. Mengidentifikasi Persyaratan Modifikasi dan Kontekstualisasi</h5>
                        </div>
                        <div class="card-body">
                            <!-- 3.1 -->
                            <div class="mb-4">
                                <h6><strong>3.1. Karakteristik kandidat:</strong></h6>
                                <p>{{ $mapa->karakteristik_kandidat ?? '-' }}</p>
                            </div>

                            <div class="mb-4">
                                <h6><strong>Kebutuhan kontekstualisasi terkait tempat kerja:</strong></h6>
                                <p>{{ $mapa->kebutuhan_kontekstualisasi_tempat_kerja ?? '-' }}</p>
                            </div>

                            <!-- 3.2 -->
                            <div class="mb-4">
                                <h6><strong>2.2 Saran yang diberikan oleh paket pelatihan atau pengembangan pelatihan</strong></h6>
                                <p>{{ $mapa->saran_paket_pelatihan ?? '-' }}</p>
                            </div>

                            <!-- 3.3 -->
                            <div class="mb-4">
                                <h6><strong>2.3 Penyesuaian perangkat asesmen terkait kebutuhan kontekstualisasi</strong></h6>
                                <p>{{ $mapa->penyesuaian_perangkat_asesmen ?? '-' }}</p>
                            </div>

                            <!-- 3.4 -->
                            <div class="mb-4">
                                <h6><strong>2.4 Peluang untuk kegiatan asesmen terintegrasi dan mencatat setiap perubahan yang diperlukan untuk alat asesmen</strong></h6>
                                <p>{{ $mapa->peluang_kegiatan_terintegrasi ?? '-' }}</p>
                                @if($mapa->peluang_kegiatan_terintegrasi == 'Ada' && $mapa->kegiatan_terintegrasi_units)
                                    <ul>
                                        @foreach($mapa->kegiatan_terintegrasi_units as $unitCode)
                                            @php
                                                $unit = $unitKompetensiJudul->where('kode_unit', $unitCode)->first();
                                            @endphp
                                            @if($unit)
                                                <li>Kode Unit : {{ $unit->kode_unit }} Judul Unit : {{ $unit->judul_unit }}</li>
                                            @endif
                                        @endforeach
                                    </ul>
                                @endif
                            </div>

                            <!-- Konfirmasi dengan orang yang relevan -->
                            <div class="mb-4">
                                <h6><strong>Konfirmasi dengan orang yang relevan:</strong></h6>
                                <p>{{ $mapa->konfirmasi_orang_relevan_2 ?? '-' }}</p>
                                @if($mapa->konfirmasi_orang_relevan_2 == 'Lainnya' && $mapa->konfirmasi_orang_relevan_2_lainnya)
                                    <p><strong>Lainnya:</strong> {{ $mapa->konfirmasi_orang_relevan_2_lainnya }}</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Penyusun dan Validator -->
                    <div class="card mb-4">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">Penyusun dan Validator</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Nama</th>
                                        <th>Jabatan</th>
                                        <th>Tandatangan</th>
                                        <th>Tgl</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{ $mapa->penyusun_nama ?? '-' }}</td>
                                        <td>Penyusun</td>
                                        <td>
                                            @if($mapa->penyusun_tandatangan)
                                                <img src="{{ $mapa->penyusun_tandatangan }}" alt="Tanda Tangan Penyusun" style="max-width: 200px; max-height: 100px; border: 1px solid #ddd; border-radius: 4px;">
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>{{ $mapa->penyusun_tanggal ? $mapa->penyusun_tanggal->format('d/m/Y') : '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td>{{ $mapa->validator_nama ?? '-' }}</td>
                                        <td>Validator</td>
                                        <td>
                                            @if($mapa->validator_tandatangan)
                                                <img src="{{ $mapa->validator_tandatangan }}" alt="Tanda Tangan Validator" style="max-width: 200px; max-height: 100px; border: 1px solid #ddd; border-radius: 4px;">
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>{{ $mapa->validator_tanggal ? $mapa->validator_tanggal->format('d/m/Y') : '-' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

