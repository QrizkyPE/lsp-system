<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Daftar Hadir Peserta</title>
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

        table.bordered td,
        table.bordered th {
            padding: 0;
            margin: 0;
            border: 1px solid #000;
            line-height: 1;
        }

        table.bordered-2 td,
        table.bordered-2 th {
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

        .fs-15 {
            font-size: 15px;
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
                    <img src="{{ public_path('assets/img/logo.png') }}" alt="Logo"
                        style="width:50%; display:block; margin:auto;">
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
            <td class="p-8" style="padding: 3px;">{{ $daftarHadir->no_dokumen ?? '-' }}</td>

        </tr>
        <tr>
            <td style="padding: 3px;">Tanggal Berlaku</td>
            <td style="padding: 3px;">{{ $tanggalBerlaku }}</td>
        </tr>
        <tr>
            <td rowspan="2" colspan="2" class="p-10 text-center fw-bold fs-15">DAFTAR HADIR PESERTA UJI</td>
            <td class="p-8" style="padding: 3px;">Edisi/Revisi</td>
            <td class="p-8" style="padding: 3px;">{{ $daftarHadir->edisi_revisi ?? '-' }}</td>

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
                {{ $daftarHadir->skema ?? '-' }}
            </p>
            <p style="font-size: 12px; margin: 5px 0;">Hari/Tanggal&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:
                {{ $hariTanggalWithDay }}
            </p>

            <p style="font-size: 12px; margin: 5px 0;">
                TUK&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:
                {{ $daftarHadir->tuk->nama_tuk ?? ($daftarHadir->jadwalUji->tuk->nama_tuk ?? '-') }}
            </p>
        </div>
        <div style="clear: both;"></div>
    </div>

    <!-- Tabel Daftar Peserta -->
    <table class="bordered-2" style="margin-top: 15px;">
        <thead>
            <tr>
                <th class="p-8 text-center" style="width: 5%;">No.</th>
                <th class="p-8 text-center" style="width: 35%;">Nama Peserta</th>
                <th class="p-8 text-center" style="width: 20%;">NPM</th>
                <th class="p-8 text-center" style="width: 40%;">Tanda Tangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($asesiList as $index => $asesi)
                <tr>
                    <td class="p-8 text-center">{{ $index + 1 }}</td>
                    <td class="p-8" style="padding: 3px;">{{ $asesi['nama'] }}</td>
                    <td class="p-8 text-center" style="padding: 3px;">{{ $asesi['npm'] }}</td>
                    <td class="p-8 text-center" style="text-align: center;">
                        @if($asesi['signature'])
                            <img src="{{ $asesi['signature'] }}" alt="Tanda Tangan" class="signature-img">
                        @else
                            &nbsp;
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Footer -->
    <div style="margin-top: 40px; clear: both;">
        <div style="margin-top: 40px; clear: both;">
            <div style="text-align: right;margin-top: 10px; margin-bottom: 20px;">Palembang, {{ $hariTanggalDateOnly ?? '' }}</div>

            <table style="width: 100%">
                <thead>
                    <th style="text-align: left; width: 32%; font-weight: normal;">Asesor Kompetensi,</th>
                    <th style="text-align: right; width: 36%; font-weight: normal;">Mengetahui,
                        <br> Penanggung jawab TUK
                    </th>
                </thead>

                <tbody>
                    @if($asesorList->count() > 0)
                        @php $asesor = $asesorList->first(); @endphp
                        <tr>
                            <td style="border: none; padding: 8px 2px; text-align: left; vertical-align: middle;">
                                <div style="margin-bottom: 10px;">
                                    @if(isset($asesor['signature']) && $asesor['signature'] && trim($asesor['signature']) !== '')
                                        <div style="text-align: left;">
                                            <img src="{{ $asesor['signature'] }}" alt="Tanda Tangan" class="signature-img"
                                                style="max-width: 120px; max-height: 50px;">
                                        </div>
                                    @else
                                        <div style="text-align: center;">...........</div>
                                    @endif
                                </div>
                                {{ $asesor['nama'] }}
                                <div>No. Reg : {{ $asesor['no_reg'] ?? '-' }}</div>
                            </td>

                            <td style="border: none; padding: 8px 2px; text-align: right; vertical-align: bottom;">
                                <div style="margin-top: 80px; ">
                                    ({{ $daftarHadir->penanggung_jawab_tuk ?? '-' }})
                                </div>
                            </td>

                        </tr>
                        
                    @endif
                </tbody>

            </table>

        </div>
    </div>
</body>

</html>