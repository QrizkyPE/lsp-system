<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Ceklis Observasi Aktivitas</title>
    <style>
        @page {
            margin-top: 60px;
            margin-bottom: 80px;
        }
        body {
            font-family: "DejaVu Sans", sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
            padding-top: 60px;
            padding-bottom: 80px;
        }
        .pdf-header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 40px;
            background-color: white;
            padding: 5px 20px;
            margin-bottom: 0;
            z-index: 1000;
        }
        .pdf-content {
            padding-top: 5px;
        }
        .pdf-footer {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            background-color: white;
            padding: 0;
            z-index: 1000;
        }
        .footer-table {
            width: 100%;
            border-collapse: collapse;
        }
        .footer-page-number {
            text-align: center;
            font-size: 12px;
            font-weight: bold;
            padding: 5px 0;
        }
        
        .table-bars {
    margin: 0;
    padding: 0;
    border-collapse: collapse;
    /* width: calc(100% + 2cm);  match @page margin-left */
    margin-left: -2cm;

}
        .footer-bar-orange {
    background: #ff6a00;
    height: 16px;
    /* width: 50px; */
    width: 35%;
}

.footer-bar-spacer {
    width: 1px;           /* adjust gap size */
    height: 16px;
    background: #ffffff;
    width: 0.2%;
}

.footer-bar-grey {
    width: 10px;           /* adjust grey bar width */
    height: 16px;
    background: #666;
    width: 1.3%;
}

        .footer-text {
            text-align: left;
            font-size: 8px;
            line-height: 1.4;
            

            font-style: italic;
            padding-left: 80px;
        }
        .footer-text-bold {
            font-weight: bold;
        }
        .footer-text-normal {
            font-weight: normal;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            table-layout: fixed;
            page-break-inside: auto;
        }
        table.bordered {
            border-collapse: collapse;
            table-layout: fixed;
            width: 100%;
        }
        tr {
            page-break-inside: avoid;
            page-break-after: auto;
        }
        thead {
            display: table-header-group;
        }
        tbody {
            display: table-row-group;
        }
        tfoot {
            display: table-footer-group;
        }
        table.bordered td, table.bordered th {
            padding: 0;
            margin: 0;
            border: 1px solid #000;
            line-height: 1;
        }
        .bordered td, .bordered th {
            border: 1px solid #000;
        }
        .p-8 { padding: 8px; }
        .p-10 { padding: 10px; }
        .text-center { text-align: center; }
        .text-left { text-align: left; }
        .text-right { text-align: right; }
        .fw-bold { font-weight: bold; }
        .fs-14 { font-size: 14px; }
        .fs-15 { font-size: 15px; }
        .fs-16 { font-size: 16px; }
        .strikethrough {
            text-decoration: line-through;
        }
        .panduan-header {
            background-color: #F7CBAC;
            font-weight: bold;
            text-align: left;
            padding: 10px;
        }
        .panduan-body {
            padding: 12px;
            line-height: 1.6;
        }
        .panduan-body ul {
            margin: 0;
            padding-left: 20px;
        }
        .panduan-body li {
            margin-bottom: 8px;
        }
        .unit-header {
            background-color: #F7CBAC;
            font-weight: bold;
            padding: 10px;
        }
        .table-header {
            background-color: #F7CBAC;
            font-weight: bold;
            text-align: center;
            padding: 8px;
        }
        .table-cell {
            padding: 8px;
            vertical-align: top;
        }
        .table-cell-center {
            padding: 8px;
            text-align: center;
            vertical-align: middle;
        }
        .kriteria-text {
            font-size: 11px;
            line-height: 1.4;
        }
        .umpan-balik-header {
            background-color: #F7CBAC;
            font-weight: bold;
            padding: 10px;
        }
        .signature-cell {
            padding: 10px;
            min-height: 80px;
            vertical-align: top;
        }
        /* Mencegah pemisahan header unit kompetensi */
        .unit-header-table {
            page-break-inside: avoid;
            page-break-after: avoid;
        }
        /* Mencegah pemisahan baris dengan rowspan - lebih ketat */
        .row-with-rowspan {
            page-break-inside: avoid !important;
            page-break-after: avoid;
        }
        /* Mencegah pemisahan header tabel */
        thead tr {
            page-break-inside: avoid;
            page-break-after: avoid;
        }
        /* Mencegah pemisahan baris yang tidak diinginkan */
        tbody tr {
            page-break-inside: avoid;
        }
        /* Mencegah pemisahan cell dengan rowspan - lebih ketat */
        td[rowspan], th[rowspan] {
            page-break-inside: avoid !important;
        }
        /* Kontrol orphans dan widows - lebih ketat untuk mencegah pemisahan yang tidak rapi */
        td, th {
            orphans: 2;
            widows: 2;
        }
        /* Memastikan baris pertama dari setiap elemen tidak terpecah */
        .first-row-of-element {
            page-break-before: avoid;
        }
        /* Memastikan baris dengan rowspan tidak terpecah */
        .keep-together {
            page-break-inside: avoid !important;
        }
    </style>
