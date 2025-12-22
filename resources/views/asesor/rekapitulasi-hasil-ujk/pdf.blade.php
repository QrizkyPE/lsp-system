<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Rekapitulasi Hasil UJK</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 20px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        /* Tambahan */
        table.bordered {
            border-collapse: collapse;
        }
        table.bordered td, table.bordered th {
            padding: 0;
            margin: 0;
            border: 1px solid #000;
            line-height: 1;
        }
        
        table.bordered-2 td, table.bordered-2 th {
            padding: 0;
            margin: 0;
            border: 1px solid #000;
        }
        /* Tambahan */
        .bordered td,
        .bordered th {
            border: 1px solid #000;
        }

        .p-8 {
            padding: 8px;
        }

        .p-10 {
            padding: 10px;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .fw-bold {
            font-weight: bold;
        }

        .fs-10 {
            font-size: 10px;
        }

        .fs-14 {
            font-size: 14px;
        }

        .fs-16 {
            font-size: 16px;
        }

        .fs-18 {
            font-size: 18px;
        }

        .signature-img {
            max-width: 150px;
            max-height: 60px;
            padding: 3px;
        }

        .info-table td {
            padding: 5px;
        }

        .info-table td:first-child {
            width: 22%;
        }

        .info-table td:nth-child(3) {
            width: 12%;
        }

        .footer-table {
            margin-top: 20px;
        }

        .footer-left {
            width: 50%;
            float: left;
        }

        .footer-right {
            width: 50%;
            float: right;
        }

        .info-container {
            width: 100%;
            margin-bottom: 15px;
        }

        .info-left {
            float: left;
            width: 50%;
        }

        .info-right {
            float: right;
            width: 50%;
        }
    </style>
</head>

<body>
    <!-- Header utama mengikuti format contoh -->
    <table class="bordered p-0 m-0">
        <tr>
            <td rowspan="3" style="width: 22%; text-align:center; vertical-align:middle;">
                @if(file_exists(public_path('assets/img/logo.png')))
                    <img src="{{ public_path('assets/img/logo.png') }}" alt="Logo" style="width:50%; display:block; margin:auto;">
                @endif
            </td>
            <td rowspan="1" colspan="3" style="width: 38%; text-align: center;">
                <div class="fw-bold fs-16">LSP</div>
                <div class="fw-bold fs-16">Universitas Multi Data Palembang</div>
            </td>

        </tr>

        <tr>
            <td rowspan="2" class="text-center" style="font-weight: bold; font-size: 14px;">FORMULIR</td>
            <td class="p-8" style="padding: 3px;">No. Dokumen</td>
            <td class="p-8" style="padding: 3px;">{{ $rekapitulasi->no_dokumen ?? '-' }}</td>

        </tr>
        <tr>
            <td style="padding: 3px;">Tanggal Berlaku</td>
            <td style="padding: 3px;">{{ $tanggalBerlaku }}</td>
        </tr>
        <tr>
            <td rowspan="2" colspan="2" class="p-10 text-center fw-bold fs-15">REKAPITULASI HASIL UJK</td>
            <td class="p-8" style="padding: 3px;">Edisi/Revisi</td>
            <td class="p-8" style="padding: 3px;">{{ $rekapitulasi->edisi_revisi ?? '-' }}</td>

        </tr>
        <tr>
            <td class="p-8" style="padding: 3px;">Halaman</td>
            <td class="p-8" style="padding: 3px;">{{ $halaman ?? 1 }} dari {{ $halamanTotal ?? 1 }}</td>
        </tr>
    </table>

    <!-- Informasi Jadwal -->
    <div class="info-container" style="margin-top: 6px; margin-bottom: 15px;">
        <div class="info-left">
            <p style="font-size: 12px; margin: 5px 0;">
                Skema&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:
                {{ $rekapitulasi->skema ?? '-' }}
            </p>
            <p style="font-size: 12px; margin: 5px 0;">Hari/Tanggal&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:
                {{ $hariTanggalWithDay }}
            </p>

            <p style="font-size: 12px; margin: 5px 0;">
                TUK&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:
                {{ $rekapitulasi->tuk->nama_tuk ?? ($rekapitulasi->jadwalUji->tuk->nama_tuk ?? '-') }}
            </p>
        </div>
        <div style="clear: both;"></div>
    </div>

    <!-- Tabel Daftar Asesi -->
    <table class="bordered-2" style="margin-top: 15px;">
        <thead>
            <tr>
                <th class="p-8 text-center" style="width: 5%;">No.</th>
                <th class="p-8 text-center" style="width: 30%;">Nama Peserta</th>
                <th class="p-8 text-center" style="width: 20%;">NPM</th>
                <th colspan="2" class="p-8 text-center" style="width: 20%;">Hasil</th>
                <th class="p-8 text-center" style="width: 25%;">Tanda Tangan</th>
            </tr>
            <tr>
                <th></th>
                <th></th>
                <th></th>
                <th class="p-8 text-center" style="width: 10%;">K</th>
                <th class="p-8 text-center" style="width: 10%;">BK</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach($asesiList as $index => $asesi)
                <tr>
                    <td class="p-8 text-center">{{ $index + 1 }}</td>
                    <td class="p-8" style="padding: 3px;">{{ $asesi['nama'] }}</td>
                    <td class="p-8 text-center" style="padding: 3px;" >{{ $asesi['npm'] }}</td>
                    <td class="p-8 text-center">
                        @if($asesi['k'])
                            <div
                                style="width: 16px; height: 16px; border: 2px solid #000; margin: 0 auto; position: relative; display: inline-block;">
                                <svg width="16" height="16" style="position: absolute; top: -2px; left: -2px;">
                                    <path d="M 2 8 L 6 12 L 14 4" stroke="#000" stroke-width="2" fill="none"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </div>
                        @else
                            <div
                                style="width: 16px; height: 16px; border: 2px solid #000; margin: 0 auto; display: inline-block;">
                            </div>
                        @endif
                    </td>
                    <td class="p-8 text-center">
                        @if($asesi['bk'])
                            <div
                                style="width: 16px; height: 16px; border: 2px solid #000; margin: 0 auto; position: relative; display: inline-block;">
                                <svg width="16" height="16" style="position: absolute; top: -2px; left: -2px;">
                                    <path d="M 2 8 L 6 12 L 14 4" stroke="#000" stroke-width="2" fill="none"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </div>
                        @else
                            <div
                                style="width: 16px; height: 16px; border: 2px solid #000; margin: 0 auto; display: inline-block;">
                            </div>
                        @endif
                    </td>
                    <td class="p-8 text-center" style="text-align: center; ">
                        @if($asesi['signature'])
                            <img src="{{ $asesi['signature'] }}" alt="Tanda Tangan" class="signature-img" >
                        @else
                            &nbsp;
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Summary Hasil -->
    <div style="margin-top: 15px;">
        <table style="width: 100%; border: none;">
            <tr>
                <td style="width: 50%; border: none; padding: 5px;">
                    <strong>Hasil:</strong>
                </td>
                <td style="width: 50%; border: none; padding: 5px; text-align: right;">
                    &nbsp;
                </td>
            </tr>
            <tr>
                <td style="border: none; padding: 5px;">
                    Kompeten: <strong>{{ $countK }} orang</strong>
                </td>
                <td style="border: none; padding: 5px; text-align: right;">
                    Belum Kompeten: <strong>{{ $countBK }} orang</strong>
                </td>
            </tr>
        </table>
    </div>

    <!-- Footer -->
    <!-- <div style="margin-top: 40px; clear: both;">
        <table style="width: 100%; border: none;">
            <tr>
                <td style="width: 50%; border: none; vertical-align: top;">
                    <div><strong>Tim Asesor Kompetensi</strong></div>
                    <table style="margin-top: 10px; border: none; width: 100%;">
                        <tr>
                            <td style="border: none; width: 50%; padding: 2px; text-align: left;">Nama</td>
                            <td style="border: none; width: 50%; padding: 2px; text-align: center; margin-left: 100px;">Tanda Tangan</td>
                        </tr>
                        @foreach($asesorList as $index => $asesor)
                            <tr>
                                <td style="border: none; padding: 8px 2px; text-align: left; vertical-align: middle;">{{ $index + 1 }}. {{ $asesor['nama'] }}</td>
                                <td style="border: none; padding: 8px 2px; text-align: center; vertical-align: middle; width: 50%; ">
                                    @if(isset($asesor['signature']) && $asesor['signature'] && trim($asesor['signature']) !== '')
                                        <div style="text-align: center;">
                                            <img src="{{ $asesor['signature'] }}" alt="Tanda Tangan" class="signature-img" style="max-width: 120px; max-height: 50px;">
                                        </div>
                                    @else
                                        <div style="text-align: center;">...........</div>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </td>
                <td style="width: 50%; border: none; vertical-align: top; text-align: right;">
                    <div style="margin-top: 20px;">Mengetahui,</div>
                    <div style="margin-top: 5px;">Penanggung jawab TUK</div>
                    <div style="margin-top: 80px; font-weight: bold;">
                        ({{ $rekapitulasi->tuk->nama_tuk ?? ($rekapitulasi->jadwalUji->tuk->nama_tuk ?? '-') }})
                    </div>
                </td>
            </tr>
        </table>
    </div> -->
    <div style="margin-top: 40px; clear: both;">
        <div style="margin-top: 40px; clear: both;">
            <div><strong>Tim Asesor Kompetensi</strong></div>

            <table style="width: 100%">
                <thead>
                    <th style="text-align: left; width: 32%">Nama</th>
                    <th style="text-align: center; width: 32%">Tanda Tangan</th>
                    <th style="text-align: right; width: 36%">Mengetahui,
                        <br> Penanggung jawab TUK 
                        
                    </th>
                </thead>

                <tbody>
                    @foreach($asesorList as $index => $asesor)
                        <tr>
                            <td style="border: none; padding: 8px 2px; text-align: left; vertical-align: middle;">
                                {{ $index + 1 }}. {{ $asesor['nama'] }}</td>
                            <td
                                style="border: none; padding: 8px 2px; text-align: center; vertical-align: middle; width: 50%; ">
                                @if(isset($asesor['signature']) && $asesor['signature'] && trim($asesor['signature']) !== '')
                                    <div style="text-align: center;">
                                        <img src="{{ $asesor['signature'] }}" alt="Tanda Tangan" class="signature-img"
                                            style="max-width: 120px; max-height: 50px;">
                                    </div>
                                @else
                                    <div style="text-align: center;">...........</div>
                                @endif
                            </td>
                            @endforeach
                            <td>
                            <div style="margin-top: 10px; font-weight: bold; text-align: right;">({{ $rekapitulasi->penanggung_jawab_tuk ?? '-' }})</div>
                        
                            </td>
                        </tr>
                    
        
                </tbody>
                
            </table>

        </div>
    </div>

   
</body>

</html>