@extends('layouts.app')

@section('title', 'Pendaftaran Step 4 - Asesmen Mandiri')
@section('page-title', 'Pendaftaran Step 4 - Asesmen Mandiri')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><strong>FR.APL.02. ASESMEN MANDIRI</strong></h4>
                </div>
                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Informasi Skema Sertifikasi -->
                    <div class="mb-4">
                        <table border="1" cellspacing="0" cellpadding="5" style="border-collapse: collapse; width: 100%;">
                            <tr>
                                <td style="width: 30%;">Skema Sertifikasi<br><span class="strike">KKNI</span>/Okupasi/<span class="strike">Klaster</span></td>
                                <td style="width: 10%;">Judul</td>
                                <td style="width: 60%;">: {{ $selectedJudul }}</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>Nomor</td>
                                <td>: {{ $nomorSkema }}</td>
                            </tr>
                        </table>
                    </div>

                    <!-- Panduan Asesmen Mandiri -->
                    <div class="mb-4">
                        <table border="1" cellspacing="0" cellpadding="5" style="border-collapse: collapse; width: 100%;">
                            <tr>
                                <td><strong>PANDUAN ASESMEN MANDIRI</strong></td>
                            </tr>
                            <tr>
                                <td>
                                    <strong>Instruksi:</strong><br>
                                    • Baca setiap pertanyaan di kolom sebelah kiri.<br>
                                    • Beri tanda centang (√) pada kotak jika Anda yakin dapat melakukan tugas yang dijelaskan.<br>
                                    • Isi kolom di sebelah kanan dengan menuliskan bukti yang relevan untuk menunjukkan bahwa Anda melakukan pekerjaan.
                                </td>
                            </tr>
                        </table>
                    </div>

                    <!-- Form Asesmen Mandiri -->
                    <form action="{{ route('mahasiswa.pendaftaran.step4.store') }}" method="POST" id="asesmenForm">
                        @csrf
                        
                        <!-- Unit Kompetensi 1 -->
                        <div class="mb-4" id="unitKompetensiContainer">
                            <!-- Content akan diisi oleh JavaScript -->
                        </div>

                        <!-- Tombol Submit -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('mahasiswa.pendaftaran.step3') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Kembali ke Step 3
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save me-2"></i>Simpan Asesmen Mandiri
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.strike {
    text-decoration: line-through;
}

.unit-kompetensi-table {
    border-collapse: collapse;
    width: 100%;
    margin-bottom: 20px;
}

.unit-kompetensi-table th,
.unit-kompetensi-table td {
    border: 1px solid #000;
    padding: 8px;
    text-align: left;
    vertical-align: top;
}

.unit-kompetensi-table th {
    background-color: #f8f9fa;
    font-weight: bold;
}

.unit-kompetensi-table .unit-header {
    background-color: #e9ecef;
    font-weight: bold;
}

.unit-kompetensi-table .elemen-header {
    background-color: #f8f9fa;
    font-weight: bold;
}

.checkbox-container {
    text-align: center;
}

.bukti-container {
    min-height: 40px;
}

.bukti-file {
    background-color: #f8f9fa;
    border: 1px solid #dee2e6;
    padding: 5px;
    margin: 2px 0;
    border-radius: 3px;
    font-size: 0.9em;
}
</style>
@endsection

@section('scripts')
<script>
// Data dari controller
const unitKompetensiData = @json($unitKompetensiData);
const elemenData = @json($elemenData);
const kriteriaData = @json($kriteriaData);
const buktiData = @json($buktiData);