</head>
<body>
    <!-- Header dengan Logo - Fixed di setiap halaman -->
    <div class="pdf-header">
        <img src="{{ public_path('assets/img/logo.png') }}" alt="Logo" style="width: 80px; height: auto; display: block;" onerror="this.style.display='none';">
    </div>

    <!-- Content -->
    <div class="pdf-content">

    <!-- Tabel Informasi Skema Sertifikasi -->
     
    <div style="font-weight: bold; font-size: 14px; ">
            FR.IA.01. CEKLIS OBSERVASI AKTIVITAS DI TEMPAT KERJA ATAU TEMPAT KERJA SIMULASI
        </div>
    <table class="bordered" style="width: 100%; margin-bottom: 20px;">
        <tr>
            <td rowspan="2" style="width: 25%; padding: 10px; vertical-align: top;">
                <strong>Skema Sertifikasi</strong><br>
                <span class="strikethrough">KKNI</span>/Okupasi/<span class="strikethrough">Klaster</span>
            </td>
            <td style="width: 20%; padding: 8px;">
                Judul
            </td>
            <td style="width: 3%; padding: 1px; text-align: center;">
                :
            </td>
            <td style="width: 55%; padding: 8px;" >
                {{ $observasiChecklist->judul ?? '-' }}
            </td>
        </tr>
        <tr>
            <td style="padding: 8px;">
                Nomor
            </td>
            <td style="width: 3%; padding: 1px; text-align: center;">
                :
            </td>
            <td style="padding: 8px;" >
                {{ $observasiChecklist->nomor_skema ?? '-' }}
            </td>
        </tr>
        <tr>
            <td style="padding: 8px;" colspan="2">
                TUK
            </td>
            <td style="width: 3%; padding: 1px; text-align: center;">
                :
            </td>
            <td style="padding: 8px;" >
                
                @if($observasiChecklist->tuk == 'sewaktu')
                    ☑ Sewaktu ☐ Tempat Kerja ☐ Mandiri
                @elseif($observasiChecklist->tuk == 'tempat_kerja')
                    ☐ Sewaktu ☑ Tempat Kerja ☐ Mandiri
                @elseif($observasiChecklist->tuk == 'mandiri')
                    ☐ Sewaktu ☐ Tempat Kerja ☑ Mandiri
                @else
                    ☐ Sewaktu ☐ Tempat Kerja ☐ Mandiri
                @endif
            </td>
        </tr>
        <tr>
            <td style="padding: 8px;" colspan="2">
                Nama Asesor
            </td>
            <td style="width: 3%; padding: 1px; text-align: center;">
                :
            </td>
            <td style="padding: 8px;" >
                {{ $observasiChecklist->nama_asesor ?? '-' }}
            </td>
        </tr>
        <tr>
            <td style="padding: 8px;" colspan="2">
                Nama Asesi
            </td>
            <td style="width: 1%; padding: 1px; text-align: center;">
                :
            </td>
            <td style="padding: 8px;" >
                {{ $observasiChecklist->nama_asesi ?? '-' }}
            </td>
        </tr>
        <tr>
            <td style="padding: 8px;" colspan="2">
                Tanggal
            </td>
            <td style="width: 1%; padding: 1px; text-align: center;">
                :
            </td>
            <td style="padding: 8px;">
                {{ $tanggal }}
            </td>
        </tr>
    </table>

    <!-- Tabel Panduan Bagi Asesor -->
    <table class="bordered" style="width: 100%; margin-bottom: 20px;">
        <tr>
            <td class="panduan-header" colspan="1" style="height: 25px; padding-left: 3px;">
                PANDUAN BAGI ASESOR
            </td>
        </tr>
        <tr>
            <td class="panduan-body">
                <ul>
                    <li>Lengkapi nama unit kompetensi, elemen, dan kriteria unjuk kerja sesuai kolom dalam tabel.</li>
                    <li>Istilah Acuan Pembanding dengan SOP/spesifikasi produk dari industri/organisasi dari tempat kerja atau simulasi tempat kerja</li>
                    <li>Beri tanda centang (✔) pada kolom K jika Anda yakin asesi dapat melakukan/ mendemonstrasikan tugas sesuai KUK, atau centang (✔) pada kolom BK bila sebaliknya.</li>
                    <li>Penilaian Lanjut diisi bila hasil belum dapat disimpulkan, untuk itu gunakan metode lain sehingga keputusan dapat dibuat.</li>
                </ul>
            </td>
        </tr>
    </table>

    <!-- Tabel Unit Kompetensi, Elemen dan Kriteria Unjuk Kerja -->
    @if($observasiChecklist->elemen_data)
        @php
            $globalNo = 1;
        @endphp
        @foreach($observasiChecklist->elemen_data as $unitIndex => $unit)
            <!-- Header Unit Kompetensi -->
            <table class="bordered unit-header-table" style="width: 100%; margin-bottom: 15px;">
                <tr>
                    <td rowspan="2" style="width: 25%; padding: 10px; vertical-align: middle; font-weight: bold; page-break-inside: avoid;">
                        Unit Kompetensi
                    </td>
                    <td style="width: 15%; padding: 8px;">
                        Kode Unit
                    </td>
                    <td style="width: 3%; padding:1px; text-align: center;">
                        :
                    </td>
                    <td style="width: 64%; padding: 8px;">
                        {{ $unit['kode_unit'] ?? '-' }}
                    </td>
                </tr>
                <tr>
                    <td style="padding: 8px;">
                        Judul Unit
                    </td>
                    <td style="width: 3%; padding:1px; text-align: center;">
                        :
                    </td>
                    <td style="padding: 8px;">
                        {{ $unit['nama_unit_kompetensi'] ?? '-' }}
                    </td>
                </tr>
            </table>

            <!-- Tabel Elemen dan Kriteria -->
            <table class="bordered" style="width: 100%; margin-bottom: 20px; table-layout: fixed;">
                <thead>
                    <tr style="page-break-inside: avoid;">
                        <th class="table-header" style="width: 5%;">No.</th>
                        <th class="table-header" style="width: 15%;">Elemen</th>
                        <th class="table-header" style="width: 25%;">Kriteria Unjuk Kerja*</th>
                        <th class="table-header" style="width: 20%;">Benchmark (SOP/spesifikasi produk industri)</th>
                        <th class="table-header" style="width: 15%;">
                            <table style="width: 100%; border-collapse: collapse; table-layout: fixed;">
                                <tr>
                                    <td colspan="2" style="border: none; padding: 0; text-align: center;">Rekomendasi</td>
                                </tr>
                                <tr>
                                    <td style="border: none; border-top: 1px solid #000; padding: 4px; text-align: center; width: 50%;">K</td>
                                    <td style="border: none; border-top: 1px solid #000; padding: 4px; text-align: center; width: 50%;">BK</td>
                                </tr>
                            </table>
                        </th>
                        <th class="table-header" style="width: 20%;">Penilaian Lanjut</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($unit['elemen'] as $elemenIndex => $elemen)
                        @php
                            $kriteriaCount = count($elemen['kriteria'] ?? []);
                            $firstKriteria = true;
                        @endphp
                        @foreach($elemen['kriteria'] as $kriteriaIndex => $kriteria)
                            <tr class="row-with-rowspan keep-together {{ $firstKriteria ? 'first-row-of-element' : '' }}" style="page-break-inside: avoid !important;">
                                @if($firstKriteria)
                                    <td rowspan="{{ $kriteriaCount }}" class="table-cell-center keep-together" style="vertical-align: top; padding-top: 12px; page-break-inside: avoid !important;">
                                        {{ $globalNo }}
                                    </td>
                                    <td rowspan="{{ $kriteriaCount }}" class="table-cell keep-together" style="vertical-align: top; padding:5px; page-break-inside: avoid !important;">
                                        {{ $elemen['nama_elemen'] ?? '-' }}
                                    </td>
                                    @php $firstKriteria = false; @endphp
                                @endif
                                <td class="table-cell kriteria-text" style="padding:2px;">
                                    {{ $kriteria['nomor_kriteria'] ?? '' }}. {{ $kriteria['nama_kriteria'] ?? '-' }}
                                </td>
                                <td class="table-cell" style="vertical-align: middle; padding:2px;">
                                    @if(isset($observasiChecklist->benchmark[$unitIndex][$elemenIndex][$kriteriaIndex]))
                                        @php
                                            $benchmarkValue = $observasiChecklist->benchmark[$unitIndex][$elemenIndex][$kriteriaIndex];
                                        @endphp
                                        {{ is_array($benchmarkValue) ? json_encode($benchmarkValue) : $benchmarkValue }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="table-cell-center">
                                    <table style="width: 100%; border-collapse: collapse; table-layout: fixed;">
                                        <tr>
                                            <td style="border: none; padding: 4px; text-align: center; width: 50%;">
                                                @if(isset($observasiChecklist->observasi_data[$unitIndex][$elemenIndex][$kriteriaIndex]) && $observasiChecklist->observasi_data[$unitIndex][$elemenIndex][$kriteriaIndex] === 'K')
                                                    ☑
                                                @else
                                                    ☐
                                                @endif
                                            </td>
                                            <td style="border: none; padding: 4px; text-align: center; width: 50%;">
                                                @if(isset($observasiChecklist->observasi_data[$unitIndex][$elemenIndex][$kriteriaIndex]) && $observasiChecklist->observasi_data[$unitIndex][$elemenIndex][$kriteriaIndex] === 'BK')
                                                    ☑
                                                @else
                                                    ☐
                                                @endif
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                                <td class="table-cell kriteria-text" style="padding:2px;">
                                    @if(isset($observasiChecklist->penilaian_lanjut[$unitIndex][$elemenIndex][$kriteriaIndex]) && $observasiChecklist->penilaian_lanjut[$unitIndex][$elemenIndex][$kriteriaIndex])
                                        @php
                                            $penilaianValue = $observasiChecklist->penilaian_lanjut[$unitIndex][$elemenIndex][$kriteriaIndex];
                                        @endphp
                                        {{ is_array($penilaianValue) ? json_encode($penilaianValue) : $penilaianValue }}
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        @php $globalNo++; @endphp
                    @endforeach
                </tbody>
            </table>
        @endforeach
    @endif

    <!-- Tabel Umpan Balik untuk Asesi -->
    <table class="bordered" style="width: 100%; margin-bottom: 20px;">
        <tr>
            <td class="umpan-balik-header" style="width: 35%; vertical-align: middle; padding: 10px;">
                Umpan Balik untuk Asesi:
            </td>
            <td class="table-cell" style="width: 65%; min-height: 100px; padding: 10px; height: 40px;">
                {{ $observasiChecklist->umpan_balik ?? '' }}
            </td>
        </tr>
    </table>

    <!-- Tabel Tanda Tangan dan Tanggal -->
    <table class="bordered" style="width: 100%; margin-bottom: 20px;">
        <tr>
            <th class="table-header" style="width: 25%; background-color: rgb(255, 255, 255);">Nama</th>
            <th class="table-header" style="width: 37.5%; background-color:rgb(255, 255, 255);">Asesi: {{ $observasiChecklist->nama_asesi ?? '-' }}</th>
            <th class="table-header" style="width: 37.5%; background-color: rgb(255, 255, 255);">Asesor: {{ $observasiChecklist->nama_asesor ?? '-' }}</th>
        </tr>
        <tr>
            <td class="table-header" style="width: 25%; background-color: rgb(255, 255, 255);">
                Tanda Tangan dan Tanggal
            </td>
            <td class="signature-cell" style="width: 37.5%; text-align: center; vertical-align: middle;">
                @if($observasiChecklist->mahasiswa_signature)
                    @php
                        $mahasiswaSig = $observasiChecklist->mahasiswa_signature;
                        if (strpos($mahasiswaSig, 'data:image') === 0) {
                            // Base64 data URL
                            $sigSrc = $mahasiswaSig;
                        } else {
                            // File path
                            $sigSrc = public_path('storage/' . $mahasiswaSig);
                        }
                    @endphp
                    <div style="margin-bottom: 10px; text-align: center;">
                        <img src="{{ $sigSrc }}" alt="Signature Asesi" style="max-width: 150px; max-height: 60px; display: block; margin: 0 auto;">
                    </div>
                @endif
                <!-- <div style="margin-top: 10px; text-align: center;">
                    {{ $observasiChecklist->nama_asesi ?? '-' }}
                </div> -->
                @if($tanggalMahasiswa)
                    <div style="margin-top: 5px; text-align: center;">
                        {{ $tanggalMahasiswa }}
                    </div>
                @endif
            </td>
            <td class="signature-cell" style="width: 37.5%; text-align: center; vertical-align: middle;">
                @if($observasiChecklist->asesor_signature)
                    @php
                        $asesorSig = $observasiChecklist->asesor_signature;
                        if (strpos($asesorSig, 'data:image') === 0) {
                            // Base64 data URL
                            $sigSrc = $asesorSig;
                        } else {
                            // File path
                            $sigSrc = public_path('storage/' . $asesorSig);
                        }
                    @endphp
                    <div style="margin-bottom: 10px; text-align: center;">
                        <img src="{{ $sigSrc }}" alt="Signature Asesor" style="max-width: 150px; max-height: 55px; display: block; margin: 0 auto;">
                    </div>
                @endif
                <!-- <div style="margin-top: 10px; text-align: center;">
                    {{ $observasiChecklist->nama_asesor ?? '-' }}
                </div> -->
                @if($tanggalAsesor)
                    <div style="margin-top: 5px; text-align: center;">
                        {{ $tanggalAsesor }}
                    </div>
                @endif
            </td>
        </tr>
    </table>
    </div>

    <!-- Footer dengan nomor halaman, bar warna, dan informasi BNSP - Fixed di setiap halaman -->
     

     <div class="pdf-footer">
        <table class="footer-table">
        <div style="page: fullbleed;">
         <table width="100%" cellspacing="0" cellpadding="0" class="table-bars">
         <tr>
             <!-- Orange bar -->
             <td class="footer-bar-orange"></td>
     
             <!-- White gap -->
             <td class="footer-bar-spacer"></td>
     
             <!-- Grey bar -->
             <td class="footer-bar-grey"></td>
         </tr>
            <tr>
                <td  class="footer-text">
                    <div class="footer-text-normal">Badan Nasional Sertifikasi Profesi <br>Jl. MT. Haryono Kavling 52, Jakarta, Indonesia.</div>
                    
                </td>
            </tr>
     </table>
     </div>
        </div>


    <!-- <div class="pdf-footer">
        <table class="footer-table">
            <tr>
                <td colspan="2" class="footer-page-number">
                    <script type="text/php">
                        if (isset($pdf)) {
                            $text = "Halaman {PAGE_NUM} dari {PAGE_COUNT}";
                            $size = 12;
                            $font = $fontMetrics->getFont("DejaVu Sans");
                            $width = $fontMetrics->get_text_width($text, $font, $size) / 2;
                            $x = ($pdf->get_width() - $width) / 2;
                            $y = $pdf->get_height() - 70;
                            $pdf->page_text($x, $y, $text, $font, $size);
                        }
                    </script>
                    <span style="display: none;">Halaman <span id="pageNum"></span> dari <span id="pageCount"></span></span>
                </td>
            </tr>
            <tr style="width: 100%;">
                <td class="footer-bar-orange" style="width: 90%;"></td>
                <td class="footer-bar-grey" style="width: 10%;"></td>
            </tr>
            <tr>
                <td  class="footer-text">
                    <div class="footer-text-normal">Badan Nasional Sertifikasi Profesi</div>
                    <div>Jl. MT. Haryono Kavling 52, Jakarta, Indonesia.</div>
                </td>
            </tr>
        </table>
    </div> -->
</body>
</html>

