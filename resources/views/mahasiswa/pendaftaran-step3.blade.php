@extends('layouts.app')

@section('title', 'Pendaftaran LSP - Bagian 2 Data Sertifikasi')
@section('page-title', 'Pendaftaran LSP')

@section('content')
<div class="container-fluid">
    <!-- Progress Steps -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="step-item completed">
                            <div class="step-icon bg-success text-white"><i class="fas fa-check"></i></div>
                            <div class="step-label">Data Pengajuan</div>
                        </div>
                        <div class="step-line completed"></div>
                        <div class="step-item completed">
                            <div class="step-icon bg-success text-white"><i class="fas fa-check"></i></div>
                            <div class="step-label">Profil Peserta</div>
                        </div>
                        <div class="step-line completed"></div>
                        <div class="step-item active">
                            <div class="step-icon bg-primary text-white"><i class="fas fa-certificate"></i></div>
                            <div class="step-label">Data Sertifikasi</div>
                        </div>
                        <div class="step-line"></div>
                        <div class="step-item">
                            <div class="step-icon bg-secondary text-white"><i class="fas fa-clipboard-check"></i></div>
                            <div class="step-label">Asesmen Mandiri</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-10 mx-auto">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-certificate me-2"></i>Bagian 2 : Data Sertifikasi
                    </h4>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <p class="mb-0">
                            Pilih Judul dan Nomor Skema Sertifikasi yang anda ajukan berikut Daftar Unit Kompetensi sesuai kemasan pada skema sertifikasi untuk mendapatkan pengakuan sesuai dengan latar belakang pendidikan, pelatihan serta pengalaman kerja yang anda miliki.
                        </p>
                    </div>

                    <form action="{{ route('mahasiswa.pendaftaran.step3.store') }}" method="POST" id="sertifikasiForm">
                        @csrf

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label"><strong>Skema Sertifikasi</strong></label>
                                <div class="form-control-plaintext" id="skemaDisplay">
                                    Skema Sertifikasi <span class="strike">KKNI</span>/Okupasi/<span class="strike">Klaster</span>
                                </div>
                                <div class="form-text" id="nomorSkema"></div>
                                <input type="hidden" id="skema" name="skema" value="Okupasi">
                            </div>
                            <div class="col-md-5 mb-3">
                                <label class="form-label"><strong>Judul</strong></label>
                                <select class="form-select" id="judul" name="judul" required>
                                    <option value="">Pilih Judul</option>
                                    @foreach($judulOptions as $opt)
                                        <option value="{{ $opt }}">{{ $opt }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label"><strong>Tujuan Asesmen</strong></label>
                                <select class="form-select" id="tujuan_asesmen" name="tujuan_asesmen" required>
                                    <option value="">Pilih Tujuan</option>
                                    @foreach($tujuanOptions as $opt)
                                        <option value="{{ $opt }}">{{ $opt }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <hr>
                        <h5 class="mb-3">Daftar Unit Kompetensi sesuai kemasan</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered" id="tabelUnits">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width:60px">No.</th>
                                        <th style="width:220px">Kode Unit</th>
                                        <th>Judul Unit</th>
                                        <th style="width:260px">Standar Kompetensi Kerja</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>

                        <hr class="my-4">
                        <h4 class="mb-2">Bagian 3: Bukti Kelengkapan Pemohon</h4>
                        <h5 class="mb-3">3.1 Bukti Persyaratan Dasar Pemohon</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle" id="tabelBukti">
                                <thead class="table-light align-middle text-center">
                                    <tr>
                                        <th rowspan="2" style="width:60px">No.</th>
                                        <th rowspan="2">Bukti Persyaratan Dasar</th>
                                        <th colspan="2">Ada</th>
                                        <th rowspan="2" style="width:80px">Tidak Ada</th>
                                        <th rowspan="2" style="width:220px">Unggah Bukti (max 2MB)</th>
                                    </tr>
                                    <tr>
                                        <th style="width:160px">Memenuhi Syarat</th>
                                        <th style="width:200px">Tidak Memenuhi Syarat</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>

                        <hr class="my-4">
                        <h5 class="mb-3">3.2 Bukti Administratif</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle" id="tabelBuktiAdmin">
                                <thead class="table-light align-middle text-center">
                                    <tr>
                                        <th rowspan="2" style="width:60px">No.</th>
                                        <th rowspan="2">Bukti Administratif</th>
                                        <th colspan="2">Ada</th>
                                        <th rowspan="2" style="width:80px">Tidak Ada</th>
                                        <th rowspan="2" style="width:220px">Unggah Bukti (max 2MB)</th>
                                    </tr>
                                    <tr>
                                        <th style="width:160px">Memenuhi Syarat</th>
                                        <th style="width:200px">Tidak Memenuhi Syarat</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>

                        <hr class="my-4">
                        <h5 class="mb-3">Rekomendasi dan Persetujuan</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered" style="border-collapse: collapse; width: 100%; font-family: Arial, sans-serif;">
                                <tr>
                                    <!-- Kolom kiri: Rekomendasi -->
                                    <td rowspan="2" style="width: 65%; vertical-align: top; padding: 15px;">
                                        <strong>Rekomendasi (diisi oleh LSP):</strong><br>
                                        Berdasarkan ketentuan persyaratan dasar, maka pemohon:<br><br>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="rekomendasi" id="diterima" value="Diterima" disabled>
                                            <label class="form-check-label" for="diterima">
                                                <strong>Diterima</strong>
                                            </label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="rekomendasi" id="tidak_diterima" value="Tidak diterima" disabled>
                                            <label class="form-check-label" for="tidak_diterima">
                                                <strong>Tidak diterima</strong>
                                            </label>
                                        </div>
                                        <span class="text-muted small"></span> sebagai peserta sertifikasi
                                    </td>

                                    <!-- Kolom kanan atas: Pemohon -->
                                    <td style="width: 35%; vertical-align: top; padding: 15px;">
                                        <strong>Pemohon/ Kandidat :</strong><br><br>
                                        <div class="mb-2">
                                            <label class="form-label">Nama :</label>
                                            <input type="text" class="form-control form-control-sm" name="nama_pemohon" id="nama_pemohon" value="{{ auth()->user()->nama_lengkap }}" readonly>
                                        </div>
                                        <div class="mb-2">
                                            <label class="form-label">Tanda tangan :</label>
                                            <div class="signature-container">
                                                <canvas id="signatureCanvas" width="300" height="100" style="border: 1px solid #ccc; cursor: crosshair;"></canvas>
                                                <div class="mt-2">
                                                    <button type="button" class="btn btn-sm btn-outline-secondary" id="clearSignature">Hapus</button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-2">
                                            <label class="form-label">Tanggal :</label>
                                            <input type="text" class="form-control form-control-sm" name="tanggal_pemohon" id="tanggal_pemohon" readonly>
                                        </div>
                                    </td>
                                </tr>

                                <tr>
                                    <!-- Kolom kanan bawah: Admin LSP -->
                                    <td style="vertical-align: top; padding: 15px;">
                                        <strong>Admin LSP :</strong><br><br>
                                        <div class="mb-2">
                                            <label class="form-label">Nama :</label>
                                            <input type="text" class="form-control form-control-sm" name="nama_admin" readonly placeholder="Akan diisi oleh Admin">
                                        </div>
                                        <div class="mb-2">
                                            <label class="form-label">Tanda tangan/ Tanggal :</label>
                                            <div class="text-muted small">
                                                Akan diisi oleh Admin
                                            </div>
                                        </div>
                                    </td>
                                </tr>

                                <tr>
                                    <!-- Baris catatan (1 kolom penuh) -->
                                    <td colspan="2" style="vertical-align: top; padding: 15px;">
                                        <strong>Catatan :</strong><br><br>
                                        <textarea class="form-control" name="catatan_admin" rows="3" readonly placeholder="Akan diisi oleh Admin"></textarea>
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('mahasiswa.pendaftaran.step2') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Kembali
                            </a>
                            <button type="submit" class="btn btn-success" onclick="saveSignatureData()">
                                Lanjut ke Asesmen Mandiri <i class="fas fa-arrow-right ms-2"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.step-item{display:flex;flex-direction:column;align-items:center}
.step-icon{width:40px;height:40px;border-radius:50%;display:flex;align-items:center;justify-content:center;margin-bottom:8px}
.step-label{font-size:12px;color:#6c757d}
.step-item.active .step-label{color:#0d6efd;font-weight:600}
.step-item.completed .step-label{color:#198754;font-weight:600}
.step-line{width:100px;height:2px;background:#e9ecef;margin:0 10px}
.step-line.completed{background:#198754}
.strike{ text-decoration: line-through; }
</style>
@endsection

@section('scripts')
<script>
// Data unit kompetensi dari database
const unitsByJudul = @json($unitKompetensiData->groupBy('judul_sertifikasi')->map(function($items) {
    return $items->map(function($item) {
        return [$item->kode_unit, $item->judul_unit, $item->standar_kompetensi_kerja];
    })->toArray();
}));

// Skema display format

const nomorSkemaByJudul = {
  'PENGEMBANG WEB (WEB DEVELOPER)': 'Nomor: 621/UMDP/XI/Q/2022',
  'TEKNISI PERPAJAKAN (PAJAK PENGHASILAN ORANG PRIBADI)': 'Nomor: 612/UMDP/XI/Q/2022',
  'System Analyst': 'Nomor: 606/UMDP/XI/Q/2022',
  'Junior Web Programmer': 'Nomor: 617/UMDP/XI/Q/2022',
  'Database Administrator': 'Nomor: 603/UMDP/XI/Q/2022',
  'Analis Senior Hubungan Industrial': 'Nomor: 617/UMDP/XI/Q/2022'
};

const buktiByJudul = {
  'PENGEMBANG WEB (WEB DEVELOPER)': [
    'Transkrip nilai mahasiswa Universitas Multi Data Palembang: Untuk Program Studi S1 Sistem Informasi minimal semester 5 (lima) dan telah menyelesaikan dan lulus mata kuliah Analisis Sistem Informasi, Algoritma dan Struktur Data, Pengembagnan Aplikasi Web 1, Pengembangan Aplikasi Web 2, Rekayasa Perangkat Lunak, Perancangan Sistem Informasi, Pengujian Perangkat Lunak, Komputasi Aman, Sistem Terdistribusi dan Komputasi Paralel, Manajemen Proyek Sistem Informasi; atau Untuk Program Studi S1 Informatika minimal Semester 5 (lima) dan telah menyelesaikan dan lulus mata kuliah Basis Data II, Pemrograman Web II, Pemrograman Berorientasi Objek, Interaksi manusia dan Komputer, dan Rekayasa Perangkat Lunak.',
    'Surat Keterangan telah menyelesaikan Praktek Kerja Lapang atau magang di bidang Software Development'
  ],
  'TEKNISI PERPAJAKAN (PAJAK PENGHASILAN ORANG PRIBADI)': [
    'Transkrip Nilai minimal di Semester 5 (Lima) : lulus mata kuliah Pengantar Perpajakan, Perpajakan, Praktikum Pajak, dan Akuntansi Pajak',
    'Surat Keterangan telah menyelesaikan PKL atau magang di bidang Perpajakan'
  ],
  'System Analyst': [
    'Transkrip nilai mahasiswa Universitas Multi Data Palembang Program Studi S1 Sistem Informasi minimal semester 5 (lima) Universitas Multi Data Palembang yang telah menyelesaikan mata kuliah yaitu: Analisis Sistem Informasi, Rekayasa Perangkat Lunak, Perancangan Sistem Informasi, Perancangan UI/UX, Pengujian Perangkat Lunak.',
    'Surat Keterangan telah menyelesaikan Praktek Kerja Lapang atau magang di bidang Software Requirements Analysis and design'
  ],
  'Junior Web Programmer': [
    'Copy Transkrip Nilai Mahasiswa Program Studi D3 Manajemen Informatika Universitas Multi Data Palembang minimal semester 5 dan telah menyelesaikan (lulus) mata kuliah Algoritma dan Pemrograman, Pemrograman Berorientasi Objek, Rancangan Antarmuka dan Pengalaman Pengguna, Pemrograman Web 1 dan Pemrograman Web 2',
    'Surat Keterangan telah menyelesaikan Praktek Kerja Lapang atau magang di bidang Software Development'
  ],
  'Database Administrator': [
    'Salinan Transkrip Nilai Mahasiswa : Program Studi S1 Sistem Informasi minimal semester 5 (lima) Universitas Multi Data Palembang dan lulus mata kuliah Sistem basis Data dan Basis Data Terapan; atau Program Studi S1 Informatika minimal Semester 5 (lima) Universitas Multi Data Palembang yang telah lulus mata kuliah Basis Data II, Komputer dan Masyarakat, Rekayasa Perangkat Lunak, Pemrograman Berorientasi Objek dan Keamanan Sistem Komputer.',
    'Surat keterangan telah menyelesaikan Praktik Kerja Lapang atau magang di bidang pengelolaan basis data.'
  ],
  'Analis Senior Hubungan Industrial': [
    'Copy Transkrip Nilai Mahasiswa Program Studi S1 Manajemen Universitas Multi Data Palembang minimal semester 5 dan telah menyelesaikan (lulus) mata kuliah Pengantar Manajemen, Manajemen Sumber Daya Manusia, dan Sumber Daya Manusia Lanjutan.',
    'Surat Keterangan  telah menyelesaikan praktek kerja lapang atau magang di bidang Manajemen Sumber Daya Manusia.'
  ]
};

const buktiAdminByJudul = {
  'PENGEMBANG WEB (WEB DEVELOPER)': [
    'Salinan KTP',
    'Salinan KTM',
    'Pas foto terbaru 3x4 background merah 2 lembar'
  ],
  'TEKNISI PERPAJAKAN (PAJAK PENGHASILAN ORANG PRIBADI)': [
    'Fotocopy KTP dan KTM (masing-masing 1 lembar)',
    'Pas Foto 3x4 background merah sebanyak 2 lembar'
  ],
  'System Analyst': [
    'Fotocopy KTP',
    'Fotocopy KTM',
    'Pas foto terbaru 3x4 background merah 2 lembar'
  ],
  'Junior Web Programmer': [
    'Fotocopy KTP',
    'Fotocopy KTM',
    'Pas foto terbaru 3x4 background merah 2 lembar'
  ],
  'Database Administrator': [
    'Salinan KTP',
    'Salinan KTM',
    'Pas foto terbaru 3x4 berpakaian formal dengan latar merah 2 lembar'
  ],
  'Analis Senior Hubungan Industrial': [
    'Fotocopy KTP (1 lembar)',
    'Fotocopy KTM (1 lembar)',
    'Pas foto terbaru 3x4 background merah 2 lembar'
  ]
};

function renderUnits(judul){
  const tbody = document.querySelector('#tabelUnits tbody');
  tbody.innerHTML = '';
  const rows = unitsByJudul[judul] || [];
  rows.forEach((r,i)=>{
    const tr = document.createElement('tr');
    tr.innerHTML = `<td>${i+1}</td><td>${r[0]}</td><td>${r[1]}</td><td>${r[2]}</td>`;
    tbody.appendChild(tr);
  });
}

function renderBukti(judul){
  const tbody = document.querySelector('#tabelBukti tbody');
  if (!tbody) return;
  tbody.innerHTML = '';
  const items = buktiByJudul[judul] || [];
  items.forEach((text, idx) => {
    const i = idx + 1;
    const rowId = `bukti_${i}`;
    const tr = document.createElement('tr');
    tr.innerHTML = `
      <td>${i}.</td>
      <td>${text}</td>
      <td class="text-center">
        <input class="form-check-input msChk" type="checkbox" data-row="${rowId}" aria-label="Memenuhi Syarat">
      </td>
      <td class="text-center">
        <input class="form-check-input tmsChk" type="checkbox" data-row="${rowId}" aria-label="Tidak Memenuhi Syarat">
      </td>
      <td></td>
      <td>
        <input type="file" class="form-control form-control-sm uploadCtl" data-row="${rowId}" accept=".pdf,.jpg,.jpeg,.png" style="display:none;">
        <small class="text-muted">PDF/JPG/PNG, maks 2MB</small>
      </td>
    `;
    tbody.appendChild(tr);
  });

  // wire interactions: Memenuhi Syarat toggles upload; Tidak Memenuhi hides upload
  tbody.querySelectorAll('.msChk').forEach(chk => {
    chk.addEventListener('change', e => {
      const row = e.target.getAttribute('data-row');
      const upload = tbody.querySelector(`.uploadCtl[data-row="${row}"]`);
      const tms = tbody.querySelector(`.tmsChk[data-row="${row}"]`);
      if (e.target.checked){
        tms.checked = false;
        upload.style.display = 'block';
      } else {
        upload.style.display = 'none';
        upload.value='';
      }
    });
  });

  tbody.querySelectorAll('.tmsChk').forEach(chk => {
    chk.addEventListener('change', e => {
      const row = e.target.getAttribute('data-row');
      const ms = tbody.querySelector(`.msChk[data-row="${row}"]`);
      const upload = tbody.querySelector(`.uploadCtl[data-row="${row}"]`);
      if (e.target.checked){
        ms.checked = false;
        upload.style.display = 'none';
        upload.value='';
      }
    });
  });
}

function renderBuktiAdmin(judul){
  const tbody = document.querySelector('#tabelBuktiAdmin tbody');
  if (!tbody) return;
  tbody.innerHTML = '';
  const items = buktiAdminByJudul[judul] || [];
  items.forEach((text, idx) => {
    const i = idx + 1;
    const rowId = `bukti_admin_${i}`;
    const tr = document.createElement('tr');
    tr.innerHTML = `
      <td>${i}.</td>
      <td>${text}</td>
      <td class="text-center">
        <input class="form-check-input msChk" type="checkbox" data-row="${rowId}" aria-label="Memenuhi Syarat">
      </td>
      <td class="text-center">
        <input class="form-check-input tmsChk" type="checkbox" data-row="${rowId}" aria-label="Tidak Memenuhi Syarat">
      </td>
      <td></td>
      <td>
        <input type="file" class="form-control form-control-sm uploadCtl" data-row="${rowId}" accept=".pdf,.jpg,.jpeg,.png" style="display:none;">
        <small class="text-muted">PDF/JPG/PNG, maks 2MB</small>
      </td>
    `;
    tbody.appendChild(tr);
  });

  // wire interactions: Memenuhi Syarat toggles upload; Tidak Memenuhi hides upload
  tbody.querySelectorAll('.msChk').forEach(chk => {
    chk.addEventListener('change', e => {
      const row = e.target.getAttribute('data-row');
      const upload = tbody.querySelector(`.uploadCtl[data-row="${row}"]`);
      const tms = tbody.querySelector(`.tmsChk[data-row="${row}"]`);
      if (e.target.checked){
        tms.checked = false;
        upload.style.display = 'block';
      } else {
        upload.style.display = 'none';
        upload.value='';
      }
    });
  });

  tbody.querySelectorAll('.tmsChk').forEach(chk => {
    chk.addEventListener('change', e => {
      const row = e.target.getAttribute('data-row');
      const ms = tbody.querySelector(`.msChk[data-row="${row}"]`);
      const upload = tbody.querySelector(`.uploadCtl[data-row="${row}"]`);
      if (e.target.checked){
        ms.checked = false;
        upload.style.display = 'none';
        upload.value='';
      }
    });
  });
}

// Signature Canvas functionality
let isDrawing = false;
let canvas, ctx;

function initSignatureCanvas() {
  canvas = document.getElementById('signatureCanvas');
  if (!canvas) return;
  
  ctx = canvas.getContext('2d');
  ctx.strokeStyle = '#000';
  ctx.lineWidth = 2;
  ctx.lineCap = 'round';

  // Mouse events
  canvas.addEventListener('mousedown', startDrawing);
  canvas.addEventListener('mousemove', draw);
  canvas.addEventListener('mouseup', stopDrawing);
  canvas.addEventListener('mouseout', stopDrawing);

  // Touch events for mobile
  canvas.addEventListener('touchstart', handleTouch);
  canvas.addEventListener('touchmove', handleTouch);
  canvas.addEventListener('touchend', stopDrawing);

  // Clear button
  document.getElementById('clearSignature').addEventListener('click', clearSignature);
}

function startDrawing(e) {
  isDrawing = true;
  const rect = canvas.getBoundingClientRect();
  const x = e.clientX - rect.left;
  const y = e.clientY - rect.top;
  ctx.beginPath();
  ctx.moveTo(x, y);
}

function draw(e) {
  if (!isDrawing) return;
  const rect = canvas.getBoundingClientRect();
  const x = e.clientX - rect.left;
  const y = e.clientY - rect.top;
  ctx.lineTo(x, y);
  ctx.stroke();
}

function stopDrawing() {
  isDrawing = false;
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
}

function getSignatureData() {
  return canvas.toDataURL('image/png');
}

function setCurrentDate() {
  const now = new Date();
  const options = { 
    year: 'numeric', 
    month: 'long', 
    day: 'numeric' 
  };
  const dateString = now.toLocaleDateString('id-ID', options);
  document.getElementById('tanggal_pemohon').value = dateString;
}

function setPemohonName() {
  // Name is now auto-filled from auth()->user()->nama_lengkap in the HTML
  // No need to set it via JavaScript anymore
}

function saveSignatureData() {
  // Save signature data to hidden input before form submission
  const signatureData = getSignatureData();
  let hiddenInput = document.getElementById('signature_data');
  if (!hiddenInput) {
    hiddenInput = document.createElement('input');
    hiddenInput.type = 'hidden';
    hiddenInput.name = 'signature_data';
    hiddenInput.id = 'signature_data';
    document.getElementById('sertifikasiForm').appendChild(hiddenInput);
  }
  hiddenInput.value = signatureData;
}

function updateNomorSkema(judul) {
  const nomorSkemaElement = document.getElementById('nomorSkema');
  if (nomorSkemaElement) {
    const nomor = nomorSkemaByJudul[judul] || '';
    nomorSkemaElement.textContent = nomor;
  }
}

document.addEventListener('DOMContentLoaded', () => {
  const judul = document.getElementById('judul');

  judul.addEventListener('change', () => {
    renderUnits(judul.value);
    renderBukti(judul.value);
    renderBuktiAdmin(judul.value);
    updateNomorSkema(judul.value);
  });

  // Initialize signature canvas and set current date
  initSignatureCanvas();
  setCurrentDate();
  setPemohonName();

  // initial render if judul is already selected
  if (judul.value){
    renderUnits(judul.value);
    renderBukti(judul.value);
    renderBuktiAdmin(judul.value);
    updateNomorSkema(judul.value);
  }
});
</script>
@endsection