// Fungsi untuk membuat tabel unit kompetensi
function renderUnitKompetensi() {
    const container = document.getElementById('unitKompetensiContainer');
    let html = '';

    // Group kriteria by unit kompetensi
    const unitsByKode = {};
    kriteriaData.forEach(kriteria => {
        if (!unitsByKode[kriteria.kode_unit]) {
            unitsByKode[kriteria.kode_unit] = {
                kode_unit: kriteria.kode_unit,
                judul_unit: unitKompetensiData.find(u => u.kode_unit === kriteria.kode_unit)?.judul_unit || '',
                elemen: {}
            };
        }
        
        if (!unitsByKode[kriteria.kode_unit].elemen[kriteria.nomor_elemen]) {
            const elemen = elemenData.find(e => e.nomor_elemen === kriteria.nomor_elemen && e.kode_unit === kriteria.kode_unit);
            unitsByKode[kriteria.kode_unit].elemen[kriteria.nomor_elemen] = {
                nomor_elemen: kriteria.nomor_elemen,
                nama_elemen: elemen?.nama_elemen || '',
                kriteria: []
            };
        }
        
        unitsByKode[kriteria.kode_unit].elemen[kriteria.nomor_elemen].kriteria.push(kriteria);
    });

    // Render setiap unit kompetensi
    let unitIndex = 0;
    
    unitKompetensiData.forEach((unitRow) => {
        const unit = unitsByKode[unitRow.kode_unit];
        if (!unit) {
            return; 
        }
        unitIndex += 1;
        html += `
            <div class="mb-4">
                <table border="1" cellspacing="0" cellpadding="5" style="border-collapse: collapse; width: 100%; margin-bottom: 20px;">
                    <tr>
                        <td rowspan="2" style="width: 25%; font-weight: bold;">Unit Kompetensi ${unitIndex}</td>
                        <td style="width: 10%;">Kode</td>
                        <td style="width: 3%;">:</td>
                        <td style="width: 62%;">${unit.kode_unit}</td>
                    </tr>
                    <tr>
                        <td>Judul</td>
                        <td>:</td>
                        <td>${unit.judul_unit}</td>
                    </tr>
                </table>
                
                <table class="unit-kompetensi-table">
                    <thead>
                        <tr>
                            <th style="width: 50%;">Dapatkah saya ?</th>
                            <th style="width: 10%;">K</th>
                            <th style="width: 10%;">BK</th>
                            <th style="width: 30%;">Bukti yang relevan</th>
                        </tr>
                    </thead>
                    <tbody>
        `;
 
        // Render setiap elemen (sorted by nomor_elemen)
        const elemenList = Object.values(unit.elemen).sort((a, b) => {
            // numeric aware compare, fallback to string
            const ax = a.nomor_elemen?.toString() || '';
            const bx = b.nomor_elemen?.toString() || '';
            return ax.localeCompare(bx, 'id', { numeric: true, sensitivity: 'base' });
        });
        elemenList.forEach((elemen, elemenIndex) => {
            html += `
                <tr>
                    <td class="elemen-header" colspan="4">
                        ${elemenIndex + 1} Elemen : ${elemen.nama_elemen}
                    </td>
                </tr>
            `;
 
            // Render setiap kriteria unjuk kerja (sorted by nomor_kriteria)
            const kriteriaList = (elemen.kriteria || []).slice().sort((a, b) => {
                const ax = a.nomor_kriteria?.toString() || '';
                const bx = b.nomor_kriteria?.toString() || '';
                return ax.localeCompare(bx, 'id', { numeric: true, sensitivity: 'base' });
            });
            kriteriaList.forEach((kriteria, kriteriaIndex) => {
                const buktiFiles = buktiData.filter(b => b.kode_unit === kriteria.kode_unit);
                const buktiHtml = buktiFiles.map(bukti => 
                    `<div class="bukti-file">${bukti.nama_file}</div>`
                ).join('');
 
                html += `
                    <tr>
                        <td style="padding-left: 20px;">
                            ${kriteria.nomor_kriteria} ${kriteria.deskripsi_kriteria}
                        </td>
                        <td class="checkbox-container">
                            <input type="checkbox" 
                                   name="kriteria[${kriteria.id}][kompeten]" 
                                   value="1" 
                                   class="form-check-input kompeten-checkbox"
                                   data-kriteria="${kriteria.id}">
                        </td>
                        <td class="checkbox-container">
                            <input type="checkbox" 
                                   name="kriteria[${kriteria.id}][belum_kompeten]" 
                                   value="1" 
                                   class="form-check-input belum-kompeten-checkbox"
                                   data-kriteria="${kriteria.id}">
                        </td>
                        <td class="bukti-container">
                            ${buktiHtml}
                        </td>
                    </tr>
                `;
            });
        });
 
        html += `
                    </tbody>
                </table>
            </div>
        `;
    });
 
    container.innerHTML = html;

    // Add event listeners untuk checkbox mutual exclusive
    document.querySelectorAll('.kompeten-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            if (this.checked) {
                const kriteriaId = this.dataset.kriteria;
                const belumKompetenCheckbox = document.querySelector(`input[name="kriteria[${kriteriaId}][belum_kompeten]"]`);
                if (belumKompetenCheckbox) {
                    belumKompetenCheckbox.checked = false;
                }
            }
        });
    });

    document.querySelectorAll('.belum-kompeten-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            if (this.checked) {
                const kriteriaId = this.dataset.kriteria;
                const kompetenCheckbox = document.querySelector(`input[name="kriteria[${kriteriaId}][kompeten]"]`);
                if (kompetenCheckbox) {
                    kompetenCheckbox.checked = false;
                }
            }
        });
    });
}

// Initialize when page loads
document.addEventListener('DOMContentLoaded', function() {
    renderUnitKompetensi();
});
</script>
@endsection
