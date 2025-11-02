@extends('layouts.app')

@section('title', 'Detail Penyesuaian Checklist')
@section('page-title', 'Detail Penyesuaian Checklist')

@section('content')
<div class="container-fluid">
    <!-- Main Content -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">FR.AK.07 CEKLIS PENYESUAIAN YANG WAJAR DAN BERALASAN</h4>
                    <a href="{{ route('mahasiswa.penyesuaian-checklist') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i>Kembali
                    </a>
                </div>
                <div class="card-body">
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
                                        <td>{{ $penyesuaianChecklist->judul }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Nomor</strong></td>
                                        <td>{{ $penyesuaianChecklist->nomor_skema }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>TUK</strong></td>
                                        <td>
                                            @switch($penyesuaianChecklist->tuk)
                                                @case('sewaktu')
                                                    ☑ Sewaktu ☐ Tempat Kerja ☐ Mandiri
                                                    @break
                                                @case('tempat_kerja')
                                                    ☐ Sewaktu ☑ Tempat Kerja ☐ Mandiri
                                                    @break
                                                @case('mandiri')
                                                    ☐ Sewaktu ☐ Tempat Kerja ☑ Mandiri
                                                    @break
                                            @endswitch
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Nama Asesor</strong></td>
                                        <td>{{ $penyesuaianChecklist->nama_asesor }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Nama Asesi</strong></td>
                                        <td>{{ $penyesuaianChecklist->nama_asesi }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Tanggal</strong></td>
                                        <td>{{ $penyesuaianChecklist->tanggal->format('d F Y') }}</td>
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
                    @if($penyesuaianChecklist->potensi_asesi)
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5><strong>Potensi Asesi</strong></h5>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead class="table-dark">
                                            <tr>
                                                <th width="80%">Potensi Asesi</th>
                                                <th width="20%" class="text-center">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Hasil pelatihan dan / atau pendidikan, dimana Kurikulum dan fasilitas praktek mampu telusur terhadap standar kompetensi</td>
                                                <td class="text-center">
                                                    @if(in_array('pelatihan_telusur', $penyesuaianChecklist->potensi_asesi))
                                                        <i class="fas fa-check text-success"></i>
                                                    @else
                                                        <i class="fas fa-times text-muted"></i>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Hasil pelatihan dan / atau pendidikan, dimana kurikulum belum berbasis kompetensi.</td>
                                                <td class="text-center">
                                                    @if(in_array('pelatihan_belum_kompetensi', $penyesuaianChecklist->potensi_asesi))
                                                        <i class="fas fa-check text-success"></i>
                                                    @else
                                                        <i class="fas fa-times text-muted"></i>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Pekerja berpengalaman, dimana berasal dari industri/tempat kerja yang dalam operasionalnya mampu telusur dengan standar kompetensi</td>
                                                <td class="text-center">
                                                    @if(in_array('pekerja_telusur', $penyesuaianChecklist->potensi_asesi))
                                                        <i class="fas fa-check text-success"></i>
                                                    @else
                                                        <i class="fas fa-times text-muted"></i>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Pekerja berpengalaman, dimana berasal dari industri/tempat kerja yang dalam operasionalnya belum berbasis kompetensi.</td>
                                                <td class="text-center">
                                                    @if(in_array('pekerja_belum_kompetensi', $penyesuaianChecklist->potensi_asesi))
                                                        <i class="fas fa-check text-success"></i>
                                                    @else
                                                        <i class="fas fa-times text-muted"></i>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Pelatihan / belajar mandiri atau otodidak.</td>
                                                <td class="text-center">
                                                    @if(in_array('mandiri_otodidak', $penyesuaianChecklist->potensi_asesi))
                                                        <i class="fas fa-check text-success"></i>
                                                    @else
                                                        <i class="fas fa-times text-muted"></i>
                                                    @endif
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Modifikasi dan Kontekstualisasi -->
                    @if($penyesuaianChecklist->modifikasi_data)
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
                                            @for($i = 1; $i <= 8; $i++)
                                                <tr>
                                                    <td>{{ $i }}</td>
                                                    <td>
                                                        @switch($i)
                                                            @case(1)
                                                                Keterbatasan asesi terhadap persyaratan bahasa, literasi, numerasi.
                                                                @break
                                                            @case(2)
                                                                Penyediaan dukungan pembaca, penerjemah, pelayan, penulis.
                                                                @break
                                                            @case(3)
                                                                Penggunaan teknologi adaptif atau peralatan khusus. (Tidak dapat menggunakan teknologi adaptif (misal: mengoperasikan komputer dan printer, peralatan digital dsb).
                                                                @break
                                                            @case(4)
                                                                Pelaksanaan asesmen secara fleksibel karena alasan keletihan atau keperluan pengobatan.
                                                                @break
                                                            @case(5)
                                                                Penyediaan peralatan asesmen berupa braille, audio/video-tape.
                                                                @break
                                                            @case(6)
                                                                Penyesuaian tempat fisik/lingkungan asesmen
                                                                @break
                                                            @case(7)
                                                                Pertimbangan umur/usia lanjut/gender asesi. (Adanya perbedaan usia dengan asesor yang lebih muda).
                                                                @break
                                                            @case(8)
                                                                Pertimbangan budaya/tradisi/agama.
                                                                @break
                                                        @endswitch
                                                    </td>
                                                    <td class="text-center">
                                                        @if(isset($penyesuaianChecklist->modifikasi_data[$i]) && is_array($penyesuaianChecklist->modifikasi_data[$i]))
                                                            <i class="fas fa-check text-success"></i>
                                                        @else
                                                            <i class="fas fa-times text-muted"></i>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        @if(!isset($penyesuaianChecklist->modifikasi_data[$i]) || !is_array($penyesuaianChecklist->modifikasi_data[$i]))
                                                            <i class="fas fa-check text-success"></i>
                                                        @else
                                                            <i class="fas fa-times text-muted"></i>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if(isset($penyesuaianChecklist->modifikasi_data[$i]) && is_array($penyesuaianChecklist->modifikasi_data[$i]))
                                                            <ul class="list-unstyled mb-0">
                                                                @foreach($penyesuaianChecklist->modifikasi_data[$i] as $keterangan)
                                                                    @php
                                                                        $keteranganText = $keterangan;
                                                                        // Mapping untuk mengkonversi key menjadi teks yang sesuai
                                                                        $keteranganMapping = [
                                                                            'dukungan_pembaca' => 'Memerlukan dukungan pembaca, penerjemah, pelayan, penulis untuk merekam jawaban asesi.',
                                                                            'asesmen_verbal' => 'Melakukan asesmen verbal (gunakan pertanyaan lisan/pertanyaan wawancara) dengan dilengkapi gambar diagram dan bentuk-bentuk visual.',
                                                                            'hasil_produksi' => 'Menggunakan Hasil produksi',
                                                                            'ceklis_observasi' => 'Menggunakan Ceklis observasi/demonstrasi.',
                                                                            'instruksi_terstruktur' => 'Menggunakan daftar instruksi terstruktur.',
                                                                            'pertanyaan_lisan_visual' => 'Menggunakan pertanyaan lisan dengan dilengkapi gambar diagram dan bentuk-bentuk visual.',
                                                                            'pertanyaan_wawancara_visual' => 'Menggunakan pertanyaan wawancara dengan dilengkapi gambar diagram dan bentuk-bentuk visual.',
                                                                            'ceklis_observasi_demo' => 'Ceklis observasi/demonstrasi Demonstrasi.',
                                                                            'pertanyaan_lisan' => 'Pertanyaan lisan',
                                                                            'pertanyaan_tertulis' => 'Pertanyaan tertulis.',
                                                                            'pertanyaan_wawancara' => 'Pertanyaan wawancara.',
                                                                            'daftar_instruksi_terstruktur' => 'Daftar instruksi terstruktur.',
                                                                            'ceklis_verifikasi_portofolio' => 'Ceklis verifikasi portofolio.',
                                                                            'dukungan_operator_komputer' => 'Menggunakan dukungan operator komputer.',
                                                                            'juru_tulis' => 'Menggunakan juru tulis.',
                                                                            'kamera_perekam' => 'Menggunakan kamera perekam video/audio.',
                                                                            'waktu_lebih_panjang' => 'Memperbolehkan periode waktu yang lebih panjang untuk menyelesaikan tugas pekerjaan dalam asesmen.',
                                                                            'waktu_lebih_pendek' => 'Melakukan tugas pekerjaan dalam asesmen dengan waktu lebih pendek.',
                                                                            'instruksi_spesifik' => 'Menggunakan instruksi-instruksi spesifik pada proyek yang dapat dilakukan pada berbagai tingkatan.',
                                                                            'pertanyaan_lisan_braille' => 'Menggunakan pertanyaan lisan.',
                                                                            'pertanyaan_wawancara_braille' => 'Menggunakan pertanyaan wawancara.',
                                                                            'pertanyaan_lisan_lingkungan' => 'Pertanyaan lisan.',
                                                                            'pertanyaan_tulis_lingkungan' => 'Pertanyaan tulis.',
                                                                            'pertanyaan_wawancara_lingkungan' => 'Pertanyaan wawancara.',
                                                                            'ceklis_verifikasi_portofolio_lingkungan' => 'Ceklis Verifikasi portofolio.',
                                                                            'ceklis_reviu_produk' => 'Ceklis reviu produk.',
                                                                            'daftar_instruksi_terstruktur_lingkungan' => 'Daftar instruksi terstruktur.',
                                                                            'studi_kasus_usia' => 'Menggunakan studi kasus/daftar instruksi terstruktur',
                                                                            'instrumen_huruf_normal' => 'Menggunakan instrumen asesmen dengan huruf normal jangan terlalu kecil.',
                                                                            'asesor_jenis_kelamin_sama' => 'Menggunakan asesor dengan jenis kelamin yang sama dengan asesi.',
                                                                            'instrumen_sama_jenis_kelamin' => 'Menggunakan instrumen asesmen yang sama walaupun berbeda jenis kelamin (tidak boleh memberi tanda tambahan pada instrumen asesmen yang digunakan dengan tujuan untuk membedakan jenis kelamin).',
                                                                            'studi_kasus_budaya' => 'Menggunakan studi kasus daftar instruksi terstruktur',
                                                                            'asesor_tanpa_pertimbangan_budaya' => 'Menggunakan asesor tanpa pertimbangan budaya/tradisi/agama.',
                                                                            'instrumen_sama_budaya' => 'Menggunakan instrumen asesmen yang sama walaupun berbeda budaya/tradisi/agama.'
                                                                        ];
                                                                        
                                                                        if (isset($keteranganMapping[$keterangan])) {
                                                                            $keteranganText = $keteranganMapping[$keterangan];
                                                                        }
                                                                    @endphp
                                                                    <li><i class="fas fa-check text-success me-1"></i>{{ $keteranganText }}</li>
                                                                @endforeach
                                                            </ul>
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endfor
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Hasil Penyesuaian -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5><strong>Hasil Penyesuaian yang wajar dan beralasan disepakati menggunakan :</strong></h5>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <tr>
                                        <td width="20%"><strong>Acuan Pembanding Asesmen:</strong></td>
                                        <td><span class="text-muted">( Tuliskan nama acuan pembanding )</span></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Metode Asesmen:</strong></td>
                                        <td><span class="text-muted">( Tuliskan nama metode asesmen )</span></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Instrumen Asesmen:</strong></td>
                                        <td><span class="text-muted">( Tuliskan nama formulir instrumen asesmen )</span></td>
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
                                                <span>{{ $penyesuaianChecklist->nama_asesor }}</span>
                                            </td>
                                            <td>
                                                <div class="text-center">
                                                    @if($penyesuaianChecklist->asesor_signature)
                                                        <img src="{{ $penyesuaianChecklist->asesor_signature }}" alt="Tanda Tangan Asesor" style="max-width: 100%; height: auto; border: 1px solid #ddd; border-radius: 4px;">
                                                    @else
                                                        <div class="border rounded p-3 bg-light">
                                                            <i class="fas fa-signature fa-2x text-muted"></i>
                                                            <p class="mb-0 mt-2 text-muted">Belum ada tanda tangan</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <div class="text-center">
                                                    @if($penyesuaianChecklist->tanggal_asesor)
                                                        <p class="mb-0">{{ $penyesuaianChecklist->tanggal_asesor->format('d F Y') }}</p>
                                                    @else
                                                        <p class="mb-0 text-muted">-</p>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="bg-light">
                                                <strong>Asesi:</strong><br>
                                                <span>{{ $penyesuaianChecklist->nama_asesi }}</span>
                                            </td>
                                            <td>
                                                <div class="text-center">
                                                    @if($penyesuaianChecklist->mahasiswa_signature)
                                                        <img src="{{ $penyesuaianChecklist->mahasiswa_signature }}" alt="Tanda Tangan Asesi" style="max-width: 100%; height: auto; border: 1px solid #ddd; border-radius: 4px;">
                                                    @else
                                                        <div class="border rounded p-3 bg-light">
                                                            <i class="fas fa-signature fa-2x text-muted"></i>
                                                            <p class="mb-0 mt-2 text-muted">Belum ditandatangani</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <div class="text-center">
                                                    @if($penyesuaianChecklist->tanggal_mahasiswa)
                                                        <p class="mb-0">{{ $penyesuaianChecklist->tanggal_mahasiswa->format('d F Y') }}</p>
                                                    @else
                                                        <p class="mb-0 text-muted">-</p>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Form Tanda Tangan Mahasiswa -->
                    @if(!$penyesuaianChecklist->mahasiswa_signature)
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="card border-warning">
                                    <div class="card-header bg-warning text-dark">
                                        <h5 class="mb-0">
                                            <i class="fas fa-signature me-2"></i>Tanda Tangan Penyesuaian Checklist
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <form method="POST" action="{{ route('mahasiswa.penyesuaian-checklist.signature', $penyesuaianChecklist->id) }}" id="signatureForm">
                                            @csrf
                                            
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label class="form-label"><strong>Canvas Tanda Tangan</strong></label>
                                                    <div class="text-center">
                                                        <canvas id="mahasiswaSignature" width="400" height="200" style="border: 1px solid #ddd; border-radius: 4px; background: white; cursor: crosshair;"></canvas>
                                                        <div class="mt-2">
                                                            <button type="button" class="btn btn-sm btn-secondary" onclick="clearSignature()">
                                                                <i class="fas fa-eraser me-1"></i>Hapus
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label"><strong>Tanggal Tanda Tangan</strong></label>
                                                    <input type="date" class="form-control" name="tanggal_mahasiswa" value="{{ date('Y-m-d') }}" required>
                                                    
                                                    <div class="mt-3">
                                                        <div class="alert alert-info">
                                                            <i class="fas fa-info-circle me-2"></i>
                                                            <strong>Petunjuk:</strong>
                                                            <ul class="mb-0 mt-2">
                                                                <li>Gambar tanda tangan Anda pada canvas di sebelah kiri</li>
                                                                <li>Pastikan tanggal sudah benar</li>
                                                                <li>Klik "Konfirmasi Tanda Tangan" untuk konfirmasi</li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <!-- Hidden input for signature -->
                                            <input type="hidden" name="mahasiswa_signature" id="mahasiswa_signature_input">
                                            
                                            <div class="row mt-3">
                                                <div class="col-12">
                                                    <button type="submit" class="btn btn-success">
                                                        <i class="fas fa-check me-1"></i>Konfirmasi Tanda Tangan
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="alert alert-success">
                                    <i class="fas fa-check-circle me-2"></i>
                                    <strong>Penyesuaian checklist telah ditandatangani pada {{ $penyesuaianChecklist->tanggal_mahasiswa->format('d F Y') }}</strong>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@if(!$penyesuaianChecklist->mahasiswa_signature)
<script>
document.addEventListener('DOMContentLoaded', function() {
    const canvas = document.getElementById('mahasiswaSignature');
    const ctx = canvas.getContext('2d');
    let isDrawing = false;

    // Signature drawing
    canvas.addEventListener('mousedown', startDrawing);
    canvas.addEventListener('mousemove', draw);
    canvas.addEventListener('mouseup', stopDrawing);
    canvas.addEventListener('mouseout', stopDrawing);

    // Touch events for mobile
    canvas.addEventListener('touchstart', handleTouch);
    canvas.addEventListener('touchmove', handleTouch);
    canvas.addEventListener('touchend', stopDrawing);

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
        document.getElementById('mahasiswa_signature_input').value = signatureData;
    }

    // Form submission
    document.getElementById('signatureForm').addEventListener('submit', function(e) {
        const signatureData = document.getElementById('mahasiswa_signature_input').value;
        
        if (!signatureData || signatureData === 'data:,') {
            e.preventDefault();
            alert('Silakan gambar tanda tangan Anda terlebih dahulu.');
            return false;
        }
        
        if (!confirm('Apakah Anda yakin ingin menandatangani penyesuaian checklist ini?')) {
            e.preventDefault();
            return false;
        }
    });
});
</script>
@endif
@endsection
