@extends('layouts.app')

@section('title', 'Buat MAPA')
@section('page-title', 'Buat MAPA - Merencanakan Aktivitas dan Proses Asesmen')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Buat MAPA</h4>
                    <a href="{{ route('asesor.mapa.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i>Kembali
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('asesor.mapa.store', $skema->id) }}" method="POST" id="mapaForm">
                        @csrf

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
                                        <td>{{ $skema->nama_skema }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Nomor:</strong></td>
                                        <td>{{ $skema->nomor_skema ?? '-' }}</td>
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
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="peserta" id="peserta_pelatihan" value="Hasil pelatihan dan / atau pendidikan">
                                        <label class="form-check-label" for="peserta_pelatihan">
                                             Hasil pelatihan dan / atau pendidikan
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="peserta" id="peserta_pekerja" value="Pekerja berpengalaman">
                                        <label class="form-check-label" for="peserta_pekerja">
                                             Pekerja berpengalaman
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="peserta" id="peserta_mandiri" value="Pelatihan / belajar mandiri">
                                        <label class="form-check-label" for="peserta_mandiri">
                                             Pelatihan / belajar mandiri
                                        </label>
                                    </div>
                                </div>

                                <!-- Tujuan Asesmen -->
                                <div class="mb-4">
                                    <h6><strong>Tujuan Asesmen</strong></h6>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="tujuan_asesmen" id="tujuan_sertifikasi" value="Sertifikasi">
                                        <label class="form-check-label" for="tujuan_sertifikasi"> Sertifikasi</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="tujuan_asesmen" id="tujuan_rcc" value="RCC">
                                        <label class="form-check-label" for="tujuan_rcc"> RCC</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="tujuan_asesmen" id="tujuan_rpl" value="RPL">
                                        <label class="form-check-label" for="tujuan_rpl"> RPL</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="tujuan_asesmen" id="tujuan_pelatihan" value="Hasil pelatihan/proses pembelajaran">
                                        <label class="form-check-label" for="tujuan_pelatihan"> Hasil pelatihan/proses pembelajaran</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="tujuan_asesmen" id="tujuan_lainnya" value="Lainnya">
                                        <label class="form-check-label" for="tujuan_lainnya"> Lainnya</label>
                                    </div>
                                </div>

                                <!-- Konteks Asesmen -->
                                <div class="mb-4">
                                    <h6><strong>Konteks Asesmen:</strong></h6>
                                </div>

                                <!-- Lingkungan -->
                                <div class="mb-4">
                                    <h6><strong>Lingkungan</strong></h6>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="lingkungan" id="lingkungan_nyata" value="Tempat kerja nyata">
                                        <label class="form-check-label" for="lingkungan_nyata"> Tempat kerja nyata</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="lingkungan" id="lingkungan_simulasi" value="Tempat kerja simulasi">
                                        <label class="form-check-label" for="lingkungan_simulasi"> Tempat kerja simulasi</label>
                                    </div>
                                </div>

                                <!-- Peluang untuk mengumpulkan bukti -->
                                <div class="mb-4">
                                    <h6><strong>Peluang untuk mengumpulkan bukti dalam sejumlah situasi</strong></h6>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="peluang_bukti" id="peluang_tersedia" value="Tersedia">
                                        <label class="form-check-label" for="peluang_tersedia"> Tersedia</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="peluang_bukti" id="peluang_terbatas" value="Terbatas">
                                        <label class="form-check-label" for="peluang_terbatas"> Terbatas</label>
                                    </div>
                                </div>

                                <!-- Hubungan antara standar kompetensi -->
                                <div class="mb-4">
                                    <h6><strong>Hubungan antara standar kompetensi dan:</strong></h6>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label>Bukti untuk mendukung asesmen / RPL:</label>
                                            <select name="hubungan_standar_kompetensi[bukti_asesmen]" class="form-select">
                                                <option value="">Pilih</option>
                                                <option value="😊">😊</option>
                                                <option value="😐">😐</option>
                                                <option value="😞">😞</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Aktivitas kerja di tempat kerja kandidat:</label>
                                            <select name="hubungan_standar_kompetensi[aktivitas_kerja]" class="form-select">
                                                <option value="">Pilih</option>
                                                <option value="😊">😊</option>
                                                <option value="😐">😐</option>
                                                <option value="😞">😞</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Kegiatan Pembelajaran:</label>
                                            <select name="hubungan_standar_kompetensi[kegiatan_pembelajaran]" class="form-select">
                                                <option value="">Pilih</option>
                                                <option value="😊">😊</option>
                                                <option value="😐">😐</option>
                                                <option value="😞">😞</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- Siapa yang melakukan Asesmen / RPL -->
                                <div class="mb-4">
                                    <h6><strong>Siapa yang melakukan Asesmen / RPL</strong></h6>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="pelaku_asesmen" id="pelaku_lembaga" value="Lembaga Sertifikasi">
                                        <label class="form-check-label" for="pelaku_lembaga"> Lembaga Sertifikasi</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="pelaku_asesmen" id="pelaku_organisasi" value="Organisasi Pelatihan">
                                        <label class="form-check-label" for="pelaku_organisasi"> Organisasi Pelatihan</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="pelaku_asesmen" id="pelaku_asesor" value="Asesor Perusahaan">
                                        <label class="form-check-label" for="pelaku_asesor"> Asesor Perusahaan</label>
                                    </div>
                                </div>

                                <!-- Konfirmasi dengan orang yang relevan -->
                                <div class="mb-4">
                                    <h6><strong>Konfirmasi dengan orang yang relevan</strong></h6>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="konfirmasi_orang_relevan_1" id="konfirmasi1_manajer" value="Manajer sertifikasi LSP P1 UMDP">
                                        <label class="form-check-label" for="konfirmasi1_manajer"> Manajer sertifikasi LSP P1 UMDP</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="konfirmasi_orang_relevan_1" id="konfirmasi1_master" value="Master Assessor/Master Trainer/Asesor Utama Kompetensi">
                                        <label class="form-check-label" for="konfirmasi1_master"> Master Assessor/Master Trainer/Asesor Utama Kompetensi</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="konfirmasi_orang_relevan_1" id="konfirmasi1_training" value="Manajer pelatihan Lembaga Training terakreditasi / Lembaga Training terdaftar">
                                        <label class="form-check-label" for="konfirmasi1_training"> Manajer pelatihan Lembaga Training terakreditasi / Lembaga Training terdaftar</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="konfirmasi_orang_relevan_1" id="konfirmasi1_lainnya" value="Lainnya">
                                        <label class="form-check-label" for="konfirmasi1_lainnya"> Lainnya:</label>
                                    </div>
                                    <input type="text" name="konfirmasi_orang_relevan_1_lainnya" class="form-control mt-2" placeholder="Sebutkan lainnya" style="max-width: 400px;">
                                </div>
                            </div>
                        </div>

                        <!-- Section 1.2: Tolok ukur Asesmen -->
                        <div class="card mb-4">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0">1.2 Tolok ukur Asesmen</h5>
                            </div>
                            <div class="card-body">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="tolok_ukur_asesmen" id="tolok_standar" value="Standar Kompetensi: SKKNI No. 282 Tahun 2016 subbidang Pemrograman, dan SKKNI No. 268 Tahun 2020 subbidang Data Management">
                                    <label class="form-check-label" for="tolok_standar">
                                         Standar Kompetensi: SKKNI No. 282 Tahun 2016 subbidang Pemrograman, dan SKKNI No. 268 Tahun 2020 subbidang Data Management
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="tolok_ukur_asesmen" id="tolok_kurikulum" value="Kriteria asesmen dari kurikulum pelatihan">
                                    <label class="form-check-label" for="tolok_kurikulum"> Kriteria asesmen dari kurikulum pelatihan</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="tolok_ukur_asesmen" id="tolok_spesifikasi" value="Spesifikasi kinerja suatu perusahaan atau industri:">
                                    <label class="form-check-label" for="tolok_spesifikasi"> Spesifikasi kinerja suatu perusahaan atau industri:</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="tolok_ukur_asesmen" id="tolok_produk" value="Spesifikasi Produk:">
                                    <label class="form-check-label" for="tolok_produk"> Spesifikasi Produk:</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="tolok_ukur_asesmen" id="tolok_pedoman" value="Pedoman khusus:">
                                    <label class="form-check-label" for="tolok_pedoman"> Pedoman khusus:</label>
                                </div>
                            </div>
                        </div>

                        <!-- Section 2: Mempersiapkan rencana asesmen -->
                        <div class="card mb-4">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0">2. Mempersiapkan rencana asesmen</h5>
                            </div>
                            <div class="card-body">
                                @foreach($unitKompetensiJudul as $unit)
                                    <div class="mb-4">
                                        <h6><strong>Unit Kompetensi</strong></h6>
                                        <p><strong>Kode Unit:</strong> {{ $unit->kode_unit }} | <strong>Judul Unit:</strong> {{ $unit->judul_unit }}</p>
                                        
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-sm">
                                                <thead class="table-dark">
                                                    <tr>
                                                        <th>Kriteria Unjuk Kerja</th>
                                                        <th>Bukti - Bukti (Kinerja, Produk, Portofolio, dan / atau hafalan) diidentifikasi berdasarkan Kriteria Unjuk Kerja dan Pendekatan Asesmen.</th>
                                                        <th>Jenis bukti<br>(L/TL/T)</th>
                                                        <th>Metode dan Perangkat Asesmen</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($unit->elemenJudul as $elemen)
                                                        @foreach($elemen->kriteriaUnjukKerja as $kriteria)
                                                            <tr>
                                                                <td>
                                                                    <strong>{{ $elemen->nama_elemen }}</strong><br>
                                                                    {{ $kriteria->deskripsi_kriteria }}
                                                                </td>
                                                                <td>
                                                                    <textarea name="rencana_asesmen[{{ $unit->kode_unit }}][{{ $kriteria->id }}][bukti]" class="form-control form-control-sm" rows="2" placeholder="Masukkan bukti"></textarea>
                                                                </td>
                                                                <td>
                                                                    @php
                                                                        $jenisBuktiOptions = [
                                                                            'L' => 'L (Langsung)',
                                                                            'TL' => 'TL (Tidak Langsung)',
                                                                            'T' => 'T (Tambahan)'
                                                                        ];
                                                                        $jenisBuktiValue = $kriteria->jenis_bukti ?? '';
                                                                    @endphp
                                                                    <input type="text" class="form-control form-control-sm" value="{{ $jenisBuktiValue ? ($jenisBuktiOptions[$jenisBuktiValue] ?? $jenisBuktiValue) : '-' }}" readonly style="background-color: #f8f9fa;">
                                                                    <input type="hidden" name="rencana_asesmen[{{ $unit->kode_unit }}][{{ $kriteria->id }}][jenis_bukti]" value="{{ $jenisBuktiValue }}">
                                                                </td>
                                                                <td>
                                                                    @php
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
                                                                        $metodeValue = $kriteria->metode_asesmen ?? '';
                                                                        $perangkatValue = $kriteria->perangkat_asesmen ?? '';
                                                                    @endphp
                                                                    <input type="text" class="form-control form-control-sm mb-2" value="{{ $metodeValue ? ($metodeOptions[$metodeValue] ?? $metodeValue) : '-' }}" readonly style="background-color: #f8f9fa;">
                                                                    <input type="hidden" name="rencana_asesmen[{{ $unit->kode_unit }}][{{ $kriteria->id }}][metode]" value="{{ $metodeValue }}">
                                                                    <input type="text" class="form-control form-control-sm" value="{{ $perangkatValue ?: '-' }}" readonly style="background-color: #f8f9fa;">
                                                                    <input type="hidden" name="rencana_asesmen[{{ $unit->kode_unit }}][{{ $kriteria->id }}][perangkat]" value="{{ $perangkatValue }}">
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
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="karakteristik_kandidat" id="karakteristik_ada" value="Ada Karateristik Khusus">
                                        <label class="form-check-label" for="karakteristik_ada"> Ada Karateristik Khusus:</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="karakteristik_kandidat" id="karakteristik_tidak" value="Tidak Ada Karakteristik Khusus">
                                        <label class="form-check-label" for="karakteristik_tidak"> Tidak Ada Karakteristik Khusus</label>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <h6><strong>Kebutuhan kontekstualisasi terkait tempat kerja:</strong></h6>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="kebutuhan_kontekstualisasi_tempat_kerja" id="kontekstualisasi_ada" value="Ada Konstekstualisasi">
                                        <label class="form-check-label" for="kontekstualisasi_ada"> Ada Konstekstualisasi:</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="kebutuhan_kontekstualisasi_tempat_kerja" id="kontekstualisasi_tidak" value="Tidak Ada Kontekstualisasi">
                                        <label class="form-check-label" for="kontekstualisasi_tidak"> Tidak Ada Kontekstualisasi</label>
                                    </div>
                                </div>

                                <!-- 3.2 -->
                                <div class="mb-4">
                                    <h6><strong>3.2 Saran yang diberikan oleh paket pelatihan atau pengembangan pelatihan</strong></h6>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="saran_paket_pelatihan" id="saran_diperlukan" value="Diperlukan perubahan/penyesuaian/kontekstualisasi:">
                                        <label class="form-check-label" for="saran_diperlukan"> Diperlukan perubahan/penyesuaian/kontekstualisasi:</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="saran_paket_pelatihan" id="saran_tidak" value="Tidak diperlukan perubahan/penyesuaian/kontekstualisasi">
                                        <label class="form-check-label" for="saran_tidak"> Tidak diperlukan perubahan/penyesuaian/kontekstualisasi</label>
                                    </div>
                                </div>

                                <!-- 3.3 -->
                                <div class="mb-4">
                                    <h6><strong>3.3 Penyesuaian perangkat asesmen terkait kebutuhan kontekstualisasi</strong></h6>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="penyesuaian_perangkat_asesmen" id="penyesuaian_ada" value="Ada">
                                        <label class="form-check-label" for="penyesuaian_ada"> Ada:</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="penyesuaian_perangkat_asesmen" id="penyesuaian_tidak" value="Tidak Ada">
                                        <label class="form-check-label" for="penyesuaian_tidak"> Tidak Ada</label>
                                    </div>
                                </div>

                                <!-- 3.4 -->
                                <div class="mb-4">
                                    <h6><strong>3.4 Peluang untuk kegiatan asesmen terintegrasi dan mencatat setiap perubahan yang diperlukan untuk alat asesmen</strong></h6>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="radio" name="peluang_kegiatan_terintegrasi" id="terintegrasi_ada" value="Ada" onchange="toggleTerintegrasiUnits(this)">
                                        <label class="form-check-label" for="terintegrasi_ada"> Ada:</label>
                                    </div>
                                    <div id="terintegrasi_units_container" style="display: none;">
                                        @php
                                            // Kelompokkan unit kompetensi berdasarkan kelompok
                                            $unitsByKelompok = [];
                                            
                                            // Get all unit IDs
                                            $unitIds = $unitKompetensiJudul->pluck('id')->toArray();
                                            
                                            // Query langsung dari database untuk memastikan data ter-load
                                            $kelompokData = \Illuminate\Support\Facades\DB::table('unit_kompetensi_judul_asesor_kelompok')
                                                ->whereIn('unit_kompetensi_judul_id', $unitIds)
                                                ->select('unit_kompetensi_judul_id', 'kelompok')
                                                ->distinct()
                                                ->get()
                                                ->groupBy('unit_kompetensi_judul_id');
                                            
                                            // Iterate through all units and group them by kelompok
                                            foreach ($unitKompetensiJudul as $unit) {
                                                // Check if unit has pembagian kelompok
                                                if ($unit->ada_pembagian_kelompok == true) {
                                                    // Check if this unit has kelompok data from database
                                                    if (isset($kelompokData[$unit->id])) {
                                                        $kelompokNumbers = $kelompokData[$unit->id]->pluck('kelompok')->unique()->toArray();
                                                        
                                                        foreach ($kelompokNumbers as $kelompokNum) {
                                                            $kelompokNum = (int)$kelompokNum;
                                                            if ($kelompokNum > 0) {
                                                                if (!isset($unitsByKelompok[$kelompokNum])) {
                                                                    $unitsByKelompok[$kelompokNum] = [];
                                                                }
                                                                // Add unit if not already added
                                                                $unitExists = false;
                                                                foreach ($unitsByKelompok[$kelompokNum] as $existingUnit) {
                                                                    if ($existingUnit->id === $unit->id) {
                                                                        $unitExists = true;
                                                                        break;
                                                                    }
                                                                }
                                                                if (!$unitExists) {
                                                                    $unitsByKelompok[$kelompokNum][] = $unit;
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                            
                                            // Sort by kelompok number
                                            ksort($unitsByKelompok);
                                        @endphp
                                        
                                        @if(count($unitsByKelompok) > 0)
                                            @foreach($unitsByKelompok as $kelompokNum => $units)
                                                @if(count($units) > 0)
                                                    <div class="mb-2">
                                                        <strong>Kelompok {{ $kelompokNum }}:</strong>
                                                        @foreach($units as $index => $unit)
                                                            ({{ $unit->kode_unit }} - {{ $unit->judul_unit }})@if($index < count($units) - 1), @endif
                                                        @endforeach
                                                    </div>
                                                @endif
                                            @endforeach
                                        @endif
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="peluang_kegiatan_terintegrasi" id="terintegrasi_tidak" value="Tidak Ada" onchange="toggleTerintegrasiUnits(this)">
                                        <label class="form-check-label" for="terintegrasi_tidak"> Tidak Ada</label>
                                    </div>
                                </div>

                                <!-- Konfirmasi dengan orang yang relevan -->
                                <div class="mb-4">
                                    <h6><strong>Konfirmasi dengan orang yang relevan:</strong></h6>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="konfirmasi_orang_relevan_2" id="konfirmasi2_manajer" value="Manajer sertifikasi LSP P1 UMDP">
                                        <label class="form-check-label" for="konfirmasi2_manajer"> Manajer sertifikasi LSP P1 UMDP</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="konfirmasi_orang_relevan_2" id="konfirmasi2_master" value="Master Assessor / Master Trainer / Asesor Utama kompetensi">
                                        <label class="form-check-label" for="konfirmasi2_master"> Master Assessor / Master Trainer / Asesor Utama kompetensi</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="konfirmasi_orang_relevan_2" id="konfirmasi2_training" value="Manajer pelatihan Lembaga Training terakreditasi / Lembaga Training terdaftar">
                                        <label class="form-check-label" for="konfirmasi2_training"> Manajer pelatihan Lembaga Training terakreditasi / Lembaga Training terdaftar</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="konfirmasi_orang_relevan_2" id="konfirmasi2_lainnya" value="Lainnya">
                                        <label class="form-check-label" for="konfirmasi2_lainnya"> Lainnya:</label>
                                    </div>
                                    <input type="text" name="konfirmasi_orang_relevan_2_lainnya" class="form-control mt-2" placeholder="Sebutkan lainnya" style="max-width: 400px;">
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
                                            <td>
                                                <input type="text" name="penyusun_nama" class="form-control" placeholder="Nama Penyusun">
                                            </td>
                                            <td>Penyusun</td>
                                            <td>
                                                <div class="signature-container">
                                                    <canvas id="penyusunSignatureCanvas" width="300" height="150" style="border: 1px solid #ddd; border-radius: 4px; background: white; cursor: crosshair; width: 100%; max-width: 300px;"></canvas>
                                                    <div class="mt-2">
                                                        <button type="button" class="btn btn-sm btn-secondary" onclick="clearSignature('penyusunSignatureCanvas', 'penyusun_tandatangan')">
                                                            <i class="fas fa-eraser me-1"></i>Hapus
                                                        </button>
                                                    </div>
                                                    <input type="hidden" name="penyusun_tandatangan" id="penyusun_tandatangan">
                                                </div>
                                            </td>
                                            <td>
                                                <input type="date" name="penyusun_tanggal" class="form-control">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <input type="text" name="validator_nama" class="form-control" placeholder="Nama Validator">
                                            </td>
                                            <td>Validator</td>
                                            <td>
                                                <div class="signature-container">
                                                    <canvas id="validatorSignatureCanvas" width="300" height="150" style="border: 1px solid #ddd; border-radius: 4px; background: white; cursor: crosshair; width: 100%; max-width: 300px;"></canvas>
                                                    <div class="mt-2">
                                                        <button type="button" class="btn btn-sm btn-secondary" onclick="clearSignature('validatorSignatureCanvas', 'validator_tandatangan')">
                                                            <i class="fas fa-eraser me-1"></i>Hapus
                                                        </button>
                                                    </div>
                                                    <input type="hidden" name="validator_tandatangan" id="validator_tandatangan">
                                                </div>
                                            </td>
                                            <td>
                                                <input type="date" name="validator_tanggal" class="form-control">
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-save me-2"></i>Simpan MAPA
                            </button>
                            <a href="{{ route('asesor.mapa.index') }}" class="btn btn-secondary btn-lg">
                                <i class="fas fa-times me-2"></i>Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function toggleTerintegrasiUnits(radio) {
    const container = document.getElementById('terintegrasi_units_container');
    if (radio.id === 'terintegrasi_ada' && radio.checked) {
        container.style.display = 'block';
    } else {
        container.style.display = 'none';
        // Uncheck all checkboxes
        container.querySelectorAll('input[type="checkbox"]').forEach(cb => cb.checked = false);
    }
}

// Signature Canvas Functions
function initSignatureCanvas(canvasId, hiddenInputId) {
    const canvas = document.getElementById(canvasId);
    const ctx = canvas.getContext('2d');
    const hiddenInput = document.getElementById(hiddenInputId);
    
    let isDrawing = false;
    let lastX = 0;
    let lastY = 0;

    // Set canvas background to white
    ctx.fillStyle = 'white';
    ctx.fillRect(0, 0, canvas.width, canvas.height);

    // Set drawing properties
    ctx.strokeStyle = '#000000';
    ctx.lineWidth = 2;
    ctx.lineCap = 'round';
    ctx.lineJoin = 'round';

    // Mouse events
    canvas.addEventListener('mousedown', function(e) {
        isDrawing = true;
        const rect = canvas.getBoundingClientRect();
        const scaleX = canvas.width / rect.width;
        const scaleY = canvas.height / rect.height;
        lastX = (e.clientX - rect.left) * scaleX;
        lastY = (e.clientY - rect.top) * scaleY;
    });

    canvas.addEventListener('mousemove', function(e) {
        if (!isDrawing) return;
        const rect = canvas.getBoundingClientRect();
        const scaleX = canvas.width / rect.width;
        const scaleY = canvas.height / rect.height;
        const currentX = (e.clientX - rect.left) * scaleX;
        const currentY = (e.clientY - rect.top) * scaleY;

        ctx.beginPath();
        ctx.moveTo(lastX, lastY);
        ctx.lineTo(currentX, currentY);
        ctx.stroke();

        lastX = currentX;
        lastY = currentY;
        
        // Auto-save signature
        hiddenInput.value = canvas.toDataURL('image/png');
    });

    canvas.addEventListener('mouseup', function() {
        isDrawing = false;
        hiddenInput.value = canvas.toDataURL('image/png');
    });

    canvas.addEventListener('mouseout', function() {
        isDrawing = false;
        hiddenInput.value = canvas.toDataURL('image/png');
    });

    // Touch events for mobile
    canvas.addEventListener('touchstart', function(e) {
        e.preventDefault();
        isDrawing = true;
        const rect = canvas.getBoundingClientRect();
        const scaleX = canvas.width / rect.width;
        const scaleY = canvas.height / rect.height;
        const touch = e.touches[0];
        lastX = (touch.clientX - rect.left) * scaleX;
        lastY = (touch.clientY - rect.top) * scaleY;
    });

    canvas.addEventListener('touchmove', function(e) {
        e.preventDefault();
        if (!isDrawing) return;
        const rect = canvas.getBoundingClientRect();
        const scaleX = canvas.width / rect.width;
        const scaleY = canvas.height / rect.height;
        const touch = e.touches[0];
        const currentX = (touch.clientX - rect.left) * scaleX;
        const currentY = (touch.clientY - rect.top) * scaleY;

        ctx.beginPath();
        ctx.moveTo(lastX, lastY);
        ctx.lineTo(currentX, currentY);
        ctx.stroke();

        lastX = currentX;
        lastY = currentY;
        
        // Auto-save signature
        hiddenInput.value = canvas.toDataURL('image/png');
    });

    canvas.addEventListener('touchend', function(e) {
        e.preventDefault();
        isDrawing = false;
        hiddenInput.value = canvas.toDataURL('image/png');
    });
}

function clearSignature(canvasId, hiddenInputId) {
    const canvas = document.getElementById(canvasId);
    const ctx = canvas.getContext('2d');
    const hiddenInput = document.getElementById(hiddenInputId);
    
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    ctx.fillStyle = 'white';
    ctx.fillRect(0, 0, canvas.width, canvas.height);
    hiddenInput.value = '';
}

// Initialize signature canvases when page loads
document.addEventListener('DOMContentLoaded', function() {
    initSignatureCanvas('penyusunSignatureCanvas', 'penyusun_tandatangan');
    initSignatureCanvas('validatorSignatureCanvas', 'validator_tandatangan');
    
    // Save signatures before form submit
    const form = document.getElementById('mapaForm');
    form.addEventListener('submit', function(e) {
        const penyusunCanvas = document.getElementById('penyusunSignatureCanvas');
        const validatorCanvas = document.getElementById('validatorSignatureCanvas');
        const penyusunInput = document.getElementById('penyusun_tandatangan');
        const validatorInput = document.getElementById('validator_tandatangan');
        
        // Save signatures if canvas has drawing
        if (penyusunCanvas) {
            penyusunInput.value = penyusunCanvas.toDataURL('image/png');
        }
        if (validatorCanvas) {
            validatorInput.value = validatorCanvas.toDataURL('image/png');
        }
    });
});
</script>
@endsection

