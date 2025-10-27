@extends('layouts.app')

@section('title', 'Buat Ceklis Penyesuaian Yang Wajar dan Beralasan')
@section('page-title', 'Buat Ceklis Penyesuaian Yang Wajar dan Beralasan')

@section('content')
<div class="container-fluid">
    <!-- Main Content -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header">
                    <h4 class="mb-0">FR.AK.07 CEKLIS PENYESUAIAN YANG WAJAR DAN BERALASAN</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('asesor.penyesuaian.store') }}" id="penyesuaianForm">
                        @csrf
                        
                        <!-- Informasi Dasar -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="alert alert-info">
                                    <strong>Informasi Ceklis Penyesuaian</strong><br>
                                    Pilih mahasiswa yang telah menyelesaikan <strong>persetujuan asesmen</strong> untuk membuat ceklis penyesuaian.
                                    <br><small class="text-muted">Hanya mahasiswa yang sudah menyelesaikan persetujuan asesmen yang akan muncul dalam daftar pilihan.</small>
                                </div>
                            </div>
                        </div>

                        <!-- Pilih Mahasiswa -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <label for="pendaftaran_id" class="form-label"><strong>Pilih Mahasiswa</strong></label>
                                @if($pendaftaran->count() > 0)
                                    <select class="form-select @error('pendaftaran_id') is-invalid @enderror" 
                                            id="pendaftaran_id" name="pendaftaran_id" required>
                                        <option value="">-- Pilih Mahasiswa --</option>
                                        @foreach($pendaftaran as $p)
                                            <option value="{{ $p->id }}" 
                                                    data-judul="{{ $p->skemaSertifikasi->nama_skema }}"
                                                    data-nomor="{{ $p->skemaSertifikasi->nomor_skema }}"
                                                    data-nama="{{ $p->user->name }}">
                                                {{ $p->user->name }} - {{ $p->skemaSertifikasi->nama_skema }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('pendaftaran_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                @else
                                    <div class="alert alert-warning">
                                        <i class="fas fa-exclamation-triangle me-2"></i>
                                        <strong>Tidak ada mahasiswa yang tersedia</strong><br>
                                        Belum ada mahasiswa yang telah menyelesaikan persetujuan asesmen.
                                        <br><small class="text-muted">Silakan tunggu mahasiswa menyelesaikan persetujuan asesmen terlebih dahulu.</small>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Informasi Dasar -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5><strong>Informasi Dasar</strong></h5>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <tr>
                                            <td width="20%"><strong>Skema Sertifikasi</strong></td>
                                            <td>
                                                <span class="text-decoration-line-through">KKNI</span> / 
                                                <strong>Okupasi</strong> / 
                                                <span class="text-decoration-line-through">Klaster</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Judul</strong></td>
                                            <td id="judul-display">-</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Nomor</strong></td>
                                            <td id="nomor-display">-</td>
                                        </tr>
                                        <tr>
                                            <td><strong>TUK</strong></td>
                                            <td>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="tuk" id="tuk_sewaktu" value="sewaktu" required {{ $pendaftaran->count() == 0 ? 'disabled' : '' }}>
                                                    <label class="form-check-label" for="tuk_sewaktu">Sewaktu</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="tuk" id="tuk_tempat_kerja" value="tempat_kerja" required {{ $pendaftaran->count() == 0 ? 'disabled' : '' }}>
                                                    <label class="form-check-label" for="tuk_tempat_kerja">Tempat Kerja</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="tuk" id="tuk_mandiri" value="mandiri" required {{ $pendaftaran->count() == 0 ? 'disabled' : '' }}>
                                                    <label class="form-check-label" for="tuk_mandiri">Mandiri</label>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Nama Asesor</strong></td>
                                            <td>{{ Auth::user()->name }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Nama Asesi</strong></td>
                                            <td id="nama-asesi-display">-</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Tanggal</strong></td>
                                            <td>
                                                <input type="date" class="form-control" name="tanggal" value="{{ date('Y-m-d') }}" required {{ $pendaftaran->count() == 0 ? 'disabled' : '' }}>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Panduan Asesor -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5><strong>PANDUAN BAGI ASESOR</strong></h5>
                                <div class="alert alert-info">
                                    <p>Formulir ini dapat digunakan (sebelum pra asesmen, saat pelaksanaan pra asesmen, setelah pra asesmen)* jika ada asesi yang mempunyai keterbatasan sesuai karakteristik yang dimilikinya sehingga diperlukan penyesuaian yang wajar dan beralasan, jika rencana asesmen dan perangkat asesmen tidak sesuai dengan acuan pembanding, potensi asesi dan konteks asesi, jika asesi merasa keletihan, sakit, serta jika kondisi alam, listrik padam,……..</p>
                                    <p><strong>Coretlah pada tanda * yang tidak sesuai.</strong></p>
                                    <p><strong>Berilah tanda √ pada kotak '☐' pada kolom potensi asesi</strong></p>
                                    <p><strong>Berilah tanda √ Ya atau Tidak pada tanda ** sesuai pilihan, jika jawaban Ya selanjutnya pada kolom keterangan berilah tanda √ di kotak '☐' yang tersedia, pilihan boleh lebih dari satu.</strong></p>
                                </div>
                            </div>
                        </div>

                        <!-- Potensi Asesi -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5><strong>Potensi Asesi</strong></h5>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead class="table-dark">
                                            <tr>
                                                <th width="80%">Potensi Asesi</th>
                                                <th width="20%" class="text-center">Pilih</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Hasil pelatihan dan / atau pendidikan, dimana Kurikulum dan fasilitas praktek mampu telusur terhadap standar kompetensi</td>
                                                <td class="text-center">
                                                    <input type="checkbox" name="potensi_asesi[]" value="pelatihan_telusur" class="form-check-input" {{ $pendaftaran->count() == 0 ? 'disabled' : '' }}>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Hasil pelatihan dan / atau pendidikan, dimana kurikulum belum berbasis kompetensi.</td>
                                                <td class="text-center">
                                                    <input type="checkbox" name="potensi_asesi[]" value="pelatihan_belum_kompetensi" class="form-check-input" {{ $pendaftaran->count() == 0 ? 'disabled' : '' }}>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Pekerja berpengalaman, dimana berasal dari industri/tempat kerja yang dalam operasionalnya mampu telusur dengan standar kompetensi</td>
                                                <td class="text-center">
                                                    <input type="checkbox" name="potensi_asesi[]" value="pekerja_telusur" class="form-check-input" {{ $pendaftaran->count() == 0 ? 'disabled' : '' }}>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Pekerja berpengalaman, dimana berasal dari industri/tempat kerja yang dalam operasionalnya belum berbasis kompetensi.</td>
                                                <td class="text-center">
                                                    <input type="checkbox" name="potensi_asesi[]" value="pekerja_belum_kompetensi" class="form-check-input" {{ $pendaftaran->count() == 0 ? 'disabled' : '' }}>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Pelatihan / belajar mandiri atau otodidak.</td>
                                                <td class="text-center">
                                                    <input type="checkbox" name="potensi_asesi[]" value="mandiri_otodidak" class="form-check-input" {{ $pendaftaran->count() == 0 ? 'disabled' : '' }}>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Modifikasi dan Kontekstualisasi -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5><strong>Mengidentifikasi Persyaratan Modifikasi dan Kontekstualisasi (karakteristik asesi):</strong></h5>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead class="table-dark">
                                            <tr>
                                                <th width="5%">No</th>
                                                <th width="40%">Diperlukan penyesuaian</th>
                                                <th width="10%" class="text-center">Ya</th>
                                                <th width="10%" class="text-center">Tidak</th>
                                                <th width="35%">Keterangan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>1</td>
                                                <td>Keterbatasan asesi terhadap persyaratan bahasa, literasi, numerasi.</td>
                                                <td class="text-center">
                                                    <input type="radio" name="modifikasi_1" value="ya" class="form-check-input" onchange="toggleKeterangan(1)">
                                                </td>
                                                <td class="text-center">
                                                    <input type="radio" name="modifikasi_1" value="tidak" class="form-check-input" onchange="toggleKeterangan(1)">
                                                </td>
                                                <td>
                                                    <div id="keterangan_1" style="display: none;">
                                                        <div class="form-check">
                                                            <input type="checkbox" name="modifikasi_data[1][]" value="dukungan_pembaca" class="form-check-input">
                                                            <label class="form-check-label">Memerlukan dukungan pembaca, penerjemah, pelayan, penulis untuk merekam jawaban asesi.</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input type="checkbox" name="modifikasi_data[1][]" value="asesmen_verbal" class="form-check-input">
                                                            <label class="form-check-label">Melakukan asesmen verbal (gunakan pertanyaan lisan/pertanyaan wawancara) dengan dilengkapi gambar diagram dan bentuk-bentuk visual.</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input type="checkbox" name="modifikasi_data[1][]" value="hasil_produksi" class="form-check-input">
                                                            <label class="form-check-label">Menggunakan Hasil produksi</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input type="checkbox" name="modifikasi_data[1][]" value="ceklis_observasi" class="form-check-input">
                                                            <label class="form-check-label">Menggunakan Ceklis observasi/demonstrasi.</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input type="checkbox" name="modifikasi_data[1][]" value="instruksi_terstruktur" class="form-check-input">
                                                            <label class="form-check-label">Menggunakan daftar instruksi terstruktur.</label>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>2</td>
                                                <td>Penyediaan dukungan pembaca, penerjemah, pelayan, penulis.</td>
                                                <td class="text-center">
                                                    <input type="radio" name="modifikasi_2" value="ya" class="form-check-input" onchange="toggleKeterangan(2)">
                                                </td>
                                                <td class="text-center">
                                                    <input type="radio" name="modifikasi_2" value="tidak" class="form-check-input" onchange="toggleKeterangan(2)">
                                                </td>
                                                <td>
                                                    <div id="keterangan_2" style="display: none;">
                                                        <div class="form-check">
                                                            <input type="checkbox" name="modifikasi_data[2][]" value="pertanyaan_lisan_visual" class="form-check-input">
                                                            <label class="form-check-label">Menggunakan pertanyaan lisan dengan dilengkapi gambar diagram dan bentuk-bentuk visual.</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input type="checkbox" name="modifikasi_data[2][]" value="pertanyaan_wawancara_visual" class="form-check-input">
                                                            <label class="form-check-label">Menggunakan pertanyaan wawancara dengan dilengkapi gambar diagram dan bentuk-bentuk visual.</label>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>3</td>
                                                <td>Penggunaan teknologi adaptif atau peralatan khusus. (Tidak dapat menggunakan teknologi adaptif (misal: mengoperasikan komputer dan printer, peralatan digital dsb).</td>
                                                <td class="text-center">
                                                    <input type="radio" name="modifikasi_3" value="ya" class="form-check-input" onchange="toggleKeterangan(3)">
                                                </td>
                                                <td class="text-center">
                                                    <input type="radio" name="modifikasi_3" value="tidak" class="form-check-input" onchange="toggleKeterangan(3)">
                                                </td>
                                                <td>
                                                    <div id="keterangan_3" style="display: none;">
                                                        <div class="form-check">
                                                            <input type="checkbox" name="modifikasi_data[3][]" value="ceklis_observasi_demo" class="form-check-input">
                                                            <label class="form-check-label">Ceklis observasi/demonstrasi Demonstrasi.</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input type="checkbox" name="modifikasi_data[3][]" value="pertanyaan_lisan" class="form-check-input">
                                                            <label class="form-check-label">Pertanyaan lisan</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input type="checkbox" name="modifikasi_data[3][]" value="pertanyaan_tertulis" class="form-check-input">
                                                            <label class="form-check-label">Pertanyaan tertulis.</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input type="checkbox" name="modifikasi_data[3][]" value="pertanyaan_wawancara" class="form-check-input">
                                                            <label class="form-check-label">Pertanyaan wawancara.</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input type="checkbox" name="modifikasi_data[3][]" value="daftar_instruksi_terstruktur" class="form-check-input">
                                                            <label class="form-check-label">Daftar instruksi terstruktur.</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input type="checkbox" name="modifikasi_data[3][]" value="ceklis_verifikasi_portofolio" class="form-check-input">
                                                            <label class="form-check-label">Ceklis verifikasi portofolio.</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input type="checkbox" name="modifikasi_data[3][]" value="dukungan_operator_komputer" class="form-check-input">
                                                            <label class="form-check-label">Menggunakan dukungan operator komputer.</label>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>4</td>
                                                <td>Pelaksanaan asesmen secara fleksibel karena alasan keletihan atau keperluan pengobatan.</td>
                                                <td class="text-center">
                                                    <input type="radio" name="modifikasi_4" value="ya" class="form-check-input" onchange="toggleKeterangan(4)">
                                                </td>
                                                <td class="text-center">
                                                    <input type="radio" name="modifikasi_4" value="tidak" class="form-check-input" onchange="toggleKeterangan(4)">
                                                </td>
                                                <td>
                                                    <div id="keterangan_4" style="display: none;">
                                                        <div class="form-check">
                                                            <input type="checkbox" name="modifikasi_data[4][]" value="juru_tulis" class="form-check-input">
                                                            <label class="form-check-label">Menggunakan juru tulis.</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input type="checkbox" name="modifikasi_data[4][]" value="kamera_perekam" class="form-check-input">
                                                            <label class="form-check-label">Menggunakan kamera perekam video/audio.</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input type="checkbox" name="modifikasi_data[4][]" value="waktu_lebih_panjang" class="form-check-input">
                                                            <label class="form-check-label">Memperbolehkan periode waktu yang lebih panjang untuk menyelesaikan tugas pekerjaan dalam asesmen.</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input type="checkbox" name="modifikasi_data[4][]" value="waktu_lebih_pendek" class="form-check-input">
                                                            <label class="form-check-label">Melakukan tugas pekerjaan dalam asesmen dengan waktu lebih pendek.</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input type="checkbox" name="modifikasi_data[4][]" value="instruksi_spesifik" class="form-check-input">
                                                            <label class="form-check-label">Menggunakan instruksi-instruksi spesifik pada proyek yang dapat dilakukan pada berbagai tingkatan.</label>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>5</td>
                                                <td>Penyediaan peralatan asesmen berupa braille, audio/video-tape.</td>
                                                <td class="text-center">
                                                    <input type="radio" name="modifikasi_5" value="ya" class="form-check-input" onchange="toggleKeterangan(5)">
                                                </td>
                                                <td class="text-center">
                                                    <input type="radio" name="modifikasi_5" value="tidak" class="form-check-input" onchange="toggleKeterangan(5)">
                                                </td>
                                                <td>
                                                    <div id="keterangan_5" style="display: none;">
                                                        <div class="form-check">
                                                            <input type="checkbox" name="modifikasi_data[5][]" value="pertanyaan_lisan_braille" class="form-check-input">
                                                            <label class="form-check-label">Menggunakan pertanyaan lisan.</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input type="checkbox" name="modifikasi_data[5][]" value="pertanyaan_wawancara_braille" class="form-check-input">
                                                            <label class="form-check-label">Menggunakan pertanyaan wawancara.</label>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>6</td>
                                                <td>Penyesuaian tempat fisik/lingkungan asesmen</td>
                                                <td class="text-center">
                                                    <input type="radio" name="modifikasi_6" value="ya" class="form-check-input" onchange="toggleKeterangan(6)">
                                                </td>
                                                <td class="text-center">
                                                    <input type="radio" name="modifikasi_6" value="tidak" class="form-check-input" onchange="toggleKeterangan(6)">
                                                </td>
                                                <td>
                                                    <div id="keterangan_6" style="display: none;">
                                                        <div class="form-check">
                                                            <input type="checkbox" name="modifikasi_data[6][]" value="pertanyaan_lisan_lingkungan" class="form-check-input">
                                                            <label class="form-check-label">Pertanyaan lisan.</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input type="checkbox" name="modifikasi_data[6][]" value="pertanyaan_tulis_lingkungan" class="form-check-input">
                                                            <label class="form-check-label">Pertanyaan tulis.</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input type="checkbox" name="modifikasi_data[6][]" value="pertanyaan_wawancara_lingkungan" class="form-check-input">
                                                            <label class="form-check-label">Pertanyaan wawancara.</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input type="checkbox" name="modifikasi_data[6][]" value="ceklis_verifikasi_portofolio_lingkungan" class="form-check-input">
                                                            <label class="form-check-label">Ceklis Verifikasi portofolio.</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input type="checkbox" name="modifikasi_data[6][]" value="ceklis_reviu_produk" class="form-check-input">
                                                            <label class="form-check-label">Ceklis reviu produk.</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input type="checkbox" name="modifikasi_data[6][]" value="daftar_instruksi_terstruktur_lingkungan" class="form-check-input">
                                                            <label class="form-check-label">Daftar instruksi terstruktur.</label>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>7</td>
                                                <td>Pertimbangan umur/usia lanjut/gender asesi. (Adanya perbedaan usia dengan asesor yang lebih muda).</td>
                                                <td class="text-center">
                                                    <input type="radio" name="modifikasi_7" value="ya" class="form-check-input" onchange="toggleKeterangan(7)">
                                                </td>
                                                <td class="text-center">
                                                    <input type="radio" name="modifikasi_7" value="tidak" class="form-check-input" onchange="toggleKeterangan(7)">
                                                </td>
                                                <td>
                                                    <div id="keterangan_7" style="display: none;">
                                                        <div class="form-check">
                                                            <input type="checkbox" name="modifikasi_data[7][]" value="studi_kasus_usia" class="form-check-input">
                                                            <label class="form-check-label">Menggunakan studi kasus/daftar instruksi terstruktur</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input type="checkbox" name="modifikasi_data[7][]" value="instrumen_huruf_normal" class="form-check-input">
                                                            <label class="form-check-label">Menggunakan instrumen asesmen dengan huruf normal jangan terlalu kecil.</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input type="checkbox" name="modifikasi_data[7][]" value="asesor_jenis_kelamin_sama" class="form-check-input">
                                                            <label class="form-check-label">Menggunakan asesor dengan jenis kelamin yang sama dengan asesi.</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input type="checkbox" name="modifikasi_data[7][]" value="instrumen_sama_jenis_kelamin" class="form-check-input">
                                                            <label class="form-check-label">Menggunakan instrumen asesmen yang sama walaupun berbeda jenis kelamin (tidak boleh memberi tanda tambahan pada instrumen asesmen yang digunakan dengan tujuan untuk membedakan jenis kelamin).</label>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>8</td>
                                                <td>Pertimbangan budaya/tradisi/agama.</td>
                                                <td class="text-center">
                                                    <input type="radio" name="modifikasi_8" value="ya" class="form-check-input" onchange="toggleKeterangan(8)">
                                                </td>
                                                <td class="text-center">
                                                    <input type="radio" name="modifikasi_8" value="tidak" class="form-check-input" onchange="toggleKeterangan(8)">
                                                </td>
                                                <td>
                                                    <div id="keterangan_8" style="display: none;">
                                                        <div class="form-check">
                                                            <input type="checkbox" name="modifikasi_data[8][]" value="studi_kasus_budaya" class="form-check-input">
                                                            <label class="form-check-label">Menggunakan studi kasus daftar instruksi terstruktur</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input type="checkbox" name="modifikasi_data[8][]" value="asesor_tanpa_pertimbangan_budaya" class="form-check-input">
                                                            <label class="form-check-label">Menggunakan asesor tanpa pertimbangan budaya/tradisi/agama.</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input type="checkbox" name="modifikasi_data[8][]" value="instrumen_sama_budaya" class="form-check-input">
                                                            <label class="form-check-label">Menggunakan instrumen asesmen yang sama walaupun berbeda budaya/tradisi/agama.</label>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Hasil Penyesuaian -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5><strong>Hasil Penyesuaian yang wajar dan beralasan disepakati menggunakan :</strong></h5>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <tr>
                                            <td width="20%"><strong>Acuan Pembanding Asesmen:</strong></td>
                                            <td>
                                                <span class="text-muted">(Tuliskan nama acuan pembanding)</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Metode Asesmen:</strong></td>
                                            <td>
                                                <span class="text-muted">(Tuliskan nama metode asesmen)</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Instrumen Asesmen:</strong></td>
                                            <td>
                                                <span class="text-muted">(Tuliskan nama formulir instrumen asesmen)</span>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Tanda Tangan dan Tanggal -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5><strong>Tanda Tangan dan Tanggal</strong></h5>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead class="table-dark">
                                            <tr>
                                                <th width="20%">Nama</th>
                                                <th width="40%">Tanda Tangan</th>
                                                <th width="40%">Tanggal</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="bg-light">
                                                    <strong>Asesor:</strong><br>
                                                    <span>{{ Auth::user()->name }}</span>
                                                </td>
                                                <td>
                                                    <div class="text-center">
                                                        <canvas id="asesorSignature" width="400" height="200" style="border: 1px solid #ddd; border-radius: 4px; background: white; {{ $pendaftaran->count() == 0 ? 'opacity: 0.5; pointer-events: none;' : '' }}"></canvas>
                                                        <div class="mt-2">
                                                            <button type="button" class="btn btn-sm btn-secondary" onclick="clearSignature()" {{ $pendaftaran->count() == 0 ? 'disabled' : '' }}>
                                                                <i class="fas fa-eraser me-1"></i>Hapus
                                                            </button>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="text-center">
                                                        <input type="date" class="form-control" name="tanggal_asesor" value="{{ date('Y-m-d') }}" readonly>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="bg-light">
                                                    <strong>Asesi:</strong><br>
                                                    <span id="nama-asesi-signature">-</span>
                                                </td>
                                                <td>
                                                    <div class="text-center">
                                                        <p class="text-muted mb-2">
                                                            <i class="fas fa-info-circle me-1"></i>
                                                            Tanda tangan asesi akan diisi oleh mahasiswa setelah ceklis penyesuaian dikirim
                                                        </p>
                                                        <div class="border rounded p-3 bg-light">
                                                            <i class="fas fa-signature fa-2x text-muted"></i>
                                                            <p class="mb-0 mt-2 text-muted">Tanda Tangan Asesi</p>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="text-center">
                                                        <p class="text-muted mb-2">
                                                            <i class="fas fa-info-circle me-1"></i>
                                                            Tanggal akan diisi otomatis saat mahasiswa menandatangani
                                                        </p>
                                                        <div class="border rounded p-3 bg-light">
                                                            <i class="fas fa-calendar fa-2x text-muted"></i>
                                                            <p class="mb-0 mt-2 text-muted">Tanggal Asesi</p>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Hidden inputs -->
                        <input type="hidden" name="asesor_signature" id="asesor_signature_input">

                        <!-- Buttons -->
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('asesor.penyesuaian.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left me-1"></i>Kembali
                                    </a>
                                    @if($pendaftaran->count() > 0)
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save me-1"></i>Simpan Ceklis Penyesuaian
                                        </button>
                                    @else
                                        <button type="button" class="btn btn-primary" disabled>
                                            <i class="fas fa-save me-1"></i>Simpan Ceklis Penyesuaian
                                        </button>
                                    @endif
                                </div>
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
    const pendaftaranSelect = document.getElementById('pendaftaran_id');
    const judulDisplay = document.getElementById('judul-display');
    const nomorDisplay = document.getElementById('nomor-display');
    const namaAsesiDisplay = document.getElementById('nama-asesi-display');
    const namaAsesiSignature = document.getElementById('nama-asesi-signature');

    // Signature canvas setup
    const canvas = document.getElementById('asesorSignature');
    const ctx = canvas.getContext('2d');
    let isDrawing = false;

    // Only setup signature if canvas is available and not disabled
    if (canvas && !canvas.style.pointerEvents.includes('none')) {
        // Load signature from personalization
        loadSignatureFromPersonalization();
    }

    pendaftaranSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        if (selectedOption.value) {
            judulDisplay.textContent = selectedOption.dataset.judul;
            nomorDisplay.textContent = selectedOption.dataset.nomor;
            namaAsesiDisplay.textContent = selectedOption.dataset.nama;
            namaAsesiSignature.textContent = selectedOption.dataset.nama;
        } else {
            judulDisplay.textContent = '-';
            nomorDisplay.textContent = '-';
            namaAsesiDisplay.textContent = '-';
            namaAsesiSignature.textContent = '-';
        }
    });

    // Signature drawing - only if canvas is not disabled
    if (canvas && !canvas.style.pointerEvents.includes('none')) {
        canvas.addEventListener('mousedown', startDrawing);
        canvas.addEventListener('mousemove', draw);
        canvas.addEventListener('mouseup', stopDrawing);
        canvas.addEventListener('mouseout', stopDrawing);

        // Touch events for mobile
        canvas.addEventListener('touchstart', handleTouch);
        canvas.addEventListener('touchmove', handleTouch);
        canvas.addEventListener('touchend', stopDrawing);
    }

    function startDrawing(e) {
        isDrawing = true;
        draw(e);
    }

    function draw(e) {
        if (!isDrawing) return;

        const rect = canvas.getBoundingClientRect();
        const x = e.clientX - rect.left;
        const y = e.clientY - rect.top;

        ctx.lineWidth = 2;
        ctx.lineCap = 'round';
        ctx.strokeStyle = '#000';

        ctx.lineTo(x, y);
        ctx.stroke();
        ctx.beginPath();
        ctx.moveTo(x, y);
    }

    function stopDrawing() {
        if (isDrawing) {
            isDrawing = false;
            ctx.beginPath();
            updateSignatureInput();
        }
    }

    function handleTouch(e) {
        e.preventDefault();
        const touch = e.touches[0];
        const mouseEvent = new MouseEvent(e.type === 'touchstart' ? 'mousedown' : 
                                         e.type === 'touchmove' ? 'mousemove' : 'mouseup', {
            clientX: touch.clientX,
            clientY: touch.clientY
        });
        canvas.dispatchEvent(mouseEvent);
    }

    function clearSignature() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        updateSignatureInput();
    }

    function updateSignatureInput() {
        const signatureData = canvas.toDataURL();
        document.getElementById('asesor_signature_input').value = signatureData;
    }

    // Load signature from personalization
    function loadSignatureFromPersonalization() {
        if (!canvas || canvas.style.pointerEvents.includes('none')) {
            return;
        }
        
        fetch('/asesor/personalization/get-signature')
            .then(response => response.json())
            .then(data => {
                if (data.success && data.signature) {
                    const img = new Image();
                    img.onload = function() {
                        ctx.clearRect(0, 0, canvas.width, canvas.height);
                        ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
                        updateSignatureInput();
                    };
                    img.src = data.signature;
                }
            })
            .catch(error => {
                console.log('No signature found in personalization');
            });
    }
});

// Function to toggle keterangan visibility
function toggleKeterangan(index) {
    const keterangan = document.getElementById(`keterangan_${index}`);
    const radioButtons = document.querySelectorAll(`input[name="modifikasi_${index}"]`);
    
    let showKeterangan = false;
    radioButtons.forEach(radio => {
        if (radio.checked && radio.value === 'ya') {
            showKeterangan = true;
        }
    });
    
    keterangan.style.display = showKeterangan ? 'block' : 'none';
    
    // Clear checkboxes when hidden
    if (!showKeterangan) {
        const checkboxes = keterangan.querySelectorAll('input[type="checkbox"]');
        checkboxes.forEach(checkbox => {
            checkbox.checked = false;
        });
    }
}
</script>
@endsection
